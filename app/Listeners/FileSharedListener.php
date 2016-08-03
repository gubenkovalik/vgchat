<?php
namespace App\Listeners;

use App\Events\FileSharedEvent;
use App\Http\SocketIO;


class FileSharedListener
{
    public function __construct()
    {
        //
    }

    public function handle(FileSharedEvent $event)
    {
        SocketIO::getInstance()->send("user notify", [
            'sessid' => $event->getSessid(),
            'msg' => trans('files.event_shared', [
                'username' => $event->getUsername(),

                'filename' => $event->getFilename(),

            ]),
            'loclink' => '/files',
            'uid' => $event->getUid()
        ]);

    }

}