<?php
/*
	@Author: Williams Martinez
	Required files for login, credentials and general functions

*/

use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
use App\Legacy\Credentials;
use App\Legacy\Settings;
include('config/datetime_parameters.php');


$general_functions = new General;
/*

	Structure and stuff.

*/
$credentials_obj = new Credentials;

$credentials_json = $credentials_obj->SelectedCompanyCredentials();

$credentials = json_decode($credentials_json);

if($credentials[0]->registers_settings == 0)
    header('Location: dashboard');


/*

	Areas list

*/

$settings_ = new Settings;

$settings = $settings_->GetSettings();
$modules = $settings_->getModuleSettings();
$moduleConf = [
    "MREC" => 0,
    "MPED" => 0,
    "MFAC" => 0,


];

foreach ($modules as $module){

    if($module['module_code'] == "MREC"){
        $moduleConf['MREC'] =$module['visible'];
    }elseif($module['module_code'] == "MPED"){
        $moduleConf["MPED"] =$module['visible'];
    }elseif($module['module_code'] == "MFAC"){
        $moduleConf["MFAC"] =$module['visible'];
    }


}


/*
  Shows document header
  @include('config/structure/header.php')
  must be included for this function to run

*/

$header = new Header;
$header->Initialize(0, "Configuración general");

/*
  Shows navigation menu
  @include('config/structure/navigation_menu.php')
  must be included for this function to run

*/
$navigation = new Navigation;
$navigation->Initialize();

?>
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Inicio</a></li>
                    <li class="active"><span>Configuración general</span></li>
                </ol>

                <h1>Configuración general</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body clearfix" style="padding: 25px;">
                        <div class="tabs-wrapper">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
                                <?php  if($moduleConf['MPED'] == 1){

                                ?>
                                <li><a href="#tab-pedidos" data-toggle="tab">Pedidos</a></li>
                                <?php }
                                if($moduleConf['MFAC'] == 1){

                                ?>
                                <li><a href="#tab-facturacionranchera" data-toggle="tab">Facturación Ranchera</a></li>
                                <?php }
                                if($moduleConf['MREC'] == 1){

                                ?>
                                <li><a href="#tab-recibos" data-toggle="tab">Recibos</a></li>
                                <?php }


                                ?>
                            </ul>

                            <form action="" onsubmit="event.preventDefault();"
                                  method="POST" id="settingsedit">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="tab-general">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="setting_submit" id="setting_submit" value="1" />

                                                    <?php
                                                    $condition = function ($var)
                                                    {
                                                        if ($var == 1)
                                                            $var = 'checked';
                                                        else
                                                            $var = '';
                                                        return $var;

                                                    };

                                                    // Array para saber si la opcion es 1 o 0
                                                    if (!empty($settings))
                                                        $settingsarray = array_map($condition, $settings[0]);

                                                    // Array para ver los valores de los campos
                                                    if (!empty($settings))
                                                        $settingsarray2 = $settings[0];

                                                    ?>

                                                    <?php if (isset($settingsarray['add_product_with_click'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="add_product_with_click" id="add_product_with_click" value="1" <?php echo $settingsarray['add_product_with_click']; ?>>
                                                            <label for="add_product_with_click">Agregar productos con un click.</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['add_orders_with_click'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="add_orders_with_click" id="add_orders_with_click" value="1" <?php echo $settingsarray['add_orders_with_click']; ?>>
                                                            <label for="add_orders_with_click">Agregar pedidos con un click.</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['require_active_gps'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="require_active_gps" id="require_active_gps" value="1" <?php echo $settingsarray['require_active_gps']; ?>>
                                                            <label for="require_active_gps">Requerir GPS activo</label>

                                                        </div>


                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['advances_classification_by_customer'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="advances_classification_by_customer" id="advances_classification_by_customer" value="1" <?php echo $settingsarray['advances_classification_by_customer']; ?>>
                                                            <label for="advances_classification_by_customer">Recibo de adelanto por compañía de cliente</label>

                                                        </div>


                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['compact_product_list'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="compact_product_list" id="compact_product_list" value="1" <?php echo $settingsarray['compact_product_list']; ?>>
                                                            <label for="compact_product_list">Lista de productos compacta</label>

                                                        </div>

                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['remember_product_search'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="remember_product_search" id="remember_product_search" value="1" <?php echo $settingsarray['remember_product_search']; ?>>
                                                            <label for="remember_product_search">Recordar ultima busqueda de productos</label>

                                                        </div>

                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['remember_client_search'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="remember_client_search" id="remember_client_search" value="1" <?php echo $settingsarray['remember_client_search']; ?>>
                                                            <label for="remember_client_search">Recordar ultima busqueda de clientes</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['remember_selected_customer'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="remember_selected_customer" id="remember_selected_customer" value="1" <?php echo $settingsarray['remember_selected_customer']; ?>>
                                                            <label for="remember_selected_customer">Recordar ultima selección de clientes</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['tax_is_included'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="tax_is_included" id="tax_is_included" value="1" <?php echo $settingsarray['tax_is_included']; ?>>
                                                            <label for="tax_is_included">Mostrar los precios con el ITBIS incluido</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['show_only_customers_of_the_day'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="show_only_customers_of_the_day" id="show_only_customers_of_the_day" value="1" <?php echo $settingsarray['show_only_customers_of_the_day']; ?>>
                                                            <label for="show_only_customers_of_the_day">Mostrar solo clientes del dia</label>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['control_location_vendor'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="control_location_vendor" id="control_location_vendor" value="1" <?php echo $settingsarray['control_location_vendor']; ?>>
                                                            <label for="control_location_vendor">Controlar localizacion del vendedor</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['show_img_products'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="show_img_products" id="show_img_products" value="1" <?php echo $settingsarray['show_img_products']; ?>>
                                                            <label for="show_img_products">Mostrar imágenes de productos </label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['sync_transaction_completed'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="sync_transaction_completed" id="sync_transaction_completed" value="1" <?php echo $settingsarray['sync_transaction_completed']; ?>>
                                                            <label for="sync_transaction_completed">Sincronizar al finalizar la transacción</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['show_product_existence'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="show_product_existence" id="show_product_existence" value="1" <?php echo $settingsarray['show_product_existence']; ?>>
                                                            <label for="show_product_existence">Mostrar existencia de productos</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['print_transaction_completed'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="print_transaction_completed" id="print_transaction_completed" value="1" <?php echo $settingsarray['print_transaction_completed']; ?>>
                                                            <label for="print_transaction_completed">Imprimir al finalizar transacción</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['show_product_without_existence'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="show_product_without_existence" id="show_product_without_existence" value="1" <?php echo $settingsarray['show_product_without_existence']; ?>>
                                                            <label for="show_product_without_existence">Mostrar productos sin existencia</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['control_reprint'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="control_reprint" id="control_reprint" value="1" <?php echo $settingsarray['control_reprint']; ?>>
                                                            <label for="control_reprint">Controlar reimpresión</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['send_information_periodically'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="send_information_periodically" id="send_information_periodically" value="1" <?php echo $settingsarray['send_information_periodically']; ?>>
                                                            <label for="send_information_periodically">Enviar informacion periodicamente</label>

                                                        </div>
                                                    <?php } ?>
                                                    <?php if (isset($settingsarray['sync_lock'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="sync_lock" id="sync_lock" value="1" <?php echo $settingsarray['sync_lock']; ?>>
                                                            <label for="sync_lock">Bloqueo por sincronizacion</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['use_default_return_types'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="use_default_return_types" id="use_default_return_types" value="1" <?php echo $settingsarray['use_default_return_types']; ?>>
                                                            <label for="use_default_return_types">Usar tipos de devoluciones predeterminadas</label>

                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>


                                            <?php if (!empty($settingsarray2)) { ?>
                                                <div class="col-md-4">

                                                    <div class="form-group col-md-7">

                                                    </div>

                                                    <?php if (isset($settingsarray['returned_check_doc_type'])) { ?>
                                                    <div class="form-group col-md-7">
                                                        <label for="returned_check_doc_type">Tipo de documento de devoluciones </label>
                                                        <input class="form-control input-sm" value="<?= $settingsarray2['returned_check_doc_type']?>"
                                                               id="returned_check_doc_type" name="returned_check_doc_type">

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['password'])) { ?>
                                                    <div class="form-group col-md-7">

                                                        <label for="password">Contraseña para configurar los dispositivos:</label>
                                                        <input class="form-control input-sm" type="text" value="<?= $settingsarray2['password']?>" id="password" name="password">

                                                    </div>
                                                    <?php } ?>

                                                     <?php if (isset($settingsarray['sync_location_time_by_user'])) { ?>
                                                    <div class="form-group col-md-7">

                                                        <label for="sync_location_time_by_user">Controlar tiempo de la localizacion:</label>
                                                        <select name="sync_location_time_by_user" class="form-control input-sm">
                                                            <option value="2"<?php if ($settingsarray2['sync_location_time_by_user'] == 2)
                                                                echo 'selected'; ?>>Por usuarios</option>
                                                            <option value="1"<?php if ($settingsarray2['sync_location_time_by_user'] == 1)
                                                                echo 'selected'; ?>>General</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['user_sync_location_time'])) { ?>
                                                    <div class="form-group col-md-2">

                                                        <label for="user_sync_location_time">Minutos:</label>

                                                        <input class="form-control input-sm" style="width: 70px" type="number" value="<?php echo $settingsarray2['user_sync_location_time']; ?>" id="user_sync_location_time" name="user_sync_location_time">

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['use_distance_by_user'])) { ?>
                                                    <div class="form-group col-md-7">

                                                        <label for="use_distance_by_user">Controlar distancia de la localizacion:</label>
                                                        <select name="use_distance_by_user" class="form-control input-sm">
                                                            <option value="2"<?php if ($settingsarray2['use_distance_by_user'] == 2)
                                                                echo 'selected'; ?>>Por usuarios</option>
                                                            <option value="1"<?php if ($settingsarray2['use_distance_by_user'] == 1)
                                                                echo 'selected'; ?>>General</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['distance_between_location'])) { ?>
                                                    <div class="form-group col-md-2">

                                                        <label for="distance_between_location">Metros:</label>

                                                        <input class="form-control input-sm" style="width: 70px" type="number" value="<?php echo $settingsarray2['distance_between_location']; ?>" id="distance_between_location" name="distance_between_location">

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['show_existence'])) { ?>
                                                    <div class="form-group col-md-7">

                                                        <label for="show_existence">Mostrar existencia de los productos:</label>
                                                        <select name="show_existence" class="form-control input-sm">
                                                            <option value="1"<?php if ($settingsarray2['show_existence'] == 1)
                                                                echo 'selected'; ?>>Pedidos</option>
                                                            <option value="2"<?php if ($settingsarray2['show_existence'] == 2)
                                                                echo 'selected'; ?>>Facturacion Ranchera</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['print_copies'])) { ?>
                                                    <div class="form-group col-md-7">
                                                        <label for="print_copies">Cantidad de copias por defecto:</label>
                                                        <input class="form-control input-sm" type="text" value="<?php echo $settingsarray2['print_copies']; ?>" id="print_copies" name="print_copies">

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['bank_deposits_start_date'])) { ?>
                                                        <div class="form-group col-md-7">
                                                            <label for="bank_deposits_start_date">Fecha de inicio de depositos bancarios:</label>
                                                            <input class="form-control input-sm" type="date"
                                                                   value="<?php echo $settingsarray2['bank_deposits_start_date']; ?>" id="bank_deposits_start_date" name="bank_deposits_start_date">

                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } else { ?>
                                                <span>No hay configuraciones para la compañía actual.</span>
                                            <?php } ?>
                                        </div>
                                    </div>


                                    <div class="tab-pane fade" id="tab-pedidos">
                                        <?php if (!empty($settingsarray)) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <?php if (isset($settingsarray['order_boxes'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="order_boxes" id="order_boxes" value="1" <?php echo $settingsarray['order_boxes']; ?>>
                                                            <label for="order_boxes">Permitir uso de la unidad "cajas" en los pedidos</label>
                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['control_minimum_amount_order'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="control_minimum_amount_order" id="control_minimum_amount_order" value="1" <?php echo $settingsarray['control_minimum_amount_order']; ?>>
                                                            <label for="control_minimum_amount_order">Controlar monto mínimo</label>
                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['minimum_amount_order_general'])) { ?>
                                                        <div style="margin-left: 40px" class="form-group">
                                                            <div class="radio">
                                                                <input type="radio" name="minimum_amount_order_general" id="minimum_amount_order_general" value="1" <?php echo $settingsarray['minimum_amount_order_general'] ?> >
                                                                <label for="minimum_amount_order_general">
                                                                    General
                                                                </label>
                                                            </div>
                                                            <?php } ?>

                                                            <?php if (isset($settingsarray['minimum_amount_order_by_user'])) { ?>
                                                            <div class="radio">
                                                                <input type="radio" name="minimum_amount_order_by_user" id="minimum_amount_order_by_user" value="1" <?php echo $settingsarray['minimum_amount_order_by_user'] ?>>
                                                                <label for="minimum_amount_order_by_user">
                                                                    Por usuario
                                                                </label>
                                                            </div>
                                                            <?php } ?>

                                                            <?php if (isset($settingsarray['minimum_amount_order'])) { ?>
                                                            <label for="minimum_amount_order">Monto minimo</label>
                                                            <input class="form-control" type="text"  value="<?php echo $settingsarray2['minimum_amount_order'] ?>" id="minimum_amount_order" name="minimum_amount_order" style="width: 100px" >
                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['allow_price_changes_in_orders'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="allow_price_changes_in_orders" id="allow_price_changes_in_orders" value="1" <?php echo $settingsarray['allow_price_changes_in_orders']; ?>>
                                                            <label for="allow_price_changes_in_orders">Permitir cambiar precio</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['quick_order'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="quick_order" id="quick_order" value="1" <?php echo $settingsarray['quick_order']; ?>>

                                                            <label for="quick_order">Pedido rapido</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['control_existence_orders'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="control_existence_orders" id="control_existence_orders" value="1" <?php echo $settingsarray['control_existence_orders']; ?>>
                                                            <label for="control_existence_orders">Controlar existencia de los productos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['allow_discount_on_orders'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="allow_discount_on_orders" id="allow_discount_on_orders" value="1" <?php echo $settingsarray['allow_discount_on_orders']; ?>>
                                                            <label for="allow_discount_on_orders">Permitir descuento</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['allow_change_order_type'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="allow_change_order_type" id="allow_change_order_type" value="1" <?php echo $settingsarray['allow_change_order_type']; ?>>
                                                            <label for="allow_change_order_type">Permitir cambiar tipo de orden</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['apply_automatic_discount_on_orders'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="apply_automatic_discount_on_orders" id="apply_automatic_discount_on_orders" value="1" <?php echo $settingsarray['apply_automatic_discount_on_orders']; ?>>
                                                            <label for="apply_automatic_discount_on_orders">Aplicar descuento automatico</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['print_orders'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="print_orders" id="print_orders" value="1" <?php echo $settingsarray['print_orders']; ?>>
                                                            <label for="print_orders">Imprimir pedidos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['not_allow_delete_orders'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="not_allow_delete_orders" id="not_allow_delete_orders"
                                                                   value="1" <?php echo $settingsarray['not_allow_delete_orders']; ?>>
                                                            <label for="not_allow_delete_orders">No permitir eliminar pedidos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['geolocation_require_in_order'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="geolocation_require_in_order" id="geolocation_require_in_order" value="1" <?php echo $settingsarray['geolocation_require_in_order']; ?>>
                                                            <label for="geolocation_require_in_order">Requerir geolocalizacion</label>

                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">

                                                    <?php if (isset($settingsarray['origin_discount_orders'])) { ?>
                                                    <div class="form-group">

                                                        <label for="origin_discount_orders">Descuento a utilizar en los pedidos:</label>
                                                        <select name="origin_discount_orders" class="form-control input-sm">
                                                            <option value="1" <?php if ($settingsarray2['origin_discount_orders'] == 1)
                                                                echo 'selected'; ?>>Cliente</option>
                                                            <option value="2" <?php if ($settingsarray2['origin_discount_orders'] == 2)
                                                                echo 'selected'; ?>>Vendedor</option>
                                                            <option value="3" <?php if ($settingsarray2['origin_discount_orders'] == 3)
                                                                echo 'selected'; ?>>Productos</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['default_order_type'])) { ?>
                                                    <div class="form-group">

                                                        <label for="default_order_type">Tipo de pedido por defecto:</label>
                                                        <select name="default_order_type" class="form-control input-sm">
                                                            <option value="1" <?php if ($settingsarray2['default_order_type'] == 1)
                                                                echo 'selected'; ?>>Credito</option>
                                                            <option value="2" <?php if ($settingsarray2['default_order_type'] == 2)
                                                                echo 'selected'; ?>>Contado</option>
                                                            <option value="3" <?php if ($settingsarray2['default_order_type'] == 3)
                                                                echo 'selected'; ?>>Elegir en el pedido</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['type_product_prices_in_orders'])) { ?>
                                                    <div class="form-group">

                                                        <label for="type_product_prices_in_orders">Precio a utilizar en los pedidos:</label>

                                                        <select name="type_product_prices_in_orders" class="form-control input-sm">
                                                            <option value="1" <?php if ($settingsarray2['type_product_prices_in_orders'] == 1)
                                                                echo 'selected'; ?>>Por mayor</option>
                                                            <option value="2" <?php if ($settingsarray2['type_product_prices_in_orders'] == 2)
                                                                echo 'selected'; ?>>Contado 1</option>
                                                            <option value="3" <?php if ($settingsarray2['type_product_prices_in_orders'] == 3)
                                                                echo 'selected'; ?>>Contado 2</option>
                                                            <option value="4" <?php if ($settingsarray2['type_product_prices_in_orders'] == 4)
                                                                echo 'selected'; ?>>Contado 3</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['order_below_cost'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="order_below_cost" id="order_below_cost" value="1" <?php echo $settingsarray['order_below_cost']; ?>>
                                                            <label for="order_below_cost">Vender por debajo de costo</label>

                                                        </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['split_order_by_warehouse'])) { ?>
                                                    <div class="checkbox-nice">
                                                        <input type="checkbox" name="split_order_by_warehouse" id="split_order_by_warehouse" value="1" <?php echo $settingsarray['split_order_by_warehouse']; ?>>
                                                        <label for="split_order_by_warehouse">Dividir pedido por almacen</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['max_amount_by_credit_limited'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="max_amount_by_credit_limited" id="max_amount_by_credit_limited" value="1" <?php echo $settingsarray['max_amount_by_credit_limited']; ?>>
                                                        <label for="max_amount_by_credit_limited"> Monto máximo hasta límite de crédito</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['enable_price_selection'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="enable_price_selection" id="enable_price_selection" value="1" <?php echo $settingsarray['enable_price_selection']; ?>>
                                                        <label for="enable_price_selection"> Habilitar selección de precios</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['price_one'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="price_one" id="price_one" value="1" <?php echo $settingsarray['price_one']; ?>>
                                                        <label for="price_one">Precio uno</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['price_two'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="price_two" id="price_two" value="1" <?php echo $settingsarray['price_two']; ?>>
                                                        <label for="price_two">Precio dos</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['price_three'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="price_three" id="price_three" value="1" <?php echo $settingsarray['price_three']; ?>>
                                                        <label for="price_three">Precio tres</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['price_four'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="price_four" id="price_four" value="1" <?php echo $settingsarray['price_four']; ?>>
                                                        <label for="price_four">Precio cuatro</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['minimum_price'])) { ?>
                                                    <label style="margin-top: 10px" for="minimum_price">Precio mínimo</label>
                                                    <select name="minimum_price" class="form-control input-sm">
                                                        <option value="1" <?php if ($settingsarray2['minimum_price'] == 1)
                                                            echo 'selected'; ?>>Precio uno</option>
                                                        <option value="2" <?php if ($settingsarray2['minimum_price'] == 2)
                                                            echo 'selected'; ?>>Precio dos</option>
                                                        <option value="3" <?php if ($settingsarray2['minimum_price'] == 3)
                                                            echo 'selected'; ?>>Precio tres</option>
                                                        <option value="4" <?php if ($settingsarray2['minimum_price'] == 4)
                                                            echo 'selected'; ?>>Precio cuatro</option>
                                                    </select>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['minimum_price_with_discount'])) { ?>
                                                    <div style="margin-top: 10px" class="checkbox-nice">

                                                        <input type="checkbox" name="minimum_price_with_discount" id="minimum_price_with_discount" value="1" <?php echo $settingsarray['minimum_price_with_discount']; ?>>
                                                        <label for="minimum_price_with_discount">Precio mínimo con descuento aplicado</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['allow_discount_on_products'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="allow_discount_on_products" id="allow_discount_on_products" value="1" <?php echo $settingsarray['allow_discount_on_products']; ?>>
                                                        <label for="allow_discount_on_products">Descuento manual en productos</label>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['apply_discount_to_exempt_products'])) { ?>
                                                    <div class="checkbox-nice">

                                                        <input type="checkbox" name="apply_discount_to_exempt_products" id="apply_discount_to_exempt_products" value="1" <?php echo $settingsarray['apply_discount_to_exempt_products']; ?>>
                                                        <label for="apply_discount_to_exempt_products">Aplicar descuento a productos exentos</label>

                                                    </div>
                                                    <?php } ?>

                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>


                                    <div class="tab-pane fade" id="tab-recibos">
                                        <?php if (!empty($settingsarray)) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <?php if (isset($settingsarray['just_seller_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="just_seller_invoices" id="just_seller_invoices" value="<?php echo $settingsarray['just_seller_invoices']; ?>">
                                                            <label for="just_seller_invoices">Ver solo factura de vendedor</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['payment_by_oldest_invoice'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="payment_by_oldest_invoice" id="payment_by_oldest_invoice" value="1" <?php echo $settingsarray['payment_by_oldest_invoice']; ?>>
                                                            <label for="payment_by_oldest_invoice">Recibos por antiguedad de saldo</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['just_futuristic_check_in_payment'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="just_futuristic_check_in_payment" id="just_futuristic_check_in_payment" value="1" <?php echo $settingsarray['just_futuristic_check_in_payment']; ?>>
                                                            <label for="just_futuristic_check_in_payment">Solo cheque futurista en recibo</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['control_receipts_without_deposits'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="control_receipts_without_deposits" id="control_receipts_without_deposits" value="1" <?php echo $settingsarray['control_receipts_without_deposits']; ?>>
                                                            <label for="control_receipts_without_deposits">Controlar recibos sin depositos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['show_customer_balance_in_print'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="show_customer_balance_in_print" id="show_customer_balance_in_print" value="1" <?php echo $settingsarray['show_customer_balance_in_print']; ?>>
                                                            <label for="show_customer_balance_in_print">Mostrar balance de cliente en impresion</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['discount_on_receipts'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="discount_on_receipts" id="discount_on_receipts" value="1" <?php echo $settingsarray['discount_on_receipts']; ?>>
                                                            <label for="discount_on_receipts">Descuento en recibos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['max_discount_on_receipts'])) { ?>
                                                        <div style="margin-top: 20px" class="form-group col-md-7">

                                                            <label for="max_discount_on_receipts">Descuento máximo en recibos:</label>
                                                            <input class="form-control input-sm" type="text" value="<?php echo $settingsarray2['max_discount_on_receipts']; ?>" id="max_discount_on_receipts" name="max_discount_on_receipts">

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['discount_to_use_on_receipts'])) { ?>
                                                        <div class="form-group col-md-7">

                                                            <label for="discount_to_use_on_receipts">Descuento a usar en recibos:</label>
                                                            <select name="discount_to_use_on_receipts" class="form-control input-sm">
                                                                <option value="1"<?php if ($settingsarray2['discount_to_use_on_receipts'] == 1)
                                                                    echo 'selected'; ?>>Cliente</option>
                                                                <option value="2"<?php if ($settingsarray2['discount_to_use_on_receipts'] == 2)
                                                                    echo 'selected'; ?>>Vendedor</option>
                                                                <option value="3"<?php if ($settingsarray2['discount_to_use_on_receipts'] == 3)
                                                                    echo 'selected'; ?>>Configurable</option>
                                                            </select>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['geolocation_require_in_receipts'])) { ?>
                                                        <div style="margin-top: 200px" class="checkbox-nice">

                                                            <input type="checkbox" name="geolocation_require_in_receipts" id="geolocation_require_in_receipts" value="1" <?php echo $settingsarray['geolocation_require_in_receipts']; ?>>
                                                            <label for="geolocation_require_in_receipts">Geolocalización requerida en recibos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['not_print_header_on_receipts'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="not_print_header_on_receipts" id="not_print_header_on_receipts" value="1"
                                                                <?php echo $settingsarray['not_print_header_on_receipts']; ?>>
                                                            <label for="not_print_header_on_receipts">No imprimir encabezado en recibos</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['discount_just_on_not_expired_invoices'])) { ?>
                                                            <div class="checkbox-nice">

                                                                <input type="checkbox" name="discount_just_on_not_expired_invoices" id="discount_just_on_not_expired_invoices" value="1" <?php echo $settingsarray['discount_just_on_not_expired_invoices']; ?>>
                                                                <label for="discount_just_on_not_expired_invoices">Descuento solo a facturas no vencidas</label>

                                                            </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['not_allow_discount_on_returned_check'])) { ?>
                                                            <div class="checkbox-nice">

                                                                <input type="checkbox" name="not_allow_discount_on_returned_check" id="not_allow_discount_on_returned_check" value="1" <?php echo $settingsarray['not_allow_discount_on_returned_check']; ?>>
                                                                <label for="not_allow_discount_on_returned_check">No permitir descuento en cheques devueltos</label>

                                                            </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['apply_automatic_discount_on_receipts'])) { ?>
                                                            <div class="checkbox-nice">

                                                                <input type="checkbox" name="apply_automatic_discount_on_receipts" id="apply_automatic_discount_on_receipts" value="1" <?php echo $settingsarray['apply_automatic_discount_on_receipts']; ?>>
                                                                <label for="apply_automatic_discount_on_receipts">Aplicar descuento automático en recibos</label>

                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>


                                    <div class="tab-pane fade" id="tab-facturacionranchera">
                                        <?php if (!empty($settingsarray)) { ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <?php if (isset($settingsarray['invoice_boxes'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="invoice_boxes" id="invoice_boxes" value="1" <?php echo $settingsarray['invoice_boxes']; ?>>
                                                            <label for="invoice_boxes">Permitir el uso de la unidad "cajas" en la factura</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['allow_price_changes_on_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="allow_price_changes_on_invoices" id="allow_price_changes_on_invoices" value="1" <?php echo $settingsarray['allow_price_changes_on_invoices']; ?>>
                                                            <label for="allow_price_changes_on_invoices">Permitir cambiar el precio</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['quick_bill'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="quick_bill" id="quick_bill" value="1" <?php echo $settingsarray['quick_bill']; ?>>
                                                            <label for="quick_bill">Factura Ranchera rapida</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['allow_discount_on_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="allow_discount_on_invoices" id="allow_discount_on_invoices" value="1" <?php echo $settingsarray['allow_discount_on_invoices']; ?>>
                                                            <label for="allow_discount_on_invoices">Permitir descuento</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['apply_automatic_discount_on_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="apply_automatic_discount_on_invoices" id="apply_automatic_discount_on_invoices" value="1" <?php echo $settingsarray['apply_automatic_discount_on_invoices']; ?>>
                                                            <label for="apply_automatic_discount_on_invoices">Aplicar descuento automatico</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['print_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="print_invoices" id="print_invoices" value="1" <?php echo $settingsarray['print_invoices']; ?>>
                                                            <label for="print_invoices">Imprimir facturas</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['print_invoices_automatically'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="print_invoices_automatically" id="print_invoices_automatically" value="1" <?php echo $settingsarray['print_invoices_automatically']; ?>>
                                                            <label for="print_invoices_automatically">Imprimir factura ranchera automaticamente</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['reprint_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="reprint_invoices" id="reprint_invoices" value="1" <?php echo $settingsarray['reprint_invoices']; ?>>
                                                            <label for="reprint_invoices">Re-Imprimir Factura Ranchera</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['cancel_bills'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="cancel_bills" id="cancel_bills" value="1" <?php echo $settingsarray['cancel_bills']; ?>>
                                                            <label for="cancel_bills">Permitir Anular Factura Ranchera</label>

                                                        </div>
                                                        <?php } ?>

                                                        <?php if (isset($settingsarray['control_existence_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="control_existence_invoices" id="control_existence_invoices" value="1" <?php echo $settingsarray['control_existence_invoices']; ?>>
                                                            <label for="control_existence_invoices">Controlar existencia de los productos</label>

                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <?php if (isset($settingsarray['geolocation_require_invoices'])) { ?>
                                                        <div class="checkbox-nice">

                                                            <input type="checkbox" name="geolocation_require_invoices" id="geolocation_require_invoices" value="1" <?php echo $settingsarray['geolocation_require_invoices']; ?>>
                                                            <label for="geolocation_require_invoices">Requerir geolocalizacion</label>

                                                        </div>
                                                        <?php } ?>

                                                    </div>

                                                    <?php if (isset($settingsarray['origin_discount_bills'])) { ?>
                                                    <div class="form-group">

                                                        <label for="origin_discount_bills">Descuento a utilizar en la factura:</label>

                                                        <select name="origin_discount_bills" class="form-control input-sm">
                                                            <option value="1" <?php if ($settingsarray2['origin_discount_bills'] == 1)
                                                                echo 'selected'; ?>>Cliente</option>
                                                            <option value="2" <?php if ($settingsarray2['origin_discount_bills'] == 2)
                                                                echo 'selected'; ?>>Vendedor</option>
                                                            <option value="3" <?php if ($settingsarray2['origin_discount_bills'] == 3)
                                                                echo 'selected'; ?>>Productos</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['default_type_invoice'])) { ?>
                                                    <div class="form-group">

                                                        <label for="default_type_invoice">Tipo de factura por defecto:</label>

                                                        <select name="default_type_invoice" class="form-control input-sm">
                                                            <option value="1" <?php if ($settingsarray2['default_type_invoice'] == 1)
                                                                echo 'selected'; ?>>Credito</option>
                                                            <option value="2" <?php if ($settingsarray2['default_type_invoice'] == 2)
                                                                echo 'selected'; ?>>Contado</option>
                                                            <option value="3" <?php if ($settingsarray2['default_type_invoice'] == 3)
                                                                echo 'selected'; ?>>Elegir en la factura</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>

                                                    <?php if (isset($settingsarray['type_product_prices_on_invoices'])) { ?>
                                                    <div class="form-group">

                                                        <label for="type_product_prices_on_invoices">Precio a utilizar en las facturas:</label>

                                                        <select name="type_product_prices_on_invoices" class="form-control input-sm">
                                                            <option value="1" <?php if ($settingsarray2['type_product_prices_on_invoices'] == 1)
                                                                echo 'selected'; ?>>Por mayor</option>
                                                            <option value="2" <?php if ($settingsarray2['type_product_prices_on_invoices'] == 2)
                                                                echo 'selected'; ?>>Contado 1</option>
                                                            <option value="3" <?php if ($settingsarray2['type_product_prices_on_invoices'] == 3)
                                                                echo 'selected'; ?>>Contado 2</option>
                                                            <option value="4" <?php if ($settingsarray2['type_product_prices_on_invoices'] == 4)
                                                                echo 'selected'; ?>>Contado 3</option>
                                                        </select>

                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>


                                <?php if (!empty($settingsarray2)) { ?>
                                    <div class="form-group text-center">
                                        <input type="submit" value="Aceptar" class="btn btn-primary" id="submit">
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                        <div id="response"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

/*
	Shows document footer
	@include('config/structure/footer.php')
	must be included for this function to run

*/

Footer::Initialize();

/*
	Shows document scripts
	@include('config/structure/scripts.php')
	must be included for this function to run

*/

Scripts::Initialize();

?>


<script type="text/javascript">

  $('#settingsedit').on('submit', function()
  {
    $.ajax({

      method: 'POST',

      url: '/legacy/ajax/Settings',

      data: $('#settingsedit').serialize(),

      success: function(response){

        $('#response').html('<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>');

      },

      error: function(response){


        $('#response').html('<h2>Hubo un error al enviar los datos.</h2>');

      }
    });

  });

</script>
</body>
</html>