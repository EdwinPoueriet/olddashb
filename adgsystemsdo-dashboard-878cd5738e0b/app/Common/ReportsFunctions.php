<?php
namespace App\Common;

trait ReportsFunctions {

    public   function formatNum ($num) {
        $num = floatval($num);
        return number_format($num, 2, '.', ',');
    }
}