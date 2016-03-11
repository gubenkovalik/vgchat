<?php

class Cachier
{
    private $hash;
    private $redis;

    private static $instance;

    private final function __clone()
    {
    }

    private final function __wakeup()
    {
    }

    private final function __sleep()
    {
    }

    /**
     * Cachier constructor.
     */
    private function __construct()
    {
        $this->hash = sha1($_COOKIE['suspendSecure'] . (empty($_COOKIE['lang']) ? ($_COOKIE['lang']) : ""));
        $this->redis = $this->getRedis();
    }

    /**
     * @return Cachier
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * @return Redis
     */
    private final function getRedis()
    {
        if ($this->redis != null) {
            return $this->redis;
        }
        $redis = new Redis;
        $redis->connect('127.0.0.1', 6379);

        return $redis;
    }

    /**
     * @param string $content
     * @return bool
     */
    public function cachePage($content = "")
    {
        return $this->redis->set($this->hash, $content);
    }

    /**
     * @return bool
     */
    public function hasCached()
    {
        return $this->redis->exists($this->hash);
    }

    /**
     * @return bool|string
     */
    public function getFromCache()
    {
        return $this->redis->get($this->hash);
    }

    /**
     * @return bool
     */
    public function deleteFromCache()
    {
        $this->redis->delete($this->hash);
        return true;
    }

}


/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__ . '/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);


$response->send();


$kernel->terminate($request, $response);





