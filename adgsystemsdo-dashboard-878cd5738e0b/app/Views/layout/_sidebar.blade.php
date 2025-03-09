<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div id="user-left-box"  class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">';
                <div class="user-box" >
									<span class="name">
<!--                                        --><?php
//                                        foreach ( as $a){
//                                            $data[$a];
//
//                                        }
//                                        echo $data;
//
//                                        ?>





                                        {{$authUser->user_first_name}}
                                    </span>
                    <span class="status" >
                        <i style="color: white" class="fa fa-bank"></i> Compañía {{$authUser->default_company}}
                    </span>
                    @if ($authUser->isAdmin)
                        <br><i style="color: white" class="fa fa-user fa-fw"></i> Administrador
                        @endif
                </div>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    @if (isset($masterClients) && !is_null($masterClients))
                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                            Cambiar Clientes
                        </li>
                        <li> <form method="post" action="/public/selectclient">
                                <div class="row" style="padding-left: 10px; padding-right: 10px; text-align: center">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <select name="client" id="client-selector" class="form-control">
                                                @foreach ($masterClients as $client)
                                                    <option
                                                            @if($authUser->client_details->client_id == $client['client_id'])
                                                            selected
                                                            @endif
                                                            value="{{$client['client_id']}}">{{$client['client_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </li>
                        <script>
                            $('#client-selector').change(function() {
                                this.form.submit();
                            });
                        </script>
                    @endif
                    <li class="nav-header nav-header-first hidden-sm hidden-xs">
                        Menu principal
                    </li>
                    <li>
                        <a href="/">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                   @if ($credentials->authorization == 1)
                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-file-text"></i>
                                <span>Autorizar Docs.</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu">';
                                @if ($credentials->authorization_ri == 1)
                                <li>
                                    <a href="/authri">
                                        Recibos
                                    </a>
                                </li>
                                @endif
                                @if ($credentials->authorization_ra == 1)

                                <li>
                                    <a href="/authra">
                                        Recibo de adelanto
                                    </a>
                                </li>
                                @endif
                                @if ($credentials->authorization_dv == 1)
                                echo'
                                <li>
                                    <a href="/authdv">
                                        Devoluciones
                                    </a>
                                </li>
                                @endif
                                @if ($credentials->authorization_df == 1)
                                <li>
                                    <a href="/authdf">
                                        Facturas Depositadas
                                    </a>
                                </li>
                                @endif
                               </ul>
                        </li>
                    @endif

                        <li>
                            <a href="/userdevices">
                                <i class="fa fa-tablet"></i>
                                <span>Dispositivos</span>
                            </a>
                        </li>

                        {{--@if (\App\Common\CompatibilityChecker::PuedeVerModulosPorUsuario())--}}
                            {{--<li>--}}
                                {{--<a href="/modules">--}}
                                    {{--<i class="fa fa-cubes"></i>--}}
                                    {{--<span>Módulos de Usuario</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--@endif--}}
                    @if ($credentials->maps == 1)
                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-map"></i>
                                <span>Mapas</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu">';

                               @if ($credentials->maps_customers == 1)
                                <li>
                                    <a href="/customers_map">
                                        Clientes
                                    </a>
                                </li>
                                @endif

                                @if ($credentials->maps_sellers == 1)

                                <li>
                                    <a href="/sellers_map">
                                        Vendedores
                                    </a>
                                </li>

                                    @endif
                                @if ($credentials->maps_routes == 1)

                                <li>
                                    <a href="/routes_map">
                                        Rutas
                                    </a>
                                </li>
                                        @endif

                              </ul>
                        </li>
                    @endif
                    @if ($credentials->reports == 1)
                        @if($authUser->modules->MREP)
                        <li class="@if(str_contains($_SERVER['REQUEST_URI'], 'reports')) open @endif">
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-line-chart"></i>
                                <span>Reportes</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu"   @if(str_contains($_SERVER['REQUEST_URI'], 'reports')) style="display: block;"@endif >
                                {{--@if($authUser->client_details->version_code >= 76)--}}
                                <li>
                                    <a href="/reports/activity">
                                        Actividades
                                    </a>
                                </li>
                                {{--@endif--}}
                                @if(isset($authUser->modules->MPED))
                                <li>
                                    <a href="/reports/ventas">
                                        Ventas
                                    </a>
                                </li>
                                @endif
                                @if(isset($authUser->modules->MFAC))
                                <li>
                                    <a href="/reports/facturas/rancheras">
                                        Facturas Rancheras
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a href="/reports/cobros">
                                        Cobros
                                    </a>
                                </li>

                                @if(isset($authUser->modules->MDEV))
                                <li>
                                    <a href="/reports/devoluciones">
                                        Devoluciones
                                    </a>
                                </li>
                                @endif
                                @if(isset($authUser->modules->MPRO))
                                <li>
                                    <a href="/reports/products">
                                        Ventas por productos
                                    </a>
                                </li>
                                @endif
                                @if(isset($authUser->modules->MCXC))
                                <li>
                                    <a href="/reports/cuentacobrar">
                                        Cuenta por Cobrar
                                    </a>
                                </li>
                                @endif
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
                                @if(isset($authUser->modules->MDEP))
                                <li>
                                    <a href="/reports/depositos">
                                        Depositos de Factura
                                    </a>
                                </li>
                                @endif
                                    {{--@if($authUser->client_details->version_code >= 76)--}}
                                <li>
                                    <a href="/reports/horastrabajadas">
                                        Horas Trabajadas
                                    </a>
                                </li>
                                    {{--@endif--}}

                                @if (\App\Common\CompatibilityChecker::PuedeVerReportesDeVisitasEfectivas())
                                <li>
                                    <a href="/reports/visitas">
                                        Visita Efectiva
                                    </a>
                                </li>
                                @endif
                                    {{--@if($authUser->client_details->version_code >= 76)--}}
                                <li>
                                    <a href="/reports/visitas/no/ventas">
                                        Visita No Ventas
                                    </a>
                                </li>
                                        {{--@endif--}}

                            </ul>
                        </li>
                            @endif




                    @endif
                    @if ($credentials->registers_settings == 1)
                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                            Configuraciones
                        </li>
                    @if($moreThanOneCompany)
                            <li>
                                <a href="/select">
                                    <i class="fa fa-exchange"></i>
                                    <span>Cambiar compañía</span>
                                </a>
                            </li>
                    @endif
                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="fa fa-gears"></i>
                                <span>Configuraciones</span>
                                <i class="fa fa-angle-right drop-icon"></i>
                            </a>
                            <ul class="submenu">
                                <li>
                                    <a href="/settings">
                                        Configuración SDM
                                    </a>
                                </li>
                                @if ($authUser->isAdmin)
                                <li>
                                    <a href="/adminsettings">
                                        Configuración Global
                                    </a>
                                </li>
                                @endif
                                @if ($credentials->registers_users == 1)
                                <li>
                                    <a href="/users">
                                       Config. Usuarios
                                    </a>
                                </li>
                                @endif
                             </ul>
                        </li>
                            <li>
                                <a href="/qrgenerator">
                                    <i class="fa fa-qrcode"></i>
                                    <span>Generador QR</span>
                                </a>
                            </li>
                    @endif


                </ul>
            </div>
        </div>
    </section>
</div>