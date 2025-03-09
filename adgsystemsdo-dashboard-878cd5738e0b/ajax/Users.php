<?php

namespace App\Legacy;

class Users extends General 
{

  function __construct()

  {

    parent::__construct();

  }

	public function GetUserList()
	
  {



   $getuserlistsql = self::$con->prepare("SELECT us.user_id, us.user_name, us.user_nickname, us.user_email, us.user_created_at, sell.seller_name, coll.collector_name
FROM ".self::$user_database.".users_accounts as us
LEFT JOIN ".self::$user_database.".users_companies as comp
ON comp.user_id = us.user_id
LEFT JOIN ".self::$user_database.".sellers as sell
ON sell.seller_code = comp.seller_code AND sell.company_id = ".self::$default_company."
LEFT JOIN ".self::$user_database.".collectors as coll
ON coll.collector_code = comp.collector_code AND coll.company_id = ".self::$default_company." ORDER BY us.user_name asc");

   $getuserlistsql->execute();

   $getuserlistsql = $getuserlistsql->fetchAll(\PDO::FETCH_ASSOC);



   foreach ($getuserlistsql as $row)
   {

     echo '
     <form style="display: none" id="'.$row['user_id'].'" method="POST" onsubmit="event.preventDefault();">
       <input type="hidden" name="form_id" value="editoptions"></input>
       <input type="hidden" name="user_id" value="'.$row['user_id'].'"></input>
       <input type="hidden" name="user_created_at" value="'.$row['user_created_at'].'"></input>
     </form>
     <tr>
      <td>
       <h2>'.$row['user_name'].'</h2>
     </td>
     <td><h2>'.$row['seller_name'].'</h2></td>
     <td><h2>'.$row['collector_name'].'</h2></td>
     <td>
       '.$row['user_created_at'].'
     </td>
     <td>
       <a href="#">'.$row['user_email'].'</a>
     </td>
     <td style="width: 20%;">
       <a class="table-link edit" style="cursor:pointer" id="'.$row['user_id'].'">
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

/*********************************************
*
* Funciones para formulario de editar usuarios
*
*********************************************/

   public function CompaniesSql()
    {

    $selectsql = self::$con->prepare("SELECT company_id, company_name, company_catalog_id FROM ".self::$user_database.".companies");

    $selectsql->execute();

    $selectsql = $selectsql->fetchAll(\PDO::FETCH_ASSOC);

    return json_encode($selectsql);
    }

  // Seleccionar datos de las companias de los usuarios

  public function UsersCompaniesSql($user_id, $company_id)

  {

    $usercompanyseselectsql = self::$con->prepare("SELECT * FROM ".self::$user_database.".users_companies WHERE user_id = :user_id AND company_id = :company_id ");

    $usercompanyseselectsql->bindParam(':user_id', $user_id);

    $usercompanyseselectsql->bindParam(':company_id', $company_id);

    $usercompanyseselectsql->execute();

    $usercompanyseselectsql = $usercompanyseselectsql->fetchAll(\PDO::FETCH_ASSOC);

    return json_encode($usercompanyseselectsql);

  }

public function EditUserOptions() 
{

	$this->condition = function ($var)
	{
		if ($var == 1)
			$var = 'checked';
		else
			$var = '';
		return $var;

	};

  function SelectedCondition($user_setting, $general_setting){

    if (trim($user_setting) == trim($general_setting))
      $usercondition = 'selected';
    else 
      $usercondition = '';
    return $usercondition;

  };

  function FiltersCondition($filter_array, $filter_option){

    $arrayfiltro = explode(",", $filter_array);

    if (in_array(trim(str_replace("'", "", $filter_option)), str_replace("'", "", $arrayfiltro), true))
      $filtercondition = 'selected';
    else 
      $filtercondition = '';
    return $filtercondition;              
  }

    // Array para saber si la opcion es 1 o 0 //

  $users = self::$con->prepare("SELECT 
  *
    FROM ".self::$user_database.".users_accounts x
    LEFT JOIN ".self::$user_database.".serial_numbers y ON (y.user_id = x.user_id)
    WHERE x.user_id = :user_id");
  $users->bindParam(':user_id', $_POST['user_id']);
  $users->execute();
  $users = $users->fetchAll(\PDO::FETCH_ASSOC);
  $row = $users[0];
  $rowc = array_map($this->condition, $users[0]);

  // Seleccionar companias


  echo '
  <div class="tabs-wrapper">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab-general" data-toggle="tab">Datos relacionados al usuario</a></li>
      <li><a href="#tab-compania" data-toggle="tab">Datos relacionados a la compania</a></li>
      <li><a href="#tab-dispositivo" data-toggle="tab">Datos relacionados al dispositivo</a></li>
      <li><a href="#tab-filtros" data-toggle="tab">Filtros extra</a></li>
    </ul>
    <form id="sellereditsubmit" action="" onsubmit="event.preventDefault();" method="POST">                   

      <input type="hidden" name="form_id" id="form_id" value="submitedit">
      
      <input type="hidden" name="user_id" id="user_id" value="'.$_POST['user_id'].'">
      
      <div class="tab-content">
        <div class="tab-pane fade in active" id="tab-general">

          <div class="row">
            <div class="col-md-3">
                <h4>Datos del usuario: </h4>
   
                <div class="form-group ">
    
                  <label for="user_nickname">Alias de usuario:</label>
    
                  <input class="form-control input-sm" style="text-transform: uppercase;" type="text" name="user_nickname" id="user_nickname" value="'.$row['user_nickname'].'" disabled>
    
                </div>

                <div class="form-group">
    
                  <label id="label-user" for="user_name">Nombre de usuario:</label>
    
                  <input class="form-control input-sm"  type="text" name="user_name" id="user_name" value="'.$row['user_name'].'" maxlength="20">
    
                </div>

                <div class="form-group ">
    
                  <label for="user_email">E-mail de usuario:</label>
    
                  <input class="form-control input-sm" type="email" name="user_email" id="user_email" value="'.$row['user_email'].'">
    
                </div>

                <div class="form-group">
    
                  <label for="user_password">Modificar contrasena de usuario:</label>
    
                  <input class="form-control input-sm" type="text" name="user_password" id="user_password">
    
                </div>
            
            </div>
            
            <div class="col-md-3">
            
             <h4>Enlaces: </h4>
            
            
            <div class="form-group">

              <label id="label-bluetooh" for="user_bluetooh_address">Direccion de Bluetooth:</label>

              <input class="form-control input-sm" type="text" name="user_bluetooh_address" id="user_bluetooh_address" value="'.$row['user_bluetooh_address'].'">

            </div>

            <div class="form-group ">

              <label for="user_printer">Tipo de impresora:</label>

              <select class="form-control input-sm" type="number" name="user_printer" id="user_printer" value="0">

                <option value="1"'; if ($row['user_printer'] == 1) echo 'selected';echo'>Datecs 250</option>
                <option value="2"'; if ($row['user_printer'] == 2) echo 'selected';echo'>Datecs 350</option>
                <option value="3"'; if ($row['user_printer'] == 3) echo 'selected';echo'>Zebra MZ-320</option>

              </select>

            </div>

            <div class="form-group">

              <label for="user_generate_ncf">Generar NCF automatico:</label>

              <select class="form-control input-sm" name="user_generate_ncf" id="user_generate_ncf">
                <option value="0"'; if ($row['user_generate_ncf'] == 2) echo 'selected';echo'>No</option>
                <option value="1"'; if ($row['user_generate_ncf'] == 1) echo 'selected';echo'>Si</option>

              </select>

            </div>

            <div class="form-group">

              <label for="user_not_require_active_gps">El usuario requiere localizacion:</label>

              <select class="form-control input-sm" name="user_not_require_active_gps" id="user_not_require_active_gps">
                <option value="0"'; if ($row['user_not_require_active_gps'] == 0) echo 'selected';echo'>No</option>
                <option value="1"'; if ($row['user_not_require_active_gps'] == 1) echo 'selected';echo'>Si</option>n>
              </select>

            </div>
            
            
            </div>
            
            <div class="col-md-3">
            
            <h4>Config. App: </h4>
            
            <div class="form-group">

              <label for="user_name">Monto minimo de pedido:</label>

              <input class="form-control input-sm"  type="text" name="minimum_amount_order" id="minimum_amount_order" value="'.$row['minimum_amount_order'].'" >

            </div>
      
            <div class="form-group">

              <label for="user_not_require_active_gps">Cada cuantos metros se actualizara el dispositivo:</label>

              <input class="form-control input-sm" type="number" name="location_distance" id="location_distance" value="'.$row['location_distance'].'">

            </div>

            <div class="form-group">

              <label for="user_sync_location_time">Cada cuantos minutos se actualizara el dispositivo:</label>

              <input class="form-control input-sm" type="number" name="user_sync_location_time" id="user_sync_location_time" value="'.$row['user_sync_location_time'].'">

            </div>
            
            
            </div>
            
            <div class="col-md-3">
            
             <h4>Sync: </h4>
            <div class="form-group">

              <label>Sincronizacion obligatoria de:</label>
                <div id="check-checkbox">
                

              <div class="checkbox-nice">

                <input type="checkbox" name="user_sync_customer" id="user_sync_customer" value="1" '.$rowc['user_sync_customer'].'>

                <label id="label-client" for="user_sync_customer">Clientes</label>

              </div>

              <div class="checkbox-nice">

                <input type="checkbox" name="user_sync_income" id="user_sync_income" value="1" '.$rowc['user_sync_income'].'>

                <label id="label-ingress" for="user_sync_income">Recibos de Ingreso</label>

              </div>

              <div class="checkbox-nice">

                <input type="checkbox" name="user_sync_order" id="user_sync_order" value="1" '.$rowc['user_sync_order'].'>

                <label id="label-order"  for="user_sync_order">Pedidos</label>

              </div>

              <div class="checkbox-nice">

                <input type="checkbox" name="user_sync_invoice" id="user_sync_invoice" value="1" '.$rowc['user_sync_invoice'].'>

                <label id="label-invoice" for="user_sync_invoice">Facturas</label>

              </div>

              <div class="checkbox-nice">

                <input type="checkbox" name="user_sync_visit" id="user_sync_visit" value="1" '.$rowc['user_sync_visit'].'>

                <label id="label-visit" for="user_sync_visit">Visitas no venta</label>

              </div>
              
              </div>

            </div>
            
            
            
            </div>
          </div>


     
          

        </div>

        <div class="tab-pane fade" id="tab-compania">';

          $companyarray = json_decode($this->CompaniesSql());

          $userscompaniesdefaultarray = array(
          "company_id" => "", 
          "cost_center_code" => "", 
          "seller_code" => "", 
          "warehouse_code" => "",
          "box_code" => "",
          "warehouse_billing_code" => "",
          "ncf_type_default" => "",
          "not_controlled" => "",
          "collector_code" => "",
          "required_bar_code" => "");

          $userscompaniesdefaultarray = json_decode(json_encode($userscompaniesdefaultarray));

          foreach ($companyarray as $carray){

            $userscompaniesresult = json_decode($this->UsersCompaniesSql($_POST['user_id'], $carray->company_id));

            $userscompaniesarray = (empty($userscompaniesresult[0]) ? $userscompaniesdefaultarray : $userscompaniesresult[0]);

            echo '<div class="row vert-offset-top-2"><h2>'.$carray->company_id. ' - '.$carray->company_name .'</h2></div>';

            echo '<div class="row col-md-offset-1">

            <div class="form-group col-md-4">

              <label for="company_id">Compania:</label>';

              echo '<input class="form-control input-sm" name="company_id[]" id="company_id" value="'.$carray->company_id.'" readonly>';                

              echo '

            </div>

            <div class="form-group col-md-4">

              <label for="company_id">Centro de costo:</label>

              <select class="form-control input-sm" name="cost_center_code[]" id="cost_center_code">
                <option value=""></option>';

                $costcenters = self::$con->prepare("SELECT company_id, cost_center_code, cost_center_name FROM ".self::$user_database.".cost_center WHERE company_id = :company_id ORDER BY cost_center_code");
                $costcenters->bindParam(':company_id', $carray->company_id);
                $costcenters->execute();
                $costcenters = $costcenters->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($costcenters as $ccrow){

                  echo '<option value="'.$ccrow['cost_center_code'].'" '.SelectedCondition(trim($userscompaniesarray->cost_center_code), trim($ccrow['cost_center_code'])).'>
                  CIA:'.$ccrow['company_id'].' - '.$ccrow['cost_center_code'].' - '.$ccrow['cost_center_name'].'</option>';

                }

                echo '</select>

              </div>

              <div class="form-group col-md-4">

                <label for="seller_code">Vendedor:</label>

                <select class="form-control input-sm" name="seller_code[]" id="seller_code">
                  <option value=""></option>';

                  $sellers = self::$con->prepare("
                    SELECT 
                    u.seller_code, sn.seller_name  
                    FROM ".self::$user_database.".users_companies u
                    LEFT JOIN ".self::$user_database.".sellers sn ON (sn.seller_code = u.seller_code)
                    WHERE 
                    u.user_id = :user_id 
                    AND u.company_id = :company_id UNION 
                    SELECT 
                    s.seller_code, s.seller_name 
                    FROM ".self::$user_database.".sellers s 
                    LEFT JOIN ".self::$user_database.".users_companies uc ON(uc.seller_code = s.seller_code)
                    WHERE 
                    uc.seller_code IS NULL AND s.company_id = :company_id");
                  $sellers->bindParam(':user_id', $_POST['user_id']);
                  $sellers->bindParam(':company_id', $carray->company_id);
                  $sellers->execute();
                  $sellers = $sellers->fetchAll(\PDO::FETCH_ASSOC);

                  foreach ($sellers as $srow){

                    echo '<option value="'.$srow['seller_code'].'" '.SelectedCondition($userscompaniesarray->seller_code, $srow['seller_code']).'>'.$srow['seller_code'].' - '.$srow['seller_name'].'</option>';

                  }

                  echo '</select>

                </div>

              </div>

              <div class="row">

                <div class="form-group col-md-4">

                  <label for="warehouse_code">Almacen de pedido:</label>

                  <select class="form-control input-sm" name="warehouse_code[]" id="warehouse_code">';

                    $warehouses = self::$con->prepare("SELECT warehouse_code, warehouse_name FROM ".self::$user_database.".warehouses WHERE company_id = :company_id");
                    $warehouses->bindParam(':company_id', $carray->company_catalog_id);
                    $warehouses->execute();
                    $warehouses = $warehouses->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($warehouses as $wrow){

                      echo '<option value="'.$wrow['warehouse_code'].'" '.SelectedCondition($userscompaniesarray->warehouse_code, $wrow['warehouse_code']).'>'.$wrow['warehouse_code'].' - '.$wrow['warehouse_name'].'</option>';

                    }

                    echo '</select>

                  </div>


                  <div class="form-group  col-md-4">

                    <label for="warehouse_code">Codigo de caja:</label>

                    <input class="form-control input-sm" type="number" name="box_code[]" id="box_code" value="'.($userscompaniesarray->box_code == '' ? 0 : $userscompaniesarray->box_code).'" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); if (this.value < 0 || this.value > 99) this.value = 0" maxlength="2" min="0" max="99">

                  </div>

                  <div class="form-group col-md-4">

                    <label for="warehouse_billing_code">Almacen de facturacion:</label>

                    <select class="form-control input-sm" name="warehouse_billing_code[]" id="warehouse_billing_code">';

                      $warehouses = self::$con->prepare("SELECT warehouse_code, warehouse_name FROM ".self::$user_database.".warehouses
                       WHERE company_id = :company_id ");
                      $warehouses->bindParam(':company_id', $carray->company_catalog_id);
                      $warehouses->execute();
                      $warehouses = $warehouses->fetchAll(\PDO::FETCH_ASSOC);

                      foreach ($warehouses as $wrow){

                        echo '<option value="'.$wrow['warehouse_code'].'" '.SelectedCondition($userscompaniesarray->warehouse_billing_code, $wrow['warehouse_code']).'>'.$wrow['warehouse_code'].' - '.$wrow['warehouse_name'].'</option>';

                      }

                      echo '</select>


                    </div>

                  </div>

                  <div class="row">

                    <div class="form-group col-md-3">

                      <label for="ncf_type_default[]">Tipo de comprobante por defecto:</label>

                      <select class="form-control input-sm" name="ncf_type_default[]" id="ncf_type_default[]">
                        <option value=""></option>
                        <option value="1"'; if ($userscompaniesarray->ncf_type_default == 1) echo 'selected';echo'>1 - V&aacute;lido para credito fiscal</option>
                        <option value="2"'; if ($userscompaniesarray->ncf_type_default == 2) echo 'selected';echo'>2 - Consumidor final</option>
                        <option value="3"'; if ($userscompaniesarray->ncf_type_default == 3) echo 'selected';echo'>3 - Regimen especial</option>
                        <option value="4"'; if ($userscompaniesarray->ncf_type_default == 4) echo 'selected';echo'>4 - Comprobante gubernamental</option>

                      </select>

                    </div>

                    <div class="form-group col-md-3">

                      <label for="collector_code">Cobrador:</label>

                      <select class="form-control input-sm" name="collector_code[]" id="collector_code">
                        <option value=""></option>';


                        $collectors = self::$con->prepare("SELECT company_id, collector_code, collector_name FROM ".self::$user_database.".collectors
                         WHERE company_id = :company_id");
                        $collectors->bindParam(':company_id', $carray->company_id);
                        $collectors->execute();
                        $collectors = $collectors->fetchAll(\PDO::FETCH_ASSOC);

                        foreach ($collectors as $ccrow){

                          echo '<option value="'.$ccrow['collector_code'].'" '.SelectedCondition($userscompaniesarray->collector_code, $ccrow['collector_code']).'>
                         CIA: '.$ccrow['company_id'].' - '.$ccrow['collector_code'].' - '.$ccrow['collector_name'].'</option>';

                        }

                        echo '</select>

                      </div>

                      <div class="form-group col-md-3">

                        <label for="not_controlled">Inventario controlado:</label>

                        <select class="form-control input-sm" name="not_controlled[]" id="not_controlled[]">
                        <option value="0"'; if ($userscompaniesarray->not_controlled == 1) echo 'selected';echo'>No</option>
                        <option value="1"'; if ($userscompaniesarray->not_controlled == 0) echo 'selected';echo'>Si</option>
                        </select>


                      </div>

                      <div class="form-group col-md-3">

                        <label for="warehouse_code">Codigo de barra del cliente requerido:</label>


                        <select class="form-control input-sm" name="required_bar_code[]" id="required_bar_code[]">
                          <option value="0"'; if ($userscompaniesarray->required_bar_code == 0) echo 'selected';echo'>No</option>
                          <option value="1"'; if ($userscompaniesarray->required_bar_code == 1) echo 'selected';echo'>Si</option>
                        </select>

                      </div>

                    </div>
                    ';

                  };


//Aqui la opcion de imei
                  echo '</div><div class="tab-pane fade" id="tab-dispositivo">

                  <div class="row vert-offset-top-2">

                    <div class="form-group col-md-4 col-md-offset-2">

                      <label for="user_related_imei">IMEI o serial del dispositivo:</label>

                      <input class="form-control input-sm" type="text" name="user_related_imei" id="user_related_imei" value="'.$row['user_related_imei'].'">

                    </div>

                    <div class="form-group col-md-4">

                      <label for="user_related_imei">Serial que se le asignara al usuario:</label>

                      <select class="form-control input-sm" name="user_serial_number" id="user_serial_number">

                        <option value=""></option>';

                  if (isset($_POST['user_id']))
                    $query  = $_POST['user_id'];
                  else
                     $query  = 0;

                        $serials = self::$con->prepare("SELECT user_serial_number, user_id FROM ".self::$user_database.".serial_numbers 
                        WHERE user_serial_number != '' and ( user_id = 0 OR user_id = ".$query.")");
                        $serials->execute();
                        $serials = $serials->fetchAll(\PDO::FETCH_ASSOC);

                        foreach ($serials as $sn) {

                          echo '<option value="'.$sn['user_serial_number'].'"';

                          if ($sn['user_id'] == $_POST['user_id']) 

                            echo 'selected';

                          else if ($sn['user_id'] != 0)

                            echo 'disabled';

                          echo '>'.$sn['user_serial_number'].'</option>';

                        }


                        echo '</select>

                      </div>

                      <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog modal-sm">
                          <div class="modal-content">
                            <div class="modal-header" style="border: none">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="text-center" id="qrcode"></div>
                            <div class="modal-footer" style="border: none"></div>

                          </div>
                        </div>
                      </div>

                    </div>

                  </div>

                  <div class="tab-pane fade" id="tab-filtros">';

                    foreach ($companyarray as $carray){

                  // Select para buscar los filtros // 

                      $filtersql = self::$con->prepare("SELECT *
                        FROM ".self::$user_database.".users_filters WHERE user_id = :user_id and company_id = :company_id");

                      $filtersql->bindParam(':user_id', $_POST['user_id']);

                      $filtersql->bindParam(':company_id', $carray->company_id);

                      $filtersql->execute();

                      $filtersql = $filtersql->fetchAll(\PDO::FETCH_ASSOC);

                      if (!empty($filtersql))

                        $filterrow = $filtersql[0];

                      else 

                        $filterrow = '';

                      echo '<div class="row vert-offset-top-2"><h2>'.$carray->company_id. ' - '.$carray->company_name .'</h2></div>

                      <div class="row vert-offset-top-1">

                        <div class="form-group col-md-4 col-md-offset-2">

                          <label for="filter_areas['.$carray->company_id.'][]">Filtrar zonas:</label>

                          <select class="form-control input-sm sel2Multi" name="filter_areas['.$carray->company_id.'][]" id="filter_areas['.$carray->company_id.'][]" multiple>';

                            $filterareas = self::$con->prepare("SELECT area_code, area_name from ".self::$user_database.".areas");
                            $filterareas->execute();
                            $filterareas = $filterareas->fetchAll(\PDO::FETCH_ASSOC);

                            foreach ($filterareas as $arow) {

                              echo '<option value="'.$arow['area_code'].'" '.FiltersCondition($filterrow['areas'], $arow['area_code']).'>'.$arow['area_code'].' - '.$arow['area_name'].'</option>';

                            }


                            echo '</select>

                          </div>

                          <div class="form-group col-md-4">

                            <label for="filter_collectors['.$carray->company_id.'][]">Filtrar cobradores:</label>

                            <select class="form-control input-sm sel2Multi" name="filter_collectors['.$carray->company_id.'][]" id="filter_collectors['.$carray->company_id.'][]" multiple>';

                              $filtercollectors = self::$con->prepare("SELECT collector_code, collector_name from ".self::$user_database.".collectors");
                              $filtercollectors->execute();
                              $filtercollectors = $filtercollectors->fetchAll(\PDO::FETCH_ASSOC);

                              foreach ($filtercollectors as $fcrow) {

                                echo '<option value="'.$fcrow['collector_code'].'" '.FiltersCondition($filterrow['collectors'], $fcrow['collector_code']).'>'.$fcrow['collector_code'].' - '.$fcrow['collector_name'].'</option>';

                              }

                              echo '</select>

                            </div>

                          </div>

                          <div class="row vert-offset-bottom-2">

                            <div class="form-group col-md-4 col-md-offset-2">

                              <label for="filter_routes['.$carray->company_id.'][]">Filtrar rutas:</label>

                              <select class="form-control input-sm sel2Multi" name="filter_routes['.$carray->company_id.'][]" id="filter_routes['.$carray->company_id.'][]" multiple>';

                                $filterroutes = self::$con->prepare("SELECT route_code, route_name from ".self::$user_database.".routes");
                                $filterroutes->execute();
                                $filterroutes = $filterroutes->fetchAll(\PDO::FETCH_ASSOC);

                                foreach ($filterroutes as $rrow) {

                                  echo '<option value="'.$rrow['route_code'].'" '.FiltersCondition($filterrow['routes'], $rrow['route_code']).'>'.$rrow['route_code'].' - '.$rrow['route_name'].'</option>';

                                }


                                echo '</select>

                              </div>

                              <div class="form-group col-md-4">

                                <label for="filter_sellers['.$carray->company_id.'][]">Filtrar vendedores:</label>

                                <select class="form-control input-sm sel2Multi" name="filter_sellers['.$carray->company_id.'][]" id="filter_sellers['.$carray->company_id.'][]" multiple>';

                                  $filtersellers = self::$con->prepare("SELECT seller_code, seller_name from ".self::$user_database.".sellers");
                                  $filtersellers->execute();
                                  $filtersellers = $filtersellers->fetchAll(\PDO::FETCH_ASSOC);

                                  foreach ($filtersellers as $srow) {

                                    echo '<option value="'.$srow['seller_code'].'" '.FiltersCondition($filterrow['sellers'], $srow['seller_code']).'>'.$srow['seller_code'].' - '.$srow['seller_name'].'</option>';

                                  }

                                  echo '</select>

                                </div>

                              </div>';

                            }


                            echo '</div>

                          </div>
                          <div class="form-group text-center">
                            <input onclick="useredit()" value="Aceptar" class="btn btn-success" id="submit" style="width: 100px">
                          </div>
                        </form>
                        <div id="response"></div>
                      </div>';

                    }

/**
*
* // Final de listado de usuarios //
*
**/

/**
*
* // Funciones para editar un usuario //
*
**/


public function EditGeneral()
{
	$this->EditUser();
	$this->EditUserCompanies();
	$this->EditUserFilters();
	$this->DeleteSerial();
	$this->EditSerial();
}

public function EditUser()

{
  if ($_POST['user_password'] != '')
   $user_password = md5($_POST['user_password']);

 else {

  $pwdstmt = self::$con->prepare("SELECT user_password from ".self::$user_database.".users_accounts where user_id = :user_id");
  $pwdstmt->bindParam(':user_id', $_POST['user_id']);
  $pwdstmt->execute();
  $user_password = $pwdstmt->fetchColumn();

} 

$edituseraccountsql = self::$con->prepare("
  UPDATE ".self::$user_database.".users_accounts SET 
  user_name = :user_name,
  user_email = :user_email,
  user_password = :user_password,
  user_bluetooh_address = :user_bluetooh_address,
  user_printer = :user_printer,
  user_generate_ncf = :user_generate_ncf,
  user_sync_customer = :user_sync_customer,
  user_sync_income = :user_sync_income,
  user_sync_order = :user_sync_order,
  minimum_amount_order = :minimum_amount_order,
  user_sync_invoice = :user_sync_invoice,
  user_sync_visit = :user_sync_visit,
  user_sync_location_time = :user_sync_location_time,
  location_distance = :location_distance,
  user_not_require_active_gps = :user_not_require_active_gps
  WHERE user_id = :user_id
  ");


 $income = isset($_POST['user_sync_income']) ? $_POST['user_sync_income']: 0;
 $user_sync = isset($_POST['user_sync_customer']) ? $_POST['user_sync_customer'] : 0;
 $minimun = $_POST['minimum_amount_order'] ? $_POST['minimum_amount_order'] : 0;
 $order = isset($_POST['user_sync_order']) ? $_POST['user_sync_order'] : 0;
 $sync = isset($_POST['user_sync_invoice']) ? $_POST['user_sync_invoice'] : 0;
 $sync2 = isset($_POST['user_sync_visit']) ? $_POST['user_sync_visit'] : 0;
    $sync3 = isset($_POST['user_sync_location_time']) ? $_POST['user_sync_location_time'] : 0;
  $edituseraccountsql->bindParam(':minimum_amount_order', $minimun);
$edituseraccountsql->bindParam(':user_id', $_POST['user_id']);
$edituseraccountsql->bindParam(':user_name', $_POST['user_name']);
$edituseraccountsql->bindParam(':user_email', $_POST['user_email']);
$edituseraccountsql->bindParam(':user_password', $user_password);
$edituseraccountsql->bindParam(':user_bluetooh_address', $_POST['user_bluetooh_address']);
$edituseraccountsql->bindParam(':user_printer', $_POST['user_printer']);
$edituseraccountsql->bindParam(':user_generate_ncf', $_POST['user_generate_ncf']);
$edituseraccountsql->bindParam(':user_sync_customer', $user_sync);
$edituseraccountsql->bindParam(':user_sync_income', $income);
$edituseraccountsql->bindParam(':user_sync_order', $order);
$edituseraccountsql->bindParam(':user_sync_invoice', $sync);
$edituseraccountsql->bindParam(':user_sync_visit', $sync2);
$edituseraccountsql->bindParam(':user_sync_location_time',
    $sync3);
$edituseraccountsql->bindParam(':location_distance', $_POST['location_distance']);
$edituseraccountsql->bindParam(':user_not_require_active_gps', $_POST['user_not_require_active_gps']);
$edituseraccountsql->execute();

}

public function EditUserCompanies()

{

  $n = 0;

  foreach ($_POST['company_id'] as $row) {   

    $array_companies[] = array(
      "company_id" =>  $_POST['company_id'][$n], 
      "cost_center_code" => (isset($_POST['cost_center_code'][$n]) ? $_POST['cost_center_code'][$n] : ""), 
      "seller_code" => (isset($_POST['seller_code'][$n]) ? $_POST['seller_code'][$n] : ""), 
      "warehouse_code" => (isset($_POST['warehouse_code'][$n]) ? $_POST['warehouse_code'][$n] : ""),
      "required_bar_code" => (isset($_POST['required_bar_code'][$n]) ? $_POST['required_bar_code'][$n] : ""),
      "box_code" => (isset($_POST['box_code'][$n]) ? $_POST['box_code'][$n] : ""),
      "warehouse_billing_code" => (isset($_POST['warehouse_billing_code'][$n]) ? $_POST['warehouse_billing_code'][$n] : ""),
      "ncf_type_default" => (isset($_POST['ncf_type_default'][$n]) ? $_POST['ncf_type_default'][$n] : ""),
      "not_controlled" => (isset($_POST['not_controlled'][$n]) ? $_POST['not_controlled'][$n] : ""),
      "collector_code" => (isset($_POST['collector_code'][$n]) ? $_POST['collector_code'][$n] : ""));

    $n = $n + 1;

  }

  $json_companies = json_decode(json_encode($array_companies));

  // Borrar registros del usuario //

  $deleteusercompaniessql = self::$con->prepare("DELETE FROM ".self::$user_database.".users_companies where user_id = :user_id");
  $deleteusercompaniessql->bindParam(':user_id', $_POST['user_id']);
  $deleteusercompaniessql->execute();

  foreach ($json_companies as $row) { 

    if ((($row->cost_center_code != "") && ($row->seller_code != "")) && (($row->warehouse_code != "") && ($row->collector_code != ""))) {

      $editusercompaniessql = self::$con->prepare("
        INSERT INTO ".self::$user_database.".users_companies
        (
        company_id,
        cost_center_code,
        seller_code,
        user_id,
        warehouse_code,
        required_bar_code,
        box_code,
        warehouse_billing_code,
        ncf_type_default,
        not_controlled,
        collector_code
        )
        VALUES
        (
        :company_id,
        :cost_center_code,
        :seller_code,
        :user_id,
        :warehouse_code,
        :required_bar_code,
        :box_code,
        :warehouse_billing_code,
        :ncf_type_default,
        :not_controlled,
        :collector_code
        )
        ");

      $editusercompaniessql->bindParam(':company_id', $row->company_id);
      $editusercompaniessql->bindParam(':cost_center_code', $row->cost_center_code);
      $editusercompaniessql->bindParam(':seller_code', $row->seller_code);
      $editusercompaniessql->bindParam(':user_id', $_POST['user_id']);
      $editusercompaniessql->bindParam(':warehouse_code', $row->warehouse_code);
      $editusercompaniessql->bindParam(':required_bar_code', $row->required_bar_code);
      $editusercompaniessql->bindParam(':box_code', $row->box_code);
      $editusercompaniessql->bindParam(':warehouse_billing_code', $row->warehouse_billing_code);
      $editusercompaniessql->bindParam(':ncf_type_default', $row->ncf_type_default);
      $editusercompaniessql->bindParam(':not_controlled', $row->not_controlled);
      $editusercompaniessql->bindParam(':collector_code', $row->collector_code);
      $editusercompaniessql->execute();

    }

  }

}

public function EditUserFilters()

{

  $n = 0;

  foreach ($_POST['company_id'] as $row) {   

    $array_users_filters[] = array(
      "company_id" => $_POST['company_id'][$n],
      "array_areas" => (empty($_POST["filter_areas"][$_POST['company_id'][$n]]) ? "" : "'".implode("','", array_map('trim', $_POST["filter_areas"][$_POST['company_id'][$n]]))."'"), 
      "array_collectors" => (empty($_POST["filter_collectors"][$_POST['company_id'][$n]]) ? "" : "'".implode("','", array_map('trim', $_POST["filter_collectors"][$_POST['company_id'][$n]]))."'"),  
      "array_routes" => (empty($_POST["filter_routes"][$_POST['company_id'][$n]]) ? "" : "'".implode("','", array_map('trim', $_POST["filter_routes"][$_POST['company_id'][$n]]))."'"), 
      "array_sellers" => (empty($_POST["filter_sellers"][$_POST['company_id'][$n]]) ? "" : "'".implode("','", array_map('trim', $_POST["filter_sellers"][$_POST['company_id'][$n]]))."'")
      );

    $n = $n + 1;

  }

  $json_filters = json_decode(json_encode($array_users_filters));

  //Borrar los datos del usuario con respecto a los filtros // 

  $deleteuserfilterssql = self::$con->prepare("DELETE FROM ".self::$user_database.".users_filters where user_id = :user_id");
  $deleteuserfilterssql->bindParam(':user_id', $_POST['user_id']);
  $deleteuserfilterssql->execute();



  foreach ($json_filters as $row) {

    if ((($row->array_areas != "") || ($row->array_collectors != "")) || (($row->array_routes != "") || ($row->array_sellers != ""))) {

      $edituserfilterssql = self::$con->prepare("
        INSERT INTO ".self::$user_database.".users_filters
        (
        areas,
        collectors,
        routes,
        sellers,
        user_id,
        company_id
        ) 
        VALUES
        (
        :filter_areas,
        :filter_collectors,
        :filter_routes,
        :filter_sellers,
        :user_id,
        :company_id
        )
        "); 

      $edituserfilterssql->bindParam(':filter_areas', $row->array_areas);
      $edituserfilterssql->bindParam(':filter_collectors', $row->array_collectors);
      $edituserfilterssql->bindParam(':filter_routes', $row->array_routes);
      $edituserfilterssql->bindParam(':filter_sellers', $row->array_sellers);
      $edituserfilterssql->bindParam(':user_id', $_POST['user_id']);
      $edituserfilterssql->bindParam(':company_id', $row->company_id);
      $edituserfilterssql->execute();

    } 
  }

}

public function DeleteSerial()
{

//	$editserialsql = self::$con->prepare("UPDATE
//		".self::$user_database.".serial_numbers SET
//		user_related_imei = '',
//		user_id = 0
//		WHERE user_id = :user_id");
//	$editserialsql->bindParam(':user_id', $_POST['user_id']);
//	$editserialsql->execute();
}

public function EditSerial()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if ($_POST['user_serial_number'] != '') {


        $stmt = self::$con->prepare("delete from
		".self::$user_database.".serial_numbers where user_serial_number = ? and user_id = 0");
        $stmt->execute([$_POST['user_serial_number']]);

        $editserialsql = self::$con->prepare("UPDATE 
		".self::$user_database.".serial_numbers SET
		user_related_imei = :user_related_imei,
		user_id = :user_id,
		user_serial_number = :user_serial_number
		WHERE user_related_imei = :user_related_imei2");
        $editserialsql->bindParam(':user_id', $_POST['user_id']);
        $editserialsql->bindParam(':user_related_imei', $_POST['user_related_imei']);
        $editserialsql->bindParam(':user_related_imei2', $_POST['user_related_imei']);
        $editserialsql->bindParam(':user_serial_number', $_POST['user_serial_number']);
        $editserialsql->execute();


        echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';
    }
    else {


        $sql = self::$con->prepare("SELECT user_serial_number from
		".self::$user_database.".serial_numbers
		WHERE user_related_imei = :user_related_imei");

        $sql->execute([
            'user_related_imei' => $_POST['user_related_imei']
        ]);

        $res = $sql->fetchColumn();

        if ($res) {
            $editserialsql = self::$con->prepare("UPDATE
		".self::$user_database.".serial_numbers SET
		user_serial_number = ''
		WHERE user_related_imei = :user_related_imei");
            $editserialsql->bindParam(':user_related_imei', $_POST['user_related_imei']);
            $editserialsql->execute();

        self::$con->query("insert into
		".self::$user_database.".serial_numbers(user_serial_number,user_id,user_related_imei) VALUES ('$res', 0, '')");
        }

        echo '<div class="text-center"><b>Los csambios han sido realizados correctamente!.</b></div>';

    }


}

/********************************************
*
* Final de funciones para editar un usuario 
*
*********************************************/

  public function CreateList()

  {

    echo '<form id="createform" name="createform" method="POST" onsubmit="event.preventDefault();">
    <input type="hidden" id="form_id" name="form_id" value="submitcreate">

    <div id="myWizard" class="wizard">
      <div class="wizard-inner">
        <ul class="steps">
          <li data-target="#step1" class="active"><span class="badge badge-primary">1</span>Paso 1<span class="chevron"></span></li>
          <li data-target="#step2"><span class="badge">2</span>Paso 2<span class="chevron"></span></li>
          <li data-target="#step3"><span class="badge">3</span>Paso 3<span class="chevron"></span></li>
          <li data-target="#step4"><span class="badge">4</span>Paso 4<span class="chevron"></span></li>
        </ul>
        <div class="actions">
          <button type="button" class="btn btn-default btn-mini btn-prev"> <i class="icon-arrow-left"></i>Anterior</button>
          <button type="button" class="btn btn-success btn-mini btn-next" data-last="Crear">Siguiente<i class="icon-arrow-right"></i></button>
        </div>
      </div>


      <div class="step-content">
        <div class="step-pane active" id="step1">

          <div class="text-center"><h4><b>Datos relacionados al usuario</b></h4></div>

          <div class="row">

            <div class="form-group col-md-3">

              <label for="user_nickname">Alias de usuario:</label>

              <input class="form-control input-sm" type="text" name="user_nickname" id="user_nickname" style="text-transform: uppercase;" required>

            </div>

            <div class="form-group col-md-3">

              <label for="user_name">Nombre de usuario:</label>

              <input class="form-control input-sm" type="text" name="user_name" id="user_name" required>

            </div>

            <div class="form-group col-md-3">

              <label for="user_email">E-mail de usuario:</label>

              <input class="form-control input-sm" type="email" name="user_email" id="user_email" required>

            </div>

            <div class="form-group col-md-3">

              <label for="user_password">Contraseña de usuario:</label>

              <input class="form-control input-sm" style="text-transform: uppercase;" type="password" name="user_password" id="user_password" required>

            </div>

          </div>

          <div class="row">

            <div class="form-group col-md-3">

              <label for="user_bluetooh_address">Dirección de Bluetooth:</label>

              <input class="form-control input-sm" type="text" name="user_bluetooh_address" id="user_bluetooh_address">

            </div>

            <div class="form-group col-md-3">

              <label for="user_printer">Tipo de impresora:</label>

              <select class="form-control input-sm" type="number" name="user_printer" id="user_printer" value="0" required>

                <option value="1">Datecs 250</option>
                <option value="2">Datecs 350</option>
                <option value="3">Zebra MZ-320</option>

              </select>

            </div>

            <div class="form-group col-md-3">

              <label for="user_generate_ncf">Generar NCF automatico:</label>

              <select class="form-control input-sm" name="user_generate_ncf" id="user_generate_ncf">
                <option value="2">No</option>
                <option value="1">Si</option>

              </select>

            </div>

            <div class="form-group col-md-3">

              <label for="user_not_require_active_gps">El usuario requiere localización:</label>

              <select class="form-control input-sm" name="user_not_require_active_gps" id="user_not_require_active_gps">
                <option value="2">No</option>
                <option value="1">Si</option>
              </select>

            </div>

          </div>

          <div class="row">

            <div class="form-group col-md-3">

              <label for="user_not_require_active_gps">Cada cuantos metros se actualizará el dispositivo:</label>

              <input class="form-control input-sm" type="number" name="location_distance" id="location_distance" value="0" required>

            </div>

            <div class="form-group col-md-3">

              <label for="user_sync_location_time">Cada cuantos minutos se actualizará el dispositivo:</label>

              <input class="form-control input-sm" type="number" name="user_sync_location_time" id="user_sync_location_time" value="0" required>

            </div>

            <div class="form-group col-md-3">

              <label for="not_controlled">Inventario controlado:</label>

              <select class="form-control input-sm" name="not_controlled" id="not_controlled">
                <option value="1">Si</option>
                <option value="0">No</option>
              </select>


            </div>

            <div class="form-group col-md-3">

              <label for="warehouse_code">Codigo de barra del cliente requerido:</label>


              <select class="form-control input-sm" name="required_bar_code" id="required_bar_code">
                <option value="0">No</option>
                <option value="1">Si</option>
              </select>

            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-3">

              <label for="user_name">Monto minimo de pedido:</label>

              <input class="form-control input-sm"  type="text" name="minimum_amount_order" id="minimum_amount_order" >

            </div>
          
</div>

          <div class="row text-center">

            <div class="form-group">

              <label>Sincronización obligatoria de:</label>

              <div class="checkbox-inline checkbox-nice">

                <input type="checkbox" name="user_sync_customer" id="user_sync_customer" value="1" checked>

                <label for="user_sync_customer">Clientes</label>

              </div>

              <div class="checkbox-inline checkbox-nice">

                <input type="checkbox" name="user_sync_income" id="user_sync_income" value="1" checked>

                <label for="user_sync_income">Recibos de Ingreso</label>

              </div>

              <div class="checkbox-inline checkbox-nice">

                <input type="checkbox" name="user_sync_order" id="user_sync_order" value="1" checked>

                <label for="user_sync_order">Pedidos</label>

              </div>

              <div class="checkbox-inline checkbox-nice">

                <input type="checkbox" name="user_sync_invoice" id="user_sync_invoice" value="1" checked>

                <label for="user_sync_invoice">Facturas</label>

              </div>

              <div class="checkbox-inline checkbox-nice">

                <input type="checkbox" name="user_sync_visit" id="user_sync_visit" value="1" checked>

                <label for="user_sync_visit">Visitas no venta</label>

              </div>

            </div>

          </div>


        </div>

        <div class="step-pane" id="step2">

          <div class="text-center"><h4><b>Datos relacionados a la compañía</b></h4></div>

          <div class="row">

            <div class="form-group col-md-4">

              <label for="company_id">Compañía:</label>

              <select class="form-control input-sm" name="company_id" id="company_id">';


                $companies = self::$con->prepare("SELECT company_id, company_name FROM ".self::$user_database.".companies");
                $companies->execute();
                $companies = $companies->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($companies as $row){

                  echo '<option value="'.$row['company_id'].'">'.$row['company_id'].' - '.$row['company_name'].'</option>';

                }


                echo '</select>

              </div>

              <div class="form-group col-md-4">

                <label for="company_id">Centro de costo:</label>

                <select class="form-control input-sm" name="cost_center_code" id="cost_center_code">';

                  $costcenters = self::$con->prepare("SELECT company_id, cost_center_code, cost_center_name FROM ".self::$user_database.".cost_center");
                  $costcenters->execute();
                  $costcenters = $costcenters->fetchAll(\PDO::FETCH_ASSOC);

                  foreach ($costcenters as $row){

                    echo '<option value="'.$row['cost_center_code'].'"> CIA: '.$row['company_id'].' - '.$row['cost_center_code'].' - '.$row['cost_center_name'].'</option>';

                  }

                  echo '</select>

                </div>

                <div class="form-group col-md-4">

                  <label for="seller_code">Vendedor:</label>

                  <select class="form-control input-sm" name="seller_code" id="seller_code">';

                    $sellers = self::$con->prepare("SELECT seller_code, seller_name 
                      FROM ".self::$user_database.".sellers 
                      WHERE seller_code NOT IN
                      (
                      SELECT seller_code FROM ".self::$user_database.".users_companies 
                      WHERE seller_code != ''
                      )");
                    $sellers->execute();
                    $sellers = $sellers->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($sellers as $row){

                      echo '<option value="'.$row['seller_code'].'">'.$row['seller_code'].' - '.$row['seller_name'].'</option>';

                    }



                    echo '</select>

                  </div>

                </div>

                <div class="row">

                  <div class="form-group col-md-4">

                    <label for="warehouse_code">Almacén:</label>

                    <select class="form-control input-sm" name="warehouse_code" id="warehouse_code">';

                      $costcenters = self::$con->prepare("SELECT warehouse_code, warehouse_name FROM ".self::$user_database.".warehouses");
                      $costcenters->execute();
                      $costcenters = $costcenters->fetchAll(\PDO::FETCH_ASSOC);

                      foreach ($costcenters as $row){

                        echo '<option value="'.$row['warehouse_code'].'">'.$row['warehouse_code'].' - '.$row['warehouse_name'].'</option>';

                      }


                      echo '</select>

                    </div>


                    <div class="form-group col-md-4">

                      <label for="warehouse_code">Código de caja:</label>

                      <input class="form-control input-sm" type="text" name="box_code" id="box_code">

                    </div>

                    <div class="form-group col-md-4">

                      <label for="warehouse_billing_code">Almacen de facturacion:</label>

                      <input class="form-control input-sm" type="text" name="warehouse_billing_code" id="warehouse_billing_code">

                    </div>

                  </div>

                  <div class="row">

                    <div class="form-group col-md-3 col-md-offset-3">

                      <label for="ncf_type_default">Tipo de comprobante por defecto:</label>

                      <select class="form-control input-sm" name="ncf_type_default" id="ncf_type_default">
                        <option value="1">1 - V&aacute;lido para credito fiscal</option>
                        <option value="2">2 - Consumidor final</option>
                        <option value="3">3 - Regimen especial</option>
                        <option value="4">4 - Comprobante gubernamental</option>

                      </select>

                    </div>

                    <div class="form-group col-md-3">

                      <label for="collector_code">Cobrador:</label>

                      <select class="form-control input-sm" name="collector_code" id="collector_code">';

                        $collectors = self::$con->prepare("SELECT company_id, collector_code, collector_name FROM ".self::$user_database.".collectors");
                        $collectors->execute();
                        $collectors = $collectors->fetchAll(\PDO::FETCH_ASSOC);

                        foreach ($collectors as $row){

                          echo '<option value="'.$row['collector_code'].'"> CIA: '.$row['company_id'].' - '.$row['collector_code'].' - '.$row['collector_name'].'</option>';

                        }


                        echo '</select>

                      </div>

                    </div>

                  </div>

                  <div class="step-pane" id="step3">

                    <div class="row">

                      <div class="text-center"><h4><b>Datos relacionados al dispositivo</b></h4></div>

                      <div class="form-group col-md-4 col-md-offset-2">

                        <label for="user_related_imei">IMEI o serial del dispositivo:</label>

                        <input class="form-control input-sm" type="text" name="user_related_imei" id="user_related_imei" required>

                      </div>

                      <div class="form-group col-md-4">

                        <label for="user_related_imei">Serial que se le asignará al usuario:</label>

                        <select class="form-control input-sm" name="user_serial_number" id="user_serial_number">';

                          $serials = self::$con->prepare("SELECT user_serial_number
 FROM ".self::$user_database.".serial_numbers where user_id = 0 ");

                          $serials->execute();

                          $serials = $serials->fetchAll(\PDO::FETCH_ASSOC);

                          foreach ($serials as $row) {

                            echo '<option value="'.$row['user_serial_number'].'">'.$row['user_serial_number'].'</option>';

                          }


                          echo '</select>

                        </div>

                      </div>

                    </div>

                    <div class="step-pane" id="step4">

                      <div class="text-center"><h4><b>Filtros extras</b></h4></div>

                      <div class="row">

                        <div class="form-group col-md-5 col-md-offset-1">

                          <label for="filter_areas">Filtrar zonas:</label>

                          <select class="form-control input-sm sel2Multi" name="filter_areas[]" id="filter_areas[]" multiple>';

                            $filterareas = self::$con->prepare("SELECT area_code, area_name from ".self::$user_database.".areas");
                            $filterareas->execute();
                            $filterareas = $filterareas->fetchAll(\PDO::FETCH_ASSOC);

                            foreach ($filterareas as $row) {

                              echo '<option value="'.$row['area_code'].'">'.$row['area_code'].' - '.$row['area_name'].'</option>';

                            }


                            echo '</select>

                          </div>

                          <div class="form-group col-md-5 col-md-offset-1">

                            <label for="filter_collectors">Filtrar cobradores:</label>

                            <select class="form-control input-sm sel2Multi" name="filter_collectors[]" id="filter_collectors[]" multiple>';

                              $filtercollectors = self::$con->prepare("SELECT collector_code, collector_name from ".self::$user_database.".collectors");
                              $filtercollectors->execute();
                              $filtercollectors = $filtercollectors->fetchAll(\PDO::FETCH_ASSOC);

                              foreach ($filtercollectors as $row) {

                                echo '<option value="'.$row['collector_code'].'">'.$row['collector_code'].' - '.$row['collector_name'].'</option>';

                              }
                              

                              echo '</select>

                            </div>

                          </div>

                          <div class="row">

                            <div class="form-group col-md-5 col-md-offset-1">

                              <label for="filter_routes">Filtrar rutas:</label>

                              <select class="form-control input-sm sel2Multi" name="filter_routes[]" id="filter_routes[]" multiple>';

                                $filterroutes = self::$con->prepare("SELECT route_code, route_name from ".self::$user_database.".routes");
                                $filterroutes->execute();
                                $filterroutes = $filterroutes->fetchAll(\PDO::FETCH_ASSOC);

                                foreach ($filterroutes as $row) {

                                  echo '<option value="'.$row['route_code'].'">'.$row['route_code'].' - '.$row['route_name'].'</option>';

                                }


                                echo '</select>

                              </div>

                              <div class="form-group col-md-5 col-md-offset-1">

                                <label for="filter_sellers">Filtrar vendedores:</label>

                                <select class="form-control input-sm sel2Multi" name="filter_sellers[]" id="filter_sellers[]" multiple>';

                                  $filtersellers = self::$con->prepare("SELECT seller_code, seller_name from ".self::$user_database.".sellers");
                                  $filtersellers->execute();
                                  $filtersellers = $filtersellers->fetchAll(\PDO::FETCH_ASSOC);

                                  foreach ($filtersellers as $row) {

                                    echo '<option value="'.$row['seller_code'].'">'.$row['seller_code'].' - '.$row['seller_name'].'</option>';

                                  }


                                  echo '</select>

                                </div>

                              </div>

                            </div>
                          </div>
                        </div>
                      </form>';

                    }


                    public function CreateGeneral()

                    {

                      $this->CreateUserAccount();
                      $this->CreateUserCompanies();
                      $this->CreateUserFilters();
                      $this->AddUserSerial();
                      $this->ShowInfo();

                    }

                    public function CreateUserAccount()

                    {

                      if (!isset($_POST['user_sync_customer']))
                        $_POST['user_sync_customer'] = '0';

                      if (!isset($_POST['user_sync_income']))
                        $_POST['user_sync_income'] = '0';

                      if (!isset($_POST['user_sync_order']))
                        $_POST['user_sync_order'] = '0';

                      if (!isset($_POST['user_sync_invoice']))
                        $_POST['user_sync_invoice'] = '0';

                      if (!isset($_POST['user_sync_visit']))
                        $_POST['user_sync_visit'] = '0';

                      $date = date('Y-m-d H:i:s');

                      $user_password = md5($_POST['user_password']);

                      self::$con->beginTransaction();

                      try {
                        
                      $createuseraccountsql = self::$con->prepare("
                        INSERT INTO ".self::$user_database.".users_accounts
                        (
                        user_name,
                        user_nickname,
                        user_email,
                        user_password,
                        user_created_at,
                        user_bluetooh_address,
                        user_printer,
                        user_generate_ncf,
                        user_sync_customer,
                        user_sync_income,
                        user_sync_order,
                        user_sync_invoice,
                        user_sync_visit,
                        user_sync_location_time,
                        location_distance,
                        user_not_require_active_gps,
                        minimum_amount_order, 
                        user_related_imei,
                        ) 
                        VALUES
                        (
                        :user_name,
                        :user_nickname,
                        :user_email,
                        :user_password,
                        :user_created_at,
                        :user_bluetooh_address,
                        :user_printer,
                        :user_generate_ncf,
                        :user_sync_customer,
                        :user_sync_income,
                        :user_sync_order,
                        :user_sync_invoice,
                        :user_sync_visit,
                        :user_sync_location_time,
                        :location_distance,
                        :user_not_require_active_gps,
                        :minimum_amount_order,
                        :user_related_imei
                        )
                        ");
                        $createuseraccountsql->bindParam(':minimum_amount_order', $_POST['minimum_amount_order']);
                      $createuseraccountsql->bindParam(':user_name', $_POST['user_name']);
                      $createuseraccountsql->bindParam(':user_nickname', $_POST['user_nickname']);
                      $createuseraccountsql->bindParam(':user_email', $_POST['user_email']);
                      $createuseraccountsql->bindParam(':user_password', $user_password);
                      $createuseraccountsql->bindParam(':user_created_at', $date);
                      $createuseraccountsql->bindParam(':user_bluetooh_address', $_POST['user_bluetooh_address']);
                      $createuseraccountsql->bindParam(':user_printer', $_POST['user_printer']);
                      $createuseraccountsql->bindParam(':user_generate_ncf', $_POST['user_generate_ncf']);
                      $createuseraccountsql->bindParam(':user_sync_customer', $_POST['user_sync_customer']);
                      $createuseraccountsql->bindParam(':user_sync_income', $_POST['user_sync_income']);
                      $createuseraccountsql->bindParam(':user_sync_order', $_POST['user_sync_order']);
                      $createuseraccountsql->bindParam(':user_sync_invoice', $_POST['user_sync_invoice']);
                      $createuseraccountsql->bindParam(':user_sync_visit', $_POST['user_sync_visit']);
                      $createuseraccountsql->bindParam(':user_sync_location_time', $_POST['user_sync_location_time']);
                      $createuseraccountsql->bindParam(':location_distance', $_POST['location_distance']);
                      $createuseraccountsql->bindParam(':user_not_require_active_gps', $_POST['user_not_require_active_gps']);

                      $createuseraccountsql->bindParam(':user_related_imei', $_POST['user_related_imei']);
                      $createuseraccountsql->execute();

                      self::$con->commit();

                      } catch (\Exception $e) {
                        
                      echo "Hubo un error al procesar la solicitud: ". $e->getMessage();

                      self::$con->rollBack();
                      
                      return false;

                      }

                      self::$user_id = self::$con->lastInsertId(); 

                      return self::$user_id;

                    }

                    public function CreateUserCompanies()

                    {
                        self::$con->beginTransaction();
                      try {
                          (isset($_POST['collector_code']) ? $collector_code = $_POST['collector_code'] : $collector_code = "");

                          $createusercompaniessql = self::$con->prepare("
                        INSERT INTO ".self::$user_database.".users_companies
                        (
                        company_id,
                        cost_center_code,
                        seller_code,
                        user_id,
                        warehouse_code,
                        created_at,
                        required_bar_code,
                        box_code,
                        warehouse_billing_code,
                        ncf_type_default,
                        not_controlled,
                        collector_code
                        ) 
                        VALUES
                        (
                        :company_id,
                        :cost_center_code,
                        :seller_code,
                        :user_id,
                        :warehouse_code,
                        :created_at,
                        :required_bar_code,
                        :box_code,
                        :warehouse_billing_code,
                        :ncf_type_default,
                        :not_controlled,
                        :collector_code
                        )
                        ");
                          
                          $createusercompaniessql->bindParam(':company_id', $_POST['company_id']);
                          $createusercompaniessql->bindParam(':cost_center_code', $_POST['cost_center_code']);
                          $createusercompaniessql->bindParam(':seller_code', $_POST['seller_code']);
                          $createusercompaniessql->bindParam(':user_id', self::$user_id);
                          $createusercompaniessql->bindParam(':warehouse_code', $_POST['warehouse_code']);
                          $createusercompaniessql->bindParam(':created_at', $_POST['created_at']);
                          $createusercompaniessql->bindParam(':required_bar_code', $_POST['required_bar_code']);
                          $createusercompaniessql->bindParam(':box_code', $_POST['box_code']);
                          $createusercompaniessql->bindParam(':warehouse_billing_code', $_POST['warehouse_billing_code']);
                          $createusercompaniessql->bindParam(':ncf_type_default', $_POST['ncf_type_default']);
                          $createusercompaniessql->bindParam(':not_controlled', $_POST['not_controlled']);
                          $createusercompaniessql->bindParam(':collector_code', $collector_code);
                          $createusercompaniessql->execute();
                          self::$con->commit();

                      } catch (\Exception $e) {
                          echo "Hubo un error al procesar la solicitud: ". $e->getMessage();

                          self::$con->rollBack();

                          return false;

                      }


                    }

                    public function CreateUserFilters()

                    {
                        self::$con->beginTransaction();
                        try {
                            if (isset($_POST["filter_areas"]))
                                $arrayareas = "'".implode("','", array_map('trim', $_POST["filter_areas"]))."'";
                            else
                                $arrayareas = "";

                            if (isset($_POST['filter_collectors']))
                                $arraycollectors = "'".implode("','", array_map('trim', $_POST["filter_collectors"]))."'";
                            else
                                $arraycollectors = "";

                            if (isset($_POST['filter_routes']))
                                $arrayroutes = "'".implode("','", array_map('trim', $_POST["filter_routes"]))."'";
                            else
                                $arrayroutes = "";

                            if (isset($_POST['filter_sellers']))
                                $arraysellers = "'".implode("','", array_map('trim', $_POST["filter_sellers"]))."'";
                            else
                                $arraysellers = "";

                            $createuserfilterssql = self::$con->prepare("
                        INSERT INTO ".self::$user_database.".users_filters
                        (
                        areas,
                        collectors,
                        routes,
                        sellers,
                        user_id,
                        company_id
                        ) 
                        VALUES
                        (
                        :filter_areas,
                        :filter_collectors,
                        :filter_routes,
                        :filter_sellers,
                        :user_id,
                        :company_id
                        )
                        ");

                            $createuserfilterssql->bindParam(':filter_areas', $arrayareas);
                            $createuserfilterssql->bindParam(':filter_collectors', $arraycollectors);
                            $createuserfilterssql->bindParam(':filter_routes', $arrayroutes);
                            $createuserfilterssql->bindParam(':filter_sellers', $arraysellers);
                            $createuserfilterssql->bindParam(':user_id', self::$user_id);
                            $createuserfilterssql->bindParam(':company_id', $_POST['company_id']);
                            $createuserfilterssql->execute();
                            self::$con->commit();
                        } catch (\Exception $e){
                            echo "Hubo un error al procesar la solicitud: ". $e->getMessage();

                            self::$con->rollBack();

                            return false;
                        }

                    }

                    public function AddUserSerial()

                    {
                        self::$con->beginTransaction();
                        try {
                            $adduserserialsql = self::$con->prepare("UPDATE
                        ".self::$user_database.".serial_numbers SET
                        user_related_imei = :user_related_imei,
                        user_id = :user_id
                        WHERE
                        user_serial_number = :user_serial_number
                        ");

                            $adduserserialsql->bindParam(':user_id', self::$user_id);
                            $adduserserialsql->bindParam(':user_related_imei', $_POST['user_related_imei']);
                            $adduserserialsql->bindParam(':user_serial_number', $_POST['user_serial_number']);
                            $adduserserialsql->execute();

                            self::$con->commit();
                        }catch (\Exception $e) {
                            echo "Hubo un error al procesar la solicitud: ". $e->getMessage();

                            self::$con->rollBack();

                            return false;
                        }


                    }

                    public function ShowInfo()

                    {

                     echo '<div class="text-center vert-offset-top-2"><p>
                     El usuario ha sido creado satisfactoriamente! <br>
                     El usuario es: '.$_POST['user_name'].'<br>
                     La contraseña es: '.$_POST['user_password'].'<br>
                     El serial asignado es: <b>'. $_POST['user_serial_number'].'</b><br>
                     El código QR es <img src="http://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='. $_POST['user_serial_number'].'&choe=UTF-8" width="150px" height="150px"></p></div>';

                   }

                 }


                 if (isset($_POST['form_id'])){


                   if ($_POST['form_id'] == 'editoptions') {

                    $editoptions = new Users;
                    $editoptions->EditUserOptions();

                  }

                  if ($_POST['form_id'] == 'submitedit') {

                    $editoptions = new Users;
                    $editoptions->EditGeneral();

                  }

                  if ($_POST['form_id'] == 'createoptions') {

                    $createoptions = new Users;
                    $createoptions->CreateList();

                  }

                  if ($_POST['form_id'] == 'submitcreate') {
                    $createoptions = new Users;
                    $createoptions->CreateGeneral();
                  }

                }

                ?>
