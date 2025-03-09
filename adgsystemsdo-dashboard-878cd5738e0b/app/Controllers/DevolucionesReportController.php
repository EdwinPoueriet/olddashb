<?php

namespace App\Controllers;


use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;
use App\Repositories\DevolucionesRepository;

class DevolucionesReportController extends BaseController
{

    use ReportsFunctions;

    public function getDevolucionDetail ($id) {
        $orep = new DevolucionesRepository();
        $data = $orep->getDevolucion($id);
        if (isset($data['return_id'])) {
            $_SESSION['post_data'] = $data;
            $this->redirect('/return');
        }
        return $this->redirect('/');
    }

    public function getReport () {
        $tipo =Request::body()->get('tipo');
        if (!$tipo) {
            return $this->response('Tipo de reporte no especificado',442);
        }

        $report = new General();

        if ($tipo == 'detallado') {

            $filters = [
                'company' =>       'consolidado',
                'consolidado_group' =>       Request::body()->get('consolidado_group'),
                'rango' =>       Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'vendedor' => Request::body()->get('vendedor'),
                'tipo' => $tipo
            ];

            $devs =  $report->DevolucionesDetallado($filters);
//            return $this->jsonResponse($devs);

            if (!is_null($normalized = $this->Normalizer($devs,'detallado',$filters['consolidado_group'])))
                return $this->jsonResponse($normalized);

        }

        return $this->response('La solicitud no pudo ser resuelta.',500);
    }

    public function DevolucionesFormat($data) {
        $devs = [];
        foreach ($data as $d) {

//            foreach ($seller_code as $r) {
            $d = json_decode(json_encode($d), true);
                $seller = $d['seller_code'] . ' - ' . $d['seller_name'];
                $customer = $d['customer_code']. ' - ' . $d['customer_name'];
            $d['seller'] = $seller;
            $d['customer'] = $customer;
            $d['Total'] = $this->formatNum($d['TOTAL']);
                unset($d['customer_name']);
                unset($d['customer_code']);
                unset($d['seller_code']);
                unset($d['seller_name']);
                unset($d['TOTAL']);
                array_push($devs, $d);
//            }
        }
        return $devs;
    }

    public function byCompanyFormat ($company, $data) {
        $result = $this->DevolucionesFormat($data);
        $devs = [];
        foreach ($result as $dev) {
            $sale['Compañia'] = $company;
            array_push($devs,$dev);
        }
        return $devs;
    }

    public function Normalizer ($data, $tipo ,$group) {
        if ($tipo == 'detallado') {
            if ($group == '0') {
                return $this->DevolucionesFormat(json_decode($data));
            }
//            if ($group == 'separado') {
//                foreach (json_decode($data) as $company => $devs) {
//                    return $this->byCompanyFormat($company, $devs);
//                }
//
//            }
        }
        return null;
    }



}