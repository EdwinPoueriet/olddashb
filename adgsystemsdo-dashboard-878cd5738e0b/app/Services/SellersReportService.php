<?php

namespace App\Services;

use App\Legacy\Session;
class SellersReportService extends Session
{
    public function workedHours ($sellers, $date, $order, $order_mode) {


        $sql = "SELECT vd.module_code, vd.start_date, vd.end_date, sl.seller_code, sl.seller_name 
                FROM ".self::$user_database.".visits v
                INNER JOIN ".self::$user_database.".visit_details vd
                ON vd.visit_id = v.visit_id 
                INNER JOIN ".self::$user_database.".sellers sl
                ON sl.seller_code = v.seller_code AND sl.company_id = v.company_id
                WHERE v.visit_date = '".$date."' AND v.company_id = ".self::$company_cxc;



        if($sellers != 'todos'){
            $sql .= " AND v.seller_code = '".$sellers."'";
        }




        $sql2 = self::$con->prepare($sql);
        $sql2->execute();
        $sql2 = $sql2->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode(array_group_by($sql2, 'seller_code') );





    }

}