<?php

namespace App\Legacy;



class Subclassifications extends General 
{

	public function GetSubClassificationsList()
	
	{

  		foreach (json_decode($this->GetClassifications()) as $classrow)
			
			{
 
 			foreach (json_decode($this->GetSubclassifications($classrow->classification_code)) as $subclassrow)

  				{

					echo '
					<form style="display: none" id="'.trim($classrow->classification_code).trim($subclassrow->subclassification_code).'" method="POST" onsubmit="event.preventDefault();">
						
						<input type="hidden" name="form_id" value="editoptions"></input>
						
						<input type="hidden" name="classification_code" value="'.trim($classrow->classification_code).'"></input>
						
						<input type="hidden" name="subclassification_code" value="'.trim($subclassrow->subclassification_code).'"></input>
					</form>
					<tr>
						<td>
							<h2>'.$subclassrow->subclassification_code.'</h2>
						</td>
						<td>
							<h2>'.$subclassrow->subclassification_name.'</h2>    
						</td>
						<td>
							<h2>'.$classrow->classification_name.'</h2>    
						</td>
						<td style="width: 20%;">
							<a class="table-link edit" style="cursor:pointer" id="'.trim($classrow->classification_code).trim($subclassrow->subclassification_code).'">
								<span class="fa-stack">
									<i class="fa fa-square fa-stack-2x"></i>
									<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
								</span>
							</a>
							<a href="#" class="table-link danger">
								<span class="fa-stack">
									<i class="fa fa-square fa-stack-2x"></i>
									<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
								</span>
							</a>
						</td>
					</tr>';
  					

  				}

   			} 

	}

	 function EditSubClassificationsOptions()
	
	{

		$editsubclassificationoptionssql = $this->con->prepare("SELECT 
			sc.subclassification_code, 
			sc.subclassification_name,
			cc.classification_code
			FROM ".$this->user_database.".subclassifications sc
		INNER JOIN ".$this->user_database.".classifications cc ON(cc.classification_code = sc.classification_code AND cc.company_id = sc.company_id)
		WHERE sc.company_id = :company_id AND 
		TRIM(sc.subclassification_code) = TRIM(:subclassification_code) AND 
		TRIM(sc.classification_code) = TRIM(:classification_code)");

		$editsubclassificationoptionssql->bindParam(':classification_code', $_POST['classification_code']); 
		
		$editsubclassificationoptionssql->bindParam(':subclassification_code', $_POST['subclassification_code']); 
		
		$editsubclassificationoptionssql->bindParam(':company_id', $this->catalogue_company);
		
		$editsubclassificationoptionssql->execute();

		$editsubclassificationoptionssql = $editsubclassificationoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editsubclassificationoptionssqljson = json_decode(json_encode($editsubclassificationoptionssql));

		$row = $editsubclassificationoptionssqljson[0];

		echo '

		<form class="form-horizontal col-md-offset-3 vert-offset-top-2" id="subclassificationseditsubmit" onsubmit="event.preventDefault();" method="POST">

			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="subclassification_code" class="control-label col-sm-2">Código:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="subclassification_code" id="subclassification_code" value="'.$row->subclassification_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="classification_code" class="control-label col-sm-2">Clasificación:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="classification_code" id="classification_code" value="'.$row->classification_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="subclassification_name" class="control-label col-sm-2">Nombre:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="subclassification_name" id="subclassification_name" value="'.$row->subclassification_name.'">

				</div>

			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input onclick="subclassificationsedit()" value="Aceptar" class="btn btn-success" id="submit" style="width: 100px">
				</div>
			</div>
		</form>

		<div id="response"></div>
		';

	}

	public function EditGeneral() {

		$subclassificationeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".subclassifications SET 
			subclassification_name = :subclassification_name
			WHERE TRIM(subclassification_code) = TRIM(:subclassification_code) AND
			company_id = :company_id AND
			TRIM(classification_code) = TRIM(:classification_code)
			");
		$subclassificationeditsubmitsql->bindParam(':company_id', $cataloguecompany);
		$subclassificationeditsubmitsql->bindParam(':subclassification_name', $_POST['subclassification_name']);
		$subclassificationeditsubmitsql->bindParam(':subclassification_code', $_POST['subclassification_code']);
		$subclassificationeditsubmitsql->bindParam(':classification_code', $_POST['classification_code']);
		$subclassificationeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreateSubclassificationsOptions()

	{

		echo '<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" name="createform" method="POST" onsubmit="event.preventDefault();">

		<input type="hidden" id="form_id" name="form_id" value="submitcreate">

			<div class="form-group">

				<label for="subclassification_code" class="control-label col-sm-2">Código:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="subclassification_code" id="subclassification_code" value="000" maxlength="6">

				</div>

			</div>

			<div class="form-group">

				<label for="subclassification_name" class="control-label col-sm-2">Nombre:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="subclassification_name" id="subclassification_name" value="" maxlength="20">

				</div>

			</div>

			<div class="form-group">

				<label for="classification_code" class="control-label col-sm-2">Clasificación:</label>

				<div class="col-sm-4">

					<select class="form-control input-sm" name="classification_code" id="classification_code">

						<option value="0"></option>';

						foreach (json_decode(General::GetClassifications($this->con, $this->user_database, $cataloguecompany)) as $classrow)

						{

							echo '<option value="'.$classrow->classification_code.'">'.$classrow->classification_code.' - '.$classrow->classification_name.'</option>';

						} 

						echo '</select>

					</div>

				</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input onclick="createsubmit()" value="Aceptar" class="btn btn-success" id="submit" style="width: 100px">
			</div>
		</div>
	</form>';

	}

	public function CreateGeneral() {

		$verifysubclassification = $this->con->prepare("SELECT subclassification_code FROM ".$this->user_database.".subclassifications WHERE 
			subclassification_code = :subclassification_code AND 
			company_id = :company_id AND
			classification_code = :classification_code");

		$verifysubclassification->bindParam (':subclassification_code', $_POST['subclassification_code']);

		$verifysubclassification->bindParam (':classification_code', $_POST['classification_code']);

		$verifysubclassification->bindParam(':company_id', $cataloguecompany);

		$verifysubclassification->execute();

		$verifysubclassification = $verifysubclassification->rowCount();

		if ($verifysubclassification > 0) {

			echo '<div class="text-center"><b>La subclasificacion '.$_POST['subclassification_code'].' ya existe en la compania '.$this->default_company.'!</b></div>';
		}

		else {

			$this->con->beginTransaction();

			try{

				$createsubclassificationsubmitsql = $this->con->prepare("INSERT INTO
					".$this->user_database.".subclassifications
					(
					company_id,
					subclassification_name,
					subclassification_code,
					classification_code
					)
					VALUES 
					(
					:company_id,
					:subclassification_name,
					:subclassification_code,
					:classification_code
					)
					");

				$createsubclassificationsubmitsql->bindParam(':company_id', $this->catalogue_company);

				$createsubclassificationsubmitsql->bindParam(':subclassification_name', $_POST['subclassification_name']);

				$createsubclassificationsubmitsql->bindParam(':subclassification_code', $_POST['subclassification_code']);

				$createsubclassificationsubmitsql->bindParam(':classification_code', $_POST['subclassification_code']);

				$createsubclassificationsubmitsql->execute();

				$this->con->commit();

			}

			catch(\Exception $e){

				echo $e->getMessage();
				$this->con->rollBack();

			}

			echo '<div class="text-center"><b>La subclasificacion ha sido creado con exito!</b></div>';

		}
	}
}

if (isset($_POST['form_id'])){


	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Subclassifications;
		$editoptions->EditsubClassificationsOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Subclassifications;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Subclassifications;
		$createoptions->CreatesubClassificationsOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Subclassifications;
		$createoptions->CreateGeneral();
	}

}


?>