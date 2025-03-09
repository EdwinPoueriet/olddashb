<?php
namespace App\Legacy;

if (isset($_POST['company_serial']))
{

    class Host extends Database
    {

        function __construct()
        {

            parent::__construct();

        }

        public function getHost()
        {
            //keeping response header to json
            header('Content-Type: application/json; charset=utf-8');
            //call method
            $data['host'] = $this->getHostBySerial($_POST['company_serial']);

            echo json_encode($data, true);
            //exit script
            exit();
        }

        public function getHostBySerial($company_serial = '')
        {

            //prepare stament
            $stmt = $this->con->prepare("SELECT * FROM adg_users WHERE company_serial_number = :p_company_serial");
            $stmt->bindParam(':p_company_serial',$company_serial);
            //execute
            $stmt->execute();

            $response['host'] = array();
            //get rows with object
            $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

            //each all item
            foreach ($rows as $value)
            {
                $tmp = array();
                $tmp['company_serial'] = utf8_encode(trim($value->company_serial_number));
                $tmp['company_host']   = utf8_encode(trim($value->company_host));

                $tmp['api_key'] = utf8_encode(trim($this->getApiKey(utf8_encode(trim($value->user_database)))));

                array_push($response['host'], $tmp);
            }

            return $response['host'];
        }

        public function getApiKey($database = '')
        {
            $apistmt = $this->con->prepare("SELECT api_key FROM ".$database.".api_settings");

            $apistmt->execute();

            $api_key = $apistmt->fetchColumn();

            return $api_key;
        }
    }

    $var1 = new Host;
    $var1->getHost();
}
else
{
    $array = ['error'] = "No se ha seteado el company_serial";
    echo json_encode($array);
    exit();
}
?>