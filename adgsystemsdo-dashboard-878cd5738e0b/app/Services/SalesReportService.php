<?php

namespace App\Services;
use App\Common\LocationFunctions;
use App\Legacy\Session;

class SalesReportService  extends  Session {

use LocationFunctions;
    /**
     * Toma como referencia la compania seleccionada por el usuario.
     */
    public function durationAndAmount (
        $clients = null, $sellers = null, $date = null, $order = 'seller', $order_mode = 'desc') {
        if (is_array($sellers) && in_array('todos', $sellers)) {
            $sellers = null;
        }

        $query = "
        SELECT 
        CONCAT( c.customer_code, \" - \", c.customer_name) customer,
        CONCAT( s.seller_code, \" - \", s.seller_name) seller,
        s.seller_code,
        c.customer_code,
        DATE_FORMAT(oh.order_date_time,'%d-%m-%y %l:%i %p') order_date,
        oh.order_latitud order_lat, 
        oh.order_longitud order_lng,
        c.customer_latitude customer_lat,
        c.customer_longitude customer_lng,
        (oh.order_gross_amount - oh.order_discount_amount + oh.order_tax_amount) monto,
        TIMEDIFF(oh.order_date_finish, oh.order_date_time ) duracion
        FROM ".self::$user_database.".orders_header oh, 
        ".self::$user_database.".sellers s,  
        ".self::$user_database.".customers c
        WHERE oh.seller_code = s.seller_code
        AND oh.customer_code = c.customer_code
        AND s.company_id = :cxc
        AND c.company_id = :cxc
        AND oh.company_id = :current_company
        ";


        if ($clients) {
            $query.= " AND c.customer_code IN (".implode(',',$clients).")";
        }

        if ($sellers) {
            $query.= " AND s.seller_code IN (".implode(',',$sellers).")";
        }

        if ($date) {
            $dates = explode(' ',$date);
            $start_date = trim($dates[0]);
            $end_date = trim($dates[2]);
            $query.= " AND oh.order_date  BETWEEN ".$start_date." AND ".$end_date."";
        }

        $query.= " ORDER BY {$order} {$order_mode}";

        $stmt = self::$con->prepare($query);
        $stmt->bindParam(":cxc", self::$company_cxc);
        $stmt->bindParam(":current_company", self::$default_company);
        $stmt->execute();
        $sales = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        $query = "
        SELECT 
        s.seller_code,
        SUM(oh.order_gross_amount - oh.order_discount_amount + oh.order_tax_amount) total_monto,
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(oh.order_date_finish, oh.order_date_time)))) total_duracion
        FROM ".self::$user_database.".orders_header oh,
        ".self::$user_database.".sellers s,  ".self::$user_database.".customers c
        WHERE oh.seller_code = s.seller_code
        AND c.customer_code = oh.customer_code
        AND c.company_id = :cxc
        AND s.company_id = :cxc
        AND oh.company_id = :current_company
        ";


        if ($clients) {
            $query.= " AND c.customer_code IN (".implode(',',$clients).")";
        }

        if ($sellers) {
            $query.= " AND s.seller_code IN (".implode(',',$sellers).")";
        }

        if ($date) {
            $dates = explode(' ',$date);
            $start_date = trim($dates[0]);
            $end_date = trim($dates[2]);
            $query.= " AND oh.order_date  BETWEEN ".$start_date." AND ".$end_date."";
        }

        $query.= " GROUP BY seller_code";
        $stmt = self::$con->prepare($query);
        $stmt->bindParam(":cxc", self::$company_cxc);
        $stmt->bindParam(":current_company", self::$default_company);
        $stmt->execute();
        $totals = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result1 = [];
        foreach ($totals as $t) {
            $t['total_monto'] =  $this->convertNumber($t['total_monto']);
            $result1[$t['seller_code']] = $t;
        }
        $totals = $result1;

        $result = [];
        foreach ($sales as $sale) {
            $sale['monto'] = $this->convertNumber($sale['monto']);
            $in_location = $this->checkWasInLocation($sale['order_lat'],$sale['order_lng'],$sale['customer_lat'],$sale['customer_lng'],150);
            if (is_null($in_location)) {
                $sale['in_location'] = "N/A";
            } else {
                if ($in_location == true) {
                    $sale['in_location'] =   "Sí" ;
                } else {
                    $sale['in_location'] = "No";
                }
            }
            array_push($result, $sale);
        }

        return ['totals' => $totals, 'data' => array_group_by($result, 'seller_code')] ;

    }


    private function convertNumber ($num) {
        $num = floatval($num);
        return number_format($num, 2, '.', ',');
    }



}
