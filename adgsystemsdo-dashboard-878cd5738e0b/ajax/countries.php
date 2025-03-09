<?php

namespace App\Legacy;

class Countries extends General
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetCountriesList()
	
	{

  		echo '<div class="table-responsive" id="areas_list">
  		
  		<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
  			<thead>
  				<tr>
  					<th>ID</th>
  					<th>Nombre</th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach (json_decode($this->GetCountries()) as $row)
		
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->country_id.'</h2>
				</td>
				<td>
					<h2>'.$row->country_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="country_id" value="'.trim($row->country_id).'"></input>
  					
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

	public function EditCountriesOptions()
	
	{

		function SelectedCondition($user_setting, $general_setting){

		   if (trim($user_setting) == trim($general_setting))
		      $usercondition = 'selected';
		   else 
		     $usercondition = '';
		   return $usercondition;

		};

		$editcountryoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".countries 
		WHERE TRIM(country_id) = TRIM(:country_id)");
		
		$editcountryoptionssql->bindParam(':country_id', $_POST['country_id']); 
			
		$editcountryoptionssql->execute();

		$editcountryoptionssql = $editcountryoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editcountryoptionssqljson = json_decode(json_encode($editcountryoptionssql));

		$row = $editcountryoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<input type="hidden" name="country_id" id="country_id" value="'.$row->country_id.'">

			<div class="form-group">

				<label for="country_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="country_name" id="country_name" value="'.$row->country_name.'" maxlength="40">

				</div>

			</div>
		';

	}

	public function EditGeneral() {

		$countryeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".countries SET 
			country_name = :country_name
			WHERE country_id = :country_id
			");
		$countryeditsubmitsql->bindParam(':country_name', $_POST['country_name']);

		$countryeditsubmitsql->bindParam(':country_id', $_POST['country_id']);
		
		$countryeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateGeneral() 

	{

		$this->con->beginTransaction();

		try{

			$countryeditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".countries
				(
				country_name
				)
				VALUES 
				(
				:country_name
				)
				");

			$countryeditsubmitsql->bindParam(':country_name', $_POST['country_name']);

			$countryeditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>El pais ha sido creado con éxito!</b></div>';

	}

}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  		$getvalues = new Countries;
  		$getvalues->GetCountriesList();

  	}


	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Countries;
		$editoptions->EditCountriesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Countries;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Countries;
		$createoptions->CreateCountriesOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Countries;
		$createoptions->CreateGeneral();
	}

}


?>