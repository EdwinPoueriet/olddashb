<?php
namespace App\Legacy;


class ProductTypes extends General 
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetProductTypesList()
	
	{
  		echo '<div class="table-responsive" id="areas_list">
  
  		<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
  			<thead>
  				<tr>
  					<th>Código</th>
  					<th>Nombre</th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach (json_decode($this->GetProductTypes()) as $row)
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->product_type_code.'</h2>
				</td>
				<td>
					<h2>'.$row->product_type_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
      						<input type="hidden" name="form_id" value="editoptions"></input>
      						<input type="hidden" name="product_type_id" value="'.trim($row->product_type_id).'"></input>
      					
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

	public function EditProductTypesOptions()
	
	{

		$editbrandoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".product_types 
			WHERE TRIM(product_type_id) = TRIM(:product_type_id)");
		
		$editbrandoptionssql->bindParam(':product_type_id', $_POST['product_type_id']); 
			
		$editbrandoptionssql->execute();

		$editbrandoptionssql = $editbrandoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editbrandoptionssqljson = json_decode(json_encode($editbrandoptionssql));

		$row = $editbrandoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<input type="hidden" name="product_type_id" id="product_type_id" value="'.$row->product_type_id.'">

			<div class="form-group">

				<label for="product_type_code" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="product_type_code" id="product_type_code" value="'.$row->product_type_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="product_type_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="product_type_name" id="product_type_name" value="'.$row->product_type_name.'" maxlength="30">

				</div>

			</div>
		';

	}

	public function EditGeneral() {

		$brandeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".product_types 
			SET 
			product_type_name = :product_type_name
			WHERE product_type_id = :product_type_id
			");
		$brandeditsubmitsql->bindParam(':product_type_id', $_POST['product_type_id']);
		$brandeditsubmitsql->bindParam(':product_type_name', $_POST['product_type_name']);
		$brandeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}
	
public function CreateGeneral() {

		$this->con->beginTransaction();

		try{

			$producttypeseditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".product_types
				(
				company_id,
				product_type_code,
				product_type_name
				)
				VALUES 
				(
				:company_id,
				:product_type_code,
				:product_type_name
				)
				");

			$producttypeseditsubmitsql->bindParam(':company_id', $this->catalogue_company);

			$producttypeseditsubmitsql->bindParam(':product_type_code', $_POST['product_type_code']);

			$producttypeseditsubmitsql->bindParam(':product_type_name', $_POST['product_type_name']);

			$producttypeseditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();

			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>El Tipo de Producto ha sido creado con éxito!</b></div>';


}

}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new ProductTypes;
  			$getvalues->GetProductTypesList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new ProductTypes;
		$editoptions->EditProductTypesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new ProductTypes;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new ProductTypes;
		$createoptions->CreateGeneral();
	}

}


?>