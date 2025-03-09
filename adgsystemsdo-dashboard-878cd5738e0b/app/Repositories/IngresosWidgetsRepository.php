<?php

namespace App\Repositories;


use App\Legacy\Session;

class IngresosWidgetsRepository extends Repository
{

    public function totalDeTodosLosRecibos (
        $collectors, $start_date, $end_date, $customers, $collectors_condition = "", $customers_condition = "", $company
    ) {

        $collectorsQuery = !is_null($collectors) ? "AND rh.collector_code ".$collectors_condition." IN(" .implode(',',$collectors). ")" : "";
        $customersQuery = !is_null($customers) ? "AND rh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			SUM(receipt_income_amount) as total 
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

    public function cantidadDeRecibos (
        $collectors, $start_date, $end_date, $customers, $collectors_condition = "", $customers_condition = "", $company
    ) {
        $collectorQuery = !is_null($collectors) ? "AND rh.seller_code ".$collectors_condition." IN(" .implode(',',$collectors). ")" : "";
        $customersQuery = !is_null($customers) ? "AND rh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			COUNT(*) cantidad
			FROM receivables_header rh
			WHERE rh.company_id IN (".Session::documentCompaniesString().")
			AND receipt_income_date BETWEEN :start_date AND :end_date 
			".$collectorQuery."
			".$customersQuery."
			";
        return $this->clientCon->row($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

    }

    public function promedioDeRecibosDeCobrador ($montosDeReciboPorCobrador) {
        $total = 0;
        foreach ($montosDeReciboPorCobrador as $cobrador) {
            $total += $cobrador['monto'];
        }
        return $total/count($montosDeReciboPorCobrador);
    }

    public function promedioDeRecibos (
        $collectors, $start_date, $end_date, $customers, $collectors_condition = "", $customers_condition = "", $company
    ) {
        $collectorQuery = !is_null($collectors) ? "AND rh.collector_code ".$collectors_condition." IN(" .implode(',',$collectors). ")" : "";
        $customersQuery = !is_null($customers) ? "AND rh.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";

        $query = "SELECT 
			AVG(receipt_income_amount) as promedio 
			FROM receivables_header rh
			WHERE rh.company_id IN (".Session::documentCompaniesString().")
			AND receipt_income_date BETWEEN :start_date AND :end_date 
			".$collectorQuery."
			".$customersQuery."
			";
        return $this->clientCon->row($query,[
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

    }

}
