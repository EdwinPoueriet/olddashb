<?php

namespace App\Legacy;


class Companies extends Credentials 

{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetCompaniesList()
	
	{
		$companiesjson = json_decode($this->CredentialsCompanySql());

		foreach ($companiesjson as $row)
			
		{
		
			$company_info = $this->con->prepare("SELECT 
				company_id, 
				company_name 
			FROM ".$this->user_database.".companies 
			WHERE company_id = :company_id");
			
			$company_info->bindParam(':company_id', $row->company_id);

			$company_info->execute();

			$company_info = $company_info->fetchAll(\PDO::FETCH_ASSOC);

			$companiesarray[] = $company_info[0]; 
		
		}

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

		foreach (json_decode(json_encode($companiesarray)) as $row)

		{

			echo '
			<tr>
				<td>
					<h2>'.$row->company_id.'</h2>
				</td>
				<td>
					<h2>'.$row->company_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="company_id" value="'.trim($row->company_id).'"></input>
  					
  							<a class="table-link edit" style="cursor:pointer" onclick="$(this).submit()">
  								<span class="fa-stack">
  									<i class="fa fa-square fa-stack-2x"></i>
  									<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
  								</span>
  							</a>
  					</form>
				</td>
			</tr>';

		}

  		echo '</tbody>
  			</table>
  			<ul class="pagination pull-right hide-if-no-paging"></ul>
  		</div>';

	}

	public function EditCompaniesOptions()
	
	{

		$editcompanyoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".companies 
			WHERE company_id = :company_id AND TRIM(company_id) = TRIM(:company_id)");
			
		$editcompanyoptionssql->bindParam(':company_id', $_POST['company_id']);
		
		$editcompanyoptionssql->execute();

		$editcompanyoptionssql = $editcompanyoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editcompanyoptionssqljson = json_decode(json_encode($editcompanyoptionssql));

		$row = $editcompanyoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="company_id" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_id" id="company_id" value="'.$row->company_id.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="company_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_name" id="company_name" value="'.$row->company_name.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="company_town" class="control-label col-sm-3">Ciudad:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_town" id="company_town" value="'.$row->company_town.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="company_address" class="control-label col-sm-3">Dirección:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_address" id="company_address" value="'.$row->company_address.'">

				</div>

			</div>

			<div class="form-group">

				<label for="company_phones" class="control-label col-sm-3">Teléfonos:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_phones" id="company_phones" value="'.$row->company_phones.'">

				</div>

			</div>

			<div class="form-group">

				<label for="company_owner" class="control-label col-sm-3">Dueño:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_owner" id="company_owner" value="'.$row->company_owner.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="company_rnc" class="control-label col-sm-3">RNC:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_rnc" id="company_rnc" value="'.$row->company_rnc.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="company_catalog_id" class="control-label col-sm-3">Compañía del catálogo:</label>

				<div class="col-sm-6">

					<input class="form-control input-sm" type="text" name="company_catalog_id" id="company_catalog_id" value="'.$row->company_catalog_id.'" readonly>

				</div>

			</div>';

	}

	public function EditGeneral() {

		$companyeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".companies SET 
			company_phones = :company_phones,
			company_address = :company_address
			WHERE company_id = :company_id
			");
		$companyeditsubmitsql->bindParam(':company_id', $this->default_company);
		$companyeditsubmitsql->bindParam(':company_address', $_POST['company_address']);
		$companyeditsubmitsql->bindParam(':company_phones', $_POST['company_phones']);
		$companyeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  		$getvalues = new Companies;
  		$getvalues->GetCompaniesList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Companies;
		$editoptions->EditcompaniesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Companies;
		$editoptions->EditGeneral();

	}
}


?>