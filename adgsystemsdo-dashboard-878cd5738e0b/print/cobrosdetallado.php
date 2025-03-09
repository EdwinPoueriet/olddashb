<?php



function convertNumber ($num) {
    $num = floatval($num);
    return number_format($num, 2, '.', ',');
}
use App\Legacy\General;

$general = new General();
$request = null;


        $request =  $general->CobrosReportFull2($_POST);



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
        /*table {*/
            /*table-layout: fixed ;*/
            /*width: 100% ;*/
        /*}*/
        .foooter > td {
            font-size: 11px;
            font-weight: 600;
        }

        table {
            width: 100%;
            margin: 0px auto;
            table-layout: auto;
        }

        .fixed {
            table-layout: fixed;
        }

        table,
        td,
        th {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0px;
            /*border: solid 1px;*/
            /*text-align: center;*/
        }
        @media print {
            /*@page {}*/

            #date{
                width: 1px;
            }
            @page {
                size: landscape;
                /*size: letter;*/
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
    <div class="col-sm-6" style="text-align: right">
        <div class="row">
            <div class="col-sm-12">
                <h4>REPORTE DE COBROS</h4>
                <span>DETALLADO
                <?php
                $montos = $request['options']['montos'];
                if($montos !== 'todos'){
                    if ($montos == 'efectivo'){
                        echo '- SOLO EFECTIVO';
                    }
                    if ($montos == 'cheques'){
                        echo '- SOLO CHEQUES';
                    }
                    if ($montos == 'futuristas'){
                        echo '- SOLO CHEQUES FUTURISTAS';
                    }
                }
                ?>
                </span>
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
        $grand_check = 0;
        $grand_cash = 0;
        $grand_total = 0;
        $grand_futuristic = 0;

        function printFooter ($cash_total,$check_total,$total,$futuristic) {
            echo ' 
            <tr class=\'foooter\'>
            <td>Totales</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        
                   <td></td>
              <td></td>
             <td style=\'text-align:right\'>'.convertNumber($cash_total).'</td>  
             <td style=\'text-align:right\'>'.convertNumber($check_total).'</td>

             <td style=\'text-align:right\'>'.convertNumber($total).'</td>
     
            </tr>
           ';
        }

        function printHeader ($collector_id,$collector_name) {
            echo "    <div class=\"collectortitle\">
                                      ".$collector_id." - ".$collector_name."
                                   </div> ";
            echo '<table class="table table-compact ">
    <tr>
      <th><i class="fa fa-cloud"></i></th>
      <th id="date">Fecha</th>
      <th>Cliente</th>
      <th><i style="font-size: 16px" class="fa fa-map-marker"></i></th>
      <th>Código ERP</th>
      <th>No. Recibo</th>
      <th nowrap style=\'text-align:right\'># Cheque</th>
      <th style=\'text-align:right\'>Efectivo</th>
      <th style=\'text-align:right\'>Cheque</th>

      <th style=\'text-align:right\'>Total</th>
    </tr>
    <tbody>';
        }

        function companyTotal ($company,$cash,$check,$futuristic,$total) {
            echo ' <div style="text-align: right">
         <div> <span style="font-size: 12px"><b> Totales Compañía '.$company.'</b></span> </div>
        <div>
          <span style="font-size: 12px"> Total Efectivo :   <b>'.convertNumber($cash).'</b></span>
        </div>
        <div>
          <span style="font-size: 12px"> Total Cheques :   <b>'.convertNumber($check).'</b></span>
        </div>
       
        <div>
          <span style="font-size: 12px"> Total Neto :   <b>'.convertNumber($total).'</b></span>
        </div>
      </div>';
        }


        function printReceipt ($receipt) {


            $dt = new \Datetime($receipt->receipt_income_date_time);
            echo "
                                         <tr>
                                            <td>";

            if ($receipt->status == 1)
                echo '<i class="fa fa-check"></i> ';
            echo "
                </td>                         
               <td >".$dt->format("d-m-Y h:i A")."</td>
               <td>".$receipt->customer_code." - ".$receipt->customer_name."</td>
                <td>".$receipt->in_location."</td>
 
               <td>".$receipt->receipt_code."</td> 
               <td>".$receipt->receipt_income_reference."</td> 
          <td style='text-align:right'>".$receipt->check_number."</td>
               ";

            echo "
              <td style='text-align:right'>".convertNumber($receipt->cash_amount)."</td>
              <td style='text-align:right'>".convertNumber($receipt->check_amount)."</td>
    
              
              <td style='text-align:right'>".convertNumber($receipt->receipt_income_amount)."</td>
              
              
              ";

            echo "</tr>";
        }
        if (isset($request) && $request) {

            if ($_POST['company'] == 'consolidado') {
                if ($_POST['consolidado_group'] == 'consolidado') {

                    foreach (json_decode($request['data']) as $key => $collectors){

                        printHeader($key,$collectors[0]->collector_name);

                        $check_total = 0;
                        $cash_total = 0;
                        $total = 0;
                        $futuristic = 0;
                        $count = 0;

                        foreach ($collectors as $receipts => $receipt) {

                            $check_total+=$receipt->check_amount;
                            $cash_total+=$receipt->cash_amount;
                            $total +=$receipt->receipt_income_amount;

                            if ($receipt->futuristic_check == 1) {
                                $futuristic+=$receipt->futuristic_check_amount;
                            }

                            printReceipt($receipt);

                        }




                        $grand_check += $check_total;
                        $grand_cash += $cash_total;
                        $grand_total += $total;
                        $grand_futuristic += $futuristic;

                        echo "</tbody>";
                        printFooter($cash_total,$check_total,$total,$futuristic);
                        echo "</table>";

                    }
                }
                else if ($_POST['consolidado_group'] == 'separado') {
                    foreach (json_decode($request['data']) as $company => $collectors){

                        $company_cash = 0;
                        $company_check = 0;
                        $company_futuristic = 0;
                        $company_neto = 0;
                        $count = 0;

                        echo '<hr><h4>Compañía '.$company.'</h4>';
                        foreach ($collectors as $collector => $receipts) {

                            printHeader($collector,$receipts[0]->collector_name);

                            $check_total = 0;
                            $cash_total = 0;
                            $total = 0;
                            $futuristic = 0;

                            foreach ($receipts as $receipt) {
                                $check_total+=$receipt->check_amount;
                                $cash_total+=$receipt->cash_amount;
                                $total +=$receipt->receipt_income_amount;

                                if ($receipt->futuristic_check == 1) {
                                    $futuristic+=$receipt->futuristic_check_amount;
                                }
                                printReceipt($receipt);
                            }

                            $company_cash += $cash_total;
                            $company_check  += $check_total;
                            $company_futuristic  += $futuristic;
                            $company_neto += $total;

                            $count += count($receipts);
                            $grand_check += $check_total;
                            $grand_cash += $cash_total;
                            $grand_total += $total;
                            $grand_futuristic += $futuristic;

                            echo "</tbody>";
                            printFooter($cash_total,$check_total,$total,$futuristic);
                            echo "</table>";
                        }
                        companyTotal($company,$company_cash,$company_check,$company_futuristic,$company_neto);
                    }
                }
            } else{
                $data = json_decode($request['data']) ;
                foreach (reset($data) as $collector => $receipts ) {
                    printHeader($collector,$receipts[0]->collector_name);

                    $check_total = 0;
                    $cash_total = 0;
                    $total = 0;
                    $futuristic = 0;
                    $count = 0;

                    foreach ($receipts as $receipt) {
                        $check_total+=$receipt->check_amount;
                        $cash_total+=$receipt->cash_amount;
                        $total +=$receipt->receipt_income_amount;

                        if ($receipt->futuristic_check == 1) {
                            $futuristic+=$receipt->futuristic_check_amount;
                        }
                        printReceipt($receipt);
                    }

                    $count += count($receipts);

                    $grand_check += $check_total;
                    $grand_cash += $cash_total;
                    $grand_total += $total;
                    $grand_futuristic += $futuristic;

                    echo "</tbody>";
                    printFooter($cash_total,$check_total,$total,$futuristic);
                    echo "</table>";
                }
            }
        }


        ?>
        <hr>
        <!--    <div style="text-align: right">-->
        <!---->
        <!--      <div>-->
        <!--        <span style="font-size: 14px"> Total Efectivo :   <b>--><?php //echo convertNumber($grand_cash) ?><!--</b></span>-->
        <!--      </div>-->
        <!--      <div>-->
        <!--        <span style="font-size: 14px"> Total Cheques :    <b>--><?php //echo convertNumber($grand_check) ?><!--</b></span>-->
        <!--      </div>-->
        <!--      <div>-->
        <!--        <span style="font-size: 14px"> Total Cheques Futuristas :   <b>--><?php //echo convertNumber($grand_futuristic) ?><!--</b></span>-->
        <!--      </div>-->
        <!--      <div>-->
        <!--        <span style="font-size: 14px"> Total General :    <b>--><?php //echo convertNumber($grand_total) ?><!--</b></span>-->
        <!--      </div>-->
        <!--    </div>-->

        <table class="table">
            <thead>
            <tr>
                <th scope="col" style="text-align: right">Total Efectivo</th>
                <th scope="col" style="text-align: right">Total Cheques</th>
<!--                <th scope="col" style="text-align: right">Total Cheques Futuristas</th>-->
                <th scope="col" style="text-align: right">Total General</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row" style="text-align: right"><?php echo convertNumber($grand_cash) ?></th>
                <td style="text-align: right"><?php echo convertNumber($grand_check) ?></td>
<!--                <td style="text-align: right">--><?php //echo convertNumber($grand_futuristic) ?><!--</td>-->
                <td style="text-align: right"><?php echo convertNumber($grand_total) ?></td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">
          window.onload = function() { window.print(); }
        </script>
    </div>
</body>
</html>



