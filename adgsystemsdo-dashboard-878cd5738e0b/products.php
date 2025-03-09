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

    if($credentials[0]->registers_products == 0)
    	header('Location: home');

	include_once('config/structure/header.php');

	include_once('config/structure/navigation_menu.php');

	include_once('config/structure/footer.php');

	include_once('config/structure/scripts.php');

/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(0, "Productos");

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
						<li class="active"><span>Productos</span></li>
					</ol>

					<div class="clearfix">
						<h1 class="pull-left">Productos</h1>

						<div class="filter-block pull-right">
							<a onclick="createlist();" class="btn btn-primary pull-right">
								<i class="fa fa-plus-circle fa-lg"></i> Agregar producto
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
			<div class="modal-dialog modal-lg">
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

			url: 'ajax/legacy/products.php',

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

			url: 'ajax/legacy/products.php',

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

			url: 'ajax/legacy/products.php',

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

        '<div class="row">' +

        '<div class="col-md-6 col-sm-6">' +

			'<div class="form-group">' +

  				'<label for="product_code" class="control-label col-sm-4">Código del producto:</label>' +

  				'<div class="col-sm-8">' +

  					'<input class="form-control input-sm" type="text" name="product_code" id="product_code" placeholder="000">' +

  				'</div>' +

  			'</div>' +

  			'<div class="form-group">' +

  				'<label for="product_reference" class="control-label col-sm-4">Referencia del producto:</label>' +

  				'<div class="col-sm-8">' +

  					'<input class="form-control input-sm" type="text" name="product_reference" id="product_reference" placeholder="xxx-xxx">' +

  				'</div>'+ 

  			'</div>' +

  			'<div class="form-group">' +

  				'<label for="product_name" class="control-label col-sm-4">Nombre del producto:</label>' +

  				'<div class="col-sm-8">' +

  					'<input class="form-control input-sm" type="text" name="product_name" id="product_name" value="" maxlength="100">' +

  				'</div>' +

  			'</div>' +

  			'<div class="form-group">' +

  				'<label for="brand_id" class="control-label col-sm-4">Marca:</label>' +

  				'<div class="col-sm-8">' +

  					'<select class="form-control input-sm" type="number" name="brand_id" id="brand_id">' +
  						
  						'<option value="0"></option><?php foreach (json_decode($general_functions->GetBrands()) as $brandrow) { echo '<option value="'.$brandrow->brand_id.'">'.$brandrow->brand_name.'</option>';}?></select>' +

  					'</div>' +

  				'</div>' +

  				'<div class="form-group">' +

  					'<label for="unit_id" class="control-label col-sm-4">Unidad:</label>' +

  					'<div class="col-sm-8">' +

  						'<select class="form-control input-sm" name="unit_id" id="unit_id">' +
  							
  							'<option value="0"></option><?php foreach (json_decode($general_functions->GetUnits()) as $unitsrow){ echo '<option value="'.$unitsrow->unit_id.'">'.$unitsrow->unit_name.'</option>'; }?></select>' +

  						'</div>' +

  					'</div>' +

  					'<div class="form-group">' +

  						'<label for="classification_code" class="control-label col-sm-4">Clasificación:</label>' +

  						'<div class="col-sm-8">' +

  							'<select class="form-control input-sm" name="classification_code" id="classification_code">' +
  								'<option value="0"></option><?php foreach (json_decode($general_functions->GetClassifications()) as $classrow) {	echo '<option value="'.$classrow->classification_code.'">'.$classrow->classification_code.' - '.$classrow->classification_name.'</option>';}?></select>'+

  							'</div>' +

  						'</div>' +

  						'<div class="form-group">' +

  							'<label for="subclassification_code" class="control-label col-sm-4">Subclasificación:</label>' +

  							'<div class="col-sm-8">' +

  								'<select class="form-control input-sm" name="subclassification_code" id="subclassification_code">' +
  									'<option value="0"></option><?php foreach (json_decode($general_functions->GetClassifications()) as $classrow){echo '<optgroup label="'.$classrow->classification_code.' - '.$classrow->classification_name.'">'; foreach (json_decode($general_functions->GetSubclassifications($classrow->classification_code)) as $subclassrow) { echo '<option value="'.$subclassrow->subclassification_code.'">'.$subclassrow->subclassification_code.' - '.$subclassrow->subclassification_name.'</option>'; } echo '</optgroup>';}?></select>'+

  								'</div>'+

  							'</div>' +

              '<div class="form-group">' +

               ' <label for="product_type_code" class="control-label col-sm-4">Tipo de producto:</label>' +

                '<div class="col-sm-8">' +

                  '<select class="form-control input-sm" type="number" name="product_type_code" id="product_type_code">' +
                    '<option value="0"></option><?php foreach (json_decode($general_functions->GetProductTypes()) as $prodtyperow) { echo '<option value="'.$prodtyperow->product_type_code.'">'.$prodtyperow->product_type_name.'</option>'; }?></select>' + 

                  '</div>' +

                '</div>' +

              '<div class="form-group">' +

                '<label for="family_code" class="control-label col-sm-4">Familia del producto:</label>' +

                '<div class="col-sm-8">' +

                  '<select class="form-control input-sm" type="number" name="family_code" id="family_code">' +
                    
                    '<option value="0"></option><?php foreach (json_decode($general_functions->GetProductFamilies()) as $prodfamrow){ echo '<option value="'.$prodfamrow->family_code.'">'.$prodfamrow->family_name.'</option>';}?></select>'+ 

                  '</div>' +

                '</div>' +

                '<div class="form-group">' +

                  '<label for="group_code" class="control-label col-sm-4">Grupo del producto:</label>' +

                  '<div class="col-sm-8">' +

                    '<select class="form-control input-sm" type="number" name="group_code" id="group_code">' +
                      '<option value="0"></option><?php foreach (json_decode($general_functions->GetProductGroups()) as $prodgrprow){ echo '<option value="'.$prodgrprow->group_code.'">'.$prodgrprow->group_name.'</option>'; }?> </select>'+

                    '</div>' +

                  '</div>' +

                '<div class="form-group">' +

                  '<label for="product_in_stock" class="control-label col-sm-4">Existencia en el almacen:</label>' +

                  '<div class="col-sm-8">' +

                    '<input class="form-control input-sm" type="number" name="product_in_stock" id="product_in_stock" placeholder="0.00">' +

                  '</div>' +

                '</div>' +

                  '</div>' +

                '<div class="col-md-6 col-sm-6">' +

  							'<div class="form-group">' +

  								'<label for="product_packaging" class="control-label col-sm-4">Empaques:</label>' +

  								'<div class="col-sm-8">' +

  									'<input class="form-control input-sm" type="number" name="product_packaging" id="product_packaging" placeholder="0.00">' +

  								'</div>' +

  							'</div>' +

  							'<div class="form-group">' +

  								'<label for="product_first_price" class="control-label col-sm-4">Primer precio:</label>' +

  								'<div class="col-sm-8">' +

  									'<input class="form-control input-sm" type="number" name="product_first_price" id="product_first_price" placeholder="0.00">' +

  								'</div>' +

  							'</div>' +

  							'<div class="form-group">' +

  								'<label for="product_second_price" class="control-label col-sm-4">Segundo precio:</label>' +

  								'<div class="col-sm-8">' +

  									'<input class="form-control input-sm" type="number" name="product_second_price" id="product_second_price" placeholder="0.00">' +

  								'</div>' +

  							'</div>' +

  							'<div class="form-group">' +

  								'<label for="product_third_price" class="control-label col-sm-4">Tercer precio:</label>' +

  								'<div class="col-sm-8">' +

  									'<input class="form-control input-sm" type="number" name="product_third_price" id="product_third_price" placeholder="0.00">' +

  								'</div>' +

  							'</div>' +

  							'<div class="form-group">' +

  								'<label for="product_fourth_price" class="control-label col-sm-4">Cuarto precio:</label>' +

  								'<div class="col-sm-8">' +

  									'<input class="form-control input-sm" type="number" name="product_fourth_price" id="product_fourth_price" placeholder="0.00">' +

  								'</div>' +

  							'</div>' +

                  '<div class="form-group">' +

                    '<label for="product_format" class="control-label col-sm-4">Formato del producto:</label>' +

                    '<div class="col-sm-8">' +

                      '<select class="form-control input-sm" type="number" name="product_format" id="product_format">' +

                        '<option value="0">Todos</option>' +
                        
                        '<option value="1">Regular</option>' +

                        '<option value="2">Kit</option>' +

                        '<option value="3">Combo</option>' + 

                      '</select>' +

                    '</div>' +

                  '</div>' +

                  '<div class="form-group">' +

                    '<label for="product_tax_percent" class="control-label col-sm-4">Porciento de Impuesto:</label>' +

                    '<div class="col-sm-8">' +

                      '<input class="form-control input-sm" type="number" name="product_tax_percent" id="product_tax_percent" placeholder="0.00">' +

                    '</div>' +

                  '</div>' +

                  '<div class="form-group">' +

                    '<div class="col-md-offset-2">' +

                      '<div class="checkbox-nice">' +

                        '<input type="checkbox" name="product_accepts_discount" id="product_accepts_discount" value="S">' +
                        
                        '<label for="product_accepts_discount">El producto acepta descuentos.</label>' +

                      '</div>' +

                    '</div>' +

                  '</div>' +

                  '<div class="form-group">' +

                  '<div class="col-md-offset-2">' +
                  
                    '<div class="checkbox-nice">' +

                      '<input type="checkbox" name="product_pays_tax" id="product_pays_tax" value="S">' +

                      '<label for="product_pays_tax">El producto paga impuestos.</label>' +

                    '</div>' +
                  
                  '</div>' +

                '</div>' +

  							'<div class="form-group">' +

  								'<div class="col-md-offset-2">' +

  									'<div class="checkbox-nice">' +

  										'<input type="checkbox" name="product_offer" id="product_offer" value="1">' +

  										'<label for="product_offer">El producto esta en oferta.</label>' +

  									'</div>' +

  								'</div>' +

  							'</div>' +

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

			url: 'ajax/legacy/products.php',

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