<?php
namespace App\Legacy;


class Warehouses extends General 
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetWarehousesList()
	
	{

		$getwarehouselistsql = $this->con->prepare('SELECT warehouse_code, warehouse_name FROM '.$this->user_database.'.warehouses where company_id = :company_id ORDER BY warehouse_code'); 
		$getwarehouselistsql->bindParam(':company_id', $this->default_company);
		$getwarehouselistsql->execute();

		$getwarehouselistsql = $getwarehouselistsql->fetchAll(\PDO::FETCH_ASSOC);

		$getwarehouselistjson = json_decode(json_encode($getwarehouselistsql));

		foreach ($getwarehouselistjson as $row)
		{

			echo '
			<form style="display: none" id="'.trim($row->warehouse_code).'" method="POST" onsubmit="event.preventDefault();">
				<input type="hidden" name="form_id" value="editoptions"></input>
				<input type="hidden" name="warehouse_code" value="'.trim($row->warehouse_code).'"></input>
			</form>
			<tr>
				<td>
					<h2>'.$row->warehouse_code.'</h2>
				</td>
				<td>
					<h2>'.$row->warehouse_name.'</h2>    
				</td>
				<td style="width: 20%;">
					<a class="table-link edit" style="cursor:pointer" id="'.trim($row->warehouse_code).'">
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

	public function EditWarehousesOptions()
	
	{

		$editwarehouseoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".warehouses 
			WHERE company_id = :company_id AND TRIM(warehouse_code) = TRIM(:warehouse_code)");
		
		$editwarehouseoptionssql->bindParam(':warehouse_code', $_POST['warehouse_code']); 
		
		$editwarehouseoptionssql->bindParam(':company_id', $this->default_company);
		
		$editwarehouseoptionssql->execute();

		$editwarehouseoptionssql = $editwarehouseoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editwarehouseoptionssqljson = json_decode(json_encode($editwarehouseoptionssql));

		$row = $editwarehouseoptionssqljson[0];

		echo '
		<form class="form-horizontal col-md-offset-3 vert-offset-top-2" id="warehouseeditsubmit" onsubmit="event.preventDefault();" method="POST">

			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="warehouse_code" class="control-label col-sm-2">Codigo del Almacen:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="warehouse_code" id="warehouse_code" value="'.$row->warehouse_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="warehouse_name" class="control-label col-sm-2">Nombre del Almacen:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="warehouse_name" id="warehouse_name" value="'.$row->warehouse_name.'" maxlength="40">

				</div>

			</div>


			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input onclick="warehouseedit()" value="Aceptar" class="btn btn-success" id="submit" style="width: 100px">
				</div>
			</div>
		</form>

		<div id="response"></div>
		';

	}

	public function EditGeneral() 

	{

		$warehouseeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".warehouses SET 
			warehouse_name = :warehouse_name
			WHERE warehouse_code = :warehouse_code and company_id = :company_id
			");
		$warehouseeditsubmitsql->bindParam(':company_id', $this->default_company);
		$warehouseeditsubmitsql->bindParam(':warehouse_name', $_POST['warehouse_name']);
		$warehouseeditsubmitsql->bindParam(':warehouse_code', $_POST['warehouse_code']);
		$warehouseeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}
	
	public function CreateWarehousesOptions()
	
	{
		echo '<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" name="createform" method="POST" onsubmit="event.preventDefault();">

		<input type="hidden" id="form_id" name="form_id" value="submitcreate">

		<div class="form-group">

			<label for="warehouse_code" class="control-label col-sm-2">Codigo del Almacen:</label>

			<div class="col-sm-4">

				<input class="form-control input-sm" type="text" name="warehouse_code" id="warehouse_code" value="0000" maxlength="4" required>

			</div>

		</div>

		<div class="form-group">

			<label for="warehouse_name" class="control-label col-sm-2">Nombre del Almacen:</label>

			<div class="col-sm-4">

				<input class="form-control input-sm" type="text" name="warehouse_name" id="warehouse_name" value="Nombre" maxlength="40" required>

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

	public function CreateGeneral()

	{

		$verifywarehouse = $this->con->prepare("SELECT warehouse_code FROM ".$this->user_database.".warehouses WHERE warehouse_code = :warehouse_code and company_id = :company_id");
		$verifywarehouse->bindParam (':warehouse_code', $_POST['warehouse_code']);
		$verifywarehouse->bindParam(':company_id', $this->default_company);
		$verifywarehouse->execute();
		$verifywarehouse = $verifywarehouse->rowCount();

		if ($verifywarehouse > 0)
			echo '<div class="text-center"><b>El Almacen '.$_POST['warehouse_code'].' ya existe en la compania '.$this->default_company.'!</b></div>';
		

		else {

			$this->con->beginTransaction();

			try{

				$warehouseeditsubmitsql = $this->con->prepare("INSERT INTO
					".$this->user_database.".warehouses
					(
					company_id,
					warehouse_name,
					warehouse_code
					)
					VALUES 
					(
					:company_id,
					:warehouse_name,
					:warehouse_code
					)
					");

				$warehouseeditsubmitsql->bindParam(':company_id', $this->default_company);

				$warehouseeditsubmitsql->bindParam(':warehouse_name', $_POST['warehouse_name']);

				$warehouseeditsubmitsql->bindParam(':warehouse_code', $_POST['warehouse_code']);

				$warehouseeditsubmitsql->execute();

				$this->con->commit();

			}

			catch(\Exception $e){

				echo $e->getMessage();
				$this->con->rollBack();

			}

			echo '<div class="text-center"><b>El Almacen ha sido creado con exito!</b></div>';

		}
	}

}

if (isset($_POST['form_id'])){


	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Warehouses;
		$editoptions->EditWarehousesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Warehouses;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Warehouses;
		$createoptions->CreateWarehousesOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Warehouses;
		$createoptions->CreateGeneral();
	}

}


?>