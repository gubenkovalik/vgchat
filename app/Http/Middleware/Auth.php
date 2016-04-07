<?php
namespace App\Http\Middleware;

use App\Http\User;
use Closure;
use Illuminate\Http\Request;

class Auth {
    public function handle(Request $request, Closure $next)
    {

        dd(true);
        if (!$request->session()->has('uid')){

            return redirect()->to('/');
        }





        return $next($request);
    }
}