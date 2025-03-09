<?php

namespace App\Controllers;
use App\Http\Request;
use App\Services\SellersReportService;
use Carbon\Carbon;
class SellersReportsController extends BaseController
{

    private $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = new SellersReportService();
    }

    public function getReport()
    {
        $sellers = Request::body()->get('sellers');
        $date = Request::body()->get('date');
        $order = Request::body()->get('order_by');
        $order_method = Request::body()->get('order_method');

        if (is_array($sellers)) {
            $sellers = array_filter($sellers);
        }

        $res = $this->service->workedHours($sellers , $date, $order, $order_method);


        $data = [];
//        foreach (json_decode($res) as $seller_code => $r) {
//            $second = 0;
//            $otro = 0;
//            return $this->jsonResponse($r);

//            foreach ($r as $o){
//
//                $second +=  (strtotime($o->end_date) -strtotime($o->start_date));
//                $otro +=  ;

//                array_push($data[$seller_code], [
//
//                    "name" => $o
//
//                ]);
//            }
//            echo $this->jsonResponse($second);

//            $start = Carbon::createFromFormat('Y-m-d H:i:s', $seller['min']);
//            $end = Carbon::createFromFormat('Y-m-d H:i:s', $seller['max']);
//            $seller['span'] = $this->format_time( $end->diffInSeconds($start));
//            $seller['start'] = $start->format('d-m-y h:i A');
//            $seller['end'] = $end->format('d-m-y h:i A');
//            array_push($result, $seller);
//        }





//
        return $this->view('reports.horasTrabajadas',
            [
                'sellers' =>  $res,
                'session' => self::$company_details,
                'filters'=> compact('sellers','date','order_method','order')
            ]);
    }

    public function getDataReport(){
        $sellers = Request::body()->get('sellers');
        $date = Request::body()->get('date');
        $order = Request::body()->get('order_by');
        $order_method = Request::body()->get('order_method');
//        if (is_array($sellers)) {
//            $sellers = array_filter($sellers);
//        }

//        $seller_id = [];
//        if ($sellers != null){
//            foreach ($sellers as $s){
//                array_push($seller_id, $s['seller_id']);
//
//
//            }
//
//        }


        $res = $this->service->workedHours($sellers , $date, $order, $order_method);
        $data =[];
        foreach (json_decode($res) as $r  =>  $seller){

            $second = 0;

            foreach ($seller as $a){
                $second +=  strtotime($a->end_date) - strtotime($a->start_date);
            }
            $hours = floor($second / 3600);
            $minutes = floor(($second / 60) % 60);
            $seconds = $second % 60;
//            return $this->jsonResponse($r);

            $obj = [
                "name" => $r.'- '.$seller[0]->seller_name,
                "count" => count($seller),
                "hours" => $hours.":".$minutes.":".$seconds,
            ];

            array_push($data, $obj);





        }

        return $this->jsonResponse($data);

    }




    private function format_time($t,$f=':') // t = seconds, f = separator
    {
        return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
    }

}