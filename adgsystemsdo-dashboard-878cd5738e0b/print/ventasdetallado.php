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
        table {
            table-layout: fixed ;
            width: 100% ;
        }
        /*td {*/
            /*width: 30% ;*/
        /*}*/
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
    </div>
    <div class="col-sm-6" style="text-align: right">
        <div class="row">
            <div class="col-sm-12">
                <h4>REPORTE DE VENTAS</h4>
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

        $grand_bruto = 0;
        $grand_desc = 0;
        $grand_itbis = 0;
        $grand_neto = 0;
        $count = 0;

        function printHeader ($seller_id,$seller_name) {
            echo "
                                   <div class=\"collectortitle\">
                                      ".$seller_id." - ".$seller_name."
                                   </div>

     <table class=\"table table-compact \">

            <tr>
            <th><i class=\"fa fa-cloud\"></i></th>
               
                <th>Fecha</th>

                <th>Cliente</th>
                <th><i style=\"font-size: 16px\" class=\"fa fa-map-marker\"></i></th>
        
         
                <th style='text-align:right'>ITBIS</th>  
                <th style='text-align:right'>Total Neto</th>
          
            </tr>
            <tbody>
            ";
        }

        function printOrder ($order) {
            $dt = new \Datetime($order->order_date_time);
            echo "
                                         <tr>
                                            <td>";
            if ($order->status == 1)
                echo '<i class="fa fa-check"></i> ';
            echo "
                                        </td>
                                           
       
                                            <td nowrap>".$dt->format("d-m-Y h:i A")."</td>

                                            <td>".$order->customer_code." - ".$order->customer_name."</td>
                                             <td>".$order->in_location."</td>
                                            
                                      
                                              <td style='text-align:right'>".convertNumber($order->order_tax_amount)."</td>
                                          <td style='text-align:right'>".convertNumber($order->total)."</td>
                                         </tr>
                                      ";
        }

        function companyTotal ($company, $itbs,$neto) {
            echo ' <div style="text-align: right">
                 <div> <span style="font-size: 12px"><b> Totales Compañía '.$company.'</b></span> </div>
                <div>
                  <span style="font-size: 12px"> Total Bruto :   <b>'.convertNumber($bruto).'</b></span>
                </div>
                <div>
                  <span style="font-size: 12px"> Total Descuento :   <b>'.convertNumber($desc).'</b></span>
                </div>
                <div>
                  <span style="font-size: 12px"> Total ITBIS :   <b>'.convertNumber($itbs).'</b></span>
        
                </div>
                <div>
                  <span style="font-size: 12px"> Total Neto :   <b>'.convertNumber($neto).'</b></span>
                </div>
              </div>';
        }

        function printFooter($itbis,$neto) {
            echo ' <tfoot>
            <tr class=\'foooter\'>
            <td>Totales</td>
            <td></td>
            <td></td>
       
            <td style=\'text-align:right\'> '.convertNumber($itbis).'</td>
            <td style=\'text-align:right\'> '.convertNumber($neto).'</td>
            </tr>
            </tfoot>';
        }

        if ($_POST['company'] == 'consolidado') {
            if ($_POST['consolidado_group'] == 'consolidado') {
                foreach (json_decode($general->VentasDetallado2($_POST)) as $seller_id => $orders){
                    ?>
                    <div class="collectortitle">
                        <?php echo $seller_id.' - '.$orders[0]->seller_name ?>
                    </div>
                    <table class="table">
                        <tr>
                            <th><i class="fa fa-cloud"></i></th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th><i style="font-size: 16px" class="fa fa-map-marker"></i></th>
                            <th style='text-align:right'>ITBIS</th>
                            <th style='text-align:right'>Total Neto</th>

                        </tr>
                        <tbody>
                        <?php
                        $bruto = 0;
                        $desc = 0;
                        $itbis = 0;
                        $neto = 0;

                        foreach ($orders as $order) {
                            $neto += $order->total;
                            $itbis += $order->order_tax_amount;
                            $bruto += $order->order_gross_amount;
                            $desc += $order->order_discount_amount;
                            $dt = new \Datetime($order->order_date_time);
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    if ($order->status == 1){
                                        ?>
                                        <i class="fa fa-check"></i>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td nowrap><?php echo $dt->format("d-m-Y h:i A")?></td>
                                <td><?php echo $order->customer_code.' - '. $order->customer_name?></td>
                                <td><?php echo $order->in_location;?></td>
                                <td style='text-align:right'><?php echo convertNumber($order->order_tax_amount) ?></td>
                                <td style='text-align:right'><?php echo convertNumber($order->total) ?></td>
                            </tr>

                            <?php
                        }
                        $count += count($orders);


                        $grand_bruto += $bruto;
                        $grand_desc += $desc;
                        $grand_itbis += $itbis;
                        $grand_neto += $neto;
                        ?>
                        </tbody>
                        <tfoot>
                        <tr class='foooter'>
                            <td>Totales</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style='text-align:right'><?php echo convertNumber( $grand_itbis)?></td>
                            <td style='text-align:right'><?php echo convertNumber($grand_neto)?></td>
                        </tr>
                        </tfoot>
                    </table>
                    <?php
                }
            }
            else if ($_POST['consolidado_group'] == 'separado') {

                foreach (json_decode($general->VentasDetallado2($_POST)) as $company => $sellers){
                    $company_bruto = 0;
                    $company_desc = 0;
                    $company_itbis = 0;
                    $company_neto = 0;

                    echo '<hr><h4>Compañía '.$company.'</h4>';
                    foreach ($sellers as $seller => $orders) {

                        printHeader($seller,$orders[0]->seller_name);

                        $bruto = 0;
                        $desc = 0;
                        $itbis = 0;
                        $neto = 0;

                        foreach ($orders as $order) {
                            $neto += $order->total;
                            $itbis += $order->order_tax_amount;
                            $bruto += $order->order_gross_amount;
                            $desc += $order->order_discount_amount;
                            printOrder($order);
                        }

                        $count += count($orders);

                        $company_bruto += $bruto;
                        $company_desc += $desc;
                        $company_itbis += $itbis;
                        $company_neto += $neto;

                        $grand_bruto += $bruto;
                        $grand_desc += $desc;
                        $grand_itbis += $itbis;
                        $grand_neto += $neto;



                        echo "</tbody>";
                        printFooter($itbis,$neto);
                        echo "</table>";
                    }


//                    companyTotal($company,$company_itbis,$company_neto);
                }
            }
        } else {
            $data = json_decode($general->VentasDetallado2($_POST));
            if ((is_array($data) || is_object($data)) && reset($data))
            {

                foreach (reset($data) as $seller => $orders) {
                    printHeader($seller,$orders[0]->seller_name);

                    $bruto = 0;
                    $desc = 0;
                    $itbis = 0;
                    $neto = 0;

                    foreach ($orders as $order) {
                        $neto += $order->total;
                        $itbis += $order->order_tax_amount;
                        $bruto += $order->order_gross_amount;
                        $desc += $order->order_discount_amount;
                        printOrder($order);
                    }
                    $count += count($orders);

                    $grand_bruto += $bruto;
                    $grand_desc += $desc;
                    $grand_itbis += $itbis;
                    $grand_neto += $neto;
                    echo "</tbody>";
                    printFooter($itbis,$neto);
                    echo "</table>";
                }

            }

        }

        ?>

        <hr>



        <!--      <div style="text-align: right">-->
        <!--          <div>-->
        <!--              <span style="font-size: 14px"> Total Transacciones:   <b>--><?php //echo $count ?><!--</b></span>-->
        <!--          </div>-->
        <!--        <div>-->
        <!--          <span style="font-size: 14px"> Total General Bruto :   <b>--><?php //echo convertNumber($grand_bruto) ?><!--</b></span>-->
        <!--        </div>-->
        <!--        <div>-->
        <!--          <span style="font-size: 14px"> Total General Descuento :   <b>--><?php //echo convertNumber($grand_desc) ?><!--</b></span>-->
        <!--        </div>-->
        <!--        <div>-->
        <!--          <span style="font-size: 14px"> Total General ITBIS :   <b>--><?php //echo convertNumber($grand_itbis) ?><!--</b></span>-->

        <!--        </div>-->
        <!--        <div>-->
        <!--          <span style="font-size: 14px"> Total General Neto :   <b>--><?php //echo convertNumber($grand_neto) ?><!--</b></span>-->
        <!---->
        <!--        </div>-->
        <!--      </div>-->

        <table class="table">
            <thead>
            <tr>
                <th scope="col">Total Transacciones</th>
                <th scope="col">Total General ITBIS</th>
                <th scope="col">Total General Neto </th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row"><?php echo $count ?></th>
                <td><?php echo convertNumber($grand_itbis) ?></td>
                <td><?php echo convertNumber($grand_neto) ?></td>
                <!--                <td>@mdo</td>-->
            </tr>
            </tbody>
        </table>

        <script type="text/javascript">
          window.onload = function() { window.print(); }
        </script>
    </div>
</body>
</html>



