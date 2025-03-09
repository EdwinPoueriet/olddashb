<?php


namespace Manager\Controllers;


use App\Common\SendsResponses;
use Manager\Common\Auth;
use Manager\Common\OutputsTemplates;

class BaseController
{
    use OutputsTemplates, SendsResponses;

    public function __construct()
    {

        $this->setGlobals([
            'authUser' => Auth::$user
        ]);
    }

    public function toObject ($array) {
        return json_decode(json_encode($array));
    }
}