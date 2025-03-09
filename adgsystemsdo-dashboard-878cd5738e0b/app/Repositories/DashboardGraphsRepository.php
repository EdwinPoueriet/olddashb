<?php

namespace App\Repositories;


use App\Common\CompatibilityChecker;
use App\Legacy\Session;

class DashboardGraphsRepository extends Repository
{
    /**
     * @param $sellers
     * @param $start_date
     * @param $end_date
     * @param $customers
     * @return array
     */
    public function montosDeVentaPorVendedor (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company) {

        $sellerQuery = !is_null($sellers) ? "AND s.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $append ="";
        $hasStatus = CompatibilityChecker::columnExists('status','sellers');
       if ($hasStatus) {
           $append = ' s.status, ';
       }

       $query = "
SELECT
  *,
  COALESCE (
      (  SELECT
           customer_name
         FROM
           customers c
         WHERE
           customer_code = z.mayor_pedido_customer_code
         AND
           c.company_id = ".Session::$company_cxc." LIMIT 1
      ), 'N/A'
  ) mayor_pedido_customer_name
FROM
  (
    SELECT
     s.seller_code,
     s.seller_name,
     ".$append."
     COALESCE (t.mayor_pedido, 0) mayor_pedido,
     COALESCE (
         ( SELECT
             customer_code
           FROM
             orders_header
               WHERE
                 (order_gross_amount + order_tax_amount - order_discount_amount) = t.mayor_pedido
                 AND company_id
                     IN (".Session::documentCompaniesString().")
                 AND seller_code = s.seller_code    
           LIMIT 1
         ),
         'N/A'
     ) mayor_pedido_customer_code,
     COALESCE( totales.monto ,0) as monto,
     COALESCE( counts.cantidad_pedidos ,0)as cantidad_pedidos
    FROM sellers s
      LEFT JOIN ( SELECT 
                   MAX(order_gross_amount + order_tax_amount - order_discount_amount) mayor_pedido, 
                   seller_code
                 FROM 
                   orders_header
                 WHERE 
                   order_date BETWEEN :start_date3 AND :end_date3
                 ".$customersQuery."
                   AND company_id IN (".Session::documentCompaniesString().")
                 GROUP BY seller_code 
               ) t 
       on t.seller_code = s.seller_code
      LEFT JOIN (SELECT 
                  COUNT(*) cantidad_pedidos, 
                  seller_code
                FROM 
                  orders_header oh
                WHERE 
                  order_date BETWEEN :start_date2 AND :end_date2
                ".$customersQuery."
                  AND company_id IN (".Session::documentCompaniesString().")
                GROUP BY seller_code
               ) counts 
       on counts.seller_code = s.seller_code
      LEFT JOIN (SELECT 
                  SUM(oh.order_gross_amount + oh.order_tax_amount - oh.order_discount_amount) monto, 
                  seller_code
                FROM 
                  orders_header oh
                WHERE 
                  oh.order_date BETWEEN :start_date AND :end_date
                ".$customersQuery."
                  AND oh.company_id IN (".Session::documentCompaniesString().")
                GROUP BY oh.seller_code
               ) totales 
       on totales.seller_code = s.seller_code
    WHERE s.company_id = ".Session::$company_cxc."
       ".$sellerQuery."
    ORDER BY s.seller_code
     ) z
       ";

       $result = $this->clientCon->query($query,[
           'start_date' => $start_date,
           'end_date' => $end_date,
           'start_date2' => $start_date,
           'end_date2' => $end_date,
           'start_date3' => $start_date,
           'end_date3' => $end_date
       ]);



       $array = [];
       if ($hasStatus) {
           if ($start_date == $end_date && $start_date == date("Y-m-d")) { //Hoy
               for($i=0; $i<count($result);$i++) {
                   if ($result[$i]['monto'] !== 0 && $result[$i]['status'] == 1) { //Inactivo y sin movimientos
                       array_push($array,$result[$i]);
                   }
               }
               $result = $array;
           }
       }
        return $result;

    }

    public function montosDeCotizacionesPorVendedor (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company) {

        $sellerQuery = !is_null($sellers) ? "AND s.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $append ="";
        $hasStatus = CompatibilityChecker::columnExists('status','sellers');
        if ($hasStatus) {
            $append = ' s.status, ';
        }

        $query = "
SELECT
  *,
  COALESCE (
      (  SELECT
           customer_name
         FROM
           customers c
         WHERE
           customer_code = z.mayor_pedido_customer_code
         AND
           c.company_id = ".Session::$company_cxc." LIMIT 1
      ), 'N/A'
  ) mayor_pedido_customer_name
FROM
  (
    SELECT
     s.seller_code,
     s.seller_name,
     ".$append."
     COALESCE (t.mayor_pedido, 0) mayor_pedido,
     COALESCE (
         ( SELECT
             customer_code
           FROM
             quotations_header
               WHERE
                 (order_gross_amount + order_tax_amount - order_discount_amount) = t.mayor_pedido
                 AND company_id
                     IN (".Session::documentCompaniesString().")
                 AND seller_code = s.seller_code    
           LIMIT 1
         ),
         'N/A'
     ) mayor_pedido_customer_code,
     COALESCE( totales.monto ,0) as monto,
     COALESCE( counts.cantidad_pedidos ,0)as cantidad_pedidos
    FROM sellers s
      LEFT JOIN ( SELECT 
                   MAX(order_gross_amount + order_tax_amount - order_discount_amount) mayor_pedido, 
                   seller_code
                 FROM 
                   quotations_header
                 WHERE 
                   order_date BETWEEN :start_date3 AND :end_date3
                 ".$customersQuery."
                   AND company_id IN (".Session::documentCompaniesString().")
                 GROUP BY seller_code 
               ) t 
       on t.seller_code = s.seller_code
      LEFT JOIN (SELECT 
                  COUNT(*) cantidad_pedidos, 
                  seller_code
                FROM 
                  quotations_header oh
                WHERE 
                  order_date BETWEEN :start_date2 AND :end_date2
                ".$customersQuery."
                  AND company_id IN (".Session::documentCompaniesString().")
                GROUP BY seller_code
               ) counts 
       on counts.seller_code = s.seller_code
      LEFT JOIN (SELECT 
                  SUM(oh.order_gross_amount + oh.order_tax_amount - oh.order_discount_amount) monto, 
                  seller_code
                FROM 
                  quotations_header oh
                WHERE 
                  oh.order_date BETWEEN :start_date AND :end_date
                ".$customersQuery."
                  AND oh.company_id IN (".Session::documentCompaniesString().")
                GROUP BY oh.seller_code
               ) totales 
       on totales.seller_code = s.seller_code
    WHERE s.company_id = ".Session::$company_cxc."
       ".$sellerQuery."
    ORDER BY s.seller_code
     ) z
       ";

        $result = $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_date2' => $start_date,
            'end_date2' => $end_date,
            'start_date3' => $start_date,
            'end_date3' => $end_date
        ]);



        $array = [];
        if ($hasStatus) {
            if ($start_date == $end_date && $start_date == date("Y-m-d")) { //Hoy
                for($i=0; $i<count($result);$i++) {
                    if ($result[$i]['monto'] !== 0 && $result[$i]['status'] == 1) { //Inactivo y sin movimientos
                        array_push($array,$result[$i]);
                    }
                }
                $result = $array;
            }
        }
        return $result;

    }



    public function montosDeCobrosPorCobrador (
        $collectors, $start_date, $end_date, $customers, $collectors_condition = "", $customers_condition = "", $company) {

        $collectorsQuery = !is_null($collectors) ? "AND co.collector_code ".$collectors_condition." IN(" .implode(',',$collectors). ")" : "";
        $customersQuery = !is_null($customers) ? "AND rh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $query = "
            SELECT 
			co.collector_code,
			co.collector_name,
			COALESCE (t.mayor_recibo, 0) mayor_recibo,
			(
			SELECT customer_code 
			FROM receivables_header
			WHERE receipt_income_amount = t.mayor_recibo
			    AND company_id IN (".Session::documentCompaniesString().")
			LIMIT 1
			) mayor_recibo_customer_code,
		    (
			SELECT customer_name
			FROM customers cu
			WHERE customer_code = mayor_recibo_customer_code
			   AND cu.company_id = ".Session::$company_cxc."
			LIMIT 1
			) mayor_recibo_customer_name,
			COALESCE((SELECT SUM(rh.receipt_income_amount) 
			          FROM receivables_header rh
			          WHERE rh.collector_code = co.collector_code 
			          ".$customersQuery."
			          AND rh.receipt_income_date BETWEEN :start_date AND :end_date 
			                 AND rh.company_id IN (".Session::documentCompaniesString().")), 0) as monto,
			(
			    SELECT COUNT(*) 
			    FROM  receivables_header rh
			    WHERE rh.receipt_income_date BETWEEN :start_date3 AND :end_date3
			              AND rh.company_id IN (".Session::documentCompaniesString().")
			            AND collector_code = co.collector_code
			) cantidad_recibos      
			   
			FROM collectors co 
			LEFT JOIN (
			            SELECT MAX(receipt_income_amount) mayor_recibo,
			            collector_code
			            FROM receivables_header
			            WHERE receipt_income_date BETWEEN :start_date2 AND :end_date2
			             AND company_id IN (".Session::documentCompaniesString().")
			            GROUP BY collector_code
			          ) t on t.collector_code = co.collector_code    
			WHERE co.company_id = ".Session::$company_cxc."
			".$collectorsQuery."
			ORDER BY co.collector_code ";
        
        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_date2' => $start_date,
            'end_date2' => $end_date,
            'start_date3' => $start_date,
            'end_date3' => $end_date
        ]);
    }

    public function ventasTotalesDelPeriodo (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ){

        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $query = "
        SELECT SUM(order_gross_amount + order_tax_amount - order_discount_amount) total, 
        COUNT(*) cantidad,
        AVG(order_gross_amount + order_tax_amount - order_discount_amount) promedio,
         order_date
        FROM orders_header oh
        WHERE oh.company_id IN (".Session::documentCompaniesString().")
        AND order_date BETWEEN :start_date AND :end_date
        	".$customersQuery."
        	".$sellerQuery."
        GROUP BY order_date	
        ";
        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function cotizacionesTotalesDelPeriodo (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ){

        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $query = "
        SELECT SUM(order_gross_amount + order_tax_amount - order_discount_amount) total, 
        COUNT(*) cantidad,
        AVG(order_gross_amount + order_tax_amount - order_discount_amount) promedio,
         order_date
        FROM quotations_header oh
        WHERE oh.company_id IN (".Session::documentCompaniesString().")
        AND order_date BETWEEN :start_date AND :end_date
        	".$customersQuery."
        	".$sellerQuery."
        GROUP BY order_date	
        ";

        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function ingresosTotalesDelPeriodo(
        $collectors, $start_date, $end_date, $customers, $collectors_condition = "", $customers_condition = "", $company
    )
    {
        $collectorsQuery = !is_null($collectors) ? "AND rh.collector_code ".$collectors_condition." IN(" .implode(',',$collectors). ")" : "";
        $customersQuery = !is_null($customers) ? "AND rh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $query = "
        SELECT SUM(receipt_income_amount) total, receipt_income_date,
            COUNT(*) cantidad,
        AVG(receipt_income_amount) promedio
        FROM receivables_header rh
               WHERE rh.company_id IN (".Session::documentCompaniesString().")
        AND receipt_income_date BETWEEN :start_date AND :end_date
        	".$customersQuery."
        	".$collectorsQuery."
        GROUP BY receipt_income_date	
        ";
        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function efectividadVendedores(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company)
    {
        $sellerQuery = !is_null($sellers) ? "AND x.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND c.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "
            SELECT 
			COUNT(DISTINCT x.customer_code) as pedidos, 
			x.seller_code, 
			s.seller_name, 
			(
			  SELECT COUNT(*) 
			  FROM customers cs 
			  WHERE cs.company_id = x.company_id 
			  AND cs.seller_code = x.seller_code 
			   ".$efdates." 
			   ".$routes."
			) as cantidadclientes
			
			FROM orders_header x
			LEFT JOIN customers c ON (c.seller_code = x.seller_code
			AND c.company_id = x.company_id
			AND c.customer_code = x.customer_code) 
			LEFT JOIN sellers s ON (s.seller_code = x.seller_code
			AND s.company_id = x.company_id) 
			WHERE
			x.company_id = :cid AND
			".$sellerQuery."
			".$customersQuery."
			".$ordersdates."
			GROUP BY x.seller_code, s.seller_name
        ";

    }

}