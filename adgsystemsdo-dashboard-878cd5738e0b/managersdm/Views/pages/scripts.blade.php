@extends('base')
@section('title','Scripts')

@section('content')

    <div class="row outer">
        <div class=" col-lg-12">

            <div class="row">
                <div class="col-lg-12">
                    <div id="content-header" class="clearfix">
                        <div class="pull-left">
                            <h1>Scripts</h1>
                        </div>
                    </div>
                </div>
            </div>


            <div  class="main-content row">
                <!-- SPECIFIC CONTENT PREFIX-->
                <div class="main-box clearfix">
                    <div class="main-box-body clearfix" style="padding-top: 25px">
                        <div class="row">
                            <div class="col-xs-8 col-xs-offset-2">
                                <h2>Script de crear nueva base de datos al crear nuevo cliente</h2>
                                <hr style="margin-top: 8px">
                                <form action="/manager/scripts/databasecode" method="post">
                                    <textarea name="database_code" class="form-control" rows="15">{{$database_code}}</textarea>
                                    <button style="margin-top: 10px" type="submit" class="btn btn-primary btn-block"> Salvar </button>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection