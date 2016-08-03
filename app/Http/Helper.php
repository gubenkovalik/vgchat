<?php
namespace App\Http;
class Helper
{
    public static function getLastSeen($last_seen)
    {
        $ts = strtotime($last_seen);

        $yest = date('d', time() - 86400);
        $today = date('d', time());

        $day = date('d', $ts);

        $str = "";

        if ($day == $yest) {
            $str .= trans('users.yesterday') . " " . date("H:i:s", $ts);
        } else if ($day == $today) {
            $str .= trans('users.today') . " " . date("H:i:s", $ts);
        } else {
            $str .= date("d.m.Y H:i:s", $ts);
        }

        return $str;

    }

    public static function getGoodDate($last_seen)
    {
        $ts = strtotime($last_seen);

        $yest = date('d', time() - 86400);
        $today = date('d', time());

        $day = date('d', $ts);

        $str = "";

        if ($day == $today) {
            $str .= date("H:i:s", $ts);
        } else {
            $str .= date("d.m.Y H:i:s", $ts);
        }

        return $str;

    }
}