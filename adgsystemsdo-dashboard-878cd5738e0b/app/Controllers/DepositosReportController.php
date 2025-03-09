<?php

namespace App\Controllers;


use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;

class DepositosReportController extends BaseController
{

    use ReportsFunctions;

    public function getReport () {
        $tipo =Request::body()->get('tipo');
        if (!$tipo) {
            return $this->response('Tipo de reporte no especificado',442);
        }

        $report = new General();

        if ($tipo == 'compacto') {
            $filters = [
                'company' =>       Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group'),
                'rango' =>       Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'tipo' => $tipo
            ];
            $depositos =  $report->DepositosReportCompact($filters);

            if (!is_null($normalized = $this->Normalizer($depositos,'compacto',$filters['consolidado_group'])))
                return $this->jsonResponse($normalized);

        }

//        if ($tipo == 'detallado') {
//
//            $filters = [
//                'company' =>       Request::body()->get('company'),
//                'consolidado_group' => Request::body()->get('consolidado_group'),
//                'rango' =>       Request::body()->get('rango'),
//                'order_by' => Request::body()->get('order_by'),
//                'order_method' => Request::body()->get('order_method'),
//                'vendedor' => Request::body()->get('vendedor'),
//                'tipo' => $tipo
//            ];
//
//            $depositos =  $report->DepositosReportFull($filters);
//
//            if (!is_null($normalized = $this->Normalizer($depositos,'detallado',$filters['consolidado_group'])))
//                return $this->jsonResponse($normalized);
//
//        }

        return $this->response('La solicitud no pudo ser resuelta.',500);

    }

    public function depositoFormat ($data) {
        $result = [];
        foreach ($data as $key => $value) {
            array_push($result,[
                'vendedor' => $value[0]->seller_code .' - '.$value[0]->seller_name,
                'cantidad' =>  $value[0]->COUNT,
                'Total' => $this->formatNum($value[0]->TOTAL)
            ]);
        }
       return $result;
    }

    public function depositoByCompanyFormat($company, $deposits)
    {
        $result = $this->depositoFormat($deposits);
        $res = [];
        foreach ($result as $a) {
            $a['Compañia'] = $company;
            array_push($res,$a);
        }
        return $res;
    }

    public function fullFormat ($data) {

        $deposits = [];
        foreach ($data as $seller_code) {
            foreach ($seller_code as $r) {
                $r = json_decode(json_encode($r), true);
                $seller = $r['seller_code'] . ' - ' . $r['seller_name'];
                $customer = $r['customer_code']. ' - ' . $r['customer_name'];
                unset($r['customer_name']);
                unset($r['customer_code']);
                unset($r['seller_code']);
                unset($r['seller_name']);
                $r['seller'] = $seller;
                $r['customer'] = $customer;
                $r['order_gross_amount'] = $this->formatNum($r['order_gross_amount']);
                $r['order_discount_amount'] = $this->formatNum($r['order_discount_amount']);
                $r['order_tax_amount'] = $this->formatNum($r['order_tax_amount']);
                $r['total'] = $this->formatNum($r['total']);
                array_push($deposits, $r);
            }
        }

        return $deposits;
    }

    public function Normalizer($data, $tipo, $group) {
        if ($tipo == 'compacto') {

            if ($group == 'consolidado') {
                return   $this->depositoFormat(json_decode($data));
            }

            if ($group == 'separado') {
                foreach (json_decode($data) as $company => $deposits) {
                    return $this->depositoByCompanyFormat($company, $deposits);
                }
            }

        }

//        else if ($tipo == 'detallado') {
//            if ($group == 'consolidado') {
//                echo $data;die();
//                return $this->fullFormat(json_decode($data));
//            }
//            if ($group == 'separado') {
//                foreach (json_decode($data) as $company => $receipts) {
//                    return $this->fullFormatbyCompany($company, $receipts);
//                }
//            }
//        }

    }

}