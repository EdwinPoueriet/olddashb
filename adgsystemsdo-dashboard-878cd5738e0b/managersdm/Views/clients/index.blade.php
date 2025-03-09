@extends('base')
@section('title','Administrar Clientes')
@section('styles')
    <style>
        .extra-space {
            margin-top: 25px;
        }
    </style>
    @endsection
@section('content')
    <div class="row outer">
        <div class=" col-lg-12">

            <div class="row">
                <div class="col-lg-12">
                    <div id="content-header" class="clearfix">
                        <div class="pull-left">
                            <h1>Clientes</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div  class="main-content row">
                <!-- SPECIFIC CONTENT PREFIX-->
                <div class="main-box clearfix">
                    <div class="main-box-body clearfix" style="padding-top: 25px">
                        <div class="row">
                            @if (isset($success))
                                <div class="alert alert-success fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-check-circle fa-fw fa-lg"></i>
                                    {{$success}}
                                </div>
                            @endif
                            @if (isset($errors))
                                <div class="alert alert-danger fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-times-circle fa-fw fa-lg"></i>
                                    {{$errors}}
                                </div>
                            @endif
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <h2>Crear Nuevo</h2>
                                        <hr style="margin-top: 8px">
                                        <form action="/manager/clients" method="post">
                                            <div class="form-group">
                                                <label> Nombre del Cliente </label>
                                                <input type="text" class="form-control" placeholder="Ingrese Nombre" name="client_name">
                                            </div>
                                            <div class="form-group">
                                                <label>Host</label>
                                                <input type="text" class="form-control" placeholder="Ingrese Host" name="client_host">
                                            </div>
                                            <div class="form-group">
                                                <label>Base de datos</label>
                                                <input type="text" class="form-control" name="client_database" placeholder="nombre base de datos" >
                                            </div>
                                            <div class="form-group">
                                                <label>Api Key</label>
                                                <input type="text" class="form-control" name="api_key" placeholder="api key" >
                                            </div>
                                            <div class="form-group">
                                                <label>Cantidad de dispositivos</label>
                                                <input type="number" class="form-control" name="devices_amount">
                                                <p class="help-block">La base de datos del cliente necesita haber sido creada para que esta operacion sea posible. </p>
                                            </div>

                                            {{--<div class="form-group">--}}
                                                {{--<label>Nombre base de datos</label>--}}
                                                {{--<input type="text" class="form-control" placeholder="adgsoft_******" name="database_name">--}}
                                            {{--</div>--}}
                                            <button class="btn btn-primary btn-block" type="submit">Crear</button>
                                        </form>
                                    </div>
                                    <div class="col-xs-6">
                                        <h2>Asignar Usuario Administrativo a Cliente</h2>
                                        <hr style="margin-top: 8px">
                                        <form action="/manager/clients/associate" method="post">
                                            <div class="form-group">
                                                <label> Seleccione Usuario </label>
                                                <select  class="form-control" name="user_id">
                                                    @if (isset($adg_users))
                                                        @foreach($adg_users as $user)
                                                            <option value="{{$user->user_id}}"> {{$user->user_first_name}} - {{$user->user_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Seleccione Cliente </label>
                                                <select  class="form-control" name="client_id">
                                                    @if (isset($adg_clients))
                                                        @foreach($adg_clients as $client)
                                                            <option value="{{$client->client_id}}">{{$client->client_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <button class="btn btn-primary btn-block" type="submit">Asociar</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12" style="margin-top: 25px">
                                <h2>Listado de Clientes</h2>
                                <hr style="margin-top: 8px">
                                <table  class="table table-bordered table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Usuario Admin</th>
                                            <th>Compañias</th>
                                            <th>Host</th>
                                            <th>Serial</th>
                                            <th>Api key</th>
                                            <th>Base de Datos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($client_list as $c)
                                        <tr>
                                            <td>{{$c->client_id}}</td>
                                            <td>{{$c->client_name}}</td>
                                            <td>{{$c->user or 'N/A'}}</td>
                                            <td>{{$c->companies or 'N/A'}}</td>
                                            <td>{{$c->client_host or 'N/A'}}</td>
                                            <td>{{$c->client_serial_number or 'N/A'}}</td>
                                            <td>{{$c->api_key or 'N/A'}}</td>
                                            <td>{{$c->client_database}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection