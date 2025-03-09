<?php

namespace App\Controllers;


class ModulesController extends BaseController
{

    public function index()
    {
        $data = null;
        return $this->view('modules.indexModules',
            [
                'baseData' => json_encode($data)
            ]);
    }

}