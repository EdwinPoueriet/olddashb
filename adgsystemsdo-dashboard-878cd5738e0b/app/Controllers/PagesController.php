<?php

namespace App\Controllers;


use App\Common\OutputsTemplates;
use Manager\Repositories\ClientRepository;
use Manager\Repositories\SettingsRepository;

class PagesController
{

    use OutputsTemplates;

    public function twoFactor () {

//        $repo = new SettingsRepository();
//        $c = new ClientRepository();
//        $c = $c->getAllClients();
//        foreach ($c as $client) {
//            $repo->updateClientSettings($client['client_id']);
//        }

        return $this->view('pages.twoFactorTutorial');
    }

    public function getLogin()
    {
        return $this->view('auth.login');
    }

}