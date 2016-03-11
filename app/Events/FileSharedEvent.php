<?php
namespace App\Events;

class FileSharedEvent extends Event
{

    /** @var string $username * */
    private $username;

    /** @var string $filename * */
    private $filename;


    private $sessid;
    private $uid;

    /**
     * FileSharedEvent constructor.
     * @param string $filename
     * @param string $username
     */
    public function __construct($filename = '', $username = '', $sessid = '', $uid)
    {
        $this->username = $username;
        $this->filename = $filename;
        $this->sessid = $sessid;
        $this->uid = $uid;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getSessid()
    {
        return $this->sessid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }


}