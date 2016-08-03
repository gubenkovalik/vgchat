<?php
namespace App\Http\Controllers;

use App\Http\LinkFinder;
use App\Http\Messages;
use App\Http\User;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function get(Request $request)
    {
        if ($request->session()->has('uid') == false) {
            return response()->json(['error' => 'Access denied'], 403, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => 'true'
            ]);
        }


        $messages = Messages::select(['messages.*', 'messages.id as mid', 'users.id as user_id', 'users.avatar as avatar', 'users.nickname as nickname'])
            ->leftJoin('users', 'users.id', '=', 'messages.user_id')
            ->orderBy('id', "DESC")
            ->limit(20)
            ->skip($request->get('skip', 0))
            ->get()
            ->toArray();


        $user = User::where('id', $request->session()->get('uid'))->first();

        if (!$user) exit;

        $nickname = $user->nickname;

        foreach ($messages as $k => $m) {
            if ($m['nickname'] == $nickname) {
                $messages[$k]['position'] = "right";
            } else {
                $messages[$k]['position'] = "left";
            }
            $format = "H:i:s";

            $today = date('d');
            $messageDay = date('d', strtotime($m['created_at']));

            if ($today != $messageDay) {
                $format = $format . " d.m.y";
            }

            $messages[$k]['date'] = date($format, strtotime($m['created_at']));
        }


        return response()->json($messages, 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Credentials' => 'true'
        ]);
    }

    public function send(Request $request)
    {
        if ($request->session()->has('uid') == false) {
            return response()->json(['error' => 'Access denied'], 403, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Credentials' => 'true'
            ]);
        }

        $message = $request->get('message');
        $user = User::where('id', $request->session()->get('uid'))->first();


        $user->last_seen = Carbon::now()->toDateTimeString();
        $user->save();

        $message = LinkFinder::replace($message);


        if (!$user) exit;

        $nickname = $user->nickname;

        if ($nickname == null) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Credentials: true");
            die("error");
        }

        if ($nickname != null && $message != null) {
            $messages = new Messages();
            $messages->user_id = $user->id;
            $messages->message = $message;

            Cache::put($nickname, $nickname, 10);
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Credentials: true");
            header("Content-Type: application/json");


            echo json_encode(['success' => $messages->save(), 'message' => $messages->message]);

            exit;
        }


        return response()->json(['success' => false])->header('Access-Control-Allow-Origin', '*');
    }

}
