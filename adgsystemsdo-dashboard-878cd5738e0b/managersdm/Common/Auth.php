<?php

namespace Manager\Common;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use App\Http\Request;

abstract class Auth
{

    public static $user;

    public static function initialize () {

        $session = new Session(new PhpBridgeSessionStorage());
        $session->start();
        Request::instance()->setSession($session);

        if (self::isSessionSet()) {
            //
        }
    }


    private static function isSessionSet () {
        $request = Request::instance();
        if ($user = $request->getSession()->get('user')) {
            if ($user) {
                unset($user['password']);
                self::$user = (object) $user;
                return true;
            }
        }

        return false;
    }

    public static function check () {
        return !is_null(self::$user);
    }

}