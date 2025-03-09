<?php

namespace App\Legacy;

class Navigation extends Credentials
{

    function __construct()

    {

        parent::__construct();

    }

    function Initialize(){

        $credentialsobject = json_decode($this->SelectedCompanyCredentials());

        $credentials = $credentialsobject[0];

        $header = new Header;

        echo '
			<div id="page-wrapper" class="container">
				<div class="row">
					<div id="nav-col">
						<section id="col-left" class="col-left-nano">
							<div id="col-left-inner" class="col-left-nano-content">
								<div id="user-left-box"  class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">';

//									$header->ProfileInfo("profile_picture");

        echo '<div class="user-box">
									<span class="name">';

        $header->ProfileInfo("first_name");

        echo '</span>
										<span class="status">
											<i style="color: white" class="fa fa-bank"></i> Compañía '; echo Credentials::$default_company;
        if (self::$isAdmin) {
            echo '<br><i style="color: white" class="fa fa-user fa-fw"></i>   Administrador';
        }
        echo '
										</span>
									</div>
								</div>';

        echo '<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
									<ul class="nav nav-pills nav-stacked">
										<li class="nav-header nav-header-first hidden-sm hidden-xs">
											Menu principal
										</li>';

        $this->Home();

        if ($credentials->authorization == 1)
            $this->Authorizations($credentials);


        $this->Devices();

        $this->QRGenerator();
//        if (\App\Common\CompatibilityChecker::PuedeVerModulosPorUsuario()) {
//            echo '    <li>
//                                <a href="/modules">
//                                    <i class="fa fa-cubes"></i>
//                                    <span>Módulos de Usuario</span>
//                                </a>
//                            </li>' ;
//        }



        if ($credentials->maps == 1)
            $this->Maps($credentials);

        if ($credentials->reports == 1)
            $this->Reports($credentials);

//										if ($credentials->registers == 1)
//                                            $this->Registers($credentials);


        if ($credentials->registers_settings == 1)

            echo '<li class="nav-header nav-header-first hidden-sm hidden-xs">
											Configuraciones
										</li>';
        if (count(array_values(json_decode($this->CredentialsCompanySql(), true))) > 1)
            $this->SelectCompany();
        echo'
                                              <li>
                <a href="#" class="dropdown-toggle">
				 <i class="fa fa-gears"></i>
					                                 <span>Configuraciones</span>
					<i class="fa fa-angle-right drop-icon"></i>
				</a>
				<ul class="submenu">\'
				
					                   <li>
						                <a href="settings">
						            	Configuración SDM
						        </a>
					      </li> 
					     ';


        if (self::$isAdmin) {
            echo'
					<li>
						<a href="adminsettings">
							Configuración Global
						</a>
					</li>';
        }

        if ($credentials->registers_users == 1)
            echo'
					<li>
						<a href="users">
							Usuarios
						</a>
					</li>';
        echo'</ul>
				</li>';
        ;



        echo '
									</ul>
								</div>
							</div>
						</section>
						<div id="nav-col-submenu"></div>
					</div>
					<div id="content-wrapper">';
    }


    static function Home()

    {
        echo '<li>
					<a href="/dashboard">
						<i class="fa fa-dashboard"></i>
						<span>Dashboard</span>
					</a>
				</li>';

    }
    static function QRGenerator(){
        echo '<li>
					<a href="qrgenerator">
						<i class="fa fa-qrcode"></i>
						<span>Generador QR</span>
					</a>
				</li>';
    }

    static function Maps($credentials)

    {
        echo '<li>
				<a href="#" class="dropdown-toggle">
					<i class="fa fa-map"></i>
					<span>Mapas</span>
					<i class="fa fa-angle-right drop-icon"></i>
				</a>
				<ul class="submenu">';

        if ($credentials->maps_customers == 1)
            echo '
					<li>
						<a href="customers_map">
							Clientes
						</a>
					</li>';


        if ($credentials->maps_sellers == 1)
            echo '
					<li>
						<a href="sellers_map">
							Vendedores
						</a>
					</li>';


        if ($credentials->maps_routes == 1){}
        echo '
					<li>
						<a href="routes_map">
							Rutas
						</a>
					</li>';

        echo '</ul>
				</li>';

    }

    static function Reports($credentials)
    {

        echo  '
 <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-line-chart"></i>
                                <span>Reportes</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu">
                             <li>
                                    <a href="/reports/activity">
                                        Actividades
                                    </a>
                                </li>
    <li>
                                    <a href="/reports/ventas">
                                        Ventas
                                    </a>
                                </li>
                                <li>
                                    <a href="/reports/cobros">
                                        Cobros
                                    </a>
                                </li>
                                 <li>
                                    <a href="/reports/devoluciones">
                                        Devoluciones
                                    </a>
                                </li>
                                <li>
                                    <a href="/reports/products">
                                        Ventas por productos
                                    </a>
                                </li>
                                 <li>
                                    <a href="/reports/cuentacobrar">
                                        Cuenta por Cobrar
                                    </a>
                                </li>
                                <li>
                                    <a href="/reports/cobrosadelanto">
                                        Cobros de Adelanto
                                    </a>
                                </li>
                                   <li>
                                    <a href="/reports/ventasduracion">
                                        Duración por Venta
                                    </a>
                                </li>
                               
                               
                                <li>
                                    <a href="/reports/depositos">
                                        Depositos de Factura
                                    </a>
                                </li>
                                
                                    <li>
                                    <a href="/reports/horastrabajadas">
                                        Horas Trabajadas
                                    </a>
                                </li>
                                
                              
                            </ul>
                        </li>
				
				
				
				';

    }



    static function Devices () {
        echo  '<li>
					<a href="userdevices">
						<i class="fa fa-tablet"></i>
						<span>Dispositivos</span>
					</a>
				</li>';
    }

    static function Authorizations($credentials){
        echo '<li>
				<a href="#" class="dropdown-toggle">
					<i class="fa fa-file-text"></i>
					<span>Autorizar Docs.</span>
					<i class="fa fa-angle-right drop-icon"></i>
				</a>
				<ul class="submenu">';

        if ($credentials->authorization_ri == 1)
            echo'
					<li>
						<a href="authri">
							Recibos
						</a>
					</li>';
        if ($credentials->authorization_ra == 1)
            echo'
					<li>
						<a href="authra">
							Recibo de adelanto
						</a>
					</li>';
        if ($credentials->authorization_dv == 1)
            echo'
					<li>
						<a href="authdv">
							Devoluciones
						</a>
					</li>';

        if ($credentials->authorization_df == 1)
            echo'
					<li>
						<a href="authdf">
							Facturas Depositadas
						</a>
					</li>';

//                if ($credentials->authorization_df == 1)
//                    echo'
//					<li>
//						<a href="authdf">
//							Deposito de factura
//						</a>
//					</li>';


        echo'</ul>
				</li>';

    }

    static function Registers($credentials)
    {
        echo '<li>
				<a href="#" class="dropdown-toggle">
					<i class="fa fa-wpforms"></i>
					<span>Registros</span>
					<i class="fa fa-angle-right drop-icon"></i>
				</a>
				<ul class="submenu">';


        if ($credentials->registers_warehouses == 1)
            echo'
					<li>
						<a href="warehouses">
							Almacenes
						</a>
					</li>';

        if ($credentials->registers_banks == 1)
            echo'
					<li>
						<a href="banks">
							Bancos
						</a>
					</li>';


        if ($credentials->registers_costcenters == 1)
            echo'
					<li>
						<a href="costcenters">
							Centros de Costo
						</a>
					</li>';



        if ($credentials->registers_cities)
            echo'
					<li>
						<a href="cities">
							Ciudades
						</a>
					</li>';

        if ($credentials->registers_collectors == 1)
            echo'
					<li>
						<a href="collectors">
							Cobradores
						</a>
					</li>';

        if ($credentials->registers_companies == 1)
            echo'
					<li>
						<a href="companies">
							Compañías
						</a>
					</li>';


        if ($credentials->registers_classifications)
            echo'
					<li>
						<a href="classifications">
							Clasificaciones
						</a>
					</li>';


        if ($credentials->registers_product_families)
            echo'
					<li>
						<a href="families">
							Familias
						</a>
					</li>';



        if ($credentials->registers_import)
            echo'
					<li>
						<a href="import">
							Importar
						</a>
					</li>';


        if ($credentials->registers_brands)
            echo'
					<li>
						<a href="brands">
							Marcas
						</a>
					</li>';

        if ($credentials->registers_countries == 1)
            echo'
					<li>
						<a href="countries">
							Paises
						</a>
					</li>';


        if ($credentials->registers_products)
            echo'
					<li>
						<a href="products">
							Productos
						</a>
					</li>';


        if ($credentials->registers_provinces == 1)
            echo'
					<li>
						<a href="provinces">
							Provincias
						</a>
					</li>';


        if ($credentials->registers_routes == 1)
            echo'
					<li>
						<a href="routes">
							Rutas
						</a>
					</li>';



        if ($credentials->registers_sectors)
            echo'
					<li>
						<a href="sectors">
							Sectores
						</a>
					</li>';


        if ($credentials->registers_product_subclassifications)
            echo'
					<li>
						<a href="subclassifications">
							Subclasificaciones
						</a>
					</li>';


        if ($credentials->registers_product_types)
            echo'
					<li>
						<a href="producttypes">
							Tipos de Producto
						</a>
					</li>';


        if ($credentials->registers_sellers == 1)
            echo'
					<li>
						<a href="sellers">
							Vendedores
						</a>
					</li>';

        if ($credentials->registers_areas == 1)
            echo'
					<li>
						<a href="areas">
							Zonas
						</a>
						</li>';




        echo'</ul>
				</li>';

    }

    static function SelectCompany()
    {

        echo '<li>
					<a href="select">
						<i class="fa fa-exchange"></i>
						<span>Cambiar compañía</span>
					</a>
				</li>';

    }

}
?>