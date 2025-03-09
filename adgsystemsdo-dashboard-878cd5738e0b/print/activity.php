<?php

function convertNumber ($num) {
    $num = floatval($num);
    return number_format($num, 2, '.', ',');
}

function type($value){

    if ($value == '1'){
        return "Credito";

    }
    else{
        return "Al Contado";

    }

}

use App\Legacy\General;
use App\Services\ActivityService;

$activity = new ActivityService();
$general  = new General();

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
                <h4>REPORTE DE ACTIVIDADES</h4>
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
        function printHeader ($seller_id,$seller_name) {
            echo "
                                   <div class=\"collectortitle\">
                                      ".$seller_id." - ".$seller_name."
                                   </div>
     <table class=\"table table-compact \">
            <tr>
           
                <th>Fecha</th>
                <th>Tipo Doc</th>
                <th>Cliente</th>  
                <th>No. Documento</th>
            </tr>
            <tbody>
            ";
        }



        if($_POST["printOption"] == 1) {

            $total_order = 0;
            $total_quotation = 0;
            $total_receivable = 0;
            $total_devolution = 0;
            $total_visit = 0;
            $total_deposit = 0;


            ?>

            <table class="table table-compact">
                <tr>
                    <th>Vendedor</th>
                    <th style="text-align: center">Pedidos</th>
                    <th style="text-align: center">Cobros</th>
                    <th style="text-align: center">Cotizaciones</th>

                    <th style="text-align: center">Devoluciones</th>
                    <th style="text-align: center">Visita No Ventas</th>
                    <th style="text-align: center">Deposito Bancario</th>
                </tr>
                <tbody>
                <?php
                $data = $general->SellersActivity($_POST);
//                echo json_encode($data['data']);

                foreach ($data['data'] as $seller_name => $c){
                    $total_order += $c[1]['orderi'];
                    $total_quotation +=  $c[2]['quotation'];
                    $total_receivable += $c[3]['receivable'];
                    $total_devolution += $c[4]['devo'];
                    $total_visit += $c[5]['visit'];
                    $total_deposit += $c[6]['bank'];



                    ?>
                    <tr>
                        <td ><?php echo $seller_name; ?></td>
                        <td style="text-align: center"><?php echo $c[1]['orderi']; ?></td>
                        <td style="text-align: center"><?php echo $c[3]['receivable'];; ?></td>
                        <td style="text-align: center"><?php echo $c[2]['quotation']; ?></td>
                        <td style="text-align: center"><?php echo  $c[4]['devo']; ?></td>
                        <td style="text-align: center"><?php echo$c[5]['visit']; ?></td>
                        <td style="text-align: center"><?php echo $c[6]['bank']; ?></td>
                    </tr>
                    <?php
                }

                ?>
                <tfoot class="foooter">
               <tr style="">
                   <td  scope="row">Vendedores: <?php echo $data['total']; ?> </td>
                   <td style="text-align: center" scope="row"><?php echo $total_order; ?></td>
                   <td style="text-align: center" scope="row"><?php echo $total_receivable; ?></td>
                   <td style="text-align: center" scope="row"><?php echo $total_quotation; ?></td>
                   <td style="text-align: center" scope="row"><?php echo $total_devolution; ?></td>
                   <td style="text-align: center" scope="row"><?php echo $total_visit; ?></td>
                   <td style="text-align: center" scope="row"><?php echo $total_deposit; ?></td>
               </tr>
                </tfoot>

                </tbody>
            </table>









        <?php }else{

            foreach ($activity->printActivity($_POST) as $seller_id => $activities){
                ?>
                <div class="collectortitle">
                    <?php echo $seller_id ; ?> - <?php echo $activities[0]["seller_name"] ; ?>
                </div>
                <table class="table table-compact">
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo Doc</th>

                        <th>No. Documento</th>
                        <th>Cliente</th>
                        <th><i style="font-size: 16px" class="fa fa-map-marker"></i></th>
                    </tr>
                    <tbody>
                    <?php
                    if ($_POST['OrderMethod'] == 'desc'){
                        usort($activities, function($a1, $a2) {
                            $v1 = strtotime($a1['date_time']);
                            $v2 = strtotime($a2['date_time']);
                            return $v2 - $v1; // $v2 - $v1 to reverse direction
                        });

                    }else{
                        usort($activities, function($a1, $a2) {
                            $v1 = strtotime($a1['date_time']);
                            $v2 = strtotime($a2['date_time']);
                            return $v1 - $v2; // $v2 - $v1 to reverse direction
                        });

                    }


                    foreach ($activities as $a){
                        $dt = new \Datetime($a["date_time"]);
                        ?>

                        <tr>
                            <th scope="row"><?php echo $dt->format("d-m-Y h:i A"); ?></th>
                            <th><?php echo $a["table_indentify"] ; ?></th>

                            <td><?php echo $a["noDoc"] ; ?></td>
                            <td><?php echo $a["customer_code"] ; ?> - <?php echo $a["customer_name"] ; ?></td>
                            <td><?php echo $a["in_location"] ; ?></td>

                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php

            }

            ?>

            <?php
        }?>


    </div>
    <script type="text/javascript">
      window.onload = function() { window.print(); }
    </script>
</div>
</body>
</html>
