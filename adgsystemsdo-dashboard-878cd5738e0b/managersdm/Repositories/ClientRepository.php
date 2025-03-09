<?php

namespace Manager\Repositories;

class ClientRepository extends BaseRepository
{

//    public $scriptRepo;

    public function __construct()
    {
        parent::__construct();
//        $this->scriptRepo = new ScriptsRepository();
    }

    public function createClient($name,$host,$db, $api,$devices)
    {
        try {
            $this->con->beginTransaction();
            $this->con->bind('namee', $name);
            $this->con->bind('serial',   $this->generateRandomSerial());
            $this->con->bind('host', $host);
            $this->con->bind('db', $db);
            $this->con->bind('api', $api);

            $insert =  $this->con->query(
                'INSERT INTO adgsoft_maps.clients (client_name,client_serial_number,client_host,client_database,api_key) 
                      VALUES (:namee,:serial,:host, :db, :api)');
            $client_id = $this->con->lastInsertId();
            if ($insert > 0) {
                for ($i=0; $i<(int)$devices;$i++) {
                    $this->createSerialNumber($client_id);
                }
                $this->con->executeTransaction();
                return $this->con->lastInsertId();
            } else
                throw new \Exception('No se pudo guardar el cliente.');

        } catch (\Exception $e) {
            $this->con->rollBack();
            throw  $e;
        }
    }

    public function createSerialNumber ($client_id) {
        $db = $this->getClientDatabase($client_id);
        $sei = $this->generateRandomSerial();
        $res  = $this->con->query(
            "INSERT INTO $db.serial_numbers (user_serial_number, user_id, user_related_imei) 
                      VALUES ('$sei',0,'')");
        if ($res == 0) {
            throw new \Exception('No se pudo generar serial');
        }
        return $sei;
    }

    public function getClientSerialNumbers ($client_id) {
        $db = $this->getClientDatabase($client_id);
        return $this->con->query(
            "Select sn.*, ua.user_name, ua.user_nickname
from $db.serial_numbers sn 
left join $db.users_accounts ua on ua.user_id = sn.user_id
where user_serial_number != ''
");
    }

    public function getClientDatabase ($id) {
        return $this->con->single("SELECT client_database from adgsoft_maps.clients where client_id = $id");
    }

    private function generateRandomString($length)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),
            0, $length);
    }

    private function generateRandomSerial () {
        $seri =$this->generateRandomString(5).'-'.$this->generateRandomString(5)
            .'-'.$this->generateRandomString(5).'-'.$this->generateRandomString(5).
            '-'.$this->generateRandomString(5);
        return strtoupper($seri);
    }

    /**
     * @param $client_id
     * @param $user_id
     * @return bool
     */
    public function associateClientAndAdminUser ($client_id,$user_id) {
        $update =  $this->con->query(
            'UPDATE adgsoft_maps.clients SET admin_user_id = :user_id WHERE client_id = :client_id', [
            'client_id' => $client_id,
            'user_id' => $user_id
        ]);

        if ($update> 0) {
            return true;
        }
        return false;
    }

    public function getClientsTable()
    {
        $query = $this->con->query(
            'SELECT c.*, CONCAT(u.user_name, " - ",u.user_first_name) user 
                   FROM adgsoft_maps.clients c
                   LEFT JOIN adgsoft_maps.adg_users u ON u.user_id = c.admin_user_id
                   ');
        $result = [];
        foreach ($query as $row) {
            if (isset($row["client_database"]) && !is_null($row["client_database"]) && $row["client_database"] !== '') {
                try {
                    $companies = $this->con->query(
                        'SELECT c.company_name, c.company_id
                   FROM '.$row["client_database"].'.companies c
                   ');
                    $arr = [];
                    foreach ($companies as $company) {
                        array_push($arr,$company['company_id'].' - '.$company['company_name']);
                    }
                    $row['companies'] = implode(', ',$arr);

                }catch (\Exception $e) {
                    $row['companies'] = '';
                }
            }
            array_push($result,$row);
        }

        return $result;
    }

    public function getAllClients () {
        return $this->con->query('SELECT * FROM adgsoft_maps.clients ORDER BY client_name ASC');
    }

    public function getClientById ($id) {
        return $this->con->row("SELECT * FROM adgsoft_maps.clients Where client_id = $id");
    }

    public function deleteSerialNumber($client, $serial_number)
    {
        $db = $this->getClientDatabase($client);
        return $this->con->query(
            "DELETE FROM $db.serial_numbers where user_serial_number = '$serial_number' and user_related_imei = '' and user_id = 0");
    }

}
