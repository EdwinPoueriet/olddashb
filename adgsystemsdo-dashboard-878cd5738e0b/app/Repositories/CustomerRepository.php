<?php
namespace App\Repositories;


use App\Legacy\Session;

class CustomerRepository extends Repository
{

    public function cantidadDeClientesOrdenes (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ) {
        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			 COUNT(DISTINCT oh.customer_code) cantidad
			FROM orders_header oh
				WHERE oh.company_id IN (".Session::documentCompaniesString().")
			AND order_date BETWEEN :start_date AND :end_date 
			".$sellerQuery."
			".$customersQuery."
			";

        return $this->clientCon->row($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

    }

    public function cantidadDeClientesCotizaciones (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ) {
        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			 COUNT(DISTINCT oh.customer_code) cantidad
			FROM quotations_header oh
				WHERE oh.company_id IN (".Session::documentCompaniesString().")
			AND order_date BETWEEN :start_date AND :end_date 
			".$sellerQuery."
			".$customersQuery."
			";

        return $this->clientCon->row($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

    }

    public function cantidadDeClientesRecibos (
        $collectors, $start_date, $end_date, $customers, $collectors_condition = "", $customers_condition = "", $company
    ) {
        $collectorsQuery = !is_null($collectors) ? "AND rh.collector_code".$collectors_condition." IN(" .implode(',',$collectors). ")" : "";
        $customersQuery = !is_null($customers) ? "AND rh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			 COUNT(DISTINCT rh.customer_code) cantidad
			FROM receivables_header rh
			WHERE rh.company_id IN (".Session::documentCompaniesString().")
			AND receipt_income_date BETWEEN :start_date AND :end_date 
			".$collectorsQuery."
			".$customersQuery."
			";

        return $this->clientCon->row($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

    }


    public function topClientesVentas(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    )
    {
        $sellerQuery1 = !is_null($sellers) ? "AND yup.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery1 = !is_null($customers) ? "AND yup.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $sellerQuery2 = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery2 = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $sellerQuery3 = !is_null($sellers) ? "AND c.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery3 = !is_null($customers) ? "AND customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "
        SELECT c.customer_code,
        c.customer_name,
        t.vendido,
        c.seller_code,
        s.seller_name,
        z.cantidad cantidad_ventas
        FROM customers c,   (
                              SELECT SUM(order_gross_amount + order_tax_amount - order_discount_amount) vendido, customer_code
                              FROM orders_header yup
                              WHERE company_id IN (".Session::documentCompaniesString().")
                              AND order_date BETWEEN :start_date AND :end_date 
                               ".$sellerQuery1."
                            ".$customersQuery1."
                              GROUP BY customer_code
                            ) t, 
                            (
                            SELECT COUNT(*) cantidad, customer_code
                            FROM orders_header oh
                            WHERE oh.company_id IN (".Session::documentCompaniesString().")
                            AND order_date BETWEEN :start_date2 AND :end_date2
                            ".$sellerQuery2."
                            ".$customersQuery2."
                            GROUP BY customer_code
                            ) z,
                            sellers s
        WHERE c.company_id = ".Session::$company_cxc."
        AND s.seller_code = c.seller_code
        AND s.company_id = ".Session::$company_cxc."
        AND t.customer_code = c.customer_code
        AND z.customer_code = c.customer_code
        ".$customersQuery3."
        ".$sellerQuery3."
        ORDER BY t.vendido DESC LIMIT 10
        ";

        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_date2' => $start_date,
            'end_date2' => $end_date
        ]);
    }

    public function topClientesCotizaciones(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "
        SELECT c.customer_code,
        c.customer_name,
        t.vendido,
        c.seller_code,
        s.seller_name,
        z.cantidad cantidad_ventas
        FROM customers c,   (
                              SELECT SUM(order_gross_amount + order_tax_amount - order_discount_amount) vendido, customer_code
                              FROM quotations_header
                              WHERE company_id IN (".Session::documentCompaniesString().")
                              AND order_date BETWEEN :start_date AND :end_date 
                               ".$sellerQuery."
                            ".$customersQuery."
                              GROUP BY customer_code
                            ) t, 
                            (
                            SELECT COUNT(*) cantidad, customer_code
                            FROM quotations_header oh
                            WHERE oh.company_id IN (".Session::documentCompaniesString().")
                            AND order_date BETWEEN :start_date2 AND :end_date2
                            ".$sellerQuery."
                            ".$customersQuery."
                            GROUP BY customer_code
                            ) z,
                            sellers s
        WHERE c.company_id = ".Session::$company_cxc."
        AND s.seller_code = c.seller_code
        AND s.company_id = ".Session::$company_cxc."
        AND t.customer_code = c.customer_code
        AND z.customer_code = c.customer_code
        ".$customersQuery."
        ".$sellerQuery."
        ORDER BY t.vendido DESC LIMIT 10
        ";

        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_date2' => $start_date,
            'end_date2' => $end_date
        ]);
    }



    public function topClientesCobros(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND c.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND c.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "
        SELECT c.customer_code,
        c.customer_name,
        t.cobrado,
        c.collector_code,
        co.collector_name,
        (
          SELECT COUNT(*)
          FROM receivables_header rh
          WHERE rh.company_id IN (".Session::documentCompaniesString().")
          AND rh.customer_code = c.customer_code
          AND receipt_income_date BETWEEN :start_date2 AND :end_date2
           ".$sellerQuery."
        ) cantidad_ventas
        FROM customers c,   (
                              SELECT SUM(order_gross_amount + order_tax_amount - order_discount_amount) cobrado, customer_code
                              FROM orders_header oh
                              WHERE oh.company_id IN (".Session::documentCompaniesString().")
                              AND oh.order_date BETWEEN :start_date AND :end_date 
                              GROUP BY customer_code
                            ) t ,sellers s
        WHERE c.company_id = ".Session::$company_cxc."
        AND s.seller_code = c.seller_code
        AND s.company_id = ".Session::$company_cxc."
        AND t.customer_code = c.customer_code
        ".$customersQuery."
        ".$sellerQuery."
        ORDER BY t.cobrado DESC LIMIT 10
        ";

        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_date2' => $start_date,
            'end_date2' => $end_date
        ]);
    }


    /**WORK IN PROGRES>>
     * @param $sellers
     * @param $start_date
     * @param $end_date
     * @param $customers
     * @param string $sellers_condition
     * @param string $customers_condition
     * @param $company
     * @param $modo
     * @return mixed
     */
    public function mejoresYPeoresClientes(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company,$modo
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND c.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $order = $modo == 'mejores' ? 'DESC' : 'ASC';

        $query = "
        SELECT c.customer_code,
        c.customer_name,
        c.customer_balance,
        t.vendido,
        (t.vendido - c.customer_balance) deuda
        FROM customers c, (
           SELECT SUM(order_gross_amount + order_tax_amount - order_discount_amount) vendido, customer_code
           FROM orders_header oh
           WHERE oh.order_date BETWEEN :start_date AND :end_date
           AND  oh.company_id = :cid 
           GROUP BY customer_code
        ) t
        WHERE company_id = :cid2
        AND c.customer_code = t.customer_code
        ".$customersQuery."
        ".$sellerQuery."
        ORDER BY deuda ".$order." LIMIT 10
        ";

      return  $this->clientCon->query($query, [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'cid' => $company,
            'cid2' => $company
        ]);
    }

}