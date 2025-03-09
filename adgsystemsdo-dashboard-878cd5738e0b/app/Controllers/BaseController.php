<?php

namespace App\Controllers;
use App\Common\SendsResponses;
use App\Legacy\Credentials;
use App\Common\OutputsTemplates;
use App\Legacy\General;
use Manager\Repositories\ClientRepository;

class BaseController extends Credentials {

   use OutputsTemplates, SendsResponses;

    /**
     * BaseController constructor.
     * Todas las vistan tienen la informacion del usuario y credenciales disponibles
     */
   public function __construct()
    {
        parent::__construct();

        $all = json_decode(json_encode($this->getAllParameters()));
        $ismaster = $all->user_name == 'master';
        $this->setGlobals([
            'authUser' => $all,
            'credentials' => json_decode(json_encode(self::$credentials)),
            'moreThanOneCompany' => $this->moreThanOneCompany(),
            'masterClients' => $ismaster ? (new ClientRepository())->getAllClients() : null
        ]);
    }

}