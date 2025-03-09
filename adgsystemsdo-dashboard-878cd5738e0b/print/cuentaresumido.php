<?php


function convertNumber ($num) {
    $num = floatval($num);
    return number_format($num, 2, '.', ',');
}
use App\Legacy\General;

$general = new General();
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - Vista de Impresión</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />


    <style>
        .company-name {
            font-weight: 600;
            font-size: 14px;
        }

        .collectortitle{
            font-weight: 600;
            font-size: 13px;

        }
    </style>
    <style>
        td{
            font-size: 10px;
            padding-bottom: 3px !important;
            padding-top: 3px !important;
        }
        th {
            font-size: 11px;
        }
        .foooter > td {
            font-size: 11px;
            font-weight: 600;
        }

        @media print {

            @page {
                size: letter;
                margin-bottom: 15px;
            }
            th {
                font-size: 11px !important;
            }
            .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                float: left;
            }
            .col-sm-12 {
                width: 100%;
            }
            .col-sm-11 {
                width: 91.66666667%;
            }
            .col-sm-10 {
                width: 83.33333333%;
            }
            .col-sm-9 {
                width: 75%;
            }
            .col-sm-8 {
                width: 66.66666667%;
            }
            .col-sm-7 {
                width: 58.33333333%;
            }
            .col-sm-6 {
                width: 50%;
            }
            .col-sm-5 {
                width: 41.66666667%;
            }
            .col-sm-4 {
                width: 33.33333333%;
            }
            .col-sm-3 {
                width: 25%;
            }
            .col-sm-2 {
                width: 16.66666667%;
            }
            .col-sm-1 {
                width: 8.33333333%;
            }
            .col-sm-pull-12 {
                right: 100%;
            }
            .col-sm-pull-11 {
                right: 91.66666667%;
            }
            .col-sm-pull-10 {
                right: 83.33333333%;
            }
            .col-sm-pull-9 {
                right: 75%;
            }
            .col-sm-pull-8 {
                right: 66.66666667%;
            }
            .col-sm-pull-7 {
                right: 58.33333333%;
            }
            .col-sm-pull-6 {
                right: 50%;
            }
            .col-sm-pull-5 {
                right: 41.66666667%;
            }
            .col-sm-pull-4 {
                right: 33.33333333%;
            }
            .col-sm-pull-3 {
                right: 25%;
            }
            .col-sm-pull-2 {
                right: 16.66666667%;
            }
            .col-sm-pull-1 {
                right: 8.33333333%;
            }
            .col-sm-pull-0 {
                right: auto;
            }
            .col-sm-push-12 {
                left: 100%;
            }
            .col-sm-push-11 {
                left: 91.66666667%;
            }
            .col-sm-push-10 {
                left: 83.33333333%;
            }
            .col-sm-push-9 {
                left: 75%;
            }
            .col-sm-push-8 {
                left: 66.66666667%;
            }
            .col-sm-push-7 {
                left: 58.33333333%;
            }
            .col-sm-push-6 {
                left: 50%;
            }
            .col-sm-push-5 {
                left: 41.66666667%;
            }
            .col-sm-push-4 {
                left: 33.33333333%;
            }
            .col-sm-push-3 {
                left: 25%;
            }
            .col-sm-push-2 {
                left: 16.66666667%;
            }
            .col-sm-push-1 {
                left: 8.33333333%;
            }
            .col-sm-push-0 {
                left: auto;
            }
            .col-sm-offset-12 {
                margin-left: 100%;
            }
            .col-sm-offset-11 {
                margin-left: 91.66666667%;
            }
            .col-sm-offset-10 {
                margin-left: 83.33333333%;
            }
            .col-sm-offset-9 {
                margin-left: 75%;
            }
            .col-sm-offset-8 {
                margin-left: 66.66666667%;
            }
            .col-sm-offset-7 {
                margin-left: 58.33333333%;
            }
            .col-sm-offset-6 {
                margin-left: 50%;
            }
            .col-sm-offset-5 {
                margin-left: 41.66666667%;
            }
            .col-sm-offset-4 {
                margin-left: 33.33333333%;
            }
            .col-sm-offset-3 {
                margin-left: 25%;
            }
            .col-sm-offset-2 {
                margin-left: 16.66666667%;
            }
            .col-sm-offset-1 {
                margin-left: 8.33333333%;
            }
            .col-sm-offset-0 {
                margin-left: 0%;
            }
            .visible-xs {
                display: none !important;
            }
            .hidden-xs {
                display: block !important;
            }
            table.hidden-xs {
                display: table;
            }
            tr.hidden-xs {
                display: table-row !important;
            }
            th.hidden-xs,
            td.hidden-xs {
                display: table-cell !important;
            }
            .hidden-xs.hidden-print {
                display: none !important;
            }
            .hidden-sm {
                display: none !important;
            }
            .visible-sm {
                display: block !important;
            }
            table.visible-sm {
                display: table;
            }
            tr.visible-sm {
                display: table-row !important;
            }
            th.visible-sm,
            td.visible-sm {
                display: table-cell !important;
            }
        }

    </style>


</head>

<body>
<div class="row">
    <div class="col-sm-6">
        <div class="company-name">
            <?php echo General::$company_details['company_name'] ?>
        </div>
<!--        <div class="company-details">-->
<!--            <div class="row">-->
<!--                <div class="col-sm-12">-->
<!--                    --><?php //echo General::$company_details['company_address'] ?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="row">-->
<!--                <div class="col-sm-12">-->
<!--                    --><?php //echo General::$company_details['company_phones'] ?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="row">-->
<!--                <div class="col-sm-12">-->
<!--                    --><?php //echo General::$company_details['company_rnc'] ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </div>
    <div class="col-sm-6" style="text-align: center">
        <div class="row">
            <div class="col-sm-12">
                <h4>REPORTE DE CUENTAS POR COBRAR</h4>
                <span>RESUMIDO</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="font-size: 12px">
                Rango:
                <?php echo $_POST['rango'] ?>
            </div>
        </div>

    </div>

</div>

<div class="row" style="margin-top: 18px">
    <div class="col-sm-12">

        <?php

        $grand_count = 0;
        $grand_total = 0;



        function printHeader () {
            echo "
     <table class=\"table table-compact \">

            <tr>
                <th>Vendedor</th>
                <th>Cuentas Por Cobrar</th>
                <th style='text-align:right'>Total</th>
            </tr>
            <tbody>
            ";
        }

        function printFooter($cuentas, $total) {
            echo ' <tfoot>
            <tr class=\'foooter\'>
            <td>Totales</td>
            <td> '.$cuentas.'</td>
            <td style=\'text-align:right\'> '.convertNumber($total).'</td>
            </tr>
            </tfoot>';
        }

        if(isset($_POST['rango'])){

            $_POST['rango_vnc'] = '';
        }else{
            $_POST['rango'] = '';


        }

        if ($_POST['company'] == 'consolidado') {
            if ($_POST['consolidado_group'] == 'consolidado') {
                printHeader();
                foreach (json_decode($general->CuentaCobrarCompact($_POST)) as $seller_id => $Accounts){
                    $count = 0;
                    $total = 0;
                    foreach ($Accounts as $Account) {
                        $count += $Account->COUNT;
                        $total += $Account->total;
                        echo '
                     <tr>
                  
                        <td>'.$Account->seller_code.' - '.$Account->seller_name.'</td>
                             <td >'.$Account->COUNT.'</td>
                   
                              <td style=\'text-align:right\'>'.convertNumber($Account->total).'</td>
                   </tr>
                   
                   ';

                    }

                    $grand_count += $count;
                    $grand_total += $total;



                }
                echo "</tbody>";
                printFooter($grand_count,$grand_total);
                echo "</table>";
            }
            else if ($_POST['consolidado_group'] == 'separado') {
                foreach (json_decode($general->CuentaCobrarCompact($_POST)) as $company => $Accounts){
                    ///Company
                    printHeader();
                    $count = 0;
                    $total = 0;
                    $grand_count = 0;
                    $grand_total = 0;
                    ?>
                    <h4>Compañía <?php echo $company?></h4>
                    <?php
                    foreach ($Accounts as $Account) {

                        $count += $Account[0]->COUNT;
                        $total += $Account[0]->total;
                        echo '
                     <tr>
                  
                        <td>'.$Account[0]->seller_code.' - '.$Account[0]->seller_name.'</td>
                             <td>'.$Account[0]->COUNT.'</td>
                   
                              <td style=\'text-align:right\'>'.convertNumber($Account[0]->total).'</td>
                   </tr>
                   
                   ';

                    }

                    $grand_count += $count;
                    $grand_total += $total;
                    echo "</tbody>";
                    printFooter($grand_count,$grand_total);
                    echo "</table>";
//
                }

            }
        } else {
            $data = json_decode($general->VentasReportCompact($_POST));
            printHeader();
            ;
            foreach (json_decode($general->CuentaCobrarCompact($_POST)) as $seller_id => $Accounts){

                $count = 0;
                $total = 0;
                foreach ($Accounts as $Account) {

                    $count += $Account[0]->COUNT;
                    $total += $Account[0]->total;
                    echo '
                     <tr>
                  
                        <td>'.$Account[0]->seller_code.' - '.$Account[0]->seller_name.'</td>
                             <td >'.$Account[0]->COUNT.'</td>
                   
                              <td style=\'text-align:right\'>'.convertNumber($Account[0]->total).'</td>
                   </tr>
                   
                   ';

                }

                $grand_count += $count;
                $grand_total += $total;



            }
            echo "</tbody>";
            printFooter($grand_count,$grand_total);
            echo "</table>";
        }

        ?>


        <script type="text/javascript">
          window.onload = function() { window.print(); }
        </script>
    </div>
</div>
</body>
</html>



