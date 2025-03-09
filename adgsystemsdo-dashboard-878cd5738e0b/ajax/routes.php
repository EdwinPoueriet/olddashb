<?php
namespace App\Legacy;



class Routes extends General
{

	public function GetRoutesList()
	
	{

		$getroutelistsql = $this->con->prepare("SELECT a.route_code, a.route_name, s.seller_name 
		FROM ".$this->user_database.".routes a 
		LEFT JOIN ".$this->user_database.".sellers s ON (s.seller_code = a.seller_code)
		WHERE a.company_id = :company_id ORDER BY a.route_code"); 
		$getroutelistsql->bindParam(':company_id', $this->default_company);
		$getroutelistsql->execute();

		$getroutelistsql = $getroutelistsql->fetchAll(\PDO::FETCH_ASSOC);

		$getroutelistjson = json_decode(json_encode($getroutelistsql));

  		echo '<div class="table-responsive" id="areas_list">
  	
  		<table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
  			<thead>
  				<tr>
  					<th>Código</th>
  					<th>Ruta</th>
   					<th>Vendedor asignado</th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

		foreach ($getroutelistjson as $row)
		{

			echo '
			<tr>
				<td>
					<h2>'.$row->route_code.'</h2>
				</td>
				<td>
					<h2>'.$row->route_name.'</h2>    
				</td>
				<td>
					<h2>'.$row->seller_name.'</h2>    
				</td>
				<td style="width: 20%;">
  					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
  						<input type="hidden" name="form_id" value="editoptions"></input>
  						<input type="hidden" name="route_code" value="'.trim($row->route_code).'"></input>
  					
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

	public function EditRoutesOptions()
	
	{

		$editrouteoptionssql = $this->con->prepare("SELECT 
			route_code, 
			route_name, 
			seller_code, 
			day_id 
			FROM ".$this->user_database.".routes where company_id = :company_id AND TRIM(route_code) = TRIM(:route_code)");
		
		$editrouteoptionssql->bindParam(':route_code', $_POST['route_code']); 
		
		$editrouteoptionssql->bindParam(':company_id', $this->default_company);
		
		$editrouteoptionssql->execute();

		$editrouteoptionssql = $editrouteoptionssql->fetchAll(\PDO::FETCH_ASSOC);

		$editrouteoptionssqljson = json_decode(json_encode($editrouteoptionssql));

		$row = $editrouteoptionssqljson[0];

		echo '<input type="hidden" name="form_id" id="form_id" value="submitedit">

			<div class="form-group">

				<label for="route_code" class="control-label col-sm-3">Código:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="route_code" id="route_code" value="'.$row->route_code.'" readonly>

				</div>

			</div>

			<div class="form-group">

				<label for="route_name" class="control-label col-sm-3">Nombre:</label>

				<div class="col-sm-7">

					<input class="form-control input-sm" type="text" name="route_name" id="route_name" value="'.$row->route_name.'" maxlength="20">

				</div>

			</div>

			<div class="form-group">

				<label for="seller_code" class="control-label col-sm-3">Vendedor asignado:</label>

				<div class="col-sm-7">

                  <select class="form-control input-sm" name="seller_code" id="seller_code">
                  <option value=""></option>';

                  $sellers = $this->con->prepare("
                    SELECT 
                    a.seller_code, sn.seller_name  
                    FROM ".$this->user_database.".routes a
                    LEFT JOIN ".$this->user_database.".sellers sn ON (sn.seller_code = a.seller_code)
                    WHERE 
                    a.route_code = :route_code 
                    AND a.company_id = :company_id UNION 
                    SELECT 
                    s.seller_code, s.seller_name 
                    FROM ".$this->user_database.".sellers s 
                    WHERE 
                    s.company_id = :company_id");
                  	$sellers->bindParam(':route_code', $row->route_code);
                  	$sellers->bindParam(':seller_code', $row->seller_code);
                  	$sellers->bindParam(':company_id', $this->default_company);
                    $sellers->execute();
                    $sellers = $sellers->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($sellers as $srow){

                      echo '<option value="'.$srow['seller_code'].'"'.General::GetComparison($srow['seller_code'], $row->seller_code).'>'.$srow['seller_code'].' - '.$srow['seller_name'].'</option>';

                    }

                    echo '</select>
				</div>

			</div>

			<div class="form-group">

				<label for="day_id" class="control-label col-sm-3">Día de visita:</label>

				<div class="col-md-7">

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
	';

	}

	public function EditGeneral() 

	{

		$routeeditsubmitsql = $this->con->prepare("UPDATE  
			".$this->user_database.".routes SET 
			route_name = :route_name,
			seller_code = :seller_code,
			day_id = :day_id
			WHERE route_code = :route_code and company_id = :company_id
			");
		$routeeditsubmitsql->bindParam(':company_id', $this->default_company);
		$routeeditsubmitsql->bindParam(':route_name', $_POST['route_name']);
		$routeeditsubmitsql->bindParam(':seller_code', $_POST['seller_code']);
		$routeeditsubmitsql->bindParam(':day_id', $_POST['day_id']);
		$routeeditsubmitsql->bindParam(':route_code', $_POST['route_code']);
		$routeeditsubmitsql->execute();

		echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

	}


public function CreateGeneral() {

	$verifyroute = $this->con->prepare("SELECT route_code FROM ".$this->user_database.".routes WHERE route_code = :route_code and company_id = :company_id");
	
	$verifyroute->bindParam (':route_code', $_POST['route_code']);
	
	$verifyroute->bindParam(':company_id', $this->default_company);
	
	$verifyroute->execute();
	
	$verifyroute = $verifyroute->rowCount();

	if ($verifyroute > 0) {

		echo '<div class="text-center"><b>La zona '.$_POST['route_code'].' ya existe en la compañía '.$this->default_company.'!</b></div>';
	}

	else {

		$this->con->beginTransaction();

		try{

			$createroutesubmitsql = $this->con->prepare("INSERT INTO
				".$this->user_database.".routes
				(
				company_id,
				route_name,
				route_code,
				seller_code,
				day_id
				)
				VALUES 
				(
				:company_id,
				:route_name,
				:route_code,
				:seller_code,
				:day_id
				)
				");

			$createroutesubmitsql->bindParam(':company_id', $this->default_company);

			$createroutesubmitsql->bindParam(':route_name', $_POST['route_name']);

			$createroutesubmitsql->bindParam(':route_code', $_POST['route_code']);
			
			$createroutesubmitsql->bindParam(':seller_code', $_POST['seller_code']);
			
			$createroutesubmitsql->bindParam(':day_id', $_POST['day_id']);		

			$createroutesubmitsql->execute();

			$this->con->commit();

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

		echo '<div class="text-center"><b>La zona ha sido creado con éxito!</b></div>';

	}
}
}

if (isset($_POST['form_id'])){

	if ($_POST['form_id'] == 'getvalues'){

		$getvalues = new Routes;
		$getvalues->GetRoutesList();

	}

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Routes;
		$editoptions->EditRoutesOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Routes;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'createoptions') {

		$createoptions = new Routes;
		$createoptions->CreateRoutesOptions();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Routes;
		$createoptions->CreateGeneral();
	}

}


?>