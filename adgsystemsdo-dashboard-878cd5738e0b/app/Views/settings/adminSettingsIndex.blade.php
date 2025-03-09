
@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="content-header" class="clearfix">
                <div class="pull-left">
                    <ol class="breadcrumb">
                        <li><a href="/">Inicio</a></li>
                        <li><a href="#">Configuraciones</a></li>
                        <li class="active"><span>Configuraciones Globales</span></li>
                    </ol>
                    <h1>Configuraciones Globales</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="main-box" >
            <div class="main-box-body" style="padding-bottom: 30px; padding-top: 25px">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#seguridad" data-toggle="tab">Seguridad</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" >


                    <form action="/adminsettings" method="post">
                        <div role="tabpanel" class="tab-pane active" id="#seguridad">
                            <div class="row">
                                <div style="padding-left: 15px; padding-right: 15px;" class="col-xs-4">
                                    <h4>Autorización de Equipo</h4>
                                    <p style="font-size: 13px; text-align: justify">
                                        Solo permitir el acceso al Dashboard  a equipos autorizados previamente.
                                        Las autorizaciones seran otorgadas por medio de un enlace entregado por un mensaje
                                        a la cuenta de correo que tiene asociada al sistema.
                                    </p>
                                    <div class="checkbox-nice">
                                        <input
                                                @if(isset($settings) && $settings['machine_authorization'] == 'true') checked="checked" @endif
                                        type="checkbox" id="checkbox-2" name="machine_authorization">
                                        <label for="checkbox-2">
                                            Habilitar autorización de Equipos
                                        </label>
                                    </div>
                                </div>
                                <div style="padding-left: 15px; padding-right: 15px;" class="col-xs-4">
                                    <h4>Autenticación de dos Factores</h4>
                                    <p style="font-size: 13px; text-align: justify">Al activar esta opción, cada usuario bajo su dominio que tenga acceso a su SDM Dashboard deberá autenticarse
                                        utilizando su usuario y contraseña y adicionalmente deberá de proveer 9 dígitos generados por
                                        la aplicación Google Authenticator. <b>ADGSystems recomienda</b> habilitar este opción para poder garantizar la <b>seguridad</b>  de su información.
                                        Aprenda a usar SDM Two Factor Authentication <a href="/public/twofactortutorial">Aquí</a>
                                    </p>

                                    <div class="checkbox-nice">
                                        <input
                                                @if(isset($settings) && $settings['two_factor_enabled'] == 'true') checked="checked" @endif
                                                type="checkbox" id="checkbox-1" name="two_factor_enabled">
                                        <label for="checkbox-1">
                                            Habilitar autenticación de dos factores
                                        </label>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div style="margin-top: 20px;text-align: center">
                            <button type="submit" class="btn btn-success">Salvar Cambios</button>
                            @if (isset($message))
                               <div style="padding-top: 25px">
                                   <b>{{$message}} </b>
                               </div>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection