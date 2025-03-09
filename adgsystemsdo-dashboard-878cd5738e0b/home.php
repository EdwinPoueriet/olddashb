<?php


use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;

	include('config/datetime_parameters.php');

	$general_functions = new General;

	include('config/home_parameters.php');

/* 
	To get orders from $startdate, $enddate and selected sellers in $sellerlist
*/
	$orders_headers = json_decode($general_functions->GetOrders($seller_condition, $sellerlist, $startdate, $enddate, "cantidad"));

/*
	Count the orders
*/
	$orders_count = count($orders_headers);

/*
	To get customers from $startdate and $enddate from selected sellers in $sellerlist
*/

	$customers_list = json_decode($general_functions->GetCustomerByDate($sellerlist, $seller_condition, $cdates));

/*
	Count the customers
*/
	$customers_count = count($customers_list);

/*
	Get the amount of the orders
*/

	$orders_amount = json_decode($general_functions->GetOrders($seller_condition, $sellerlist, $startdate, $enddate, "monto"));

/*
	Graph of orders quantity
*/

	$graph_orders_quantity = json_decode($general_functions->GetOrders($seller_condition, $sellerlist, $startdate, $enddate, "grafico-cantidad"));

/*
	Graph of orders amount
*/

	$graph_orders_amount = json_decode($general_functions->GetOrders($seller_condition, $sellerlist, $startdate, $enddate, "grafico-monto"));

/* 
	Sales average, orders amount / sellers quantity
*/

	$sellers_average = (count($sellers_array) > 0 ? $orders_amount[0]->totals / count($sellers_array) : '0');

/*
	Amount per transaction average, orders amount / orders quantity
*/

	$dropsize = ($orders_count > 0 ? $orders_amount[0]->totals / $orders_count : '0'); 


/*	
	Shows document header 
	@include('config/structure/header.php') 
	must be included for this function to run 

*/

	$header = new Header;
	$header->Initialize(1, "Dashboard");

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
			<div class="row">
				<div class="col-lg-12">
					<div id="content-header" class="clearfix">
						<div class="pull-left">
							<ol class="breadcrumb">
								<li><a href="#">Inicio</a></li>
								<li class="active"><span>Home</span></li>
							</ol>

							<h1>Dashboard - Compañía <?php echo $general_functions::$default_company?></h1>
						</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-3 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored emerald-bg">
							<span class="headline">Clientes de la fecha</span>
							<span class="value">
								<?php

								echo number_format($customers_count);

								?>
							</span>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored red-bg">
							<span class="headline">Monto total de las ventas</span>
							<span class="value" id="amount">
								<?php     

								echo number_format($orders_amount[0]->totals, 2, '.', ',');

								?>
							</span>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored purple-bg">
							<span class="headline">Promedio de ventas</span>
							<span class="value">
								<?php 

								echo number_format($sellers_average, 2, '.', ',');

								?></span>
							</div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12">
							<div class="main-box infographic-box colored green-bg">
								<span class="headline">Dropsize</span>
								<span class="value">
									<?php 

									echo number_format($dropsize, 2, '.', ',');

									?>
								</span>
							</div>
					</div>
					
				</div>

				<div class="row">
						<div class="col-md-12">
							<div class="main-box">
								<header class="main-box-header clearfix">
									<h2 class="pull-left">Ventas por vendedor</h2>
									<a  style="margin-left: 25px; margin-top: 7px;" class="badge badge-primary" data-toggle="modal" href="#myModal">
										<i class="fa fa-filter fa-fw" aria-hidden="true"></i><b>Filtrar por Vendedor o Cobrador</b>
									</a>
									<div id="reportrange" class="pull-right daterange-filter" style="border-radius: 5px">
										<i class="fa fa-calendar fa-fw" aria-hidden="true"></i><span></span> <b class="caret"></b>
									</div>
								</header>

								<div class="main-box-body clearfix">
									<div class="row">
										<div class="col-md-9">
											<div id="hero-bar" style="height: 300px;"></div>
										</div>
										<div class="col-md-3">
											<div id="hero-donut" style="height: 300px"></div>
										</div>
									</div>
								</div>
							</div>	
						</div>
				</div>
<!--			<div class="row">-->
<!--				<div class="col-md-12">-->
<!--					<div class="main-box">-->
<!--						<header class="main-box-header clearfix">-->
<!--							<h2 class="pull-left">Ventas por tiempo</h2>-->
<!---->
<!--							<div id="vxtrange" class="pull-right daterange-filter" style="border-radius: 5px">-->
<!--								<i class="fa fa-calendar fa-fw" aria-hidden="true"></i><span></span> <b class="caret"></b>-->
<!--							</div>-->
<!--						</header>-->
<!---->
<!--						<div class="main-box-body clearfix">-->
<!--							<div class="row">-->
<!--								<div class="col-md-12">-->
<!--									<div id="vxtgraph" style="height: 300px;"></div>-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
				<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-12">

							<div class="main-box clearfix">
								<div style="padding: 5px; padding-left:15px; background-color: #03a9f4" >
									<div style="display: inline-block;width: 70%" >
										<h4 style="color: white;font-weight: 400">Efectividad</h4>
									</div>
									<span style="right: 0; float: right;">
										<a onClick="volver3()" style="display:none;" id="volver3">
											<i style="font-size: 20px; cursor:pointer; color: white" class="fa fa-angle-left"></i>
										</a>
									</span>
								</div>
								<div class="main-box-body clearfix" style="padding-top: 10px">
									<div id="efectividadwrapper" style="padding: 5px">
										<div id="efectividadcontenido">
											<ul class="graph-stats">
												<?php

												foreach (json_decode($general_functions->Effectiveness($efdates, $routes, $seller_condition, $sellerlist, $ordersdates)) as $row)

												{

													if ($row->cantidadclientes <= 0)

														$row->resultado = 0;

													else
														$row->resultado = ($row->pedidos / $row->cantidadclientes) * 100;

													if ($row->resultado <= 50)

													{

														$label_style = 'label-danger';
														$bar_style = '#e84e40';

													}

													else if ($row->resultado > 50 && $row->resultado <= 84)

													{

														$label_style = 'label-warning';
														$bar_style = '#FFA000';

													}

													else if ($row->resultado >= 85)

													{

														$label_style = 'label-success';
														$bar_style = '#8bc34a';

													}

													echo'<li>
												<a onClick="efectividad(this.id);" id="'.$row->seller_code.'" style="cursor:pointer;"> 
													<div class="clearfix">
														<div class="title pull-left">
															'.$row->seller_code.' - '.$row->seller_name.'
														</div>
														<div class="value pull-right">
															<span class="label label-primary" style="font-size: 9px">'.number_format($row->resultado, 2, '.', ',').'%</span>
															<span class="label '.$label_style.'" style="font-size: 9px">'.$row->pedidos.' /'.$row->cantidadclientes.'</span>
														</div>
													</div>
													<div class="progress">
														<div style="width: '.$row->resultado.'%; background-color:'.$bar_style.'" aria-valuemax="100" aria-valuemin="0" aria-valuenow="69" role="progressbar" class="progress-bar">
														</div>
													</div>
												</a>
											</li>';

												}
												?>
											</ul>
										</div>
										<div id="efectividaddetalle"></div>
									</div>
								</div>
							</div>
					</div>

					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="main-box clearfix">
							<div style="padding: 5px; padding-left:15px; background-color: #03a9f4" >
								<div style="display: inline-block;width: 70%" >
									<h4 style="color: white;font-weight: 400">Ventas por Marcas</h4>
								</div>
								<span style="right: 0; float: right;">
									<a onClick="volver3()" style="display:none;" id="volver3">
										<i style="font-size: 20px; cursor:pointer;color: white;" class="fa fa-angle-left"></i>
									</a>
								</span>

							</div>
							<div class="main-box-body clearfix" style="padding-top: 10px">
								<div id="marcaswrapper" style="padding: 5px">
									<div id="marcascontenido">
										<?php

										if ($orders_count > 0) {

											foreach (json_decode($general_functions->BrandsSales($startdate, $enddate, $seller_condition, $sellerlist)) as $row) {

												if ($color == 0) {

													$color = $color + 1 ;
													$style = "#f4f4f4";

												}

												else {

													$color = $color - 1;
													$style = "#FFFFF";

												}

												if ($row->brand_name == "")
													$row->marca = "Marca sin nombre";
												else
													$row->marca = $row->brand_name;

												echo '
										<a onclick="marca(this.id);" id="'.$row->brand_id.'">
											<div style="background-color:'.$style.'; padding: 10px 5px 10px 5px; cursor:pointer;" class="row">
												<div class="col-md-9 col-sm-9">'.ucwords(strtolower($row->marca)).'</div>
												<div style="text-align:right; float: right" class="col-md-3 col-sm-3">'.number_format($row->importe, 2, '.', ',').'</div>
											</div>
										</a>';

											}

										}
										?>
									</div>
									<div id="marcadetalle"></div>
								</div>
							</div>
						</div>

					</div>

					<div class="col-md-4 col-sm-4 col-xs-12">

						<div class="main-box clearfix">
							<div style="padding: 5px; padding-left:15px; background-color: #03a9f4" >
								<div style="display: inline-block;width: 70%" >
									<h4 style="color: white;font-weight: 400">Ventas por Productos</h4>
								</div>
								<span style="right: 0; float: right;">
									<a onClick="volver2()" style="display:none;" id="volver2">
										<i style="font-size: 20px; cursor:pointer;color: white;" class="fa fa-angle-left"></i>
									</a>
								</span>

							</div>
							<div class="main-box-body clearfix" style="padding-top: 10px">
								<div id="productoswrapper" style="padding: 5px">
									<div id="productoscontenido">
										<?php

										if ($orders_count > 0) {

											foreach (json_decode($general_functions->ProductsSales($startdate, $enddate, $seller_condition, $sellerlist)) as $row) {

												if ($color == 0) {

													$color = $color + 1 ;
													$style = "#f4f4f4";

												}

												else {
													$color = $color - 1;
													$style = "#FFFFF";

												}


												if ($row->product_name != '')
													$row->producto = $row->product_name;

												else
													$row->producto = "Producto sin nombre";

												echo '
										<a onClick="producto(this.id)" id="'.$row->product_reference.'">
											<div style="background-color:'.$style.'; padding: 10px 5px 10px 5px; cursor:pointer;" class="row">
												<div class="col-md-9 col-sm-9">'.trim(ucwords(strtolower($row->producto))).'</div>
												<div style="text-align:right; float: right" class="col-md-3 col-sm-3">'.number_format($row->importe, 2, '.', ',').'</div>
											</div>
										</a>';

											}

										}
										?>
									</div>
									<div id="productosdetalle"></div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="main-box clearfix">
							<div style="padding: 10px;background-color: #03a9f4" >
								<div style="width: 50%;display: inline-block">
									<h4 style="color: white;font-weight: 600">Visualizar Registros</h4>
								</div>
									<div style="float: right; display: inline-block;">
										<select class="form-control" id="list" style="font-size: 16px">
											<option value="orders">Listado de Ventas</option>
											<option value="receivables">Listado de Recibos de Ingreso</option>
											<option value="returns">Listado de Devoluciones</option>
										</select>
									</div>
							</div>

							<div class="main-box-body clearfix">
								<div id="loadingtable" style="text-align: center; padding: 35px">
									<i class="fa fa-spinner fa-spin fa-2x"></i><br>
									<span style="font-size: 14px;">Cargando, por favor espere</span>
								</div>
								<div style="border: 1px solid #a0a0a0;margin-top: 10px">
									<div id="table-content">
									</div>
								</div>
								<div id="tabletotal" class="pull-right" style="padding: 15px;margin-right:170px">
									 <b>Monto Total</b>:
									<span id="totaltext" class="label label-primary label-large">$ <?php

										echo number_format($orders_amount[0]->totals, 2, '.', ',');

										?> </span>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div><!-- /.col-lg-12 -->
		</div><!-- /.row -->

		<!-- Standard Bootstrap Modal -->
		<div class="modal fade" id="order_location_modal" tabindex="-1" role="dialog" aria-labelledby="order-map-label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Lugar de donde se tomó la orden.</h4>
					</div>
					<div class="modal-body">

						<div id="order_map" style="height: 300px; width: 100%"></div>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	

		<?php 

/*	
	Shows document modal for filters
	Array parameters are for the select options 
	@include('config/structure/footer.php') 
	must be included for this function to run 

*/

	$footer = new Footer;

	$footer->Modal($startdate, $enddate, array(

		"sellers" => 1,
		"collectors" => 1,
		"products" => 0,
		"producttypes" => 0,
		"routes" => 0,
		"areas" => 0,
		"brands" => 0,
		"classifications" => 0,
		"subclassifications" => 0,
		"families" => 0,
		"warehouses" => 0,
		"costcenters" => 0,
		"excludeseller" => 1,
		"didntbuy" => 0,
		"datepicker" => 0,
		"dayfilter" =>0

		));
/*	
	Shows document footer 
	@include('config/structure/footer.php') 
	must be included for this function to run 

*/

	$footer->Initialize();

/*	
	Shows document scripts 
	@include('config/structure/scripts.php') 
	must be included for this function to run 

*/

	Scripts::Initialize();

	?>

	<!-- this page specific inline scripts -->
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apikey; ?>&signed_in=true"></script>
	<script>



		/* SPARKLINE - graph in header */
		var orderValues = [10,8,5,7,4,4,3,8,0,7,10,6,5,4,3,6,8,9];

		$('.spark-orders').sparkline(orderValues, {
			type: 'bar', 
			barColor: '#ced9e2',
			height: 25,
			barWidth: 6
		});

		var revenuesValues = [8,3,2,6,4,9,1,10,8,2,5,8,6,9,3,4,2,3,7];

		$('.spark-revenues').sparkline(revenuesValues, {
			type: 'bar', 
			barColor: '#ced9e2',
			height: 25,
			barWidth: 6
		});

		/* Donut chart */

		graphDonut = Morris.Donut({
			element: 'hero-donut',
			data: <?php

			echo '[';

			foreach ($graph_orders_quantity as $row) 
			{

				echo '{"label":"V '.$row->label.'", "value":'.$row->value. '},';

			}

			echo ']';        

			?>,
			colors: ['#8bc34a', '#ffc107', '#e84e40', '#03a9f4', '#9c27b0', '#90a4ae'],
			formatter: function (y, data) 
			{ 
				return y + ' ventas';
			},
			resize: true
		});

		/* Bar chart */

		var data = {
			"xScale": "ordinal",
			"yScale": "linear",
			"main": [
			{
				"className": ".pizza",
				"data": <?php 

				echo '[';

				foreach ($graph_orders_amount as $row) 
				{

					echo '{"x":"'.$row->x.'", "y":'.$row->y. '},';

				}

				echo ']';            

				?>
			}
			],
		};

		var opt = {

			"mouseover": function (data, index) {

				graphDonut.select(index);
				$('#amount').html(Number(data.y.toFixed(1)).toLocaleString());

			},

			"mouseout": function () {

				$('#amount').html("<?php echo number_format($orders_amount[0]->totals, 2, '.', ',') ?>");

			}

		};

		var myChart = new xChart('bar', data, '#hero-bar', opt);

//		//VXT CHART
//		new Morris.Line({
//			// ID of the element in which to draw the chart.
//			element: 'vxtgraph',
//			// Chart data records -- each entry in this array corresponds to a point on
//			// the chart.
//			data: [
//				{ year: '2008', value: 20 },
//				{ year: '2009', value: 10 },
//				{ year: '2010', value: 5 },
//				{ year: '2011', value: 5 },
//				{ year: '2013', value: 20 },
//				{ year: '2014', value: 10 },
//				{ year: '2015', value: 5 },
//				{ year: '2016', value: 5 },
//				{ year: '2012', value: 20 }
//			],
//			// The name of the data record attribute that contains x-values.
//			xkey: 'year',
//			// A list of names of data record attributes that contain y-values.
//			ykeys: ['value'],
//			// Labels for the ykeys -- will be displayed when you hover over the
//			// chart.
//			labels: ['Value']
//		});

	</script>
<script type="text/javascript" src="js/jquery.slimscroll.min.js"></script>

	<script type="text/javascript">



		$('#efectividadwrapper').slimScroll({
			height: '390px',
			alwaysVisible: false
		});
		$('#marcaswrapper').slimScroll({
			height: '390px',
			alwaysVisible: false
		});
		$('#productoswrapper').slimScroll({
			height: '390px',
			alwaysVisible: false
		});
		$(document).ready(function() {
			$('#reportrange').daterangepicker({
				startDate: moment(),
				endDate: moment(),
				minDate: '01/01/2000',
				maxDate: '12/31/2022',
				dateLimit: { days: 60 },
				showDropdowns: true,
				showWeekNumbers: true,
				timePicker: false,
				timePickerIncrement: 1,
				timePicker12Hour: true,
				ranges: {
					'Hoy': [moment(), moment()],
					'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Ultimos 7 dias':[moment().subtract(6, 'days'), moment()],
					'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
					'Este mes': [moment().startOf('month'), moment().endOf('month')],
					'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				opens: 'left',
				buttonClasses: ['btn btn-default'],
				applyClass: 'btn-small btn-primary',
				cancelClass: 'btn-small',
				format: 'YYYY-MM-DD',
				separator: ' a ',
				locale: {
					applyLabel: 'Aceptar',
					cancelLabel: 'Borrar',
					fromLabel: 'Desde',
					toLabel: 'Hasta',
					customRangeLabel: 'Personalizado',
					daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
					monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
					firstDay: 1
				}
			},
			function startend(start, end) {
				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
				enviar(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
			}
			);
//
//			$('#vxtrange').daterangepicker({
//					startDate: moment(),
//					endDate: moment(),
//					minDate: '01/01/1990',
//					maxDate: '12/31/2022',
//					dateLimit: { days: 60 },
//					showDropdowns: true,
//					showWeekNumbers: true,
//					timePicker: false,
//					timePickerIncrement: 1,
//					timePicker12Hour: true,
//					ranges: {
//						'Hoy': [moment(), moment()],
//						'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//						'Ultimos 7 dias':[moment().subtract(6, 'days'), moment()],
//						'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
//						'Este mes': [moment().startOf('month'), moment().endOf('month')],
//						'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
//					},
//					opens: 'left',
//					buttonClasses: ['btn btn-default'],
//					applyClass: 'btn-small btn-primary',
//					cancelClass: 'btn-small',
//					format: 'YYYY-MM-DD',
//					separator: ' to ',
//					locale: {
//						applyLabel: 'Aceptar',
//						cancelLabel: 'Borrar',
//						fromLabel: 'Desde',
//						toLabel: 'Hasta',
//						customRangeLabel: 'Personalizado',
//						daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
//						monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
//						firstDay: 1
//					}
//				},
//
//				function startend(start, end) {
//					$('#vxtrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
//					//enviar(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
//				}
//			);



			//Set the initial state of the picker label
			$('#reportrange span').html(moment("<?php echo $startdate?>").format('MMMM D, YYYY') + ' - ' + moment("<?php echo $enddate?>").format('MMMM D, YYYY'));

			$('.reportrange-dropdown ul li').on('click', function(){
				if ($(this).html() == "Hoy") {
					enviar(moment().format('YYYY-MM-DD'), moment().format('YYYY-MM-DD'));
				}

			}); 

		});


		function enviar(inicio, final){

			$('#startrange').val(inicio);

			$('#endrange').val(final);

			$('#filters').submit();

		}

	</script>

	<script type="text/javascript">

		function volver3(){

			$('#efectividadcontenido').show(); 
			$('#efectividaddetalle').hide();   
			$('#volver3').hide()
		}

		function efectividad(id){

			$('#efectividadcontenido').hide();
			$('#volver3').show();
			$.ajax({
				method: 'POST',
				url: '/legacy/ajax/minireports',
				data: {did: '<?php echo $sdate ?>', seller: id, startdate: '<?php echo $startdate; ?>', enddate: '<?php echo $enddate?>'},
				complete: function(response){
					$('#efectividaddetalle').html(response.responseText);
					$('#efectividaddetalle').show();
				},
				error: function(){
					efectividaddetalle.innerHTML("Hubo un error en la solicitud");
				}
			});
		}

		function volver(){

			$('#marcascontenido').show(); 
			$('#marcadetalle').hide();   
			$('#volver').hide();
		}

		function marca(id){

			$('#marcascontenido').hide();
			$('#volver').show();
			$.ajax({
				method: 'POST',
				url:'/legacy/ajax/minireports',
				data: {brand: id, sellers: [<?php echo $sellerlist ?>], startdate: '<?php echo $startdate; ?>', enddate: '<?php echo $enddate?>', excluding: '<?php echo $seller_condition ?>'},
				complete: function (response) {
					$('#marcadetalle').html(response.responseText);
					$('#marcadetalle').show();
				},
				error: function () {
					$('#marcadetalle').html("Hubo un error en la solicitud");
				},
			});


		}

		function volver2(){

			$('#productoscontenido').show(); 
			$('#productosdetalle').hide();   
			$('#volver2').hide();

		}

		function producto(id){
			$('#productoscontenido').hide();
			$('#volver2').show();
			$.ajax({
				method: 'POST',
				url: '/legacy/ajax/minireports',
				data: {product: id, sellers: [<?php echo $sellerlist ?>], startdate: '<?php echo $startdate; ?>', enddate: '<?php echo $enddate?>', excluding: '<?php echo $seller_condition ?>'},
				complete: function(response){
					$('#productosdetalle').html(response.responseText);
					$('#productosdetalle').show();
				},
				error: function(){
					$('#productosdetalle').html("Hubo un error en la solicitud");
				},
			});
		}

	</script>
	<script>
	$(document).on('click', ".location",  function ()
		{
			var latitude = $(this).data("latitude");

			var longitude = $(this).data("longitude");

			function initMap(latitude, longitude) {

				var myLatLngCenter = {lat: latitude, lng: longitude};

				var myLatLng = {lat: latitude, lng: longitude};

				var geocoder = new google.maps.Geocoder();

				var infowindow = new google.maps.InfoWindow({

					content: '',

				});

				var map = new google.maps.Map(document.getElementById('order_map'), {
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					zoom: 18,
					center: myLatLngCenter
				});

				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map
				});

				geocoder.geocode({'latLng': myLatLng}, function (marker) {
					geo = marker[0].formatted_address;
					infowindow.setContent('<div class="iw-subTitle"> ' + 'Calle más cercana: ' + geo + '</div>' + '</div>');
				}); 

				infowindow.open(map, marker);

			}

			$("#order_location_modal").modal('show');

			$('#order_location_modal').on('shown.bs.modal', function () {

				initMap(latitude, longitude);

		//alert(latitude + ' ' + longitude);;
	});

		});

	</script>

<script type="text/javascript">
	function showLoadingTable() {
		$('#loadingtable').show();
		$('#tabletotal').hide();
		$('#table-content').hide();
	}
	function hideLoadingTable() {
		$('#loadingtable').hide();
		$('#table-content').show();
		$('#tabletotal').show();
	}

	function calculateTotal(rows) {
		rows.forEach(function (row) {
			console.log(row.$el.data('total'))
		})
	}


	function orders() {
		showLoadingTable();
		$.ajax({
			method: 'POST',
			url:'/legacy/general',
			data: {
				function_type: 'orders',
				startdate: '<?php echo $startdate; ?>',
				enddate: '<?php echo $enddate?>',
				seller_condition: '<?php echo $seller_condition ?>',
				sellerlist: "<?php echo $sellerlist ?>"
			},
			complete: function (response) {
				prepareTable(response);
				$('#totaltext').html('$ '+$('#orderstotal').data('initialtotal').toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
				hideLoadingTable();
			},
			error: function () {
				alert("Error");
			}
		});

	}

	function prepareTable(response) {

		var tbody = $('#table-content').html(response.responseText);
		var ftable = $('.footable').footable();

		tbody.slimScroll({
			height: '700px',
			alwaysVisible: false
		});

		ftable.on('after.ft.filtering',function (e,ft) {
			calcTotals(ft)
		});
	}

	function calcTotals(ft) {
		var totals = ft.rows.array.reduce(function (acum,val) {
			return acum+$(val.$el[0]).data('total');
		},0);
		$('#totaltext').html('$ '+totals.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'))
	}

	function receivables(){

showLoadingTable();
		$.ajax({

			method: 'POST',
			url:'/legacy/general',
			data: {
				function_type: 'invoices',
				startdate: '<?php echo $startdate; ?>',
				enddate: '<?php echo $enddate?>',
				collector_condition: '<?php echo $collector_condition ?>',
				collectorlist: "<?php echo $collectorlist ?>"
			},
			complete: function (response) {
//			  console.log(response)
				prepareTable(response);
				$('#totaltext').html('$ '+$('#receivabletotal').data('initialtotal').toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
				hideLoadingTable();
			},
			error: function () {
				alert("Error");
			}
		});

	}


	function returns(){
		$('#tabletotal').hide();
showLoadingTable();
		$.ajax({
			method: 'POST',
			url:'/legacy/general',
			data: {
				function_type: 'returns',
				startdate: '<?php echo $startdate; ?>',
				enddate: '<?php echo $enddate?>',
				seller_condition: '<?php echo $seller_condition ?>',
				sellerlist: "<?php echo $sellerlist ?>"
			},
			complete: function (response) {

				$('#table-content').html(response.responseText);
				$('.footable').footable();
				hideLoadingTable();
				$('#tabletotal').hide();
			},
			error: function () {
				alert("Error");
			}
		});

	}

	orders();

	$('#list').on('input', function (){
		var value = $('#list option:selected').val();
		if (value == "orders")
			orders();
		else if(value == "receivables")
			receivables();
		else if(value == "returns")
			returns();
	});

</script>

</body>
</html>
