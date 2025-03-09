<?php

namespace App\Controllers;
use App\Common\ReportsFunctions;
use App\Http\Request;
use App\Legacy\General;


class ProductController extends BaseController
{

    use ReportsFunctions;

    public function products()
    {
        return $this->view('reports.productReport');
    }
    public function getReport(){
        $filters = [
            'seller_code'               => Request::body()->get('seller_code'),
            'customer_code'             => Request::body()->get('customer_code'),
            'rango'                     => Request::body()->get('rango'),
            'classification_code'       => Request::body()->get('classification_code'),
            'subclassification_code'    => Request::body()->get('subclassification_code'),
            'family_code'               => Request::body()->get('family_code'),
            'brand_id'                  => Request::body()->get('brand_id'),
            'order_type'                => Request::body()->get('order_type'),
            'warehouse'                 => Request::body()->get('warehouse'),
            'company'                   => Request::body()->get('company'),
            'consolidado_group'         => Request::body()->get('consolidado_group'),
        ];
        $ventas = new General();

        $products = $ventas->VentasProductos($filters, $filters['consolidado_group']);



        return $this->jsonResponse($this->Normalizer($products));

    }
    public function productFormat($data){
        $products = [];
        foreach ($data as $product) {
            array_push($products, [
                "Referencia" => $product->product_reference,
                "Codigo" => $product->product_code,
                "Nombre" => $product->product_name,
                "Cantidad" => $product->cantidad,
                "Descuento" => $this->formatNum($product->descuento),
                "Neto" => $this->formatNum($product->neto),
                "Bruto" =>  $this->formatNum($product->bruto),
            ]);
        }
        return $products;

    }



    public function Normalizer($data) {


        return $this->productFormat(json_decode($data));


    }

}