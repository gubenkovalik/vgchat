<?php

namespace App\Http\Controllers;

use App\Http\Playlists;
use App\Http\User;
use DB;
use Illuminate\Http\Request;
use Session;

class ChatController extends Controller
{
    private $uid;

    public function __construct()
    {
        VG::checkAuth();

        $user = User::whereId(session()->get('uid'))->first();
        $this->uid = $user->id;
    }

    public function getUsers()
    {
        $users = DB::select(DB::raw('select id, nickname, avatar, last_seen, ((CURRENT_TIMESTAMP - last_seen) <= 900 ) as online from users ORDER BY last_seen DESC'));

        return view('chat.users', compact('users'));
    }

    public function audios(Request $request)
    {
        $q = $request->get('q');

        return view('chat.audio', compact('q'));
    }

    public function searchAudio(Request $request)
    {
        $q = $request->get('q');

        $q = urlencode($q);


        if ($q != 'get_added_audios_secret_query') {
            $url = 'https://api.vk.com/method/audio.search?count=70&q='.$q.'&access_token='.env('VK_ACCESS_TOKEN');
        } else {
            $url = 'https://api.vk.com/method/audio.get?owner_id=355067326&version=5.50&access_token='.env('VK_ACCESS_TOKEN');
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        curl_close($ch);


        $data = json_decode($output, true)['response'];

        array_shift($data);

        $added_ids = [];

        $pls = Playlists::get();

        foreach ($pls as $pl) {
            $added_ids[] = $pl->audio_id;
        }

        foreach ($data as $k => $v) {
            $url = str_replace('https://', 'http://', $v['url']);
            $data[$k]['url'] = '/audio/play?link='.urlencode($url);

            $data[$k]['orig_url'] = $url;
            $data[$k]['duration_seconds'] = $v['duration'];
            $data[$k]['duration'] = gmdate('i:s', $v['duration']);
            $data[$k]['added'] = in_array($v['aid'], $added_ids) ? 'done' : 'add';
        }
        $resp = json_encode($data);

        header('Content-Type: application/json');
        header('Content-Length: '.strlen($resp));
        echo $resp;
        exit;
    }

    public function addToPlaylist(Request $request)
    {
        $aid = $request->get('aid');
        $oid = $request->get('oid');

        if (Playlists::where('audio_id', $aid)->where('owner_id', $oid)->exists()) {
            return response()->json(['success' => true]);
        }

        $url = 'https://api.vk.com/method/audio.add?audio_id='.$aid.'&owner_id='.$oid.'&v=5.50&access_token='.env('VK_ACCESS_TOKEN');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);

        $pl = new Playlists();
        $pl->audio_id = $aid;
        $pl->owner_id = $oid;
        $pl->save();

        return response()->json(['success' => true]);
    }

    public function getlink(Request $request)
    {
        $link = $request->get('link');
        $data = explode('7Cy3Dh4%9)CjsdD', $link);

        $request->session()->put('dnlink', $data[0]);
        $request->session()->put('dnname', $data[1]);

        return response(hash('whirlpool', $data[0].$data[1]));
    }

    public function getLinkToPlay(Request $request)
    {
        $link = $request->get('link');

        readfile($link);
        exit;
    }

    public function download(Request $request, $whirlpool)
    {
        $url = $request->session()->get('dnlink');
        $fname = $request->session()->get('dnname');

        if (hash('whirlpool', $url.$fname) != $whirlpool) {
            die('Hmm.... something with hashes WRONG!');
        }
        if ($url == null || $fname == null) {
            return redirect()->to('/audio');
        }


        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fname.'.mp3');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($url);

        $request->session()->forget(['dnlink', 'dnname']);
        exit;
    }

    public function secureChat(Request $request, $uid)
    {
        $chatHash = md5($uid.$this->uid.time().rand());

        return view('chat.secure', ['uid' => $uid, 'id' => $this->uid]);
    }

    /**
     * @return User | null
     */
    private function getUser()
    {
        return User::where('id', $this->uid)->first();
    }
}
