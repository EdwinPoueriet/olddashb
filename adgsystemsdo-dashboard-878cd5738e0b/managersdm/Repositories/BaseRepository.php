<?php

namespace Manager\Repositories;
use App\Common\Database\Core\DBClass;
use Manager\Common\Database\DB;


class BaseRepository
{

    /**
     * @var DBClass
     */
    protected  $con;

    public function __construct()
    {
        $this->con = DB::getConnection();

    }

}