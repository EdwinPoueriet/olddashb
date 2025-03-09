<?php

use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
use App\Legacy\Credentials;
	include('config/datetime_parameters.php');


	$general_functions = new General;
/* 

	Structure and stuff. 

*/
    $credentials_obj = new Credentials;

    $credentials_json = $credentials_obj->SelectedCompanyCredentials();             

    $credentials = json_decode($credentials_json);

    if($credentials[0]->registers_sellers == 0)
    	header('Location: home');


/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(0, "Vendedores");

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
									
									<ol class="breadcrumb">
										<li><a href="#">Home</a></li>
										<li class="active"><span>Vendedores</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Vendedores</h1>
										
										<div class="filter-block pull-right">
											<a onclick="createlist();" class="btn btn-primary pull-right">
												<i class="fa fa-plus-circle fa-lg"></i> Agregar vendedor
											</a>
										</div>
									</div>
								
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-12">
									<div class="main-box no-header clearfix">
										<div class="main-box-body clearfix" id="main-content">


										</div>
									</div>
								</div>
							</div>

						</div>
	</div>

	<!-- Standard Bootstrap Modal -->
	<div class="modal fade" id="a_modal" tabindex="-1" role="dialog" aria-hidden="true">
	  	<form class="form-horizontal vert-offset-top-2" id="edit_submit" onsubmit="event.preventDefault();" method="POST">
			<div class="modal-dialog">
				<div class="modal-content">



				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</form>
	</div><!-- /.modal -->	

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

	<script>

	function getValues(){


		$.ajax({

			method: 'POST',

			url: 'ajax/legacy/sellers.php',

			data: {form_id: "getvalues"},

			complete: function(response){


				$('#main-content').html(response.responseText);
				$('table').footable();

			},

			error: function (response){

				alert('Hubo un error al enviar la solicitud');

			}

		});

	}

	$(document).on('submit', '.edit_form', function(){

		$.ajax({

			method: 'POST',

			url: 'ajax/legacy/sellers.php',

			data: $(this).serialize(),

			complete: function(response){
				$('#a_modal .modal-content').html('<div class="modal-header">' +
						'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
						'<h4 class="modal-title">Editar</h4>' +
					'</div>' +
					'<div class="modal-body">' +
					'</div>' +
					'<div class="modal-footer">'+
						'<input type="submit" value="Aceptar" class="btn btn-success" style="width: 100px">'+
					'</div>');

				$('#a_modal .modal-body').html(response.responseText);
				$('#a_modal').modal('show');
			},

			error: function (response){

				alert('Hubo un error al enviar la solicitud');

			}

		});

	});

	$(document).on('submit', '#edit_submit', function() {

		$.ajax({

			method: 'POST',

			url: 'ajax/legacy/sellers.php',

			data: $(this).serialize(),

			complete: function(response){

				$('#a_modal .modal-content').html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Editar</h4></div><div class="modal-body">' + response.responseText + '</div>');
				getValues();
			},

			error:  function(response){

				alert('');

			}

		});


	});


	function createlist(){

		$('#a_modal .modal-content').html('<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" method="POST" onsubmit="event.preventDefault();">' +

			'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Agregar</h4></div><div class="modal-body">' +

  			'<input type="hidden" id="form_id" name="form_id" value="submitcreate">' +

		'<div class="form-group">' +

			'<label for="seller_code" class="control-label col-sm-3">Código:</label>' +

			'<div class="col-sm-7">' +

				'<input class="form-control input-sm" type="text" name="seller_code" id="seller_code" placeholder="000" maxlength="3" required>' +

			'</div>' +

		'</div>' +

		'<div class="form-group">' +

			'<label for="seller_name" class="control-label col-sm-3">Nombre:</label>' +

			'<div class="col-sm-7">' +

				'<input class="form-control input-sm" type="text" name="seller_name" id="seller_name" placeholder="Nombre" maxlength="20" required>' +

			'</div>' +

		'</div>' +

		'<div class="form-group">' +

			'<label for="seller_phone" class="control-label col-sm-3">Teléfono:</label>' +

			'<div class="col-sm-7">' +

				'<input class="form-control input-sm" type="text" name="seller_phone" id="seller_phone" placeholder="XXX-XXX-XXXX" maxlength="12" required>' +

			'</div>' +

		'</div>' +

		'<div class="form-group">' +

			'<label for="seller_discount_percent" class="control-label col-sm-3">Porciento de descuento:</label>' +

			'<div class="col-sm-7">' +

				'<input class="form-control input-sm" type="number" name="seller_discount_percent" id="seller_discount_percent" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); if (this.value < 0 || this.value > 99) this.value = 0" maxlength="2" min="0" max="99" value="0" required>' +

			'</div>' +
		'</div>' +

			 '</div>' +
					'<div class="modal-footer">'+
						'<input type="submit" value="Aceptar" class="btn btn-success" style="width: 100px">'+
					'</div>' +
			'</form>');
				$('#a_modal').modal('show');

			}

	$(document).on('submit', '#createform', function(){

		$.ajax({

			method: 'POST',

			url: 'ajax/legacy/sellers.php',

			data: $(this).serialize(),

			complete: function(response){

				$('#content').html('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Agregar</h4></div><div class="modal-body">' + response.responseText + '</div>');

			},

			error:  function(response){

				alert('');

			}

		});

	}); 


	getValues();
	</script>

</body>
</html>