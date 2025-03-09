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
        .foooter > tr {
            font-size: 11px;
            font-weight: 600;
        }

        .card {
            margin-bottom: 10px;
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: .25rem;">

        }
        div.card{
            page-break-inside: avoid;

        }
        .card-body{
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
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

    </div>
    <div class="col-sm-6" style="text-align: right">
        <div class="row">
            <div class="col-sm-12">
                <h4>REPORTE DE FACTURA RANCHERA</h4>
                <span></span>
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
        $data = $general->ranchera($_POST);
//        echo json_encode($data);
        $grand_itbis = 0;
        $grand_dec = 0;

        $grand_neto = 0;
        ?>
        <table class="table table-compact">
            <thead>
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Cliente</th>
                <th>Codigo</th>
                <th>Comprobantes</th>
                <th>Impuesto</th>
                <th>Descuento</th>
                <th>Total</th>
            </thead>
            <tbody>
            <?php
            foreach (json_decode($data) as $a){
                $grand_itbis += $a->invoice_tax_amount;
                $grand_dec += $a->invoice_discount_amount;

                $grand_neto += $a->total;
                ?>
                <tr>
                    <td><?php echo $a->invoice_date_time; ?></td>
                    <td><?php echo $a->seller_code.' - '.$a->seller_name; ?></td>
                    <td><?php echo $a->customer_code.' - '.$a->customer_name; ?></td>
                    <td><?php echo $a->invoice_code; ?></td>
                    <td><?php echo $a->invoice_ncf; ?></td>
                    <td style="text-align: right"><?php echo convertNumber($a->invoice_tax_amount); ?></td>
                    <td style="text-align: right"><?php echo convertNumber($a->invoice_discount_amount); ?></td>
                    <td style="text-align: right"><?php echo convertNumber($a->total); ?></td>
                </tr>

            <?php
            }

            ?>
            <tfoot>
            <tr>
                <td>Total: <?php echo count(json_decode($data)); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

                <td style="text-align: right"><?php echo convertNumber($grand_dec) ?></td>
                <td style="text-align: right"><?php echo convertNumber($grand_itbis) ?></td>
                <td style="text-align: right"><?php echo convertNumber($grand_neto) ?></td>

            </tr>
            </tfoot>

            </tbody>
        </table>
<!--        <table class="table">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th scope="col">Total Transacciones</th>-->
<!--                <th scope="col">Total descuento</th>-->
<!--                <th scope="col">Total General ITBIS</th>-->
<!--                <th scope="col">Total General Neto </th>-->
<!---->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr>-->
<!--                <th scope="row">--><?php //echo count(json_decode($data)); ?><!--</th>-->
<!--                <td style="text-align: right">--><?php //echo convertNumber($grand_dec) ?><!--</td>-->
<!--                <td style="text-align: right">--><?php //echo convertNumber($grand_itbis) ?><!--</td>-->
<!--                <td style="text-align: right">--><?php //echo convertNumber($grand_neto) ?><!--</td>-->
<!--                               <td>@mdo</td>-->
<!--            </tr>-->
<!--            </tbody>-->
<!--        </table>-->




    </div>
    <script type="text/javascript">
      window.onload = function() { window.print(); }
    </script>
</div>
</body>
</html>
