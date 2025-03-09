<?php

namespace App\Legacy;



class Sectors extends General

{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetSectorsList()
	
	{

		$getsectorlistsql = $this->con->prepare('SELECT 
			sector_id, 
			sector_name 
			FROM '.$this->user_database.'.sectors ORDER BY sector_id'); 

		$getsectorlistsql->execute();

		$getsectorlistsql = $getsectorlistsql->fetchAll(\PDO::FETCH_ASSOC);

		$getsectorlistjson = json_decode(json_encode($getsectorlistsql));

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

		foreach ($getsectorlistjson as $row)
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->sector_id.'</h2>
				</td>
				<td>
					<h2>'.$row->sector_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="sector_id" value="'.trim($row->sector_id).'"></input>
  					
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

	public function EditSectorsOptions()
	
	{

		function SelectedCondition($user_setting, $general_setting){

		   if (trim($user_setting) == trim($general_setting))
		      $usercondition = 'selected';
		   else 
		     $usercondition = '';
		   return $usercondition;

		};

		$editsectoroptionssql = $this->con->prepare("SELECT * 
			FROM ".$this->user_database.".sectors 
			WHERE TRIM(sector_id) = TRIM(:sector_id)");
		
		$editsectoroptionssql->bindParam(':sector_id', $_POST['sector_id']); 
			
		$editsectoroptionssql->execute();

		$editsectoroptionssql = $editsectoroptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editsectoroptionssqljson = json_decode(json_encode($editsectoroptionssql));

		$row = $editsectoroptionssqljson[0];

		echo '

			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<input type="hidden" name="sector_id" id="sector_id" value="'.$row->sector_id.'">

			<div class="form-group">

				<label for="city_id" class="control-label col-sm-3">Ciudad:</label>

				<div class="col-sm-7">

                  <select class="form-control input-sm" name="city_id" id="city_id">
                  <option value=""></option>';


                    foreach (json_decode($this->GetCities()) as $crow){

                      echo '<option value="'.$crow->city_id.'"'.SelectedCondition($row->city_id, $crow->city_id).'>'.$crow->city_name.'</option>';

                    }

                    echo '</select>
				</div>

			</div>

			<div class="form-group">

				<label for="sector_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="sector_name" id="sector_name" value="'.$row->sector_name.'" maxlength="40">

				</div>

			</div>
	';

	}

	public function EditGeneral() 

	{

		$sectoreditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".sectors SET 
			city_id = :city_id,
			sector_name = :sector_name
			WHERE sector_id = :sector_id
			");

		$sectoreditsubmitsql->bindParam(':sector_name', $_POST['sector_name']);

		$sectoreditsubmitsql->bindParam(':sector_id', $_POST['sector_id']);
		
		$sectoreditsubmitsql->bindParam(':city_id', $_POST['city_id']);
		
		$sectoreditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

public function CreateGeneral() {

		$this->con->beginTransaction();

		try{

			$sectoreditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".sectors
				(
				sector_name,
				city_id
				)
				VALUES 
				(
				:sector_name,
				:city_id
				)
				");

			$sectoreditsubmitsql->bindParam(':sector_name', $_POST['sector_name']);

			$sectoreditsubmitsql->bindParam(':city_id', $_POST['city_id']);

			$sectoreditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>El Sector ha sido creado con exito!</b></div>';


}

}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new Sectors;
  			$getvalues->GetSectorsList();

  	}


	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Sectors;
		$editoptions->EditSectorsOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Sectors;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Sectors;
		$createoptions->CreateSectorsOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Sectors;
		$createoptions->CreateGeneral();
	}

}


?>