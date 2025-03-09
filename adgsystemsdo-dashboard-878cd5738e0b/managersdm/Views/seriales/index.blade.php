@extends('base')
@section('title','Administrar Seriales')
@section('content')
    <div class="row outer">
        <div class=" col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div id="content-header" class="clearfix">
                        <div class="pull-left">
                            <h1>Seriales de clientes</h1>
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
                            <div class="col-sm-6">
                                <div class="row">
                                    <form action="" method="GET">
                                        <div class="form-group">
                                            <label for="clientes">Seleccionar cliente para modificar seriales</label>
                                            <select name="cliente" id="clientes" class="form-control">
                                                @if (isset($clientes))
                                                    @foreach($clientes as $client)
                                                        <option value="{{$client->client_id}}"
                                                                @if(!is_null($cliente) && $cliente->client_id == $client->client_id)
                                                                selected
                                                                @endif

                                                        >{{$client->client_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" id="client_selct" type="submit">Seleccionar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @if(!is_null($cliente))
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Listado de seriales del cliente</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Usuario asignado</th>
                                            <th>IMEI</th>
                                            <th style="text-align: center">Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($seriales as $s)
                                            <tr>
                                                <td>{{$s->user_serial_number}}</td>
                                                @if (!is_null($s->user_name))
                                                    <td>{{$s->user_name}} - ({{$s->user_nickname}})</td>
                                                    <td>{{$s->user_related_imei}}</td>
                                                @else
                                                    <td>Ninguno</td>
                                                    <td>Ninguno</td>
                                                @endif

                                                <td style="text-align: center">
                                                    <form action="/manager/seriales/borrar" method="post">
                                                        <input type="hidden" name="cliente" value="{{$cliente->client_id}}">
                                                        <input type="hidden" name="serial_number" value="{{$s->user_serial_number}}">
                                                        <button class="btn btn-danger">
                                                            <span class="fa fa-trash"></span>
                                                        </button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <form action="/manager/seriales/generar" method="post">
                                        <input type="hidden" name="cliente" value="{{$cliente->client_id}}">
                                        <button class="btn btn-success" type="submit">Generar nuevo serial</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection