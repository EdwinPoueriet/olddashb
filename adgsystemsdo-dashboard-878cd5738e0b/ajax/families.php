<?php
namespace App\Legacy;


class Families extends General 

{

	public function GetFamiliesList()
	
	{

		$getfamilylistjson = json_decode($this->GetProductFamilies());

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


		foreach ($getfamilylistjson as $row)

		{

			echo '
			<tr>
				<td>
					<h2>'.$row->family_code.'</h2>
				</td>
				<td>
					<h2>'.$row->family_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="family_code" value="'.trim($row->family_code).'"></input>
  					
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

	public function EditFamiliesOptions()
	
	{

		$editfamilyoptionssql = $this->con->prepare("SELECT 
			family_code, 
			family_name 
		FROM ".$this->user_database.".product_families 
		WHERE company_id = :company_id AND TRIM(family_code) = TRIM(:family_code)");
		
		$editfamilyoptionssql->bindParam(':family_code', $_POST['family_code']); 
		
		$editfamilyoptionssql->bindParam(':company_id', $this->catalogue_company);
		
		$editfamilyoptionssql->execute();

		$editfamilyoptionssql = $editfamilyoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editfamilyoptionssqljson = json_decode(json_encode($editfamilyoptionssql));

		$row = $editfamilyoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="family_code" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="family_code" id="family_code" value="'.$row->family_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="family_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="family_name" id="family_name" value="'.$row->family_name.'" maxlength="20">

				</div>

			</div>
		';

	}

	public function EditGeneral() 

	{

		$familyeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".product_families 
			SET 
			family_name = :family_name
			WHERE family_code = :family_code 
			AND company_id = :company_id
			");
		$familyeditsubmitsql->bindParam(':company_id', $this->catalogue_company);
		$familyeditsubmitsql->bindParam(':family_name', $_POST['family_name']);
		$familyeditsubmitsql->bindParam(':family_code', $_POST['family_code']);
		$familyeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateGeneral() 

	{

	$verifyfamily = $this->con->prepare("SELECT family_code FROM ".$this->user_database.".product_families WHERE family_code = :family_code and company_id = :company_id");

	$verifyfamily->bindParam (':family_code', $_POST['family_code']);

	$verifyfamily->bindParam(':company_id', $this->catalogue_company);

	$verifyfamily->execute();

	$verifyfamily = $verifyfamily->rowCount();

	if ($verifyfamily > 0) {

		echo '<div class="text-center"><b>La familia '.$_POST['family_code'].' ya existe en la compañía '.$cid.'!</b></div>';
	}

	else {

		$this->con->beginTransaction();

		try{

			$createfamiliesubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".product_families
				(
				company_id,
				family_name,
				family_code
				)
				VALUES 
				(
				:company_id,
				:family_name,
				:family_code
				)
				");

			$createfamiliesubmitsql->bindParam(':company_id', $this->catalogue_company);

			$createfamiliesubmitsql->bindParam(':family_name', $_POST['family_name']);

			$createfamiliesubmitsql->bindParam(':family_code', $_POST['family_code']);

			$createfamiliesubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>La familia ha sido creado con exito!</b></div>';

		}
	}
}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  		$getvalues = new Families;
  		$getvalues->GetFamiliesList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Families;
		$editoptions->EditFamiliesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Families;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Families;
		$createoptions->CreatefamiliesOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Families;
		$createoptions->CreateGeneral();
	}

}


?>