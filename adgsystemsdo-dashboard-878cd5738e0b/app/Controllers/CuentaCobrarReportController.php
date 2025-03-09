<?php


namespace App\Controllers;

use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;

class CuentaCobrarReportController extends BaseController
{

    use ReportsFunctions;

    public function getReport()
    {

        $tipo = Request::body()->get('tipo');
        if (!$tipo) {
            return $this->response('Tipo de reporte no especificado',442);
        }

        $report = new General();

        if ($tipo == 'detallado') {

            $filters = [
                'company' =>       Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group'),
                'rango' =>  Request::body()->get('rango'),
                'rango_vnc' => Request::body()->get('rango_vnc'),
                'order_by' => Request::body()->get('order_by'),
                'vendedor' => Request::body()->get('vendedor'),
                'order_method' => Request::body()->get('order_method'),
                'tipo' => $tipo
            ];

            $facturas = $report->CuentaCobrar($filters, Request::body()->get('primera'));
//            return $this->jsonResponse($facturas);


            if (!is_null($normalized = $this->Normalizer(
                $facturas,
                'detallado',
                $filters['consolidado_group'])))
                return $this->jsonResponse($normalized);
        }
        if($tipo == 'resumido'){

            $filters = [
                'company' =>       Request::body()->get('company'),
                'consolidado_group' => Request::body()->get('consolidado_group'),
                'rango' =>  Request::body()->get('rango'),
                'rango_vnc' => Request::body()->get('rango_vnc'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'tipo' => $tipo
            ];



            $facturas = $report->CuentaCobrarCompact($filters);


            if (!is_null($normalized = $this->Normalizer(
                $facturas,
                'resumido',
                $filters['consolidado_group'])))
                return $this->jsonResponse($normalized);

        }

        return $this->response('La solicitud no pudo ser resuelta.',500);

    }

    private function invoiceFormat ($data) {
        $receipts = [];
        foreach ($data as $seller_code) {
            foreach ($seller_code as $r) {
                $r = json_decode(json_encode($r), true);
                $seller = $r['seller_code'] . ' - ' . $r['seller_name'];
                $customer = $r['customer_code']. ' - ' . $r['customer_name'];
                unset($r['seller_code']);
                unset($r['customer_name']);

                unset($r['customer_code']);
                unset($r['seller_code']);
                unset($r['seller_name']);
                $r['seller'] = $seller;
                $r['customer'] = $customer;
                $r['invoice_balance'] = $this->formatNum($r['invoice_balance']);
                array_push($receipts, $r);
            }
        }

        return $receipts;
    }

    private function byCompanyFormat ($company, $data) {
        $result = $this->invoiceFormat($data);
        $facturas = [];
        foreach ($result as $receipt) {
            $receipt['Compañia'] = $company;
            array_push($facturas,$receipt);
        }
        return $facturas;
    }


    private function byCompanyFormat2($company, $data){
        $result = $this->sellerFormat($data);
        $facturas = [];
        foreach ($result as $receipt) {
            $receipt['Compañia'] = $company;
            array_push($facturas,$receipt);
        }
        return $facturas;

    }

    private function sellerFormat($data){
        $result['data'] = [];
        foreach ($data as $key => $value) {
            array_push($result['data'],[
                'Vendedor' => $value[0]->seller_code .' - '.$value[0]->seller_name,
                'CuentasPorCobrar' => $value[0]->COUNT,
                'Total' => $this->formatNum($value[0]->total)
            ]);
        }
        $result['columns'] = ['Vendedor', 'Cuentas Por Cobrar', 'Total'];
        return $result;
    }

    private function Normalizer($data, $tipo, $group) {

        if ($tipo == 'detallado') {

            if ($group == 'consolidado') {
                return $this->invoiceFormat(json_decode($data));
            }

            if ($group == 'separado') {

                foreach (json_decode($data) as $company => $recibos) {
                    return $this->byCompanyFormat($company, $recibos);
                }
            }

        }

        if($tipo == 'resumido'){

            if ($group == 'consolidado') {
                return   $this->sellerFormat(json_decode($data));
            }

            if ($group == 'separado') {

                foreach (json_decode($data) as $company => $recibos) {
                    return $this->byCompanyFormat2($company, $recibos);
                }
            }

        }
        return null;
    }
}