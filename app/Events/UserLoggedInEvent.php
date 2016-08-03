<?php

namespace App\Events;

class UserLoggedInEvent extends Event
{
    private $username;
    private $sessid;

    public function __construct($username, $sessid)
    {
        $this->username = $username;
        $this->sessid = $sessid;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getSessid()
    {
        return $this->sessid;
    }
}
