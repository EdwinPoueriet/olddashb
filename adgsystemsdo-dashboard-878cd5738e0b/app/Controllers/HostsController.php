<?php

namespace App\Controllers;

use App\Common\SendsResponses;
use App\Legacy\Database;
class HostsController
{
    public $con;
    use SendsResponses;
    public function __construct()
    {
        $this->con = Database::getDbInstance();
    }
    public function getHost()
    {
        try {
            if (isset($_POST['client_serial'])) {
                $data = $this->getHostBySerial($_POST['client_serial']);
                if ($data) {
                    return $this->jsonResponse($data);
                } else {
                    return $this->jsonResponse(['error' => 'Serial no es válido']);
                }
            }
        } catch (\Exception $e) {
            $this->jsonResponse(['error'=> $e->getMessage()]);
        }
        return $this->jsonResponse(['error' => 'Serial no recibido']);

    }

    public function getHostBySerial($serial)
    {
        //prepare stament
        $stmt =  $this->con->prepare("
      SELECT client_serial_number,api_key,client_host 
      FROM adgsoft_maps.clients WHERE client_serial_number = :p_company_serial");
        $stmt->bindParam(':p_company_serial',$serial);

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}