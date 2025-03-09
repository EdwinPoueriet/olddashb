<?php

namespace Manager\Controllers;


use App\Http\Request;
use Manager\Repositories\ScriptsRepository;

class ScriptsController extends BaseController
{

    public $repository;
    public function __construct()
    {
        parent::__construct();
        $this->repository = new ScriptsRepository();
    }

    public function index()
    {
        return $this->baseView();
    }

    public function saveDatabaseCode()
    {
        $content = Request::body()->get('database_code');
        $this->repository->saveScript('database_code', $content);
        return $this->baseView();
    }

    public function baseView () {
        $res =  $this->repository->getScript('database_code');
      return  $this->view('pages.scripts',['database_code' => $res ? $res['content'] : '']);
    }

}