<?php
namespace App\Http\Controllers;


use App\Http\Files;
use App\Http\FileShares;
use App\Http\User;
use File;
use Hash;
use Illuminate\Http\Request;
use Validator;
use View;

define('DESC', 'DESC');

class FilesController extends Controller
{

    private $uid;

    /**
     * @return User | null
     */
    private function getUser()
    {
        return User::where('id', $this->uid)->first();
    }

    public function __construct()
    {
        $this->middleware('loggedin', ['except' => ['download']]);

        $user = User::where('id', session()->get('uid'))->first();
        $this->uid = $user->id;
    }

    public function get(Request $request)
    {

        $shares = FileShares::where('user_id', $this->uid)->get();
        $shared_ids = [];
        foreach($shares as $share){
            $shared_ids[] = $share->file_id;
        }
        $files = Files::where('user_id', $this->uid)->orWhereIn('id', $shared_ids)->orderBy('id', DESC)->get();

        return response(View::make('chat.list', ['files' => $files, 'users'=>User::get()]))
            ->header('Pragma', 'no-cache');

    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        if ($file->isValid()) {
            $storeName = sha1($file->getClientOriginalName() . md5(time()) . rand()) . ".storage";
            $f = new Files();
            $f->user_id = $this->uid;
            $f->file_name = $file->getClientOriginalName();
            $f->path = "../private_storage/" . $storeName;
            $f->access_token = md5($storeName);
            $f->public = 0;

            $file->move("../private_storage", $storeName);
            $f->save();

            echo "success";
            exit;


        }
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');

        $file = Files::where('id', $id)->first();

        if ($file != null) {
            if ($this->uid != $file->user_id) die("access violation");

            File::delete($file->path);

            $file->forceDelete();

            echo "ok";
        } else {
            echo "not found";
        }
    }

    public function setPublic(Request $request)
    {
        $id = $request->get('id');

        $file = Files::where('id', $id)->where('user_id', $this->uid)->first();

        if ($file != null) {
            $file->public = !$file->public;
            $file->save();

            FileShares::where('file_id', $file->id)->forceDelete();

            echo $file->public;

            exit;
        }
    }

    public function share(Request $request){
        $file_id = $request->get('file_id');
        $users = $request->get('users');


        if(FALSE == Files::where('user_id', $this->uid)->where('id', $file_id)->exists()) {
            return response()->json(['fuck'=>true]);
        }
        FileShares::where('file_id', $file_id)->forceDelete();

        if(!is_array($users) || count($users) == 0){
            return response()->json(['success'=>true]);
        }

        foreach($users as $uid){
            $share = new FileShares();
            $share->file_id = $file_id;
            $share->user_id = $uid;
            $share->save();
        }

        return response()->json(['success'=>true]);
    }
    public function download(Request $request, $token)
    {
        $file = Files::where('access_token', $token)->first();

        if ($file == null) return response()->make('Not found', 404);

        if ($file->public == 1) {
            return response()->download($file->path, $file->file_name);
        } else {
            if ($request->session()->has('uid')) {
                $user = User::where('id', $request->session()->get('uid'))->first();

                if ($file->user_id == $user->id) {
                    return response()->download($file->path, $file->file_name);
                } else {
                    return response()->make('<h1>Access denied</h1>', 403);
                }
            } else {
                return response()->make('<h1>Access denied</h1>', 403);
            }
        }
    }


    public function settings()
    {
        return response()->view('chat.settings', ['user' => $this->getUser()]);
    }

    public function settingsSave(Request $request)
    {


        $user = $this->getUser();
        if ($request->has('newPass')) {
            $old = $request->get('oldPass');

            if (Hash::check($old, $user->password) == false) {
                return redirect()->back()->with('error', trans('settings.wrong_current'));
            } else if (strlen($request->get('newPass')) < 6) {
                return redirect()->back()->with('error', trans('register.password_length'));
            } else {
                $user->password = Hash::make($request->get('newPass'));
            }
        }

        if ($request->has('nickname') && ($nickname = $request->get('nickname')) != $user->nickname) {
            if (User::where('nickname', $nickname)->exists()) {

                return redirect()->back()->with('error', trans('register.nickname_taken'));
            } else {
                $user->nickname = $nickname;
            }
        }
        if ($request->has('email') && $request->get('email') != $user->email) {
            $email = $request->get('email');
            $vld = Validator::make(['email' => $email], ['email' => 'email']);

            if ($vld->fails()) {
                return redirect()->back()->with('error', trans('settings.wrong_email'));
            } else {
                if (User::where('email', $email)->exists()) {
                    return redirect()->back()->with('error', trans('register.email_registered'));
                } else {
                    $user->email = $email;
                }
            }
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            $nn = md5(rand() . time()) . sha1($user->id . $user->nickname . $user->password) . "." . $file->getClientOriginalExtension();
            $file->move("./u/pics", $nn);
            $user->avatar = "/u/pics/" . $nn;


        }

        $user->save();

        return redirect()->back()->with('success', trans('settings.saved'));
    }

    public function imageCrop(Request $request)
    {
        $image = $request->get('base64');

        $user = $this->getUser();


        if ($image != null) {
            $image = explode("base64,", $image)[1];


            $bin = base64_decode($image);

            $new = md5(rand() . time()) . sha1($user->id . $user->nickname . $user->password) . ".jpg";
            $f = fopen("./u/pics/" . $new, "wb");
            fwrite($f, $bin);
            fclose($f);


            $user->avatar = "/u/pics/" . $new;
            $user->save();
            echo "1";
            exit;
        }
    }

    public function changePassword()
    {

    }
}