<?php
namespace App\Http\Controllers;

use App\Http\User;
use Redis;
use Session;

class VG {
    public static final function loginUser(User $user){

        Session::put('uid', $user->id);
        $sessid = md5(rand().time().rand().sha1(rand()));
        Session::put('sessid', $sessid);
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->set("secure:".$sessid, true);
        $redis->close();
    }
}