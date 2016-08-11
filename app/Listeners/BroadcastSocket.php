<?php

namespace App\Listeners;

use App\Events\UserLoggedInEvent;
use App\Http\SocketIO;

class BroadcastSocket
{
    public function __construct()
    {
        //
    }

    public function handle(UserLoggedInEvent $event)
    {
//        SocketIO::getInstance()->send('broadcast', ['sessid' => $event->getSessid(), 'msg' => $event->getUsername().' is online']);
    }
}
