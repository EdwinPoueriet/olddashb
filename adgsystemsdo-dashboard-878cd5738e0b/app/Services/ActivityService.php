<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 6/03/19
 * Time: 15:13
 */

namespace App\Services;
use App\Legacy\Session;


class ActivityService extends Session
{



    function getActivityRecent($params){
        extract($this->ActivityRecent($params));
        /**
         * @var $order
         * @var $quotation
         * @var $invoice
         * @var $receivable
         * @var $return
         * @var $visit

         */

        $data = array_merge($order, $quotation, $invoice, $receivable, $return, $visit);

        usort($data, function($a1, $a2) {
            $v1 = strtotime($a1['date_time']);
            $v2 = strtotime($a2['date_time']);
            return $v1 - $v2  ; // $v2 - $v1 to reverse direction
        });

        return $data;

    }


    function printActivity($params){
        extract($this->ActivityRecent($params));
        /**
         * @var $order
         * @var $quotation
         * @var $invoice
         * @var $receivable
         * @var $return
         * @var $visit

         */
        $data = array_merge($order, $quotation, $invoice, $receivable, $return, $visit);
           $other = array_group_by($data, 'seller_code');
//           foreach ($other as $seller_code => $act){
//
//
//
//           }
        ksort($other);

           return $other ;

    }




    function ActivityRecent($params){

        $order = $this->orderActivity($params);

        $quotation = $this->quotationActivity($params);
        $invoice = $this->invoiceActivity($params);
        $receivable =$this->receivableActivity($params);
        $return = $this->returActivity($params);
        $visit = $this->visitActivity($params);
//        $bank = $this->bankActivity($params);



        return compact('order', 'quotation', 'invoice', 'receivable', 'return',  'visit');


    }


    function bankActivity($params){

        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */



             $text = " SELECT '' customer_code, '' customer_name,'Deposito Bancario' table_indentify ,  bh.seller_code, s.seller_name, bh.bank_deposit_id noDoc, bh.date date_time
                 FROM ".self::$user_database.".bank_deposit_header bh 
                 INNER JOIN ".self::$user_database.".sellers s
                 ON s.seller_code = bh.seller_code AND s.company_id = bh.company_id
                 WHERE bh.company_id = ".self::$company_cxc."
                 AND bh.date BETWEEN :start_date AND :end_date";



        if($seller_code != "0"){
            $text .= " AND bh.seller_code = ".$seller_code;
        }

        $text .= " ORDER BY bh.date DESC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $result = [];
        foreach ($sql as $row) {

            $row['in_location'] =  'N/A';


            $row['view_location'] = null;


            array_push($result, $row);
        }

        return $result;



    }

    function orderActivity($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $text = "SELECT oh.customer_code,'Pedido' table_indentify , oh.order_latitud, oh.order_longitud, cm.customer_latitude, cm.customer_longitude,  oh.seller_code, s.seller_name, oh.order_code noDoc, oh.order_type, oh.order_date_time date_time, (oh.order_gross_amount-oh.order_discount_amount+oh.order_tax_amount) monto, cm.customer_name 
                 FROM ".self::$user_database.".orders_header oh 
                 INNER JOIN ".self::$user_database.".customers cm 
                 ON cm.customer_code = oh.customer_code AND cm.company_id = oh.company_id
                 INNER JOIN ".self::$user_database.".sellers s
                 ON s.seller_code = oh.seller_code AND s.company_id = oh.company_id
                 WHERE oh.company_id = ".self::$company_cxc." 
                 AND oh.order_date BETWEEN :start_date AND :end_date";


        if($seller_code != "0"){
            $text .= " AND oh.seller_code = ".$seller_code;
        }

        if ($customer_code != "0"){
            $text .= " AND oh.customer_code = ".$customer_code;
        }
        $text .= " ORDER BY oh.order_date_time DESC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);


        $result = [];

        $service = new SalesReportService();
        foreach ($sql as $row) {
            $in = $service->checkWasInLocation(
                $row['order_latitud'], $row['order_longitud'], $row['customer_latitude'],$row['customer_longitude'], 100);
            $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

            if ($row['order_latitud'] !== '0' && $row['order_longitud'] !== '0' ) {
                $row['view_location'] =  $row['order_latitud'].','.$row['order_longitud'];
            } else {
                $row['view_location'] = null;
            }

            array_push($result, $row);
        }

        return $result;

//        return $sql;





    }
    function quotationActivity($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */

        $text = "SELECT qh.order_code noDoc, qh.customer_code, 'Cotización' table_indentify, qh.seller_code, s.seller_name, qh.order_date_time date_time, qh.order_type, (qh.order_gross_amount-qh.order_discount_amount+qh.order_tax_amount) monto, cm.customer_name, qh.order_latitud, qh.order_longitud,  cm.customer_latitude, cm.customer_longitude
                  FROM ".self::$user_database.".quotations_header qh
                  INNER JOIN ".self::$user_database.".customers cm
                  ON cm.customer_code = qh.customer_code AND cm.company_id = qh.company_id
                  INNER JOIN ".self::$user_database.".sellers s
                  ON s.seller_code = qh.seller_code AND s.company_id = qh.company_id
                  WHERE qh.company_id = ".self::$company_cxc." 
                  AND qh.order_date BETWEEN :start_date AND :end_date";

        if($seller_code != "0"){
            $text .= " AND qh.seller_code = ".$seller_code;
        }

        if ($customer_code != "0"){
            $text .= " AND qh.customer_code = ".$customer_code;
        }

        $text .= " ORDER BY qh.order_date_time DESC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];

        $service = new SalesReportService();
        foreach ($sql as $row) {
            $in = $service->checkWasInLocation(
                $row['order_latitud'], $row['order_longitud'], $row['customer_latitude'],$row['customer_longitude'], 100);
            $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

            if ($row['order_latitud'] !== '0' && $row['order_longitud'] !== '0' ) {
                $row['view_location'] =  $row['order_latitud'].','.$row['order_longitud'];
            } else {
                $row['view_location'] = null;
            }

            array_push($result, $row);
        }

        return $result                                                                                                                                                                                                                                                  ;
//        return $sql;



    }
    function invoiceActivity($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */

        $text = "SELECT ih.customer_code, ih.seller_code, 'Factura' table_indentify,  s.seller_name, cm.customer_name, ih.invoice_code noDoc, ih.invoice_type, ih.invoice_date_time date_time, (ih.invoice_gross_amount-ih.invoice_discount_amount+ih.invoice_tax_amount) monto, ih.invoice_latitud, ih.invoice_longitud, cm.customer_latitude, cm.customer_longitude
                  FROM ".self::$user_database.".invoices_header ih 
                  INNER JOIN ".self::$user_database.".customers cm 
                  ON cm.customer_code = ih.customer_code AND cm.company_id = ih.company_id
                  INNER JOIN ".self::$user_database.".sellers s
                  ON s.seller_code = ih.seller_code AND s.company_id = ih.company_id
                  WHERE ih.company_id = ".self::$company_cxc." 
                  AND ih.invoice_date BETWEEN :start_date AND :end_date";

        if($seller_code != "0"){
            $text .= " AND ih.seller_code = ".$seller_code;
        }

        if ($customer_code != "0"){
            $text .= " AND ih.customer_code = ".$customer_code;
        }
        $text .= " ORDER BY ih.invoice_date_time DESC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $service = new SalesReportService();
        $result = [];


        foreach ($sql as $row) {
            $in = $service->checkWasInLocation(
                $row['invoice_latitud'], $row['invoice_longitud'], $row['customer_latitude'],$row['customer_longitude'], 100);
            $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

            if ($row['invoice_latitud'] !== '0' && $row['invoice_longitud'] !== '0' ) {
                $row['view_location'] =  $row['invoice_latitud'].','.$row['invoice_longitud'];
            } else {
                $row['view_location'] = null;
            }

            array_push($result, $row);
        }

        return $result;
//        return $sql;




    }
    function receivableActivity($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */

        $text = "SELECT rh.customer_code, cm.customer_name, 'Cobro' table_indentify, rh.seller_code,  s.seller_name, rh.receipt_income_code, rh.discount_amount, rh.receipt_code noDoc, rh.receipt_income_date_time date_time, rh.receipt_income_amount monto, rh.latitude, rh.longitude, cm.customer_latitude, cm.customer_longitude
                FROM ".self::$user_database.".receivables_header rh 
                INNER JOIN ".self::$user_database.".customers cm 
                ON cm.customer_code = rh.customer_code AND cm.company_id = rh.company_id
                INNER JOIN ".self::$user_database.".sellers s
                ON s.seller_code = rh.seller_code AND s.company_id = rh.company_id
                WHERE rh.company_id = ".self::$company_cxc." 
                AND rh.receipt_income_date BETWEEN :start_date AND :end_date";

        if($seller_code != "0"){
            $text .= " AND rh.seller_code = ".$seller_code;
        }

        if ($customer_code != "0"){
            $text .= " AND rh.customer_code = ".$customer_code;
        }

        $text .= " ORDER BY rh.receipt_income_date_time DESC";
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $result = [];

        $service = new SalesReportService();
        foreach ($sql as $row) {
            $in = $service->checkWasInLocation(
                $row['latitude'], $row['longitude'], $row['customer_latitude'],$row['customer_longitude'], 100);
            $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

            if ($row['latitude'] !== '0' && $row['latitude'] !== '0' ) {
                $row['view_location'] =  $row['latitude'].','.$row['longitude'];
            } else {
                $row['view_location'] = null;
            }

            array_push($result, $row);
        }

        return $result;





    }
    function returActivity($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $text = "SELECT rh.customer_code, cm.customer_name, 'Devolución' table_indentify, rh.seller_code, s.seller_name ,rh.invoice_code, rh.return_id noDoc, rh.return_date date_time, rh.invoice_date, rh.reason 
                FROM ".self::$user_database.".returns_header rh 
                INNER JOIN ".self::$user_database.".customers cm 
                ON cm.customer_code = rh.customer_code AND cm.company_id = rh.company_id
                INNER JOIN ".self::$user_database.".sellers s
                ON s.seller_code = rh.seller_code AND s.company_id = rh.company_id
                WHERE rh.company_id = ".self::$company_cxc." 
                AND rh.return_date BETWEEN :start_date AND :end_date";

        if($seller_code != "0"){
            $text .= " AND rh.seller_code = ".$seller_code;
        }

        if ($customer_code != "0"){
            $text .= " AND rh.customer_code = ".$customer_code;
        }
        $text .= " ORDER BY rh.return_date DESC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($sql as $row) {

            $row['in_location'] =  'N/A';


            $row['view_location'] = null;


            array_push($result, $row);
        }

        return $result;
//        return $sql;




    }
    function visitActivity($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */

        $verify = "SELECT * FROM  ".self::$user_database.".visits LIMIT 1";


        $sql = self::$con->prepare($verify);
        $sql->execute();
        $verify = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $text = "SELECT";

        if (isset($verify[0]['latitude']) && isset($verify[0]['longitude'])){
            $text .= " v.latitude, v.longitude,";
        }


        $text .= " v.visit_id noDoc, v.customer_code, cm.customer_name, 'Visita No Ventas' table_indentify, v.seller_code, s.seller_name, v.visit_date_time date_time, cm.customer_latitude, cm.customer_longitude
                  FROM ".self::$user_database.".visits v 
                  INNER JOIN ".self::$user_database.".customers cm 
                  ON cm.customer_code = v.customer_code AND cm.company_id = v.company_id
                  INNER JOIN ".self::$user_database.".sellers s
                  ON s.seller_code = v.seller_code AND s.company_id = v.company_id
                  WHERE v.company_id = ".self::$company_cxc." 
                  AND v.visit_type_id > 0
                  AND v.visit_date BETWEEN :start_date AND :end_date";
        if($seller_code != "0"){
            $text .= " AND v.seller_code = ".$seller_code;
        }

        if ($customer_code != "0"){
            $text .= " AND v.customer_code = ".$customer_code;
        }

        $text .= " 
                   GROUP BY v.visit_id, v.customer_code, cm.customer_name, v.seller_code, s.seller_name, v.visit_date_time, cm.customer_latitude, cm.customer_longitude
                   ORDER BY v.visit_date_time DESC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $service = new SalesReportService();

        if (isset($verify[0]['latitude']) && isset($verify[0]['longitude'])){
            $result = [];


            foreach ($sql as $row) {
                $in = $service->checkWasInLocation(
                    $row['latitude'], $row['longitude'], $row['customer_latitude'],$row['customer_longitude'], 100);
                $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

                if ($row['latitude'] !== '0' && $row['latitude'] !== '0' ) {
                    $row['view_location'] =  $row['latitude'].','.$row['longitude'];
                } else {
                    $row['view_location'] = null;
                }

                array_push($result, $row);
            }

            return $result;


        }

        $result = [];
        foreach ($sql as $row) {

            $row['in_location'] =  'N/A';


            $row['view_location'] = null;


            array_push($result, $row);
        }

        return $result;





    }




}
