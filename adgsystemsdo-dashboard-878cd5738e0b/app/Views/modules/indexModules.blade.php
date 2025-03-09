@extends('layout.mainClean')
@section('styles')
    {{--<link rel="stylesheet" href="/css/daterangepicker.css" type="text/css" />--}}
    {{--<link rel="stylesheet" href="/css/vue-multiselect.min.css" type="text/css" />--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="content-header" class="clearfix">
                <div class="pull-left">
                    <h1>Módulos de Usuario</h1>
                </div>
            </div>
        </div>
    </div>
    <div id="moduleswrapper">
        <modules></modules>
    </div>

@endsection

@section('scripts')
    <script>
        var baseDataFromServer = {{$baseData}}
    </script>
    <script src="/js/pagespecific/modules.js"></script>
@endsection