<?php

namespace Manager\Common\Database;

use App\Common\Config;
use App\Common\Database\Core\DBClass;

class DB
{

    private static $instance = null;

    protected function __construct() {

    }

    public static function getConnection() {
        if (!isset(static::$instance)) {

            static::$instance = new DBClass(self::getConnectionSettings(Config::get('manager_env')));
        }
        return static::$instance;
    }

    private static function getConnectionSettings ($connection) {
        $path = dirname(__DIR__,3).'/connections.json';
        $config = new \Noodlehaus\Config($path);
        return $config->get($connection);
    }

}