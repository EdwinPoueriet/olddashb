<?php 

use App\Legacy\Select;
use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;

	include('config/datetime_parameters.php');

  	$header = new Header;
  	$header->Initialize(0, "Seleccionar compañía");

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
						<li><a href="#">Inicio</a></li>
						<li class="active"><span>Seleccionar compañía </span></li>
					</ol>

					<h1>Seleccionar compañía </h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="main-box clearfix">
						<header class="main-box-header clearfix">
						</header>

						<div class="main-box-body clearfix col-md-offset-3">

							<form method="POST" class="form-horizontal">
								<div class="form-group col-md-7">
									<label class="control-label col-sm-2" for="companyselect"></label>
									<div class="col-sm-10">
										<?php 
										$select = new Select;
										$select->SelectShow();

										?>
									</div>
								</div>
								<div class="col-sm-offset-3 col-sm-10">
									<button type="submit" name="login" class="btn btn-success">Aceptar</button>
								</div>
							</form>


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
</body>
</html>