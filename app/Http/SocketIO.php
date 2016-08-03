<?php

namespace App\Http;


use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X;

class SocketIO
{

    private function __construct()
    {
    }

    public static function getInstance()
    {
        return new self;
    }

    public function send($event = '', array $args = [])
    {
        $elephant = new Elephant(new Version1X("https://jencat.ml:3000"));
        $elephant->initialize(false)
            ->getEngine()
            ->emit($event, $args);

        $elephant->close();
    }
}