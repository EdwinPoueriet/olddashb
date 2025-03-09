<?php

namespace App\Legacy;


class Brands extends General 
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetBrandsList()
	
	{
		$getbrandlistjson = json_decode($this->GetBrands());

  		echo '<div class="table-responsive" id="areas_list">
  	
  		<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
  			<thead>
  				<tr>
  					<th>ID</th>
  					<th>Nombre</th>
  					<th data-type="html" >Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach ($getbrandlistjson as $row)
		{

			echo '
			<form style="display: none" id="'.trim($row->brand_id).'" method="POST" onsubmit="event.preventDefault();">
				<input type="hidden" name="form_id" value="editoptions"></input>
				<input type="hidden" name="brand_id" value="'.trim($row->brand_id).'"></input>
			</form>
			<tr>
				<td>
					<h2>'.$row->brand_id.'</h2>
				</td>
				<td>
					<h2>'.$row->brand_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="brand_id" value="'.trim($row->brand_id).'"></input>
  					
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

	public function EditBrandOptions()
	
	{

		$editbrandoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".brands 
			WHERE TRIM(brand_id) = TRIM(:brand_id)");
		
		$editbrandoptionssql->bindParam(':brand_id', $_POST['brand_id']); 
			
		$editbrandoptionssql->execute();

		$editbrandoptionssql = $editbrandoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editbrandoptionssqljson = json_decode(json_encode($editbrandoptionssql));

		$row = $editbrandoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="brand_id" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="brand_id" id="brand_id" value="'.$row->brand_id.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="brand_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="brand_name" id="brand_name" value="'.$row->brand_name.'" maxlength="30">

				</div>

			</div>

		';

	}

	public function EditGeneral() {

		$brandeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".brands SET 
			brand_name = :brand_name
			WHERE brand_id = :brand_id
			");
		$brandeditsubmitsql->bindParam(':brand_id', $_POST['brand_id']);
		$brandeditsubmitsql->bindParam(':brand_name', $_POST['brand_name']);
		$brandeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}
	

public function CreateGeneral() {

		$this->con->beginTransaction();

		try{

			$brandeditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".brands
				(
				brand_name
				)
				VALUES 
				(
				:brand_name
				)
				");

			$brandeditsubmitsql->bindParam(':brand_name', $_POST['brand_name']);

			$brandeditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();

			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>La marca ha sido creada con éxito!</b></div>';


}
}

if (isset($_POST['form_id'])){


  	if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new Brands;
  			$getvalues->GetBrandsList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Brands;
		$editoptions->EditbrandOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Brands;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Brands;
		$createoptions->CreateGeneral();
	}

}


?>