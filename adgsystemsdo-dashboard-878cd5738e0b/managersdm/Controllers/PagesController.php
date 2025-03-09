<?php

namespace Manager\Controllers;


class PagesController extends BaseController
{


    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        return $this->view('pages.dashboard');
    }

}