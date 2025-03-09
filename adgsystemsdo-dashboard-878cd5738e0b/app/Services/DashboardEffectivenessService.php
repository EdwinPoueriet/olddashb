<?php

namespace App\Services;
use App\Legacy\Session;
use App\Repositories\EffectivenessRepository;
use Carbon\Carbon;

class DashboardEffectivenessService extends  Session
{

    public function getEffectivenessBySeller (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    )
    {
        $repository = new EffectivenessRepository();
        $result = $repository->efectividadPorVendedorConDia(
            $sellers, $start_date, $end_date, $customers, $sellers_condition , $customers_condition , $company
        );

        $grouped = array_group_by($result, 'seller_code');

        $sellers = [];

        foreach ($grouped as $seller_code => $seller) {

            $sellers[$seller_code]['seller_name'] = $seller[0]['seller_name'];
            $sellers[$seller_code]['seller_code'] = $seller_code;

            if (!isset($sellers[$seller_code])) {
                $sellers[$seller_code] = [];
                $sellers[$seller_code]['by_day'] = [];
                $sellers[$seller_code]['totals'] = [];
            }
            $byday =  &$sellers[$seller_code]['by_day'];
            $totals = &$sellers[$seller_code]['totals'];

            foreach ($seller as $day) {
                $byday[$day['dia']] = [
                    'efectivas' => $day['efectivas'],
                    'noefectivas' => $day['noefectivas'],
                    'total' => $day['total'],
                    'efectividad' => $day['efectividad']
                ];
            }

            $totales = array_reduce($byday, function ($v1, $v2) {
                return [
                   'efectivas'      =>  $v1['efectivas']+$v2['efectivas'],
                   'noefectivas'    =>  $v1['noefectivas']+$v2['noefectivas'],
                ];
            }, ['efectivas' => 0, 'noefectivas' => 0]);


            $total =  $totales['noefectivas'] + $totales['efectivas'];
            $totals = [
                'efectivas' => $totales['efectivas'],
                'noefectivas' => $totales['noefectivas'],
                'total' => $total,
                'efectividad' =>$totales['efectivas']/$total
            ];
        }

        return $sellers;

    }

    public function getCurrentAndPreviousPeriodTotalEffectivenes (
        $sellers, $start_date, $end_date, $customers, $sellers_condition = "", $customers_condition = "", $company
    ) {
        $repository = new EffectivenessRepository();

        $actual = $repository->getPeriodTotalEffectiveness(
            $sellers, $start_date, $end_date, $customers, $sellers_condition , $customers_condition , $company
        );

        if ($actual['efectivas'] == 0 && $actual['noefectivas'] == 0 ) {
            $actualValue = 0;
        } else {
            $actualValue =  $actual['efectivas'] / ($actual['efectivas'] + $actual['noefectivas']);
        }

        $start =  Carbon::createFromFormat('Y-m-d', $start_date);
        $end =   Carbon::createFromFormat('Y-m-d', $end_date);

        if ($start_date == $end_date) {
            return [
                'actual' =>  $actualValue,
                'anterior' => null,
                'rangos' => [
                    'actual' => 'Desde '. $start->copy()->format('d-m-Y') .' hasta '. $end->copy()->format('d-m-Y'),
                    'anterior' => null
                ]
            ];
        } else {
            $newstart = $start->copy()->subDays($end->copy()->diffInDays($start))->subDay();

            $newend = $start->copy()->subDay()->format('Y-m-d');

            $previous = $repository->getPeriodTotalEffectiveness(
                $sellers, $newstart->copy()->format('Y-m-d'), $newend,
                $customers, $sellers_condition , $customers_condition, $company
            );

            if ($previous['efectivas'] == 0 && $previous['noefectivas'] == 0 ) {
                $prevValue = 0;
            } else {
                $prevValue =  $previous['efectivas'] / ($previous['efectivas'] + $previous['noefectivas']);
            }

            return [
                'actual' =>  $actualValue,
                'anterior' => $prevValue,
                'rangos' => [
                    'actual' => 'Desde '. $start->format('d-m-Y') .' hasta '. $end->format('d-m-Y'),
                    'anterior' => 'Desde '. $newstart->copy()->format('d-m-Y').' hasta '. $start->copy()->subDay()->format('d-m-Y')
                ]
            ];
        }

    }


}