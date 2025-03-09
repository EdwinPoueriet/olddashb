<?php
namespace App\Repositories;

use App\Common\Database\ClientDB;
use App\Common\Database\Core\DBClass;
use App\Legacy\Session;
use Manager\Common\Database\DB;

class Repository
{
    /**
     * @var $con DBClass
     */
    public $clientCon, $con;

    public function __construct()
    {
        $this->clientCon = ClientDB::getConnection(Session::$client_details['client_database']);
        $this->con = DB::getConnection();

    }

}