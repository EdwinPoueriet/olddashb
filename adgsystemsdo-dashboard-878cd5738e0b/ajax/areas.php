<?php

namespace App\Legacy;

  class Areas extends General
  {

  	function __construct()

  	{

  		parent::__construct();

  		parent::ValidateSession($_SESSION['user_id']);

  	}

  	public function GetAreasList()

  	{

  		echo '<div class="table-responsive" id="areas_list">

  		<table class="table user-list footable table-hover" data-paging-size="12"
                                   data-paging-position="right" data-paging="true" data-filtering="true"
                                   data-sorting="true">
  			<thead>
  				<tr>
  					<th>Código</th>
  					<th>Nombre</th>
  					<th>Vendedor</th>
  					<th data-type="html">Acciones</th>
  				</tr>
  			</thead>
  			<tbody>';

  				foreach (json_decode($this->GetAreas()) as $row)
  				{

  					echo '
  					<tr>
  						<td>
  							<h2>'.$row->area_code.'</h2>
  						</td>
  						<td>
  							<h2>'.$row->area_name.'</h2>    
  						</td>
  						<td>
  							<h2>'.$row->seller_name.'</h2>    
  						</td>
  						<td style="width: 20%;">
      					<form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
      						<input type="hidden" name="form_id" value="editoptions"></input>
      						<input type="hidden" name="area_code" value="'.trim($row->area_code).'"></input>
      					
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

  	public function EditAreasOptions()

  	{

  		function SelectedCondition($user_setting, $general_setting)

  		{

  			if (trim($user_setting) == trim($general_setting))
  				$usercondition = 'selected';
  			else 
  				$usercondition = '';
  			return $usercondition;

  		};

  		$editareaoptionssql = $this->con->prepare("SELECT 
  			area_code, 
  			area_name, 
  			seller_code, 
  			day_id FROM ".$this->user_database.".areas where company_id = :company_id AND TRIM(area_code) = TRIM(:area_code)");

  		$editareaoptionssql->bindParam(':area_code', $_POST['area_code']); 

  		$editareaoptionssql->bindParam(':company_id', $this->default_company);

  		$editareaoptionssql->execute();

  		$editareaoptionssql = $editareaoptionssql->fetchAll(\PDO::FETCH_ASSOC);

  		$editareaoptionssqljson = json_decode(json_encode($editareaoptionssql));

  		$row = $editareaoptionssqljson[0];

  		echo '
  			<input type="hidden" name="form_id" id="form_id" value="submitedit">

  			<div class="form-group">

  				<label for="area_code" class="control-label col-sm-3 col-md-3">Codigo de la zona:</label>

  				<div class="col-md-7 col-sm-7">

  					<input class="form-control input-sm" type="text" name="area_code" id="area_code" value="'.$row->area_code.'" readonly>

  				</div>

  			</div>

  			<div class="form-group">

  				<label for="area_name" class="control-label col-sm-3 col-md-3">Nombre de la zona:</label>

  				<div class="col-md-7 col-sm-7">

  					<input class="form-control input-sm" type="text" name="area_name" id="area_name" value="'.$row->area_name.'" maxlength="20">

  				</div>

  			</div>

  			<div class="form-group">

  				<label for="seller_code" class="control-label col-sm-3 col-md-3">Vendedor asignado:</label>

  				<div class="col-md-7 col-sm-7">

  					<select class="form-control input-sm" name="seller_code" id="seller_code">
  						<option value=""></option>';

  						$sellers = $this->con->prepare("
  							SELECT 
  							a.seller_code, sn.seller_name  
  							FROM ".$this->user_database.".areas a
  							LEFT JOIN ".$this->user_database.".sellers sn ON (sn.seller_code = a.seller_code)
  							WHERE 
  							a.area_code = :area_code 
  							AND a.company_id = :company_id UNION 
  							SELECT 
  							s.seller_code, s.seller_name 
  							FROM ".$this->user_database.".sellers s 
  							WHERE 
  							s.company_id = :company_id");
  						$sellers->bindParam(':area_code', $row->area_code);
  						$sellers->bindParam(':seller_code', $row->seller_code);
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

  					<label for="day_id" class="control-label col-sm-3 col-md-3">Dia de visita:</label>

  					<div class="col-md-7 col-sm-7">

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

  		public function EditAreasSubmit() {

  			$areaeditsubmitsql = $this->con->prepare("UPDATE  
  				".$this->user_database.".areas SET 
  				area_name = :area_name,
  				seller_code = :seller_code,
  				day_id = :day_id
  				WHERE area_code = :area_code and company_id = :company_id
  				");
  			$areaeditsubmitsql->bindParam(':company_id', $this->default_company);
  			$areaeditsubmitsql->bindParam(':area_name', $_POST['area_name']);
  			$areaeditsubmitsql->bindParam(':seller_code', $_POST['seller_code']);
  			$areaeditsubmitsql->bindParam(':day_id', $_POST['day_id']);
  			$areaeditsubmitsql->bindParam(':area_code', $_POST['area_code']);
  			$areaeditsubmitsql->execute();

  			echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

  		}

  		public function CreateGeneral() {

  			$verifyarea = $this->con->prepare("SELECT area_code FROM ".$this->user_database.".areas WHERE area_code = :area_code and company_id = :company_id");
  			$verifyarea->bindParam (':area_code', $_POST['area_code']);
  			$verifyarea->bindParam(':company_id', $this->default_company);
  			$verifyarea->execute();
  			$verifyarea = $verifyarea->rowCount();

  			if ($verifyarea > 0) {

  				echo '<div class="text-center"><b>La zona '.$_POST['area_code'].' ya existe en la compañía '.$cid.'!</b></div>';
  			}

  			else {

  				$this->con->beginTransaction();

  				try{

  					$createareasubmitsql = $this->con->prepare("INSERT INTO
  						".$this->user_database.".areas
  						(
  						company_id,
  						area_name,
  						area_code,
  						seller_code,
  						day_id
  						)
  						VALUES 
  						(
  						:company_id,
  						:area_name,
  						:area_code,
  						:seller_code,
  						:day_id
  						)
  						");

  					$createareasubmitsql->bindParam(':company_id', $this->default_company);
  					$createareasubmitsql->bindParam(':area_name', $_POST['area_name']);
  					$createareasubmitsql->bindParam(':area_code', $_POST['area_code']);
  					$createareasubmitsql->bindParam(':seller_code', $_POST['seller_code']);
  					$createareasubmitsql->bindParam(':day_id', $_POST['day_id']);		

  					$createareasubmitsql->execute();

  					$this->con->commit();

					echo '<div class="text-center"><b>La zona ha sido creado con éxito!</b></div>';

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

  			$getvalues = new Areas;
  			$getvalues->GetAreasList();

  		}

  		if ($_POST['form_id'] == 'editoptions') {

  			$editoptions = new Areas;
  			$editoptions->EditAreasOptions();

  		}

  		if ($_POST['form_id'] == 'submitedit') {

  			$editoptions = new Areas;
  			$editoptions->EditAreasSubmit();

  		}

  		if ($_POST['form_id'] == 'submitcreate') {
  			$createoptions = new Areas;
  			$createoptions->CreateGeneral();
  		}

  	}
  	?>