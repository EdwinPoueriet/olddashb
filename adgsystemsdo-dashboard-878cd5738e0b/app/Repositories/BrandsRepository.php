<?php

namespace App\Repositories;


use App\Legacy\Session;

class BrandsRepository extends Repository
{

    public function ventasDeMarcasTop(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company, $catalogue_company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND o.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND o.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "
            SELECT SUM(x.order_item_amount) as importe, COALESCE(b.brand_name, 'Sin Nombre') brand_name, x.company_id, b.brand_id,SUM(x.order_item_quantity) cantidad
			FROM orders_details x
			LEFT JOIN orders_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
			LEFT JOIN products p ON (p.product_reference = x.product_reference)
			LEFT JOIN brands b on (b.brand_id = p.brand_id)
			LEFT JOIN sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
			WHERE x.company_id IN (".Session::documentCompaniesString().")
			AND x.order_item_date BETWEEN :start_date AND :end_date 
			AND p.company_id = :catalogue_company
			".$sellerQuery."
			".$customersQuery."
			GROUP BY b.brand_name, x.company_id, b.brand_id 
			ORDER BY 1 DESC LIMIT 11 ";

        return $this->clientCon->query($query, [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'catalogue_company' => $catalogue_company
        ]);

    }

    public function cotizacionesDeMarcasTop(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company, $catalogue_company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND o.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND o.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "
            SELECT SUM(x.order_item_amount) as importe, COALESCE(b.brand_name, 'Sin Nombre') brand_name, x.company_id, b.brand_id,SUM(x.order_item_quantity) cantidad
			FROM quotations_details x
			LEFT JOIN quotations_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
			LEFT JOIN products p ON (p.product_reference = x.product_reference)
			LEFT JOIN brands b on (b.brand_id = p.brand_id)
			LEFT JOIN sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
			WHERE x.company_id IN (".Session::documentCompaniesString().")
			AND x.order_item_date BETWEEN :start_date AND :end_date 
			AND p.company_id = :catalogue_company
			".$sellerQuery."
			".$customersQuery."
			GROUP BY b.brand_name, x.company_id, b.brand_id 
			ORDER BY 1 DESC LIMIT 11 ";

        return $this->clientCon->query($query, [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'catalogue_company' => $catalogue_company
        ]);

    }



}