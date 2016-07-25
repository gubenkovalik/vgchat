<?php

namespace App\Http;
require_once("../vendor/wisembly/elephant.io/src/Client.php");

require_once("../vendor/wisembly/elephant.io/src/Client.php");
require_once("../vendor/wisembly/elephant.io/src/EngineInterface.php");
require_once("../vendor/wisembly/elephant.io/src/AbstractPayload.php");
require_once("../vendor/wisembly/elephant.io/src/Exception/SocketException.php");
require_once("../vendor/wisembly/elephant.io/src/Exception/MalformedUrlException.php");
require_once("../vendor/wisembly/elephant.io/src/Exception/ServerConnectionFailureException.php");
require_once("../vendor/wisembly/elephant.io/src/Exception/UnsupportedActionException.php");
require_once("../vendor/wisembly/elephant.io/src/Exception/UnsupportedTransportException.php");

require_once("../vendor/wisembly/elephant.io/src/Engine/AbstractSocketIO.php");
require_once("../vendor/wisembly/elephant.io/src/Engine/SocketIO/Session.php");
require_once("../vendor/wisembly/elephant.io/src/Engine/SocketIO/Version0X.php");
require_once("../vendor/wisembly/elephant.io/src/Engine/SocketIO/Version1X.php");
require_once("../vendor/wisembly/elephant.io/src/Payload/Decoder.php");
require_once("../vendor/wisembly/elephant.io/src/Payload/Encoder.php");

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version0X;
use ElephantIO\Engine\SocketIO\Version1X;

class SocketIO {

    private function __construct()
    {
    }

    public static function getInstance(){
        return new self;
    }

    public function send($event = '', array $args = []){
        $elephant = new Elephant(new Version1X("https://jencat.ml:3000"));
        $elephant->initialize(false)
            ->getEngine()
            ->emit($event, $args);

        $elephant->close();
    }
}