<?php
/*
	@Author: Williams Martinez
	Required files for login, credentials and general functions
	Poner en negrita el detalle

*/
use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
use App\Legacy\Credentials;
include('config/datetime_parameters.php');


if (isset($_SESSION['post_data'])) {
    $_POST = $_SESSION['post_data'];
    $_SERVER['REQUEST_METHOD'] = 'POST';
    unset($_SESSION['post_data']);
}

/*

	If invoice number isn't posted,
	redirect to home.php

*/

if (isset($_POST['return_id']) == false)
    header('Location: home');

$general = new General;

$json_row = json_decode($general->GetCustomerByCustomerCode(array($_POST['customer_code'])));

$customer = $json_row[0];

$return_row = json_decode($general->GetReturnDetails($_POST['return_id']));

/*
	Shows document header
	@include('config/structure/header.php')
	must be included for this function to run

*/
$header = new Header;
$header->Initialize(0, 'Devolución'.$_POST['return_id']);

/*
	Shows navigation menu
	@include('config/structure/navigation_menu.php')
	must be included for this function to run

*/

$navigation = new Navigation;
$navigation->Initialize();

?>
<div class="row">
    <div class="col-lg-12">

        <div class="row" id="title">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Inicio</a></li>
                    <li class="active"><span>Devolución</span></li>
                </ol>
                <h1>Devolución</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix" style="padding: 10px;">
                    <header class="main-box-header" style="margin-top:1%">
                        <h1 style="padding: 0px; margin: 0px"><?php echo $customer->customer_code. ' - '.ucwords(strtolower($customer->customer_name)) ?></h1>
                        <h4>Devolución No. <strong><?php echo $_POST['return_id']?></strong></h4>
                    </header>

                    <div class="main-box-body clearfix">
                        <div id="invoice-companies" class="row">
                            <div class="col-sm-4 col-xs-4 invoice-box">
                                <div class="invoice-company">
                                    <?php

                                    echo '      <h5>Cliente</h5>
                                                <p>
												Propietario:  <b>' .ucwords(strtolower($customer->customer_owner_or_contact)).'</b><br>
												Dirección: <b>' .ucwords(strtolower($general->truncate($customer->customer_address, 45))).'</b><br>
												Teléfono: <b>' .$customer->customer_phone.'</b><br>
												RNC: <b>' .$customer->customer_rnc.'</b><br>
												';
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-4 invoice-box">
                                <div class="invoice-company"><p>
                                        <?php
                                        echo '
											Fecha: <b>'.$_POST['return_date'].'</b><br>   
											Vendedor: <b>'.$_POST['seller_code'].' '.$general->truncate($_POST['seller_name'], 20).'</b><br>
											Código Factura: <b>'.$_POST['invoice_code'].'</b><br> 
						                    Razón: <b>'.$_POST['reason'].'</b><br> 

											';?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-4 invoice-box">
                                <div class="invoice-company"><p>
                                        <?php
                                        echo '
											Notas de Devolución: <b>'.$_POST['note'].'</b><br>   
											';?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th><span>Cant.</span></th>
                                    <th><span>Referencia</span></th>
                                    <th><span>Descripción</span></th>
                                    <th><span>Marca</span></th>
                                    <th><span>Cantidad</span></th>
                                    <th style="text-align: right"><span>
												Precio
											</span></th>
                                    <th style="text-align: right"><span>
												Importe
											</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                foreach ($return_row as $row)

                                {

                                   echo '
											<tr class="item">

												<td class="text-center">
													<b>'.$row->item_quantity.'</b>
												</td>

												<td>
													'.$row->product_reference.'
												</td>

												<td><b>
													'.$row->product_name.'
												</b></td>
												
												<td><b>
													'.$row->brand_name.'
												</b></td>
													<td><b>
													'.$row->item_quantity.'
												</b></td>
									

												<td style="text-align: right">
													'.number_format($row->item_price, 2, '.', ',').'
												</td>

												<td style="text-align: right">
													'.number_format($row->importe, 2, '.', ',').'
												</td>

									

											</tr>';

                                }


                                ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="invoice-box-total clearfix" style="background-color: #F7FFF7">
                            <div class="row">
                                <div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
                                    <b>Total</b>
                                </div>
                                <div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value" style="padding-right: 2%">
                                    RD&dollar; <?php
                                    echo number_format($_POST['return_total'], 2, '.', ',');
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix" style="margin-top:1%" id="print-button">
                            <a class="btn btn-success pull-right" onClick="invoiceprint();">
                                <i class="fa fa-mail-forward fa-lg"></i>
                                Imprimir
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
/*
	Shows document footer
	@include('config/structure/footer.php')
	must be included for this function to run

*/

Footer::Initialize();

/*
	Shows document scripts
	@include('config/structure/scripts.php')
	must be included for this function to run

*/

Scripts::Initialize();

?>

<!-- this page specific inline scripts -->
<script type="text/javascript">
    function invoiceprint() {

        var printContents = document.getElementById("content-wrapper").innerHTML;

        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        $('#print-button').hide();
        $('#title').hide();
        $('footer').hide();

        window.print();

        document.body.innerHTML = originalContents;

        $('#print-button').show();
        $('#title').show();
        $('footer').show();

    }

</script>

</body>
</html>