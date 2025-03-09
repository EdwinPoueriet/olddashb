<?php
namespace App\Controllers;

use App\Common\ReportsFunctions;
use App\Common\SendsResponses;
use App\Http\Request;
use App\Legacy\General;
use App\Repositories\ReceiptsRepository;

class CobrosReportController extends BaseController
{

    use ReportsFunctions, SendsResponses;

    public $receiptsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->receiptsRepository = new ReceiptsRepository();
    }

    public function getReceiptDetail($id)
    {
        $data = $this->receiptsRepository->getReceipt($id);
        if (isset($data['receipt_income_reference'])) {
            $data['receipt_code'] = $data['receipt_income_reference'];
            $_SESSION['post_data'] = $data;
           $this->redirect('/invoice');
        }
        return $this->redirect('/');
    }

    public function getReport () {
        $tipo =Request::body()->get('tipo');
        if (!$tipo) {
            return $this->response('Tipo de reporte no especificado',442);
        }

        $report = new General();
        if ($tipo == 'compacto') {

            $filters = [
                'company' =>       Request::body()->get('company') != 'consolidado' ? "consolidado" :  Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group') != "consolidado" ? "consolidado" : Request::body()->get('consolidado_group'),

                'rango' =>       Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'tipo' => $tipo
            ];


            $cobros =  $report->CobrosReportCompact($filters);
//            return $this->jsonResponse($cobros);

            return $this->jsonResponse($this->cobrosNormalizer($cobros,'compacto',$filters['consolidado_group']));
        }

        if ($tipo == 'detallado') {
            $filters = [
                'company' =>       Request::body()->get('company') != 'consolidado' ? "consolidado" :  Request::body()->get('company'),
                'consolidado_group' =>       Request::body()->get('consolidado_group') != "consolidado" ? "consolidado" : Request::body()->get('consolidado_group'),
                'rango' =>       Request::body()->get('rango'),
                'order_by' => Request::body()->get('order_by'),
                'order_method' => Request::body()->get('order_method'),
                'tipo' => $tipo,
                'cobrador' => Request::body()->get('cobrador'),
                'montos' => Request::body()->get('montos'),
            ];
            $cobros =  $report->CobrosReportFull($filters);

            return $this->jsonResponse($this->cobrosNormalizer($cobros['data'],'detallado',$filters['consolidado_group']));
        }

    }

    private function fullFormat ($data) {
        $receipts = [];
        foreach ($data as $r) {
//            foreach ($seller_code as $r) {
                $r = json_decode(json_encode($r), true);
                $collector = $r['collector_code'].  ' - ' . $r['collector_name'];
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
                $r['futuristic_check_amount'] = $this->formatNum($r['futuristic_check_amount']);
                $r['receipt_income_amount'] = $this->formatNum($r['receipt_income_amount']);
                array_push($receipts, $r);
//            }
        }

        return $receipts;
    }

    private  function fullFormatbyCompany ($company, $data) {
        $result = $this->fullFormat($data);
        $recibos = [];
        foreach ($result as $receipt) {
            $receipt['Compañia'] = $company;
            array_push($recibos,$receipt);
        }
        return $recibos;
    }

    public function cobrosNormalizer($data, $tipo,$group)
    {
        if ($tipo == 'compacto') {

            if ($group == 'consolidado') {
               return   $this->cobradorFormat(json_decode($data));
            }

            if ($group == 'separado') {
                foreach (json_decode($data) as $company => $cobradores) {
                 return $this->byCompanyFormat($company, $cobradores);
                }
            }

        } else if ($tipo == 'detallado') {
            if ($group == 'consolidado') {
                return $this->fullFormat(json_decode($data));
            }
            if ($group == 'separado') {
                foreach (json_decode($data) as $company => $receipts) {
                    return $this->fullFormatbyCompany($company, $receipts);
                }
            }
        }
    }

    private function byCompanyFormat ($company, $cobradores) {
        $result = $this->cobradorFormat($cobradores);
        $cobradores = [];
        foreach ($result['data'] as $cobrador) {
            $cobrador['Compañia'] = $company;
            array_push($cobradores,$cobrador);
        }
        $result['data'] = $cobradores;
        array_unshift($result['columns'], 'Compañia');
        return $result;
    }

    private function cobradorFormat ($data) {
        $result['data'] = [];
        foreach ($data as $key => $value) {
            array_push($result['data'],[
                'Cobrador' => $value[0]->collector_code. ' - '.$value[0]->collector_name,
                'Efectivo' =>  $this->formatNum($value[0]->EFECTIVO),
                'Cheques' => $this->formatNum($value[0]->CHEQUE),
                'cantidad' => $value[0]->COUNT,
                'Cheques Futuristas' => $this->formatNum($value[0]->CHEQUEFUT) ,
                'Total' => $this->formatNum($value[0]->TOTAL)
            ]);
        }
        $result['columns'] = ['Cobrador','Efectivo','Cheques','Cheques Futuristas','Total'];
        return $result;
    }




}