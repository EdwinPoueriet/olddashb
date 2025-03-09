<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\ParameterBag;

abstract class Request
{
    /**
     * @var $request \Symfony\Component\HttpFoundation\Request
     */
    public static $request;

    /**
     * Request constructor.
     * @param $request \Symfony\Component\HttpFoundation\Request
     */

    public static function createRequest ($request) {
        self::$request = $request;
    }

    /**
     * @return ParameterBag
     */
    public static function body()
    {
        return self::$request->request;
    }

    /**
     * @return ParameterBag
     */
    public static function query()
    {
        return self::$request->query;
    }

    public static function instance () {
        return self::$request;
    }

}