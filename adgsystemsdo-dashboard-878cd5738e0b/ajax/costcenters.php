<?php

namespace App\Legacy;

class CostCenters extends General 

{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetCostCentersList()
	
	{

		$getcost_centerlistsql = $this->con->prepare('SELECT 
			cost_center_code, 
			cost_center_name 
		FROM '.$this->user_database.'.cost_center 
		WHERE company_id = :company_id ORDER BY cost_center_code'); 

		$getcost_centerlistsql->bindParam(':company_id', $this->default_company);
		
		$getcost_centerlistsql->execute();

		$getcost_centerlistsql = $getcost_centerlistsql->fetchAll(\PDO::FETCH_ASSOC);

		$getcost_centerlistjson = json_decode(json_encode($getcost_centerlistsql));

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

		foreach ($getcost_centerlistjson as $row)
		
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->cost_center_code.'</h2>
				</td>
				<td>
					<h2>'.$row->cost_center_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="cost_center_code" value="'.trim($row->cost_center_code).'"></input>
  					
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

	public function EditCostCentersOptions()
	
	{
		$editcost_centeroptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".cost_center 
			WHERE company_id = :company_id AND TRIM(cost_center_code) = TRIM(:cost_center_code)");
		
		$editcost_centeroptionssql->bindParam(':cost_center_code', $_POST['cost_center_code']); 
		
		$editcost_centeroptionssql->bindParam(':company_id', $this->default_company);
		
		$editcost_centeroptionssql->execute();

		$editcost_centeroptionssql = $editcost_centeroptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editcost_centeroptionssqljson = json_decode(json_encode($editcost_centeroptionssql));

		$row = $editcost_centeroptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="cost_center_code" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="cost_center_code" id="cost_center_code" value="'.$row->cost_center_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="cost_center_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="cost_center_name" id="cost_center_name" value="'.$row->cost_center_name.'" maxlength="40">

				</div>

			</div>
		';

	}

	public function EditGeneral() 

	{

		$cost_centereditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".cost_center 
			SET cost_center_name = :cost_center_name
			WHERE cost_center_code = :cost_center_code and company_id = :company_id
			");
		$cost_centereditsubmitsql->bindParam(':company_id', $this->default_company);

		$cost_centereditsubmitsql->bindParam(':cost_center_name', $_POST['cost_center_name']);
		
		$cost_centereditsubmitsql->bindParam(':cost_center_code', $_POST['cost_center_code']);
		
		$cost_centereditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateGeneral() {

	$verifycost_center = $this->con->prepare("SELECT 
		cost_center_code 
		FROM ".$this->user_database.".cost_center 
		WHERE cost_center_code = :cost_center_code 
		AND company_id = :company_id");

	$verifycost_center->bindParam(':cost_center_code', $_POST['cost_center_code']);
	
	$verifycost_center->bindParam(':company_id', $this->default_company);
	
	$verifycost_center->execute();
	
	$verifycost_center = $verifycost_center->rowCount();

	if ($verifycost_center > 0) {

		echo '<div class="text-center"><b>El Centro de Costo '.$_POST['cost_center_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
	}

	else {

		$this->con->beginTransaction();

		try{

			$cost_centereditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".cost_center
				(
				company_id,
				cost_center_name,
				cost_center_code
				)
				VALUES 
				(
				:company_id,
				:cost_center_name,
				:cost_center_code
				)
				");

			$cost_centereditsubmitsql->bindParam(':company_id', $this->default_company);

			$cost_centereditsubmitsql->bindParam(':cost_center_name', $_POST['cost_center_name']);

			$cost_centereditsubmitsql->bindParam(':cost_center_code', $_POST['cost_center_code']);

			$cost_centereditsubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>El Centro de Costo ha sido creado con éxito!</b></div>';

	}
}

}

if (isset($_POST['form_id'])){


  	if ($_POST['form_id'] == 'getvalues'){

  		$getvalues = new CostCenters;
  		$getvalues->GetCostCentersList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new CostCenters;
		$editoptions->EditCostCentersOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new CostCenters;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new CostCenters;
		$createoptions->CreateGeneral();
	}

}


?>