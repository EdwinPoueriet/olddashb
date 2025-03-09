<?php
namespace App\Legacy;

class Banks extends General
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetBanksList()
	
	{

		$getbankslistsql = $this->con->prepare('SELECT 
			bank_code, 
			bank_name 
		FROM '.$this->user_database.'.banks 
		WHERE company_id = :company_id ORDER BY bank_code'); 
		$getbankslistsql->bindParam(':company_id', $this->default_company);
		$getbankslistsql->execute();

		$getbankslistsql = $getbankslistsql->fetchAll(\PDO::FETCH_ASSOC);

		$getbankslistjson = json_decode(json_encode($getbankslistsql));

  		echo '<div class="table-responsive" id="areas_list">
  	
  		<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
  			<thead>
  				<tr>
  					<th><span>ID</span></th>
  					<th><span>Nombre</span></th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach ($getbankslistjson as $row)
		{

			echo '
			<form style="display: none" id="'.trim($row->bank_code).'" method="POST" onsubmit="event.preventDefault();">
				<input type="hidden" name="form_id" value="editoptions"></input>
				<input type="hidden" name="bank_code" value="'.trim($row->bank_code).'"></input>
			</form>
			<tr>
				<td>
					<h2>'.$row->bank_code.'</h2>
				</td>
				<td>
					<h2>'.$row->bank_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="bank_code" value="'.trim($row->bank_code).'"></input>
  					
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

	public function EditBanksOptions()
	
	{

		$editbanksoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".banks 
			WHERE company_id = :company_id AND TRIM(bank_code) = TRIM(:bank_code)");
		
		$editbanksoptionssql->bindParam(':bank_code', $_POST['bank_code']); 
		
		$editbanksoptionssql->bindParam(':company_id', $this->default_company);
		
		$editbanksoptionssql->execute();

		$editbanksoptionssql = $editbanksoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editbanksoptionssqljson = json_decode(json_encode($editbanksoptionssql));

		$row = $editbanksoptionssqljson[0];

		echo '
			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="bank_code" class="control-label col-sm-3">Código del banco:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="bank_code" id="bank_code" value="'.$row->bank_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="bank_name" class="control-label col-sm-3">Nombre del banco:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="bank_name" id="bank_name" value="'.$row->bank_name.'" maxlength="100">

				</div>

			</div>

		<div id="response"></div>
		';

	}

	public function EditGeneral() {

		$bankseditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".banks SET 
			bank_name = :bank_name
			WHERE bank_code = :bank_code and company_id = :company_id
			");
		$bankseditsubmitsql->bindParam(':company_id', $this->default_company);
		$bankseditsubmitsql->bindParam(':bank_name', $_POST['bank_name']);
		$bankseditsubmitsql->bindParam(':bank_code', $_POST['bank_code']);
		$bankseditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}
	
	public function CreateBanksOptions()
	{
		echo '<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" name="createform" method="POST" onsubmit="event.preventDefault();">

		<input type="hidden" id="form_id" name="form_id" value="submitcreate">

		<div class="form-group">

			<label for="bank_code" class="control-label col-sm-2">Código del banco:</label>

			<div class="col-sm-4">

				<input class="form-control input-sm" type="text" name="bank_code" id="bank_code" value="000" maxlength="3" required>

			</div>

		</div>

		<div class="form-group">

			<label for="bank_name" class="control-label col-sm-2">Nombre del banco:</label>

			<div class="col-sm-4">

				<input class="form-control input-sm" type="text" name="bank_name" id="bank_name" value="Nombre" maxlength="100" required>

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

	$verifybanks = $this->con->prepare("SELECT bank_code FROM ".$this->user_database.".banks WHERE bank_code = :bank_code and company_id = :company_id");
	$verifybanks->bindParam (':bank_code', $_POST['bank_code']);
	$verifybanks->bindParam(':company_id', $this->default_company);
	$verifybanks->execute();
	$verifybanks = $verifybanks->rowCount();

	if ($verifybanks > 0) {

		echo '<div class="text-center"><b>El banco '.$_POST['bank_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
	}

	else {

		$this->con->beginTransaction();

		try{

			$bankseditsubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".banks
				(
				company_id,
				bank_name,
				bank_code
				)
				VALUES 
				(
				:company_id,
				:bank_name,
				:bank_code
				)
				");

			$bankseditsubmitsql->bindParam(':company_id', $this->default_company);

			$bankseditsubmitsql->bindParam(':bank_name', $_POST['bank_name']);

			$bankseditsubmitsql->bindParam(':bank_code', $_POST['bank_code']);

			$bankseditsubmitsql->execute();

			$this->con->commit();

			echo '<div class="text-center"><b>El banco ha sido creado con éxito!</b></div>';

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

	}
}
}

if (isset($_POST['form_id'])){


  	if ($_POST['form_id'] == 'getvalues'){

  			$getvalues = new Banks;
  			$getvalues->GetBanksList();

  	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Banks;
		$editoptions->EditBanksOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Banks;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Banks;
		$createoptions->CreateGeneral();
	}

}


?>