<?php
/**
 * Created by PhpStorm.
 * User: valik
 * Date: 10.03.16
 * Time: 7:52
 */

namespace App\Http\Middleware;


use App\Http\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class Online
{
    public function handle(Request $request, Closure $next)
    {


        if ($request->session()->has('uid')) {
            $u = User::find($request->session()->get('uid'));
            $u->last_seen = Carbon::now()->toDateTimeString();
            $u->save();
        }



        return $next($request);
    }
}