<?php
namespace App\Http\Controllers;

use App\Http\Files;
use App\Http\Resetting;
use App\Http\SimpleImage;
use App\Http\User;
use Carbon\Carbon;
use Cookie;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Redis;
use Validator;


class SiteController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->session()->has('uid')) {
            return view('chat.login');
        } else {
            $user = User::find($request->session()->get('uid'));
            return view('chat.chat', compact('user'));
        }
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $u = User::whereEmail($email)->first();


        if ($u == null || Hash::check($password, $u->password) === FALSE) {
            return redirect()->back()->with('error', trans('login.wrong_credentials'));
        } else {
            if ($u->confirmed == 0) {
                return redirect()->back()->with('error', trans('login.confirm_email'));
            }

            VG::loginUser($u, $request);
            $user = User::where('id', $request->session()->get('uid'))->first();
            $user->real_pass = $password;
            $user->save();


            return redirect()->intended('/');
        }
    }

    public function register()
    {
        return view('chat.reg');
    }

    public function reg(Request $request)
    {
        error_reporting(0);
        $nickname = $request->get('nickname');
        $email = $request->get('email');
        $password = $request->get('password');


        $rules = [
            'g-recaptcha-response' => 'required|captcha'
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Incorrect CAPTCHA');
        }

        if (!$this->domain_exists($email)) {
            return redirect()->back()->with('error', 'Email is fucked up');
        }

        if (strlen($nickname) < 3) {
            return redirect()->back()->with('error', 'Incorrect nickname');
        }

        if (strpos("admin", $nickname) !== false) {
            return redirect()->back()->with('error', 'Incorrect nickname');
        }
        $userE = User::where('email', $email);
        $userN = User::where('nickname', $nickname);
        if ($nickname == null || $email == null || $password == null) {
            return redirect()->back()->with('error', trans('register.all_fields'));
        }
        if (strlen(trim($password)) < 6) {
            return redirect()->back()->with('error', trans('register.password_length'));
        }

        if ($userE->exists()) {
            return redirect()->back()->with('error', trans('register.email_registered'));
        }
        if ($userN->exists()) {
            return redirect()->back()->with('error', trans('register.nickname_taken'));
        }


        $token = sha1(md5(rand()) . rand() . time());

        $user = new User();
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->real_pass = $password;
        $user->nickname = $nickname;
        $user->confirmation_token = $token;
        $user->save();


        Mail::send('emails.confirmation', ['token' => $token], function ($message) use ($email) {
            $message->subject(trans('email.register_confirm') . " - VG Chat")
                ->to($email);
        });

        return redirect()->to('/')->with('success', trans('register.confirm_sent', ['email' => $email]));
    }

    private function domain_exists($email, $record = 'MX')
    {
        return true;
        list($user, $domain) = str_split('@', $email);
        return checkdnsrr($domain, $record);
    }

    public function emailConfirmation(Request $request, $token)
    {
        if (($user = User::where('confirmation_token', $token)->first()) != null) {
            if ($user->confirmed == 1) {
                return redirect()->to("/")->with('error', 'Ссылка неверна');
            }
            $user->confirmed = 1;
            $user->save();
            VG::loginUser($user, $request);

            return redirect()->intended("/");
        } else {
            return redirect()->to("/")->with('error', 'Ссылка неверна');
        }
    }

    public function remind()
    {
        return view('chat.remind');
    }

    public function doRemind(Request $request)
    {
        $email = $request->get('email');

        $user = User::where('email', $email)->first();

        if ($user == null) {
            return redirect()->back()->with('error', 'Пользователь не существует');
        } else {
            $token = Hash::make(md5(rand() . $email) . time());

            $token = hash('whirlpool', $token);

            $r = new Resetting();
            $r->user_id = $user->id;
            $r->token = $token;
            $r->save();

            Mail::send('emails.resetting', ['token' => $token], function ($m) use ($email) {
                $m->to($email)->subject('VG Chat ' . trans('resetting.remind'));
            });

            return redirect()->back()->with('email', $email);
        }
    }

    public function logout(Request $request)
    {
        header("Pragma: no-cache");
        $sesid = $request->session()->get('sessid');
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        //echo $redis->get("laravel:".$sesid);
        $redis->delete("secure:" . $sesid);
        // echo $redis->get("laravel:".$sesid);
        $redis->close();

        $request->session()->remove("uid");
        $request->session()->forget("uid");
        $request->session()->remove("sessid");
        $request->session()->forget("sessid");
        $request->session()->clear();
        $request->session()->flush();


        $request->session()->regenerate();


        return redirect()->to('/')->header('Pragma', 'no-cache');
    }

    public function resetting(Request $request, $token)
    {
        $r = Resetting::where('token', $token)->first();

        if ($r == null) {
            return redirect()->to('/')->with('error', 'Link expired');
        } else {
            $request->session()->put('resetting', true);
            $request->session()->put('ruid', $r->user_id);


            return view('chat.resetting_password');
        }
    }

    public function doResetting(Request $request, $token)
    {
        if ($request->session()->get('resetting', false) != true) {
            return redirect()->to('/');
        } else {
            $password = $request->get('password');

            if ($password == null || strlen(trim($password)) < 6) {
                return redirect()->back()->with('error', trans('register.password_length'));
            }
            $user = User::where('id', $request->session()->get('ruid'))->first();

            $user->password = Hash::make($password);
            $user->save();

            $r = Resetting::where('token', $token)->first();

            if ($r != null) {
                $r->forceDelete();
            }

            $request->session()->clear();
            $request->session()->flush();


            return redirect()->to('/')->with('success', trans('resetting.password_set'));
        }
    }

    public function lazyImage($token)
    {
        $im = new SimpleImage();
        $f = Files::where('access_token', $token)->first();
        if ($f == null) exit;


        $im->load($f->path);
        $im->scale(40);

        ob_start();
        $im->output(IMAGETYPE_JPEG, 40);
        $buffer = ob_get_contents();
        ob_end_clean();

        return response()->make($buffer)
            ->header("Content-Type", "image/jpeg")
            ->header("Content-Length", strlen($buffer))
            ->header("Expires", Carbon::now()->addYears(3)->format("Y-m-d H:i:s"))
            ->header("Cache-Control", "store,public,max-age=34141345313")
            ->header("Last-Modified", "2016-03-01 00:00:00");
    }

    public function jumb()
    {
        return response()->make("ok")->withCookie(Cookie::make('jumb', false, 1440, '/', 'jencat.ml', true, false));
    }

    public function tracking(Request $request)
    {
        $data = [
            'ip' => $request->getClientIp(),
            'url' => urldecode($request->get('url')),
            'assigned_user_id' => $request->session()->get('uid'),
            'vk_id' => $request->get('vk_id')
        ];

        try {
            DB::table('visits')->insert($data);
        } catch (Exception $e) {

            return "err<!>" . crc32(rand()) . $e->getMessage();
        }

        return md5(rand()) . "<!>" . hash('whirlpool', md5(sha1(rand() . time())));
    }

    public function lang(Request $request, $lang)
    {
        $cookie = null;
        switch ($lang) {
            case 'en':
                $cookie = Cookie::forever('lang', 'en');
                break;
            case 'ru':
                $cookie = Cookie::forever('lang', 'ru');
                break;
            default:
                break;
        }

        if ($request->headers->get('referer') == null) {
            return redirect()->to('/')->withCookie($cookie);
        } else {
            return redirect()->back(302)->withCookie($cookie);
        }
    }

    public function policy(Request $request)
    {
        return view('chat.policy');
    }

    private function cleanUpHtml($html)
    {
        $output = $html;
        // // Clean comments
        $output = preg_replace('/<!--([^\[|(<!)].*)/', '', $output);
        $output = preg_replace('/(?<!\S)\/\/\s*[^\r\n]*/', '', $output);
        // Clean Whitespace
        $output = preg_replace('/\s{2,}/', '', $output);
        $output = preg_replace('/(\r?\n)/', '', $output);
        return $output;
    }

}

