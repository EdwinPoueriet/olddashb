<?php
namespace App\Legacy;


class Classifications extends General

{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetClassificationsList()
	
	{

		$getclassificationlistjson = json_decode(General::GetClassifications());

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

		foreach ($getclassificationlistjson as $row)
		
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->classification_code.'</h2>
				</td>
				<td>
					<h2>'.$row->classification_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="classification_code" value="'.trim($row->classification_code).'"></input>
  					
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

	public function EditClassificationsOptions()
	
	{


		$editclassificationoptionssql = $this->con->prepare("SELECT 
			classification_code, 
			classification_name 
			FROM ".$this->user_database.".classifications 
			WHERE company_id = :company_id 
			AND TRIM(classification_code) = TRIM(:classification_code)");
		
		$editclassificationoptionssql->bindParam(':classification_code', $_POST['classification_code']); 
		
		$editclassificationoptionssql->bindParam(':company_id', $this->catalogue_company);
		
		$editclassificationoptionssql->execute();

		$editclassificationoptionssql = $editclassificationoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editclassificationoptionssqljson = json_decode(json_encode($editclassificationoptionssql));

		$row = $editclassificationoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="classification_code" class="control-label col-sm-3">Código de la clasificación:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="classification_code" id="classification_code" value="'.$row->classification_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="classification_name" class="control-label col-sm-3">Nombre de la clasificación:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="classification_name" id="classification_name" value="'.$row->classification_name.'" maxlength="20">

				</div>

			</div>

		';

	}

	public function EditGeneral() 

	{

		$classificationeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".classifications SET 
			classification_name = :classification_name
			WHERE classification_code = :classification_code and company_id = :company_id
			");
		$classificationeditsubmitsql->bindParam(':company_id', $this->catalogue_company);
		$classificationeditsubmitsql->bindParam(':classification_name', $_POST['classification_name']);
		$classificationeditsubmitsql->bindParam(':classification_code', $_POST['classification_code']);
		$classificationeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}
	
	public function CreateclassificationsOptions()
	
	{
		echo '<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" name="createform" method="POST" onsubmit="event.preventDefault();">

		<input type="hidden" id="form_id" name="form_id" value="submitcreate">

			<div class="form-group">

				<label for="classification_code" class="control-label col-sm-2">Código de la clasificación:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="classification_code" id="classification_code" value="000" maxlength="6">

				</div>

			</div>

			<div class="form-group">

				<label for="classification_name" class="control-label col-sm-2">Nombre de la clasificación:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="classification_name" id="classification_name" value="" maxlength="20">

				</div>

			</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input onclick="createsubmit()" value="Aceptar" class="btn btn-success" id="submit" style="width: 100px">
			</div>
		</div>
	</form>

	';

	}

	public function CreateGeneral() {

		$verifyclassification = $this->con->prepare("SELECT 
			classification_code 
			FROM ".$this->user_database.".classifications 
			WHERE classification_code = :classification_code 
			AND company_id = :company_id");
		$verifyclassification->bindParam (':classification_code', $_POST['classification_code']);
		$verifyclassification->bindParam(':company_id', $this->catalogue_company);
		$verifyclassification->execute();
		$verifyclassification = $verifyclassification->rowCount();

		if ($verifyclassification > 0) {

			echo '<div class="text-center"><b>La clasificación '.$_POST['classification_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
		}

		else {

			$this->con->beginTransaction();

			try{

				$createclassificationsubmitsql = $this->con->prepare("INSERT INTO
					".$this->user_database.".classifications
					(
					company_id,
					classification_name,
					classification_code
					)
					VALUES 
					(
					:company_id,
					:classification_name,
					:classification_code
					)
					");

				$createclassificationsubmitsql->bindParam(':company_id', $this->catalogue_company);
				$createclassificationsubmitsql->bindParam(':classification_name', $_POST['classification_name']);
				$createclassificationsubmitsql->bindParam(':classification_code', $_POST['classification_code']);
				$createclassificationsubmitsql->execute();

				$this->con->commit();

			}

			catch(\Exception $e){

				echo $e->getMessage();
				$this->con->rollBack();

			}

			echo '<div class="text-center"><b>La clasificación ha sido creado con éxito!</b></div>';

		}
	}
}

if (isset($_POST['form_id'])){

  	if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new Classifications;
  			$getvalues->GetClassificationsList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Classifications;
		$editoptions->EditClassificationsOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Classifications;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Classifications;
		$createoptions->CreateClassificationsOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Classifications;
		$createoptions->CreateGeneral();
	}

}


?>