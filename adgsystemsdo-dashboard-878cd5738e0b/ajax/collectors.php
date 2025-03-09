<?php

namespace App\Legacy;

class Collectors extends General 
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetCollectorsList()
	
	{

		$getcollectorlistjson = json_decode($this->GetCollectors());

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

		foreach ($getcollectorlistjson as $row)
		
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->collector_code.'</h2>
				</td>
				<td>
					<h2>'.$row->collector_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="collector_code" value="'.trim($row->collector_code).'"></input>
  					
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

	public function EditCollectorOptions()
	
	{

		$editcollectoroptionssql = $this->con->prepare("SELECT 
			collector_code, 
			collector_name 
			FROM ".$this->user_database.".collectors 
			WHERE company_id = :company_id 
			AND TRIM(collector_code) = TRIM(:collector_code)");
		
		$editcollectoroptionssql->bindParam(':collector_code', $_POST['collector_code']); 
		
		$editcollectoroptionssql->bindParam(':company_id', $this->default_company);
		
		$editcollectoroptionssql->execute();

		$editcollectoroptionssql = $editcollectoroptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editcollectoroptionssqljson = json_decode(json_encode($editcollectoroptionssql));

		$row = $editcollectoroptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="collector_code" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="collector_code" id="collector_code" value="'.$row->collector_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="collector_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="collector_name" id="collector_name" value="'.$row->collector_name.'" maxlength="20">

				</div>

			</div>
		';

	}

	public function EditGeneral() {

		$collectoreditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".collectors SET 
			collector_name = :collector_name
			WHERE collector_code = :collector_code 
			AND company_id = :company_id
			");
		
		$collectoreditsubmitsql->bindParam(':company_id', $this->default_company);
		
		$collectoreditsubmitsql->bindParam(':collector_name', $_POST['collector_name']);
		
		$collectoreditsubmitsql->bindParam(':collector_code', $_POST['collector_code']);
		
		$collectoreditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateGeneral() {

		$verifycollector = $this->con->prepare("SELECT 
			collector_code 
			FROM ".$this->user_database.".collectors 
			WHERE collector_code = :collector_code 
			AND company_id = :company_id");
		
		$verifycollector->bindParam (':collector_code', $_POST['collector_code']);
		
		$verifycollector->bindParam(':company_id', $this->default_company);
		
		$verifycollector->execute();
		
		$verifycollector = $verifycollector->rowCount();

		if ($verifycollector > 0) {

			echo '<div class="text-center"><b>El cobrador '.$_POST['collector_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
		}

		else {

			$this->con->beginTransaction();

			try{

				$collectoreditsubmitsql = $this->con->prepare("INSERT INTO
					".$this->user_database.".collectors
					(
					company_id,
					collector_name,
					collector_code
					)
					VALUES 
					(
					:company_id,
					:collector_name,
					:collector_code
					)
					");

				$collectoreditsubmitsql->bindParam(':company_id', $this->default_company);
				$collectoreditsubmitsql->bindParam(':collector_name', $_POST['collector_name']);
				$collectoreditsubmitsql->bindParam(':collector_code', $_POST['collector_code']);
				$collectoreditsubmitsql->execute();

				$this->con->commit();

			}

			catch(\Exception $e){

				echo $e->getMessage();
				$this->con->rollBack();

			}

			echo '<div class="text-center"><b>El cobrador ha sido creado con éxito!</b></div>';

		}
	}
}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  		$getvalues = new Collectors;
  		$getvalues->GetCollectorsList();

  	}


	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Collectors;
		$editoptions->EditCollectorOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Collectors;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Collectors;
		$createoptions->CreateGeneral();
	}

}


?>