<?php

namespace App\Jen;
/**
 * Created by PhpStorm.
 * User: Джен Кот
 * Date: 16.08.2016
 * Time: 13:03
 */


class JenCat {

    public static function getFileExtension($fileName) {
        return array_last(explode(".", $fileName));
    }
}