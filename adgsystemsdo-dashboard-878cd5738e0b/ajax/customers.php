<?php
namespace App\Legacy;


class Customers extends General
{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}

	public function GetcustomersList()
	
	{

		foreach (json_decode($this->Getcustomers()) as $row)
		{

			echo '
			<form style="display: none" id="'.trim($row->customer_code).'" method="POST" onsubmit="event.preventDefault();">
				<input type="hidden" name="form_id" value="editoptions"></input>
				<input type="hidden" name="customer_code" value="'.trim($row->customer_code).'"></input>
			</form>
			<tr>
				<td>
					<h2>'.$row->customer_code.'</h2>
				</td>

				<td>
					<h2>'.$row->customer_name.'</h2>    
				</td>

				<td>
					<h2>'.$row->seller_name.'</h2>    
				</td>

				<td style="width: 20%;">
					<a class="table-link edit" style="cursor:pointer" id="'.trim($row->customer_code).'">
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

	public function EditcustomersOptions()
	
	{

		function SelectedCondition($user_setting, $general_setting)
		
		{

    		if (trim($user_setting) == trim($general_setting))
      			$usercondition = 'selected';
   			else 
      			$usercondition = '';
    		return $usercondition;

  		};

		$editcustomeroptionssql = $this->con->prepare("SELECT *
		FROM ".$this->user_database.".customers where company_id = :company_id AND TRIM(customer_code) = TRIM(:customer_code)");
		
		$editcustomeroptionssql->bindParam(':customer_code', $_POST['customer_code']); 
		
		$editcustomeroptionssql->bindParam(':company_id', $this->default_company);
		
		$editcustomeroptionssql->execute();

		$editcustomeroptionssql = $editcustomeroptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editcustomeroptionssqljson = json_decode(json_encode($editcustomeroptionssql));

		$row = $editcustomeroptionssqljson[0];

		echo '
		<form class="form-horizontal col-md-offset-3 vert-offset-top-2" id="customereditsubmit" onsubmit="event.preventDefault();" method="POST">

			<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="customer_code" class="control-label col-sm-2">Codigo del cliente:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="customer_code" id="customer_code" value="'.$row->customer_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="customer_name" class="control-label col-sm-2">Nombre del cliente:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="customer_name" id="customer_name" value="'.$row->customer_name.'" maxlength="20">

				</div>

			</div>

			<div class="form-group">

				<label for="seller_code" class="control-label col-sm-2">Vendedor asignado:</label>

				<div class="col-sm-4">

                  <select class="form-control input-sm" name="seller_code" id="seller_code">
                  <option value=""></option>';

                  $sellers = $this->con->prepare("
                    SELECT 
                    seller_code, seller_name  
                    FROM ".$this->user_database.".sellers 
                    WHERE company_id = :company_id");
                  	$sellers->bindParam(':company_id', $this->default_company);
                    $sellers->execute();
                    $sellers = $sellers->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($sellers as $srow){

                      echo '<option value="'.$srow['seller_code'].'"'.SelectedCondition($srow['seller_code'], $row->seller_code).'>'.$srow['seller_code'].' - '.$srow['seller_name'].'</option>';

                    }

                    echo '</select>
				</div>

			</div>

			<div class="form-group">

				<label for="day_id" class="control-label col-sm-2">Dia de visita:</label>

				<div class="col-md-4">

	              	<select class="form-control input-sm" type="number" name="day_id" id="day_id">
	              	<option value=""></option>
	                <option value="1"'; if ($row->day_id == 1) echo 'selected';echo'>Domingo</option>
	                <option value="2"'; if ($row->day_id == 2) echo 'selected';echo'>Lunes</option>
	                <option value="3"'; if ($row->day_id == 3) echo 'selected';echo'>Martes</option>
	                <option value="4"'; if ($row->day_id == 4) echo 'selected';echo'>Miercoles</option>
	                <option value="5"'; if ($row->day_id == 5) echo 'selected';echo'>Jueves</option>
	                <option value="6"'; if ($row->day_id == 6) echo 'selected';echo'>Viernes</option>
					<option value="7"'; if ($row->day_id == 7) echo 'selected';echo'>Sabado</option>
					</select>

				</div>

			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input onclick="customeredit()" value="Aceptar" class="btn btn-success" id="submit" style="width: 100px">
				</div>
			</div>
		</form>

		<div id="response"></div>
		';

	}

	public function EditcustomersSubmit() {

		$customereditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".customers SET 
			customer_name = :customer_name,
			seller_code = :seller_code,
			day_id = :day_id
			WHERE customer_code = :customer_code and company_id = :company_id
			");
		$customereditsubmitsql->bindParam(':company_id', $this->default_company);
		$customereditsubmitsql->bindParam(':customer_name', $_POST['customer_name']);
		$customereditsubmitsql->bindParam(':seller_code', $_POST['seller_code']);
		$customereditsubmitsql->bindParam(':day_id', $_POST['day_id']);
		$customereditsubmitsql->bindParam(':customer_code', $_POST['customer_code']);
		$customereditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}

	public function CreatecustomersOptions()
	
	{

		echo '<form id="createform" class="form-horizontal col-md-offset-3 vert-offset-top-2" name="createform" method="POST" onsubmit="event.preventDefault();">

		<input type="hidden" id="form_id" name="form_id" value="submitcreate">

			<div class="form-group">

				<label for="customer_code" class="control-label col-sm-2">Código de la zona:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="customer_code" id="customer_code" value="000" maxlength="3">

				</div>

			</div>

			<div class="form-group">

				<label for="customer_name" class="control-label col-sm-2">Nombre de la zona:</label>

				<div class="col-sm-4">

					<input class="form-control input-sm" type="text" name="customer_name" id="customer_name" value="" maxlength="20">

				</div>

			</div>

			<div class="form-group">

				<label for="seller_code" class="control-label col-sm-2">Vendedor asignado:</label>

				<div class="col-sm-4">

                  <select class="form-control input-sm" name="seller_code" id="seller_code">
                  <option value=""></option>';

	                  foreach (json_decode($this->GetSellers()) as $srow){

                      echo '<option value="'.$srow->seller_code.'">'.$srow->seller_code.' - '.$srow->seller_name.'</option>';

                    }

                    echo '</select>
				</div>

			</div>

			<div class="form-group">

				<label for="day_id" class="control-label col-sm-2">Día de visita:</label>

				<div class="col-sm-4">

	              	<select class="form-control input-sm" type="number" name="day_id" id="day_id">
	              	<option value=""></option>
	                <option value="1">Domingo</option>
	                <option value="2">Lunes</option>
	                <option value="3">Martes</option>
	                <option value="4">Miércoles</option>
	                <option value="5">Jueves</option>
	                <option value="6">Viernes</option>
					<option value="7">Sábado</option>
					</select>

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

	$verifycustomer = $this->con->prepare("SELECT customer_code FROM ".$this->user_database.".customers WHERE customer_code = :customer_code and company_id = :company_id");
	$verifycustomer->bindParam (':customer_code', $_POST['customer_code']);
	$verifycustomer->bindParam(':company_id', $this->default_company);
	$verifycustomer->execute();
	$verifycustomer = $verifycustomer->rowCount();

	if ($verifycustomer > 0) {

		echo '<div class="text-center"><b>El usuario '.$_POST['customer_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
	}

	else {

		$this->con->beginTransaction();

		try{

			$createcustomersubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".customers
				(
				company_id,
				customer_name,
				customer_code,
				customer_owner_or_contact,
				customer_phone,
				seller_code,
				customer_email,
				collector_code,
				route_code,
				area_code,
				customer_address,
				customer_rnc,
				customer_last_date_of_purchase,
				type_account_receivable_id,
				country_id,
				province_id,
				city_id,
				sector_id,
				type_price_id,
				day_id,
				customer_credit_limited,
				customer_balance,
				customer_longitude,
				customer_latitude,
				customer_percent_discount,
				customer_ncf_type,
				status
				)
				VALUES 
				(
				:company_id,
				:customer_name,
				:customer_code,
				:customer_owner_or_contact,
				:customer_phone,
				:seller_code,
				:customer_email,
				:collector_code,
				:route_code,
				:area_code,
				:customer_address,
				:customer_rnc,
				:customer_last_date_of_purchase,
				:type_account_receivable_id,
				:country_id,
				:province_id,
				:city_id,
				:sector_id,
				:type_price_id,
				:day_id,
				:customer_credit_limited,
				:customer_balance,
				:customer_longitude,
				:customer_latitude,
				:customer_percent_discount,
				:customer_ncf_type,
				:status
				)
				");

			$createcustomersubmitsql->bindParam(':company_id', $this->default_company);
			
			$createcustomersubmitsql->bindParam(':customer_name', $_POST['customer_name']);
			
			$createcustomersubmitsql->bindParam(':customer_code', $_POST['customer_code']);

			$createcustomersubmitsql->bindParam(':customer_owner_or_contact', $_POST['customer_owner_or_contact']);

			$createcustomersubmitsql->bindParam(':customer_phone', $_POST['customer_phone']);
			
			$createcustomersubmitsql->bindParam(':seller_code', $_POST['seller_code']);

			$createcustomersubmitsql->bindParam(':customer_email', $_POST['customer_email']);

			$createcustomersubmitsql->bindParam(':collector_code', $_POST['collector_code']);
			
			$createcustomersubmitsql->bindParam(':route_code', $_POST['route_code']);

			$createcustomersubmitsql->bindParam(':area_code', $_POST['area_code']);

			$createcustomersubmitsql->bindParam(':customer_address', $_POST['customer_address']);
			
			$createcustomersubmitsql->bindParam(':customer_rnc', $_POST['customer_rnc']);

			$createcustomersubmitsql->bindParam(':customer_last_date_of_purchase', $_POST['customer_last_date_of_purchase']);

			$createcustomersubmitsql->bindParam(':type_account_receivable_id', $_POST['type_account_receivable_id']);
			
			$createcustomersubmitsql->bindParam(':country_id', $_POST['country_id']);

			$createcustomersubmitsql->bindParam(':province_id', $_POST['province_id']);

			$createcustomersubmitsql->bindParam(':city_id', $_POST['city_id']);
			
			$createcustomersubmitsql->bindParam(':sector_id', $_POST['sector_id']);

			$createcustomersubmitsql->bindParam(':type_price_id', $_POST['type_price_id']);

			$createcustomersubmitsql->bindParam(':day_id', $_POST['day_id']);
			
			$createcustomersubmitsql->bindParam(':customer_credit_limited', $_POST['customer_credit_limited']);

			$createcustomersubmitsql->bindParam(':customer_balance', $_POST['customer_balance']);

			$createcustomersubmitsql->bindParam(':customer_longitude', $_POST['customer_longitude']);

			$createcustomersubmitsql->bindParam(':customer_latitude', $_POST['customer_latitude']);

			$createcustomersubmitsql->bindParam(':customer_percent_discount', $_POST['customer_percent_discount']);

			$createcustomersubmitsql->bindParam(':customer_ncf_type', $_POST['customer_ncf_type']);

			$createcustomersubmitsql->execute();

			$this->con->commit();

			echo '<div class="text-center"><b>El cliente ha sido creado con éxito!</b></div>';

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

	}
}
}

if (isset($_POST['form_id'])){

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new customers;
		$editoptions->EditcustomersOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new customers;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new customers;
		$createoptions->CreatecustomersOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new customers;
		$createoptions->CreateGeneral();
	}

}


?>