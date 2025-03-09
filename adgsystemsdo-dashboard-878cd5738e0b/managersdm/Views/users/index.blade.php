@extends('base')
@section('title','Usuarios')
@section('styles')
    <style>

    </style>
@endsection
@section('content')

    <div class="row outer">
        <div class=" col-lg-12">

            <div class="row">
                <div class="col-lg-12">
                    <div id="content-header" class="clearfix">
                        <div class="pull-left">
                            <h1>Usuarios</h1>
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
                            <div class="col-xs-4">

                                <ul class="nav nav-tabs" role="tablist" id="Tabs">
                                    <li role="presentation" class="active"><a href="#crear"  data-toggle="tab">Crear</a></li>
                                    <li role="presentation"><a href="#editar"  data-toggle="tab">Editar</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="crear">
                                        <h2> Crear Usuario </h2>
                                        <hr style="margin-top: 8px">
                                        <form action="/manager/users" method="post">

                                            <div class="form-group">
                                                <label> Cliente </label>
                                                <select name="client_id" class="form-control" placeholder="seleccione cliente">
                                                    @foreach($clients as $c)
                                                        <option value="{{$c->client_id}}">{{$c->client_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Usuario </label>
                                                <input type="text" class="form-control" name="user_name" placeholder="login username" >
                                            </div>

                                            <div class="form-group">
                                                <label> Nombre del Usuario </label>
                                                <input type="text" class="form-control" name="user_first_name" placeholder="nombre usuario" >
                                            </div>


                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" class="form-control" name="user_email" placeholder="email" >
                                            </div>

                                            <div class="form-group">
                                                <label> Contraseña del Usuario </label>
                                                <input type="text" class="form-control" placeholder="password" name="user_password" >
                                            </div>

                                            <button type="submit" class="btn btn-block btn-primary">Crear Usuario</button>
                                        </form>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="editar">
                                        <h2>Editar Usuario</h2>
                                        <hr style="margin-top: 8px">

                                        @if (!isset($userData))
                                        <form action="/manager/users/edit" method="get">
                                            <div class="form-group">
                                                <label> Seleccionar Usuario </label>
                                                <select name="edit_user" class="form-control">
                                                    @foreach($users as $u)
                                                        <option value="{{$u->user_id}}">{{$u->user_name}} - {{$u->user_first_name}}</option>
                                                    @endforeach
                                                </select>
                                                <button style="margin-top: 10px" class="btn btn-block btn-primary"
                                                        type="submit" id="selectuser">Seleccionar</button>
                                            </div>
                                        </form>
                                        @else
                                            <form action="/manager/users/edit" method="post">
                                                <input type="hidden" name="user_id" value="{{$userData->user_id}}">

                                                <div class="form-group">
                                                    <label> Cliente </label>
                                                    <select name="client_id" class="form-control" placeholder="seleccione cliente">
                                                        @foreach($clients as $c)
                                                            <option
                                                                    @if ($userData->client_id == $c->client_id)
                                                                selected
                                                                @endif
                                                          value="{{$c->client_id}}">{{$c->client_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label> Usuario </label>
                                                    <input  value="{{$userData->user_name}}"
                                                            type="text" class="form-control" name="user_name" placeholder="login username" >
                                                </div>

                                                <div class="form-group">
                                                    <label> Nombre del Usuario </label>
                                                    <input type="text"
                                                           value="{{$userData->user_first_name}}"
                                                           class="form-control" name="user_first_name" placeholder="nombre usuario" >
                                                </div>

                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text"
                                                           value="{{$userData->user_email or ""}}"
                                                           class="form-control" name="user_email" placeholder="email" >
                                                </div>

                                                <div class="form-group">
                                                    <label> Contraseña del Usuario </label>
                                                    <input type="text"
                                                           value="{{$userData->user_password}}"
                                                           class="form-control" placeholder="password" name="user_password" >
                                                </div>

                                                <div class="form-group">
                                                    <label>Base de datos</label>
                                                    <input type="text"
                                                           value="{{$userData->user_database}}"
                                                           class="form-control" name="user_database" placeholder="nombre base de datos" >
                                                </div>
                                                <button type="submit" class="btn btn-block btn-primary">Editar Usuario</button>
                                            </form>
                                        @endif

                                    </div>

                                </div>

                            </div>
                            <div class="col-xs-8">
                                <h2>Listado de Usuarios</h2>
                                <hr style="margin-top: 8px">
                                @if (isset($users))
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Nombre</th>
                                            <th>Cliente</th>
                                            <th>Tipo</th>
                                            <th>Compañia Actual</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $u)
                                            <tr>
                                                <td>{{$u->user_id}}</td>
                                                <td>{{$u->user_name}}</td>
                                                <td>{{$u->user_email or "No tiene"}}</td>
                                                <td>{{$u->user_first_name}}</td>
                                                <td>{{$u->client_name}}</td>
                                                <td>{{$u->user_type}}</td>
                                                <td>{{$u->user_company}}</td>
                                                <td>
                                                    <form action="/manager/users/delete" method="post">
                                                        <input type="hidden" name="id" value="{{$u->user_id}}">
                                                        <button style="padding: 3px 3px 3px 3px" type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @if (isset($userData))
    <script>
      $('#Tabs a[href="#editar"]').tab('show');
    </script>
    @endif
    @endsection