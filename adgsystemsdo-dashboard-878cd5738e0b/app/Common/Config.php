<?php

namespace App\Common;


abstract class Config
{

    public static $config;

    public static function initialize ($path) {
        self::$config = \Noodlehaus\Config::load($path);
    }

    public static function get($key)
    {
        return self::$config->get($key);
    }

}