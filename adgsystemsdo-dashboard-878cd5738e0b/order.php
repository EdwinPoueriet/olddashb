<?php
use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
include('config/datetime_parameters.php');


if (isset($_SESSION['post_data'])) {
    $_POST = $_SESSION['post_data'];
    $_SERVER['REQUEST_METHOD'] = 'POST';
    unset($_SESSION['post_data']);
}

	if (isset($_POST['order_id']) == false)
		header('Location: home');

	$general = new General;

	$json_row = json_decode($general->GetCustomerByCustomerCode(array($_POST['customer_code'])));

//	var_dump($json_row);die();
	$row = $json_row[0];

	$order_rows = json_decode($general->GetOrderDetails($_POST['order_id']));

/*	
	Shows document header 
	@include('config/structure/header.php') 
	must be included for this function to run 

*/
	$header = new Header;
	$header->Initialize(0, 'Orden '. $_POST['order_id']);

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
						<li class="active"><span>Pedido</span></li>
					</ol>

					<h1>Pedido</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="main-box clearfix">
						<header class="main-box-header" style="margin-top:1%">
							<h1 style="padding: 0px; margin: 0px"><?php echo $row->customer_code. ' - '.ucwords(strtolower($row->customer_name)) ?></h1>
						</header>

						<div class="main-box-body clearfix">
							<div id="invoice-companies" class="row">
								<div class="col-sm-4 col-xs-4 invoice-box">
									<div class="invoice-company">
			                                 <?php
                                             $tipo = $_POST['order_type'] == '1' ? 'Crédito' : 'Contado';
												echo '
                                                Pedido No. : <b>'.$_POST['order_id'].'</b><br>
                                                Tipo de Pedido : <b>'.$tipo.'</b><br>
												Propietario: <b>'.ucwords(strtolower($row->customer_owner_or_contact)).'</b><br>
												Dirección: <b> '.ucwords(strtolower(General::truncate($row->customer_address, 45))).', '.$row->city_name.'</b><br>
												Teléfono: <b> '.$row->customer_phone.'</b><br>
												RNC: <b>'.$row->customer_rnc.'</b><br>
												';
										//Dia de visita: <b>'.$day[$_POST['day_id']].'</b></p> 
												
												?>
										</div>
									</div>
									<div class="col-sm-4 col-xs-4 invoice-box">
										<div class="invoice-company"><p>
											<?php
											$end = new \Datetime($_POST['order_date_finish']);
											$begin = new \Datetime($_POST['order_date_time']);
//                      Fecha de Creación: <b>'.(new \Datetime($_POST['order_created_at']))->format('d-m-Y h:i A') .'</b><br>
//                      Fecha de Subida: <b>'. (new \Datetime($_POST['date_sent']))->format('d-m-Y h:i A').'</b><br>
											echo '
											  
										
											Hora Inicio Pedido: <b>'.$begin->format('d-m-Y h:i A').'</b><br> 
											Hora Término Pedido: <b>'.$end->format('d-m-Y h:i A').'</b><br>             
										  Vendedor: <b>'.General::truncate($_POST['seller_code'].' - '. $_POST['seller_name'], 20).'</b><br>
											Duración: <b>'.$begin->diff($end)->format("%h:%I:%s").'</b>

											';?>
										</p>
									</div>
								</div>
								<div class="col-sm-4 col-xs-4 invoice-box">
									<div class="invoice-dates">
										<div class="invoice-number clearfix">
											<?php
											/**
											* 
											*/
											class Orders extends General
											{
												
												function __construct()
												{
													parent::__construct();

												}

												function getorder(){

											$montopedido = self::$con->prepare("SELECT 
												order_note, 
												order_tax_amount, 
												order_gross_amount,
												order_discount_amount
											FROM ".self::$user_database.".orders_header 
											WHERE order_id = :order_id AND company_id = :company_id");
											$montopedido->bindParam(':order_id', $_POST['order_id']);
											$montopedido->bindParam('company_id', self::$default_company);
											$montopedido->execute();
											$montopedido = $montopedido->fetchAll(\PDO::FETCH_ASSOC);

											echo 'Nota de pedido: ' . $montopedido[0]['order_note'];

											return $montopedido;

											}

											
											}

											$order = new Orders;

											$montopedido = $order->getorder();			

											?>
										</div>
									</div>
								</div>
							</div>

							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th class="text-center"><span>Cant.</span></th>
											<th><span>Ref.</span></th>
											<th><span>Descripcion</span></th>
											<th style="text-align: right"><span>
												Precio
											</span></th>
                                            <th style="text-align: right"><span>
												Descuento
											</span></th>

											<th style="text-align: right"><span>
												ITBIS
											</span></th>
                                            <th style="text-align: right"><span>
												Neto
											</span></th>
											<th style="text-align: right"><span>
												Total
											</span></th>
										</tr>
									</thead>
									<tbody>
										<?php

											foreach ($order_rows as $order_row)

											{											
										
//											$order_row->importe = $order_row->order_item_quantity * $order_row->order_item_sale_price;
//
//											$order_row->tax = ($order_row->order_item_quantity * $order_row->order_item_sale_price) * 0.18;
											$tax = $order_row->order_item_tax_amount/$order_row->order_item_quantity;
                                                $neto = ($order_row->order_item_sale_price-$order_row->order_item_discount_amount+$tax);
                                                $total = $neto*$order_row->order_item_quantity;
											echo '
											<tr class="item">

												<td class="text-center">
													<b>'.$order_row->order_item_quantity.'</b>
												</td>

												<td>
													'.$order_row->product_reference.'
												</td>

												<td><b>
													'.$order_row->product_name.'
												</b></td>

												<td style="text-align: right">
													'.number_format($order_row->order_item_sale_price, 2, '.', ',').'
												</td>

												<td style="text-align: right">
													'.number_format($order_row->order_item_discount_amount, 2, '.', ',').'
												</td>
												
												<td style="text-align: right">
													'.number_format($tax, 2, '.', ',').'
												</td>

												<td style="text-align: right">
													'.number_format($neto, 2, '.', ',').'
												</td>

												<td style="text-align: right"><b>
													'.number_format($total, 2, '.', ',').'
												</b></td>

											</tr>';
											
											}
										

										?>
									</tbody>
								</table>
							</div>

							<div class="invoice-box-total clearfix" style="background-color: #F7FFF7">
								<div class="row">
									<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
										<b>Subtotal</b>
									</div>
									<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value" style="padding-right: 2%">
										RD&dollar; <?php 
										echo number_format($montopedido[0]['order_gross_amount'], 2, '.', ',');
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
										<b>ITBIS</b> 
									</div>
									<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value" style="padding-right: 2%">
										RD&dollar; <?php 
										echo number_format($montopedido[0]['order_tax_amount'], 2, '.', ',');
										?> 
									</div>
								</div>
								<div class="row">
									<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
										<b>Descuento</b>
									</div>
									<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value" style="padding-right: 2%">
										RD&dollar; <?php
										echo number_format($montopedido[0]['order_discount_amount'], 2, '.', ',');
										?>
									</div>
								</div>
								<div class="row grand-total">
									<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
										<b>Total</b> 
									</div>
									<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value" style="padding-right: 2%">
										RD&dollar; <?php 
										echo number_format(($montopedido[0]['order_tax_amount'] + $montopedido[0]['order_gross_amount'])-$montopedido[0]['order_discount_amount'], 2, '.', ',');
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