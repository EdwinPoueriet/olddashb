<?php
/**
 * Created by PhpStorm.
 * User: nel
 * Date: 20/07/17
 * Time: 04:05 PM
 */

namespace App\Repositories;


use App\Common\CompatibilityChecker;
use App\Exceptions\TableDoesNotExistsException;

class EffectivenessRepository extends Repository
{


    public function getPeriodTotalEffectiveness(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    )
    {
        $sellerQuery = !is_null($sellers) ? "AND s.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) ? "AND v.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $query = "
                      SELECT
                          COUNT(CASE WHEN visit_type_id = 0 then 1 ELSE NULL END) efectivas,
                          COUNT(CASE WHEN visit_type_id <> 0 then 1 ELSE NULL END) noefectivas
                        FROM
                          visits v, sellers s
                        WHERE
                          s.seller_code = v.seller_code
                          AND v.visit_date BETWEEN :start_date AND :end_date
                          AND v.company_id = :company
                           {$customersQuery}
                           {$sellerQuery}
                  ";

        return $this->clientCon->row($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'company' => $company
        ]);
    }

    public function efectividadPorVendedorConDia (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company)
    {
        if (CompatibilityChecker::tableExists(['visits', 'visit_details','visit_types'])) {
            $sellerQuery = !is_null($sellers) ? "AND s.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
            $customersQuery = !is_null($customers) ? "AND v.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
            $query = "
                    SELECT
                      s.seller_code,
                      s.seller_name,
                      z.efectivas,
                      z.noefectivas,
                      (z.noefectivas+z.efectivas) total,
                      z.dia,
                      (z.efectivas / (z.efectivas+z.noefectivas)) efectividad
                    FROM
                      sellers s,
                      (
                        SELECT
                          COUNT(CASE WHEN visit_type_id = 0 then 1 ELSE NULL END) efectivas,
                          COUNT(CASE WHEN visit_type_id <> 0 then 1 ELSE NULL END) noefectivas,
                          v.seller_code,
                          DAYOFWEEK(v.visit_date) dia
                        FROM
                          visits v
                        WHERE
                          v.visit_date BETWEEN :start_date AND :end_date
                          AND v.company_id = :company2
                           {$customersQuery}
                        GROUP BY v.seller_code, dia
                      ) z
                    WHERE
                      s.company_id = :company
                      AND s.seller_code = z.seller_code;
                      {$sellerQuery}
                  ";

            return $this->clientCon->query($query,[
                'start_date' => $start_date,
                'end_date' => $end_date,
                'company' => $company,
                'company2' =>$company
            ]);

        } else {
            throw new TableDoesNotExistsException('Las tablas requeridas en la DB no existen.');
        }
    }


    public function efectividadPorVendedorTotales(
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company,$order = 's.seller_code',$method = 'ASC'
    )
    {
        $sellerQuery = !is_null($sellers) && !empty($sellers) ? "AND s.seller_code ".$sellers_condition." IN(" .implode(',',$sellers). ")" : "";
        $customersQuery = !is_null($customers) && !empty($customers)  ? "AND v.customer_code ".$customers_condition." IN(" .implode(',',$customers). ") " : "";
        $query = "
                    SELECT
                      s.seller_code,
                      s.seller_name,
                      z.efectivas,
                      z.noefectivas,
                      (z.noefectivas+z.efectivas) total,
                      (z.efectivas / (z.efectivas+z.noefectivas)) efectividad
                    FROM
                      sellers s,
                      (
                        SELECT
                          COUNT(CASE WHEN visit_type_id = 0 then 1 ELSE NULL END) efectivas,
                          COUNT(CASE WHEN visit_type_id <> 0 then 1 ELSE NULL END) noefectivas,
                          v.seller_code
                        FROM
                          visits v
                        WHERE
                          v.visit_date BETWEEN :start_date AND :end_date
                           AND v.company_id = :company2
                          {$customersQuery}
                        GROUP BY v.seller_code
                      ) z
                    WHERE
                      s.company_id = :company
                      AND s.seller_code = z.seller_code
                      {$sellerQuery}
                      
                  ";

        $query.=" ORDER BY ".$order." ".$method;

        return $this->clientCon->query($query,[
            'start_date' => $start_date,
            'end_date' => $end_date,
            'company2' => $company,
            'company' => $company
        ]);

    }
}