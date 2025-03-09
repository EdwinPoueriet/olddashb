<?php
/* 
	@Author: Williams Martinez
	Required files for login, credentials and general functions 

*/

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

    if($credentials[0]->registers_product_families == 0)
    	header('Location: home');

	include_once('config/structure/header.php');

	include_once('config/structure/navigation_menu.php');

	include_once('config/structure/footer.php');

	include_once('config/structure/scripts.php');

/* 

	Areas list 

*/

    include_once('/legacy/ajax/families.php');

/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(0, "Familias");

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
						<li class="active"><span>Familias</span></li>
					</ol>

					<div class="clearfix">
						<h1 class="pull-left">Familias</h1>

						<div class="filter-block pull-right">
							<a onclick="createlist();" class="btn btn-primary pull-right">
								<i class="fa fa-plus-circle fa-lg"></i> Agregar familia
							</a>
							<a onclick="familieslist();" class="btn btn-primary pull-right" style="display:none" id="back">
								<i class="fa fa-back fa-lg"></i> Volver
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

			url: '/legacy/ajax/families',

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

			url: '/legacy/ajax/families',

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

			url: '/legacy/ajax/families',

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


	function createlist()

	{

		$('#a_modal .modal-content').html('<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" method="POST" onsubmit="event.preventDefault();">' +

			'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Agregar</h4></div><div class="modal-body">' +

  			'<input type="hidden" id="form_id" name="form_id" value="submitcreate">' +

		'<div class="form-group">' +

			'<label for="family_code" class="control-label col-sm-3">Código:</label>' +

			'<div class="col-sm-7">' +

				'<input class="form-control input-sm" type="text" name="family_code" id="family_code" placeholder="000" required>' +

			'</div>' +

		'</div>' +

		'<div class="form-group">' +

			'<label for="family_name" class="control-label col-sm-3">Nombre:</label>' +

			'<div class="col-sm-7">' +

				'<input class="form-control input-sm" type="text" name="family_name" id="family_name" placeholder="Nombre" required>' +

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

			url: '/legacy/ajax/families',

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