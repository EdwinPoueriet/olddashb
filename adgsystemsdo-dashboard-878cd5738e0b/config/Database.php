<?php

namespace App\Legacy;

use App\Common\Config;

class Database
{
    /**
     * @var $con \PDO
     */
    protected static $connection = null;

    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    public static function getDbInstance()
    {

        if (!isset(static::$connection)) {
            static::$connection = static::createInstance();
        }
        return static::$connection;
    }

    public static function createInstance () {
            $mode = Config::get('env') == 'local';

            $dbname = "adgsoft_maps";

            if (!$mode) {
                $dbhost = "localhost";
                $dbuser = "adgsoft";
                $dbpass = "*1010537-130778442*";
            } else {
                $dbhost = "localhost";
                $dbuser = "homestead";
                $dbpass = "secret";
            }

            try {
                $connection = new \PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
                // set the PDO error mode to exception
                $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            } catch(\PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }

           return $connection;
    }

}
?>