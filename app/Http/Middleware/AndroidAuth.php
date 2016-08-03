<?php
namespace App\Http\Middleware;

use App\Http\User;
use Closure;
use Illuminate\Http\Request;

class AndroidAuth
{
    public function handle(Request $request, Closure $next)
    {


        $access_token = $request->get('access_token');

        if ($access_token == null) {
            return response()->json(['error' => 'No access']);
        }
        $user = User::where('access_token', '=', $access_token)->first();

        if ($user == null) {
            return response()->json(['error' => 'No access']);
        }
        if ($user->confirmed == 0) {
            return response()->json(['error' => 'Not confirmed']);
        }


        return $next($request);
    }
}