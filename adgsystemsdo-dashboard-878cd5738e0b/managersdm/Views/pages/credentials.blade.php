@extends('base')
@section('title','Credenciales')
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
                            <h1>Ajustar Credenciales</h1>
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
                                <div class="row extra-space">
                                    <div class="col-xs-12">
                                        <h2>Seleccionar Usuario</h2>
                                        <hr style="margin-top: 8px">
                                        <form action="/manager/users/credentials">
                                            <div class="form-group">
                                                <label> Seleccione Usuario </label>
                                                <select  class="form-control" name="user_id">
                                                    @if (isset($adg_users))
                                                        @foreach($adg_users as $user)
                                                            <option
                                                                   @if (isset($selected_user) && $selected_user == $user->user_id)
                                                                           selected
                                                                           @endif
                                                                    value="{{$user->user_id}}"> {{$user->user_first_name}} -
                                                                {{$user->user_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if (isset($companies))
                                            <div class="form-group">
                                                <label> Seleccione Compañia </label>
                                                <select  class="form-control" name="company_id">

                                                        @foreach($companies as $company)
                                                            <option
                                                                    @if (isset($selected_company) && $selected_company == $company->company_id)
                                                                    selected
                                                                    @endif
                                                                    value="{{$company->company_id}}">
                                                                {{$company->company_id}} - {{$company->company_name}}</option>
                                                        @endforeach

                                                </select>
                                            </div>
                                            @endif

                                            <button class="btn btn-primary btn-block" type="submit">

                                                @if (!isset($companies))
                                                    Seleccionar usuario
                                                    @else
                                                    Ajustar sus credenciales
                                                @endif


                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                                @if (isset($credentials) )
                                    <form action="/manager/users/credentials" method="post">
                                        <input type="hidden" name="credential_id" value="{{$credentials['credential_id']}}">
                                        <div class="col-xs-8">
                                           <h2>Tabla de Credeciales</h2>
                                            <hr style="margin-top: 8px">
                                            <div class="two-columns">
                                                <table class="table table-bordered table-striped table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach(array_slice($credentials,3) as $col => $val)
                                                        <tr>
                                                            <td>{{$col}}</td>
                                                            <td>
                                                                <input type="checkbox"  name="{{$col}}"
                                                                       @if ($val == '1')
                                                                       checked
                                                                        @endif
                                                                >
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <button type="submit" class="btn btn-block btn-primary">Guardar Credenciales</button>

                                        </div>
                                    </form>

                                    @endif


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection