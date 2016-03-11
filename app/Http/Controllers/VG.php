<?php
namespace App\Http\Controllers;

use App\Http\User;
use Event;
use Illuminate\Http\Request;
use Redis;
use Session;

class VG {
    public static final function loginUser(User $user, Request $request){

        Session::put('uid', $user->id);
        $sessid = md5(rand().time().rand().sha1(rand()));
        Session::put('sessid', $sessid);
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->set("secure:".$sessid, true);
        $redis->close();


        Event::fire(new \App\Events\UserLoggedInEvent($user->nickname, $request->session()->get('sessid')));
    }
}