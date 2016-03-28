<?php
namespace App\Http\Middleware;




use Closure;
use Illuminate\Http\Request;


class Locale {


    public function handle(Request $request, Closure $next)
    {
        if($request->hasCookie("lang")){
            app()->setLocale($request->cookie("lang"));
        }

        return $next($request);
    }

}