<?php /** @noinspection ALL */

namespace App\Repositories;

use App\Legacy\Session;

class CotizacionesWidgetsRepository extends Repository
{

    public function promediosDeVendedores ($montosDeCotizacionesPorvendedor) {
        $total = 0;
        foreach ($montosDeCotizacionesPorvendedor as $vendedor) {
            $total += $vendedor['monto'];
        }
        $count = count($montosDeCotizacionesPorvendedor) ;
        if ($count > 0)
            return $total/$count;
        else
            return 0;
    }

    public function totalDeTodasLasCotizaciones (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ) {
        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			SUM(order_gross_amount + order_tax_amount - order_discount_amount) as total 
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

    public function cantidadDeCotizaciones (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ) {
        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			COUNT(*) cantidad
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

    public function promedioDeCotizaciones (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ) {
        $sellerQuery = !is_null($sellers) ? "AND oh.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND oh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			AVG(order_gross_amount + order_tax_amount - order_discount_amount) as promedio 
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

}
