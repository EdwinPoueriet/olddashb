<?php
/**
 * Created by PhpStorm.
 * User: nelson
 * Date: 09/05/17
 * Time: 10:18 AM
 */

namespace App\Repositories;


use App\Legacy\Session;

class ProductsRepository extends Repository
{

    public function ventasDeproductosTop(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company, $catalogue_company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND o.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND o.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";


        $query = "
            SELECT SUM(x.order_item_amount) as importe, p.product_name, x.company_id, p.product_reference, SUM(x.order_item_quantity) cantidad
            FROM orders_details x
            INNER JOIN orders_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
            INNER JOIN products p ON (p.product_reference = x.product_reference)
            LEFT JOIN sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
		   	WHERE x.company_id IN (".Session::documentCompaniesString().")
            AND x.order_item_date BETWEEN :start_date AND :end_date
            AND p.company_id = :catalogue_company
            ".$sellerQuery."
            ".$customersQuery."
            GROUP BY p.product_name , x.company_id, p.product_reference
            ORDER BY 1 DESC LIMIT 11
        ";


        return $this->clientCon->query($query, [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'catalogue_company' => $catalogue_company
        ]);

    }
    public function cotizacionesDeproductosTop(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company, $catalogue_company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND o.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND o.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";


        $query = "
            SELECT SUM(x.order_item_amount) as importe, p.product_name, x.company_id, p.product_reference, SUM(x.order_item_quantity) cantidad
            FROM quotations_details x
            INNER JOIN quotations_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
            INNER JOIN products p ON (p.product_reference = x.product_reference)
            LEFT JOIN sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
		   	WHERE x.company_id IN (".Session::documentCompaniesString().")
            AND x.order_item_date BETWEEN :start_date AND :end_date
            AND p.company_id = :catalogue_company
            ".$sellerQuery."
            ".$customersQuery."
            GROUP BY p.product_name , x.company_id, p.product_reference
            ORDER BY 1 DESC LIMIT 11
        ";


        return $this->clientCon->query($query, [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'catalogue_company' => $catalogue_company
        ]);

    }





}