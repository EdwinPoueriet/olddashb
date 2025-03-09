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

	 if (isset($_POST['receipt_code']) == false)
	 	Header('Location: dashboard');

	$general = new General;

	$fetch_customer_row = json_decode($general->GetCustomerByCustomerCode(array($_POST['customer_code'])));

	$row = $fetch_customer_row[0];

	$receivable_rows = json_decode($general->GetReceivablesDetails($_POST['receipt_income_code']));

/*	
	Shows document header 
	@include('config/structure/header.php') 
	must be included for this function to run 

*/
	$header = new Header;
	$header->Initialize(0, 'Factura '. $_POST['receipt_code']);

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
						<li class="active"><span>Recibo de Ingreso</span></li>
					</ol>

					<h1>Recibo de Ingreso</h1>
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
												
												echo '

                    Recibo No. <b>'.$_POST['receipt_code'].'</b><br><p>
                    No. de Transacción:  <b>'.$_POST['receipt_income_code'].'</b><br><p>
												Propietario: <b>'.ucwords(strtolower($row->customer_owner_or_contact)).'</b><br>
												Dirección: <b>'.ucwords(strtolower(General::truncate($row->customer_address, 45))).'</b><br>
												Teléfono: <b>'.$row->customer_phone.'</b><br>
												RNC:<b>'.$row->customer_rnc.'</b><br>
												';
												//Dia de visita: <b>'.$day[$_POST['day_id']].'</b></p> 
												
												?>
										</div>
									</div>
								<div class="col-sm-4 col-xs-4 invoice-box ">
									<div class="invoice-company">
										<p>

											<?php
											echo '
											Monto Efectivo: <b>RD$ '.number_format($_POST['cash_amount'], 2, '.', ',').'</b><br>   
											Monto Cheques: <b>RD$ '.number_format($_POST['check_amount'], 2, '.', ',').'</b><br>
											';

											if ($_POST['futuristic_check']!='0') {
											    echo 'Cheque Futurista: <b> Si</b><br>';
                                            } else {
                                                echo 'Cheque Futurista: <b> No</b><br>';
                                            }
												echo '
												Fecha de depósito: <b>'.$_POST['futuristic_check_date'].'</b><br>
												Banco: <b>'.$_POST['bank_name'].'</b><br>
												Número de Cheque(s): <b> '.$_POST['check_number'].'</b><br>
												Moneda: <b>Pesos Dominicanos</b>
												';

											?>

										</p>
									</div>
								</div>
									<div class="col-sm-4 col-xs-4 invoice-box">
										<div class="invoice-company"><p>
											<?php
											echo '
											Fecha envio: <b>'.$_POST['date_sent'].'</b><br>   
											Fecha inicio: <b>'.$_POST['start_date'].'</b><br>   
											Fecha fin: <b>'.$_POST['end_date'].'</b><br>
											Cobrador: <b>'.General::truncate($_POST['collector_code'].' - '.$_POST['collector_name'], 20).'</b><br>
											Notas: <b>'.$_POST['payment_note'].'</b><br>

											';?>
										</p>
									</div>
								</div>

							</div>

							<div class="table-responsive">
								<table class="table table-hover footable">
									<thead>
										<tr>
											<th><span>No. Factura</span></th>
											<th><span>Pagaré</span></th>


											<th style="text-align: right"><span>Balance Anterior</span></th>
                                            <?php if (\App\Common\CompatibilityChecker::columnExists('discount_amount','receivables_header')){ ?>
                                                <th style="text-align: right"><span>Descuento</span></th>
                                            <?php }?>
											<th style="text-align: right"><span>
												Monto Pagado
											</span></th>
											<th style="text-align: right"><span>
												Restante
											</span></th>
											<th style="text-align: right"><span>
												Estado
											</span></th>
										</tr>
									</thead>
									<tbody>
										<?php

											foreach ($receivable_rows as $receivable_row)

											{											
											
											$receivable_row->receivable_total = $receivable_row->invoice_balance - $receivable_row->payment_amount;

											echo '
											<tr class="item">

												<td>
													<b>'.$receivable_row->invoice_code.'</b>
												</td>

												<td>
													'.$receivable_row->payment_code.'
												</td>

												<td style="text-align: right"><b>
													'.number_format($receivable_row->invoice_balance, 2, '.', ',').'
												</b></td>
												
												';

                                         if (\App\Common\CompatibilityChecker::columnExists('discount_amount','receivables_header')){
                                             echo '
												<td style="text-align: right"><b>
													'.number_format($receivable_row->discount_amount, 2, '.', ',').'
												</b></td>';
                                            }
                                                echo '
												<td style="text-align: right"><b>
													'.number_format($receivable_row->payment_amount, 2, '.', ',').'
												</b></td>

												<td style="text-align: right"><b>
													'.@number_format($receivable_row->receivable_total, 2, '.', ',').'
												</b></td>
												<td style="text-align: right"> 
												';
												if ($receivable_row->receivable_total == 0){
													echo 'Saldo';
												}else{
													echo 'Abono';
												}
												echo '
												</td>

											</tr>';
											
											}
										?>
									</tbody>
								</table>
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