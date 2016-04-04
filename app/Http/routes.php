<?php
/** MAIN ROUTER **/
use App\Http\User;

Route::group(['middleware' => ['web','locale','online']], function () {

    Route::get("/", 'SiteController@index');

    Route::post("/", array('before' => 'csrf', 'uses' => 'SiteController@login'));

    Route::get('/policy', 'SiteController@policy');

    Route::get('/register', 'SiteController@register');
    Route::post("/register", array('before' => 'csrf', 'uses' => 'SiteController@reg'));

    Route::any("/confirmation/{token}", 'SiteController@emailConfirmation');

    Route::get("/remind", 'SiteController@remind');
    Route::post('/remind', 'SiteController@doRemind');

    Route::any("/logout", 'SiteController@logout');

    Route::get('/resetting/{token}', 'SiteController@resetting');

    Route::post('/resetting/{token}', 'SiteController@doResetting');


    Route::get('/files', 'FilesController@get');
    Route::post('/files/upload', 'FilesController@upload');
    Route::delete('/files/delete', 'FilesController@delete');
    Route::patch('/files/public', 'FilesController@setPublic');
    Route::patch('/files/share', 'FilesController@share');
    Route::get('/files/download/{token}', 'FilesController@download');

    Route::get('/lazy/image/{token}', 'SiteController@lazyImage');

    Route::get('/settings', 'FilesController@settings');

    Route::post('/settings', 'FilesController@settingsSave');
    Route::post('/settings/crop', 'FilesController@imageCrop');

    Route::post('/jumb', 'SiteController@jumb');

    Route::get('/audio', 'ChatController@audios');
    Route::get('/audio/search', 'ChatController@searchAudio');
    Route::post('/audio/add', 'ChatController@addToPlaylist');
    Route::get('/audio/download/{data}', 'ChatController@download');
    Route::patch('/audio/getlink', 'ChatController@getlink');

    Route::post('/tracking', 'SiteController@tracking');

    Route::get('/lang/{lang}', 'SiteController@lang');
    Route::get('/users', 'ChatController@getUsers');
    Route::any('/users/online/get', 'NodeController@users_status_get');

    Route::get('/secure/{uid}', 'ChatController@secureChat');

    Route::any('/api/send', 'ApiController@send');

    Route::get('/api/get', 'ApiController@get');

    Route::any('/android/login', 'AndroidController@login');
    Route::any('/android/register', 'AndroidController@register');
    Route::any('/android/send', 'AndroidController@send');
    Route::any('/android/get', 'AndroidController@get');
    Route::any('/android/remind', 'AndroidController@remind');


    Route::get('/test', function (\Illuminate\Http\Request $request) {
        for($i = 0; $i < 10; $i++){
            header('Hashed-'.$i.":".hash('whirlpool', $i));
        }
    });

    Route::get('/news', function(\Illuminate\Http\Request $request) {
	
        $news = DB::table('news')->orderBy('created_at', 'DESC')->get();
        return view('chat.news', ['news'=>$news]);
	
    });

    Route::get('/news/{id}', function($id){
        $n = DB::table('news')->find($id);

        if($n == null) {
            app()->abort(404);
        } else {
            return view('chat.newsSingle', compact('n'));
        }
    })->where('id', '[0-9]');

    Route::any('/news/add', function(\Illuminate\Http\Request $request){
        if($request->method() == \Illuminate\Http\Request::METHOD_POST) {

            $title = $request->get('title');
            $html = $request->get('html');
            $image = null;

            if($request->hasFile('image')){
                $file = $request->file('image');
                $newName = md5(md5($file->getClientMimeType().$file->getClientOriginalName()).rand()).".".$file->getClientOriginalExtension();
                $file->move("feed", $newName);
                $image = $request->getSchemeAndHttpHost()."/feed/".$newName;
            }

            $data = compact('title', 'html', 'image');

            DB::table('news')->insert($data);

            return redirect()->to('/news', 302);
        }
        if(Session::get('uid') == 2345) {
            return view('chat.addNews');
        } else {
            app()->abort(404);
        }
    });


    Route::get('/rss/{type}', function($type){
        header("Pragma: no-cache");
        $supported = ['atom', 'rss'];

        if(!in_array($type, $supported)){

            return response("<i>&laquo;".htmlspecialchars($type)."&raquo;</i> is not supported", 401);
        }

        /** @var \Roumen\Feed\Feed $feed **/
        $feed = App::make("feed");

        $feed->title = "Fastest ML Feed";
        $news = DB::table('news')->orderBy('created_at')->get();
        $lnk = "https://fastest.ml/news";

        foreach($news as $n) {
            if($n->image != null){
                $n->html .= "<img src=\"".$n->image."\" alt=\"image\"/>";
            }
            $feed->add($n->title, 'V. Gubenko', $lnk."/".$n->id, $n->created_at, $n->html, $n->html);
        }


        return $feed->render('atom');
    });

    Route::any('/badbrowser', function(){
       return view('general.badbrowser');
    });
	

});
