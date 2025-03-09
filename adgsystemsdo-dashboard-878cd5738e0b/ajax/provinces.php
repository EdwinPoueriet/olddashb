<?php

namespace App\Legacy;


class Provinces extends General 

{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetProvincesList()
	
	{

		$getprovincelistsql = $this->con->prepare('SELECT 
			province_id, 
			province_name 
		FROM '.$this->user_database.'.provinces 
		ORDER BY province_id'); 

		$getprovincelistsql->execute();

		$getprovincelistsql = $getprovincelistsql->fetchAll(\PDO::FETCH_ASSOC);

		$getprovincelistjson = json_decode(json_encode($getprovincelistsql));

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

		foreach ($getprovincelistjson as $row)
		
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->province_id.'</h2>
				</td>
				<td>
					<h2>'.$row->province_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="province_id" value="'.trim($row->province_id).'"></input>
  					
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

	public function EditProvincesOptions()
	
	{

		function SelectedCondition($user_setting, $general_setting){

		   if (trim($user_setting) == trim($general_setting))
		      $usercondition = 'selected';
		   else 
		     $usercondition = '';
		   return $usercondition;

		};

		$editprovinceoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".provinces 
		WHERE TRIM(province_id) = TRIM(:province_id)");
		
		$editprovinceoptionssql->bindParam(':province_id', $_POST['province_id']); 
			
		$editprovinceoptionssql->execute();

		$editprovinceoptionssql = $editprovinceoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editprovinceoptionssqljson = json_decode(json_encode($editprovinceoptionssql));

		$row = $editprovinceoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<input type="hidden" name="province_id" id="province_id" value="'.$row->province_id.'">

			<div class="form-group">

				<label for="country_id" class="control-label col-sm-3">Pais:</label>

				<div class="col-sm-7">

                  <select class="form-control input-sm" name="country_id" id="country_id">
                  <option value=""></option>';

                    foreach (json_decode($this->GetCountries()) as $crow){

                      echo '<option value="'.$crow->country_id.'"'.SelectedCondition($row->country_id, $crow->country_id).'>'.$crow->country_name.'</option>';

                    }

                    echo '</select>
				</div>

			</div>

			<div class="form-group">

				<label for="province_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="province_name" id="province_name" value="'.$row->province_name.'" maxlength="40">

				</div>

			</div>

	';

	}

	public function EditGeneral() 

	{

		$provinceeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".provinces SET 
			country_id = :country_id,
			province_name = :province_name
			WHERE province_id = :province_id
			");
		$provinceeditsubmitsql->bindParam(':province_name', $_POST['province_name']);
		
		$provinceeditsubmitsql->bindParam(':province_id', $_POST['province_id']);
		
		$provinceeditsubmitsql->bindParam(':country_id', $_POST['country_id']);
		
		$provinceeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateGeneral() 

	{

		$this->con->beginTransaction();

		try{

			$provinceeditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".provinces
				(
				province_name,
				country_id
				)
				VALUES 
				(
				:province_name,
				:country_id
				)
				");

			$provinceeditsubmitsql->bindParam(':province_name', $_POST['province_name']);

			$provinceeditsubmitsql->bindParam(':country_id', $_POST['country_id']);

			$provinceeditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();

			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>La provincia ha sido creado con exito!</b></div>';


	}

}

	if (isset($_POST['form_id'])){

	  	if ($_POST['form_id'] == 'getvalues'){

	  			$getvalues = new Provinces;
	  			$getvalues->GetProvincesList();

	  	}

		if ($_POST['form_id'] == 'editoptions') {

			$editoptions = new Provinces;
			$editoptions->EditProvincesOptions();

		}

		if ($_POST['form_id'] == 'submitedit') {

			$editoptions = new Provinces;
			$editoptions->EditGeneral();

		}

		if ($_POST['form_id'] == 'createoptions') {

			$createoptions = new Provinces;
			$createoptions->CreateprovincesOptions();

		}

		if ($_POST['form_id'] == 'submitcreate') {
			$createoptions = new Provinces;
			$createoptions->CreateGeneral();
		}

	}


?>