<?php

namespace App\Common\Database;

use App\Common\Config;
use App\Common\Database\Core\DBClass;

class ClientDB
{

    /**
     * @var DBClass
     */
    private static $instance = null;

    public static function getConnection($client_database)
    {

        if (!isset(self::$instance) || is_null(self::$instance->getCurrentSettings()['dbname'])) {
            $env = self::getConnectionSettings(Config::get('env'));

            $settings = [
                'host' => $env['host'],
                'password'=> $env['password'],
                'user' => $env['user'],
                'dbname' => $client_database
            ];

            static::$instance = new DBClass($settings);
        }

        return static::$instance;
    }

    private static function getConnectionSettings ($connection) {
        $path = dirname(__DIR__,3).'/connections.json';
        $config = new \Noodlehaus\Config($path);
        return $config->get($connection);
    }

}