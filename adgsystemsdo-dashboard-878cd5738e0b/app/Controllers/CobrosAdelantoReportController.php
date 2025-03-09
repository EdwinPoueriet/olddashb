<?php


namespace App\Controllers;

use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;

class CobrosAdelantoReportController extends BaseController
{

    use ReportsFunctions;

    public function getReport()
    {
        $tipo =Request::body()->get('tipo');
        if (!$tipo) {
            return $this->response('Tipo de reporte no especificado',442);
        }

        $report = new General();

        if ($tipo == 'detallado') {

            $filters = [
                'company' =>       Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group'),
                'rango' =>  Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'cobrador' => Request::body()->get('cobrador'),
                'order_method' => Request::body()->get('order_method'),
                'tipo' => $tipo
            ];

            $cobros =  $report->CobrosAdelanto($filters);

            if (!is_null($normalized = $this->Normalizer($cobros,'detallado',$filters['consolidado_group'])))
               return $this->jsonResponse($normalized);

        }

      return $this->response('La solicitud no pudo ser resuelta.',500);

    }

    private function receiptsFormat ($data) {
        $receipts = [];
        foreach ($data as $seller_code) {
            foreach ($seller_code as $r) {
                $r = json_decode(json_encode($r), true);
                $collector = $r['collector_code'] . ' - ' . $r['collector_name'];
                $customer = $r['customer_code']. ' - ' . $r['customer_name'];
                unset($r['collector_code']);
                unset($r['customer_name']);
                unset($r['collector_name']);
                unset($r['customer_code']);
                unset($r['seller_code']);
                unset($r['seller_name']);
                $r['collector'] = $collector;
                $r['customer'] = $customer;
                $r['cash_amount'] = $this->formatNum($r['cash_amount']);
                $r['check_amount'] = $this->formatNum($r['check_amount']);
                $r['card_amount'] = $this->formatNum($r['card_amount']);
                $r['amount'] = $this->formatNum($r['amount']);
                array_push($receipts, $r);
            }
        }

        return $receipts;
    }

    private function byCompanyFormat ($company, $data) {
        $result = $this->receiptsFormat($data);
        $recibos = [];
        foreach ($result as $receipt) {
            $receipt['Compañia'] = $company;
            array_push($recibos,$receipt);
        }
        return $recibos;
    }

    private function Normalizer($data, $tipo, $group) {

        if ($tipo == 'detallado') {

            if ($group == 'consolidado') {
                return $this->receiptsFormat(json_decode($data));
            }

            if ($group == 'separado') {

                foreach (json_decode($data) as $company => $recibos) {
                    return $this->byCompanyFormat($company, $recibos);
                }
            }

        }
        return null;
    }
}