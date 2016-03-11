<?php
/** MAIN ROUTER **/
Route::group(['middleware' => ['web','locale','online']], function () {

    Route::get("/", 'SiteController@index');

    Route::post("/", array('before' => 'csrf', 'uses' => 'SiteController@login'));

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


    Route::post('/tracking', 'SiteController@tracking');

    Route::get('/lang/{lang}', 'SiteController@lang');
    Route::get('/users', 'ChatController@getUsers');
    Route::any('/users/online/get', 'NodeController@users_status_get');

    Route::any('/api/send', 'ApiController@send');

    Route::get('/api/get', 'ApiController@get');

    Route::get('/android/login', 'AndroidController@login');
    Route::get('/android/register', 'AndroidController@register');
    Route::get('/android/send', 'AndroidController@send');
    Route::get('/android/get', 'AndroidController@get');
    Route::get('/android/remind', 'AndroidController@remind');


    Route::get('/test', function (\Illuminate\Http\Request $request) {
        echo Carbon\Carbon::now()->toDateTimeString();
    });

});