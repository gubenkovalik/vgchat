<?php
namespace App\Http\Controllers;
use App\Http\User;
use DB;
use Session;

class ChatController extends Controller
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
        $user = User::where('id', Session::get('uid'))->first();
        $this->uid = $user->id;
    }

    public function getUsers(){
        $users = DB::select(DB::raw("select id, nickname, avatar, last_seen, ((CURRENT_TIMESTAMP - last_seen) <= 900 ) as online from users ORDER BY last_seen DESC"));
        return view('chat.users', compact('users'));
    }

}

