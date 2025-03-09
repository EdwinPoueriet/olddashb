<?php

namespace App\Controllers;

use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;
use App\Repositories\OrdersRepository;
use App\Services\SalesReportService;

class VentasReportController extends BaseController
{

    use ReportsFunctions;

    public $service;

    public function getOrderDetail ($id) {
        $orep = new OrdersRepository();
        $data = $orep->getOrder($id);

        if (isset($data['order_id'])) {
            $_SESSION['post_data'] = $data;
            $this->redirect('/order');
        }

        return $this->redirect('/');
    }

    public function __construct()
    {
        parent::__construct();
        $this->service = new SalesReportService();
    }

    public function SalesWithDurationAndAmount() {

        $sellers = Request::body()->get('sellers');
        $client = Request::body()->get('client');
        $date = Request::body()->get('date');
        $order = Request::body()->get('order_by');
        $order_method = Request::body()->get('order_method');

        if (is_array($sellers)) {
            $sellers = array_filter($sellers);
        }





        $res = $this->service->durationAndAmount($client,$sellers ? $sellers: null,$date,$order,$order_method);
        return $this->view('reports.salesWithDuration',
            [
                'sales' =>  $res['data'],
                'totals' => $res['totals'],
                'session' => self::$company_details,
                'filters'=> compact('client','sellers','date','order_method','order')
            ]);

    }

    public function changeStatusSalesReport ()
    {
        $orden_id =Request::body()->get('id');
        $status =Request::body()->get('status');

        try {
            $text = "update ".self::$user_database.".orders_header set status = $status where order_id = $orden_id";
            $sql = self::$con->prepare($text);
            $sql->execute();

            return $this->response("successful", 200);
        } catch (Exception $e) {
            $this->response('Tipo de reporte no especificado',502);
        }
    }

 
    public function getSalesReport () {
        $tipo =Request::body()->get('tipo');
        if (!$tipo) {
            return $this->response('Tipo de reporte no especificado',442);
        }

        $report = new General();
        if ($tipo == 'compacto'){

            $filters = [
                'company' =>       Request::body()->get('company') != 'consolidado' ? "consolidado" :  Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group') != "consolidado" ? "consolidado" : Request::body()->get('consolidado_group'),

                'rango' =>       Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'vendedor' => Request::body()->get('vendedor'),

            ];

            $cobros =  $report->VentasReportCompact($filters);

//            return $this->jsonResponse($cobros);
            return $this->jsonResponse($this->Normalizer($cobros,'compacto',$filters['consolidado_group']));

        }

        if ($tipo == 'detallado') {

            $filters = [
                'company' =>       Request::body()->get('company') != 'consolidado' ? "consolidado" :  Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group') != "consolidado" ? "consolidado" : Request::body()->get('consolidado_group'),

                'rango' =>       Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'vendedor' => Request::body()->get('vendedor'),
                'tipo' => $tipo
            ];

            $cobros =  $report->VentasDetallado($filters);
//            return $this->jsonResponse($cobros);

            if (!is_null($normalized = $this->Normalizer($cobros,'detallado',$filters['consolidado_group'])))
                return $this->jsonResponse($normalized);

        }

        return $this->response('La solicitud no pudo ser resuelta.',500);
    }

    public function salesFormat($data) {
        $sales = [];
        foreach ($data as $d) {
//            foreach ($seller_code as $r) {
            $d = json_decode(json_encode($d), true);
//
                $seller = $d["seller_code"] .' - '.$d["seller_name"];
//
                if ($d['customer_code'] == "" ||  is_null($d['customer_code']) ) {
                    $code = "CONTADO";
                } else {
                    $code = $d['customer_code'];
                }
//
                $customer = $code . ' - ' . $d['customer_name'];
                unset($d['customer_name']);
                unset($d['customer_code']);
                unset($d['seller_code']);
                unset($d['seller_name']);
            $d['seller'] = $seller;
            $d['customer'] = $customer;
            $d['order_gross_amount'] = $this->formatNum( $d['order_gross_amount']);
            $d['order_discount_amount'] = $this->formatNum($d['order_discount_amount']);
            $d['order_tax_amount'] = $this->formatNum($d['order_tax_amount']);
            $d['total'] = $this->formatNum($d['total']);
                array_push($sales, $d);
//            }
        }

        return $sales;
    }

    public function byCompanyFormat ($company, $data) {
        $result = $this->salesFormat($data);
        $sales = [];
        foreach ($result as $sale) {
            $sale['Compañia'] = $company;
            array_push($sales,$sale);
        }
        return $sales;
    }

    public function sellerFormat($data){
        $result['data'] = [];
        foreach ($data as $key => $value) {
            array_push($result['data'],[
                'Vendedor' => $value[0]->seller_code .' - '.$value[0]->seller_name,
                'Bruto' =>  $this->formatNum($value[0]->bruto),
                'Descuento' => $this->formatNum($value[0]->discount),
                'Impuesto' =>$this->formatNum($value[0]->tax),
                'Ventas' => $value[0]->COUNT,
//                'Total' => $this->formatNum($value[0]->CHEQUEFUT) ,
                'Total' => $this->formatNum($value[0]->total)
            ]);
        }
        $result['columns'] = ['Vendedor', 'Ventas','Bruto','Descuento','Impuesto','Total'];
        return $result;

    }

    public function Normalizer ($data, $tipo ,$group) {

        if ($tipo == 'compacto') {

            if ($group == 'consolidado') {
                return   $this->sellerFormat(json_decode($data));
            }

            if ($group == 'separado') {
                foreach (json_decode($data) as $company => $cobradores) {
                    return $this->byCompanyFormat($company, $cobradores);
                }
            }

        } else if ($tipo == 'detallado') {
            if ($group == 'consolidado') {
                return $this->salesFormat(json_decode($data));
            }
            if ($group == 'separado') {
                foreach (json_decode($data) as $company => $sales) {
                    return $this->byCompanyFormat($company, $sales);
                }

            }
        }
        return null;
    }

}
