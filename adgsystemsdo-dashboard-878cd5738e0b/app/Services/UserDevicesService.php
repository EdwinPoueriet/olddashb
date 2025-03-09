<?php

namespace App\Services;

use App\Legacy\Session;

class UserDevicesService extends Session {


    public function __construct() {
        parent::__construct();
    }

    public function getUsers () {
        $sql = self::$con->prepare("SELECT 
			ua.user_id,    
			user_name, 
			user_email, 
			CONCAT(s.seller_code, ' - ',s.seller_name) seller,
			ud.*,
			(
			SELECT date
			FROM ".self::$user_database.".users_companies uc,
			".self::$user_database.".sync_log sl
			WHERE uc.user_id = ua.user_id
			AND uc.company_id = ".$_SESSION["user_company"]."
			AND sync_type = 'Descarga'
			AND uc.seller_code = sl.seller_code
			ORDER BY sl.date DESC LIMIT 1
			) desc_date,
			 (
			SELECT date
			FROM ".self::$user_database.".users_companies uc,
			".self::$user_database.".sync_log sl
			WHERE uc.user_id = ua.user_id
			AND sync_type = 'Envio'
			AND uc.seller_code = sl.seller_code
			ORDER BY sl.date DESC LIMIT 1
			) sub_date
			FROM ".self::$user_database.".users_accounts ua, 
			".self::$user_database.".users_devices ud, 
			".self::$user_database.".sellers s,
			".self::$user_database.".users_companies uc2
			WHERE ua.user_id = ud.user_id
			AND uc2.user_id = ua.user_id
			AND uc2.company_id = ".self::$default_company."
			AND s.seller_code = uc2.seller_code
			AND s.company_id = uc2.company_id
		    ORDER BY ua.user_id ASC");

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($sql as $u)  {
            $arr =
                [
                    'user' => array_slice($u, 0,4),
                    'device' => array_slice($u, 4)
                ];
            array_push($result, $arr);
        }
        return json_encode($result);
    }

    public  function getUpdates ($userid) {
        $sql = self::$con->prepare("SELECT 
            sl.log_id,
            sl.date,
            sl.sync_type
			FROM ".self::$user_database.".users_companies uc,
			".self::$user_database.".sync_log sl
			WHERE uc.user_id = :userid
			AND uc.seller_code = sl.seller_code
			ORDER BY sl.date DESC
			LIMIT 100"
        );
        $sql->bindParam(':userid', $userid);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        foreach ($sql as $update) {
            $arr = $update;
            $arr['detail'] = $this->getUpdateDetails($update['log_id']);
            array_push($result, $arr);
        }

        return $result;

    }

    function getUpdateDetails ($log_id) {
        $sql = self::$con->prepare("SELECT 
            sld.*
			FROM ".self::$user_database.".sync_log_details sld
			WHERE sld.log_id = :logid"
        );

        $sql->bindParam(':logid', $log_id);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return $sql;
    }
    function serials(){
        $sql = self::$con->prepare("SELECT *  FROM ".self::$user_database.".serial_numbers");

//        $sql->bindParam(':logid', $log_id);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return $sql;
    }
    function users(){
        $sql = self::$con->prepare("SELECT user_id, user_name  FROM ".self::$user_database.".users_accounts");

//        $sql->bindParam(':logid', $log_id);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return $sql;
    }
    function usersUpdate($request){
//        $sql = self::$con->query("update ".self::$user_database.".serial_numbers set user_id = :user_id, user_related_imei = :user_related_imei WHERE user_serial_number = :user_serial_number");
//        $sql->bindValue(':userid', $request['user_id'], PDO::PARAM_STR);
//        $sql->bindValue(':imei', $request['user_related_imei'], PDO::PARAM_STR);
//        $sql->bindParam(':serial', $request['user_serial_number'], PDO::PARAM_STR);
//        $sql->execute();

        try {
            $sql = self::$con->prepare("update ".self::$user_database.".serial_numbers set user_id = :user_id, user_related_imei = :user_related_imei WHERE user_serial_number = :user_serial_number");
            $sql->execute($request);
            return "Success";
        } catch (Exception $e) {
            return 'Excepción capturada: '.  $e->getMessage(). "\n";
        }

    }


}
