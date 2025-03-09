<?php
namespace App\Legacy;



class Sellers extends General 
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetSellersList()
	
	{
  		echo '<div class="table-responsive" id="areas_list">
  	
  		<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
  			<thead>
  				<tr>
  					<th>Código</th>
  					<th>Nombre</th>
  					<th>Vendedor</th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach (json_decode($this->GetSellers()) as $row)
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->seller_code.'</h2>
				</td>
				<td>
					<h2>'.$row->seller_name.'</h2>    
				</td>
				<td>
					<a href="#">'.$row->seller_phone.'</a>
				</td>
				<td style="width: 20%;">
      					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
      						<input type="hidden" name="form_id" value="editoptions"></input>
      						<input type="hidden" name="seller_code" value="'.trim($row->seller_code).'"></input>
      					
      							<a class="table-link edit" style="cursor:pointer" onclick="$(this).submit()">
      								<span class="fa-stack">
      									<i class="fa fa-square fa-stack-2x"></i>
      									<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
      								</span>
      							</a>
      					</form>
					<a href="#" class="table-link danger">
						<span class="fa-stack">
							<i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
						</span>
					</a>
				</td>
			</tr>';

		}

  				echo '</tbody>
  			</table>
  			<ul class="pagination pull-right hide-if-no-paging"></ul>
  		</div>';

	}

	public function EditSellerOptions()
	
	{

		$editselleroptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".sellers 
			WHERE company_id = :company_id AND TRIM(seller_code) = TRIM(:seller_code)");
		
		$editselleroptionssql->bindParam(':seller_code', $_POST['seller_code']); 
		
		$editselleroptionssql->bindParam(':company_id', $this->default_company);
		
		$editselleroptionssql->execute();

		$editselleroptionssql = $editselleroptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editselleroptionssqljson = json_decode(json_encode($editselleroptionssql));

		$row = $editselleroptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="seller_code" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="seller_code" id="seller_code" value="'.$row->seller_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="seller_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="seller_name" id="seller_name" value="'.$row->seller_name.'" maxlength="20">

				</div>

			</div>

			<div class="form-group">

				<label for="seller_phone" class="control-label col-sm-3">Teléfono:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="seller_phone" id="seller_phone" value="'.$row->seller_phone.'" maxlength="12">

				</div>

			</div>

			<div class="form-group">

				<label for="seller_discount_percent" class="control-label col-sm-3">Porciento de descuento:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="seller_discount_percent" id="seller_discount_percent" value="'.$row->seller_discount_percent.'">

				</div>
			</div>
		';

	}

	public function EditGeneral() 

	{

		$sellereditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".sellers SET 
			seller_name = :seller_name,
			seller_phone = :seller_phone,
			seller_discount_percent = :seller_discount_percent
			WHERE seller_code = :seller_code and company_id = :company_id
			");
		
		$sellereditsubmitsql->bindParam(':company_id', $this->default_company);
		
		$sellereditsubmitsql->bindParam(':seller_name', $_POST['seller_name']);
		
		$sellereditsubmitsql->bindParam(':seller_phone', $_POST['seller_phone']);
		
		$sellereditsubmitsql->bindParam(':seller_discount_percent', $_POST['seller_discount_percent']);
		
		$sellereditsubmitsql->bindParam(':seller_code', $_POST['seller_code']);
		
		$sellereditsubmitsql->execute();

		echo '<div class="text-center">
				<b>Los cambios han sido realizados correctamente!.</b>
			  </div>';

	}

public function CreateGeneral() {

	$verifyseller = $this->con->prepare("SELECT seller_code FROM ".$this->user_database.".sellers WHERE seller_code = :seller_code and company_id = :company_id");

	$verifyseller->bindParam (':seller_code', $_POST['seller_code']);

	$verifyseller->bindParam(':company_id', $this->default_company);

	$verifyseller->execute();

	$verifyseller = $verifyseller->rowCount();

	if ($verifyseller > 0) {

		echo '<div class="text-center"><b>El vendedor '.$_POST['seller_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
	}

	else {

		$this->con->beginTransaction();

		try{

			$sellereditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".sellers
				(
				company_id,
				seller_name,
				seller_phone,
				seller_discount_percent,
				seller_code
				)
				VALUES 
				(
				:company_id,
				:seller_name,
				:seller_phone,
				:seller_discount_percent,
				:seller_code
				)
				");

			$sellereditsubmitsql->bindParam(':company_id', $this->default_company);

			$sellereditsubmitsql->bindParam(':seller_name', $_POST['seller_name']);

			$sellereditsubmitsql->bindParam(':seller_phone', $_POST['seller_phone']);

			$sellereditsubmitsql->bindParam(':seller_discount_percent', $_POST['seller_discount_percent']);

			$sellereditsubmitsql->bindParam(':seller_code', $_POST['seller_code']);

			$sellereditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>El vendedor ha sido creado con éxito!</b></div>';

	}
}
}

if (isset($_POST['form_id'])){

  		if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new Sellers;
  			$getvalues->GetSellersList();

  		}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Sellers;
		$editoptions->EditSellerOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Sellers;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Sellers;
		$createoptions->CreateSellerOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Sellers;
		$createoptions->CreateGeneral();
	}

}


?>