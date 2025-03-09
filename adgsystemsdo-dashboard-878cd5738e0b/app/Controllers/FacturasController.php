<?php

namespace App\Controllers;

use App\Http\Request;
use App\Legacy\General;


class FacturasController  extends BaseController
{


    public function index(){
        return $this->view('reports.rancherasReport');
    }

    public function getReport(){
        $filters = [
            'seller_code' =>   Request::body()->get('seller'),
            'rango' =>     Request::body()->get('rango'),
        ];
        $report = new General();
        $factura  =  $report->ranchera($filters);
        return $this->jsonResponse($factura);


    }

}