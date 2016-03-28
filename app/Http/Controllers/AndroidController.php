<?php

namespace App\Http\Controllers;
use App\Http\Helper;
use App\Http\Messages;
use App\Http\User;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Validator;

class AndroidController extends Controller
{


    private $user;

    public function __construct(Request $request)
    {
        $this->middleware('android-auth', ['except' => ['login', 'register', 'remind']]);
        $this->user = User::where('access_token', '=', $request->get('access_token'))->first();
    }

    public function get(Request $request)
    {
        $messages = Messages::select([
            'users.avatar',
            'users.nickname',
            'messages.message',
            'messages.created_at'
        ])
            ->leftJoin('users', 'messages.user_id', '=', 'users.id')
            ->orderBy('messages.id', 'DESC')
            ->limit(30)
            ->get()->toArray();


        foreach($messages as $key=>$msg){
            $messages[$key]['created_at'] = Helper::getGoodDate($msg['created_at']);
        }



        return response()->json(array_reverse($messages));
    }

    public function send(Request $request)
    {
        $message = $request->get('message');

        if($message != null && strlen(trim($message)) > 0) {
            $m = new Messages();
            $m->user_id = $this->user->id;
            $m->message = $message;
            return response()->json(['success'=>boolval($m->save())]);
        }
        return response()->json(['error'=>'Empty message']);
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $u = User::where('email', '=', $email)->first();

        if($u == null) {
            return response()->json(['error'=>'Wrong login']);
        }

        if($u->confirmed == 0){
            return response()->json(['error'=>'Not confirmed']);
        }

        if(!Hash::check($password, $u->password)){
            return response()->json(['error'=>'Wrong password']);
        }
        $u->access_token = md5(rand()).md5($u->id.time().rand().mt_rand());
        $u->save();

        VG::loginUser($u, $request);

        return response()->json(['access_token'=>$u->access_token, 'avatar'=>$u->avatar, 'nickname'=>$u->nickname, 'user_id'=>$u->id, 'sessid'=>$request->session()->get('sessid')]);
    }

    public function register(Request $request)
    {
        $nickname = $request->get('nickname');
        $password = $request->get('password');
        $email = $request->get('email');

        $validator = Validator::make(['email'=>$email], ['email'=>'required|email']);

        if($validator->fails()){
            return response()->json(['error'=>'Wrong email']);
        }

        if(User::where('nickname', '=', $nickname)->orWhere('email', '=', $email)->exists()){
            return response()->json(['error'=>'User exists']);
        }

        $u = new User;
        $u->nickname = $nickname;
        $u->email = $email;
        $u->real_pass = $password;
        $u->password = Hash::make($password);
        $token = sha1(md5(rand()).rand().time());

        $u->confirmation_token = $token;

        $u->save();
        Mail::send('emails.confirmation', ['token'=>$token], function($message) use ($email){
            $message->subject(trans('email.register_confirm')." - VG Chat")
                ->to($email);
        });

        return response()->json(['success'=>true]);
    }


}