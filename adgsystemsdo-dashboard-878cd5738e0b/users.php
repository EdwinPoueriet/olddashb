<?php
use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\Credentials;

	include('config/datetime_parameters.php');

use App\Legacy\Users;
$userlist = new Users;
$general_functions = $userlist;
/* 

	Structure and stuff. 

*/
    $credentials_obj = new Credentials;

    $credentials_json = $credentials_obj->SelectedCompanyCredentials();             

    $credentials = json_decode($credentials_json);

    if($credentials[0]->registers_users == 0)
    	header('Location: home');


/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(0, "Usuarios");

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
									<ol class="breadcrumb">
										<li><a href="#">Home</a></li>
										<li class="active"><span>Users</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Users</h1>
										
										<div class="filter-block pull-right">
											<a onclick="create();" class="btn btn-primary pull-right">
												<i class="fa fa-plus-circle fa-lg"></i> Agregar usuario
											</a>
											<a onclick="userlist();" class="btn btn-primary pull-right" style="display:none" id="back">
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
											<div class="table-responsive" id="user_list">

												<table class="table user-list footable table-hover" data-paging-size="50" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
													<thead>
														<tr>
															<th>Usuario</th>
                                                            <th>Vendedor</th>
                                                            <th>Colaborador</th>
															<th>Fecha de creacion</th>
															<th>Email</th>
															<th data-type="html">Acciones</th>
														</tr>
													</thead>
													<tbody>

														<?php 


														$userlist->GetUserList();

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


	<form onsubmit="event.preventDefault();" id="createlist" action="" method="POST" style="display:none">
		<input type="hidden" name="form_id" id="form_id" value="createoptions">
	</form>

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

		<style type="text/css">
			.select2-container .select2-selection--multiple{width: 250px}
			.select2-container--default .select2-results > .select2-results__options {width:250px;}
		</style>
	<script type="text/javascript" src="js/registers/users.js"></script>

</body>
</html>