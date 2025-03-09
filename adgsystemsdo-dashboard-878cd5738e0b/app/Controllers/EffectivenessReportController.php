<?php
/**
 * Created by PhpStorm.
 * User: nel
 * Date: 21/07/17
 * Time: 05:06 PM
 */

namespace App\Controllers;


use App\Http\Request;
use App\Legacy\General;
use App\Legacy\Session;
use App\Repositories\EffectivenessRepository;

class EffectivenessReportController  extends BaseController
{

    public function visitasEfectivas()
    {

        $sellers = Request::body()->get('sellers');
        $rango =    Request::body()->get('rango');
        $order = Request::body()->get('order_by');
        $order_method = Request::body()->get('order_method');

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        if (is_array($sellers)) {
            $sellers = array_filter($sellers);
        }

        $repo = new EffectivenessRepository();
        $res = $repo->efectividadPorVendedorTotales(
            $sellers, $start_date,$end_date,null,null,
            null, self::$default_company, $order,$order_method);

        return $this->view('reports.visitasEfectivas',
            [
                'data' => $res,
                'session' => Session::$company_details,
                'filters'=> compact('sellers','rango','order_method','order')
            ]);
    }

    public function getNoVentas(){
        $filters = [
            'sellers' =>       Request::body()->get('sellers'),
            'customer' =>       Request::body()->get('customer'),
            'rango' =>       Request::body()->get('rango'),

        ];

        $report = new General();
        $result = $report->visitNoEfective($filters);
        return $this->jsonResponse($this->Normalizer($result));

    }

    private function Normalizer($data) {

        return $this->Normalformat(json_decode($data));


    }
    public function Normalformat($data){

        $result = [];
        foreach ($data as $d) {


            $d = json_decode(json_encode($d), true);
            $seller = $d['seller_code'] . ' - ' . $d['seller_name'];
            $customer = $d['customer_code']. ' - ' . $d['customer_name'];
            unset($d['seller_code']);
            unset($d['customer_name']);

            unset($d['customer_code']);
            unset($d['seller_code']);
            unset($d['seller_name']);
            $d['seller'] = $seller;
            $d['customer'] = $customer;

                array_push($result, $d);

        }

        return $result;

//        return $data;

    }

}