<?php

namespace App\Legacy;


class Cities extends General 
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetCitiesList()
	
	{

  		echo '<div class="table-responsive" id="areas_list">
  		
  		<table class="table user-list footable table-hover" data-paging-size="12"
                                   data-paging-position="right" data-paging="true" data-filtering="true"
                                   data-sorting="true">
  			<thead>
  				<tr>
  					<th>ID</th>
  					<th>Nombre</th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach (json_decode($this->GetCities()) as $row)
		
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->city_id.'</h2>
				</td>
				<td>
					<h2>'.$row->city_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="city_id" value="'.trim($row->city_id).'"></input>
  					
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


	public function EditCitiesOptions()
	
	{

		function SelectedCondition($user_setting, $general_setting){

		   if (trim($user_setting) == trim($general_setting))
		      $usercondition = 'selected';
		   else 
		     $usercondition = '';
		   return $usercondition;

		};

		$editcityoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".cities 
		WHERE TRIM(city_id) = TRIM(:city_id)");
		
		$editcityoptionssql->bindParam(':city_id', $_POST['city_id']); 
			
		$editcityoptionssql->execute();

		$editcityoptionssql = $editcityoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editcityoptionssqljson = json_decode(json_encode($editcityoptionssql));

		$row = $editcityoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<input type="hidden" name="city_id" id="city_id" value="'.$row->city_id.'">

			<div class="form-group">

				<label for="province_id" class="control-label col-sm-3">Provincia:</label>

				<div class="col-sm-7">

                  <select class="form-control input-sm" name="province_id" id="province_id">
                  <option value=""></option>';

                    foreach (json_decode($this->GetProvinces()) as $prow){

                      echo '<option value="'.$prow->province_id.'"'.SelectedCondition($row->province_id, $prow->province_id).'>'.$prow->province_name.'</option>';

                    }

                    echo '</select>
				</div>

			</div>

			<div class="form-group">

				<label for="city_name" class="control-label col-sm-3">Nombre de la ciudad:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="city_name" id="city_name" value="'.$row->city_name.'" maxlength="40">

				</div>

			</div>
		';

	}

	public function EditGeneral() {

		$cityeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".cities SET 
			province_id = :province_id,
			city_name = :city_name
			WHERE city_id = :city_id
			");
		$cityeditsubmitsql->bindParam(':city_name', $_POST['city_name']);
		$cityeditsubmitsql->bindParam(':province_id', $_POST['province_id']);
		$cityeditsubmitsql->bindParam(':city_id', $_POST['city_id']);
		$cityeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateGeneral() {

		$this->con->beginTransaction();

		try{

			$cityeditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".cities
				(
				city_name,
				province_id
				)
				VALUES 
				(
				:city_name,
				:province_id
				)
				");

			$cityeditsubmitsql->bindParam(':city_name', $_POST['city_name']);

			$cityeditsubmitsql->bindParam(':province_id', $_POST['province_id']);

			$cityeditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>La ciudad ha sido creada con éxito!</b></div>';


}

}

if (isset($_POST['form_id'])){


  	if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new Cities;
  			$getvalues->GetCitiesList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Cities;
		$editoptions->EditCitiesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Cities;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Cities;
		$createoptions->CreateGeneral();
	}

}


?>