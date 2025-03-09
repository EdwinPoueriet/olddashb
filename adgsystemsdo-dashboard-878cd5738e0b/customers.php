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

    if($credentials[0]->registers_customers == 0)
    	header('Location: home');


/* 

	customers list 

*/

    include_once('/legacy/ajax/customers.php');

/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(0, "Clientes");

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
										<li class="active"><span>Clientes</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Clientes</h1>
										
										<div class="filter-block pull-right">
											<a onclick="createlist();" class="btn btn-primary pull-right">
												<i class="fa fa-plus-circle fa-lg"></i> Agregar cliente
											</a>
											<a onclick="customerslist();" class="btn btn-primary pull-right" style="display:none" id="back">
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
											<div class="table-responsive" id="customers_list">

												<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
													<thead>
														<tr>
															<th>Código</th>
															<th>Nombre</th>
															<th>Vendedor</th>
															<th data-type="html">Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php 

														$customerslist = new Customers;
														$customerslist->GetcustomersList();

														?>
														
													</tbody>
												</table>
											<ul class="pagination pull-right hide-if-no-paging"></ul>
											</div>
											<div id="content"></div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>

	<form onsubmit="event.preventDefault();" id="createlist" action="" method="POST" style="display:none">
		<input type="hidden" name="form_id" id="form_id" value="createoptions">
	</form>

<?php 
/*	
	Shows document footer 
	@include('config/structure/footer.php') 
	must be included for this function to run 

*/

	$footer = new Footer;

	$footer->Initialize();

/*	
	Shows document scripts 
	@include('config/structure/scripts.php') 
	must be included for this function to run 

*/

	Scripts::Initialize();

	?>

	<script src="js/registers/customers.js"></script>

</body>
</html>