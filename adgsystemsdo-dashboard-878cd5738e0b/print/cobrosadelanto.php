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
    <div class="col-sm-6" style="text-align: right">
        <div class="row">
            <div class="col-sm-12">
                <h4>REPORTE DE COBROS DE ADELANTO</h4>
                <span>DETALLADO</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" style="font-size: 12px">
                Compañía <?php echo General::$company_details['company_id'] ?>, Rango:
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
        $grand_card = 0;

        function printHeader ($collector_id,$collector_name) {
            echo "    <div class=\"collectortitle\">
                                      ".$collector_id." - ".$collector_name."
                                   </div> ";
            echo ' <table class="table table-compact ">

            <tr>
                <th><i class="fa fa-cloud"></i></th>
                <th>Fecha</th>
                <th>Referencia</th>
                <th>Cliente</th>
                <th style=\'text-align:right\'>Cheque</th>
                <th style=\'text-align:right\'>Efectivo</th>
                <th style=\'text-align:right\'>Tarjeta</th>
                <th style=\'text-align:right\'>Total</th>
                <th>Concepto</th>
               
            </tr>
            <tbody>';
        }

        function printReceipt ($receipt) {
            $dt = new \Datetime($receipt->date);
            echo "
                                         <tr>
                                            <td>";
            if ($receipt->status == 1)
                echo '<i class="fa fa-check"></i> ';
            echo "</td>
                                            <td nowrap>".$dt->format("d-m-Y")."</td>
                                            <td>".$receipt->advance_receipt_code."</td>
                                            <td>".$receipt->customer_code." - ".$receipt->customer_name."</td>
                                            <td style='text-align:right'>".convertNumber($receipt->check_amount)."</td>
                                            <td style='text-align:right'>".convertNumber($receipt->cash_amount)."</td>
                                            <td style='text-align:right'>".convertNumber($receipt->card_amount)."</td>
                                            <td style='text-align:right'>".convertNumber($receipt->amount)."</td>
                                             <td>".$receipt->concept_one."</td>
                                               
                                         </tr>
                                      ";
        }

        function printFooter ($check_total,$cash_total,$card_total,$total) {
          echo '
          <tfoot>
            <tr class=\'foooter\'>
            <td>Totales</td>
            <td></td>
            <td></td>
            <td></td>
            <td style=\'text-align:right\'>'.convertNumber($check_total).'</td>
            <td style=\'text-align:right\'>'.convertNumber($cash_total).'</td>
            <td style=\'text-align:right\'>'.convertNumber($card_total).'</td>
            <td style=\'text-align:right\'>'.convertNumber($total).'</td>
            <td></td>
            </tr>

            </tfoot>';
        }


        if ($_POST['company'] == 'consolidado') {
            if ($_POST['consolidado_group'] == 'consolidado') {

                foreach (json_decode($general->CobrosAdelanto($_POST)) as $collector_code => $receipts){ ///Company
                      printHeader($collector_code,$receipts[0]->collector_name);

                        $check_total = 0;
                        $cash_total = 0;
                        $card_total = 0;
                        $total = 0;

                        foreach ($receipts as $receipt) {
                            $check_total+=$receipt->check_amount;
                            $cash_total+=$receipt->cash_amount;
                            $total +=$receipt->amount;
                            $card_total+=$receipt->card_amount;
                            printReceipt($receipt);
                        }

                        $grand_check += $check_total;
                        $grand_cash += $cash_total;
                        $grand_total += $total;
                        $grand_card += $card_total;


                        echo "</tbody>";
                        printFooter($check_total,$cash_total,$card_total,$total);
                        echo "</table>";

                    }
                }

            else if ($_POST['consolidado_group'] == 'separado') {

                foreach (json_decode($general->CobrosAdelanto($_POST)) as $company => $collectors){
                    echo '<hr><h4>Compañía '.$company.'</h4>';
                    foreach ($collectors as $collector => $receipts) {

                        printHeader($collector,$receipts[0]->collector_name);

                        $check_total = 0;
                        $cash_total = 0;
                        $total = 0;
                        $card_total = 0;

                        foreach ($receipts as $receipt) {
                            $check_total+=$receipt->check_amount;
                            $cash_total+=$receipt->cash_amount;
                            $total +=$receipt->amount;
                            $card_total+=$receipt->card_amount;
                            printReceipt($receipt);
                        }

                        $grand_check += $check_total;
                        $grand_cash += $cash_total;
                        $grand_total += $total;
                        $grand_card += $card_total;

                        echo "</tbody>";
                        printFooter($check_total,$cash_total,$card_total,$total);
                        echo "</table>";
                    }
                }
            }
        } else{
            foreach (reset(json_decode($general->CobrosAdelanto($_POST))) as $collector => $receipts) {
                printHeader($collector,$receipts[0]->collector_name);

                $check_total = 0;
                $cash_total = 0;
                $total = 0;
                $card_total = 0;

                foreach ($receipts as $receipt) {
                    $check_total+=$receipt->check_amount;
                    $cash_total+=$receipt->cash_amount;
                    $total +=$receipt->amount;
                    $card_total+=$receipt->card_amount;
                    printReceipt($receipt);
                }

                $grand_check += $check_total;
                $grand_cash += $cash_total;
                $grand_total += $total;
                $grand_card += $card_total;

                echo "</tbody>";
                printFooter($check_total,$cash_total,$card_total,$total);
                echo "</table>";
            }
        }


        ?>

      <hr>

        <table class="table">
            <thead>
            <tr>
                <th scope="col" style="text-align: right">Total Efectivo:</th>
                <th scope="col" style="text-align: right">Total Cheques:</th>
                <th scope="col" style="text-align: right">Total Tarjeta:</th>
                <th scope="col" style="text-align: right">Total General:</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: right"><?php echo convertNumber($grand_cash) ?></td>
                <td style="text-align: right"><?php echo convertNumber($grand_check) ?></td>
                <td style="text-align: right"><?php  echo convertNumber($grand_card) ?></td>
                <td style="text-align: right"><?php echo convertNumber($grand_total) ?></td>
            </tr>
            </tbody>
        </table>
<!--      <div style="text-align: right">-->
<!--        <div >-->
<!--          <span style="font-size: 14px"> Total Efectivo:   <b>--><?php //echo convertNumber($grand_cash) ?><!--</b></span>-->
<!--        </div>-->
<!--        <div>-->
<!--          <span style="font-size: 14px"> Total Cheques :    <b>--><?php //echo convertNumber($grand_check) ?><!--</b></span>-->
<!--        </div>-->
<!--        <div>-->
<!--          <span style="font-size: 14px"> Total Tarjetas :   <b>--><?php //echo convertNumber($grand_card) ?><!--</b></span>-->
<!--        </div>-->
<!--        <div>-->
<!--          <span style="font-size: 14px"> Total General :    <b>--><?php //echo convertNumber($grand_total) ?><!--</b></span>-->
<!--        </div>-->
<!--      </div>-->

        <script type="text/javascript">
          window.onload = function() { window.print(); }
        </script>
    </div>
</body>
</html>



