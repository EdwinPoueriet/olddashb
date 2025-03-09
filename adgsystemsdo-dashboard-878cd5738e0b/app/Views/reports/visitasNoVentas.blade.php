@extends('layout.mainClean')
@section('styles')
    <link rel="stylesheet" href="/css/daterangepicker.css" type="text/css" />
    <link rel="stylesheet"  href="/css/jsgrid.min.css" type="text/css">
    <link rel="stylesheet"  href="/css/jsgrid-theme.min.css" type="text/css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="content-header" class="clearfix">
                <div class="pull-left">
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active"><span>Reporte de Visitas No Ventas</span></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="reportswrapper">
        <visitno></visitno>

    </div>

@endsection

@section('scripts')
    <script>
        <?php $general = new \App\Legacy\General();  $general->ReportsInitialJsData() ?>
    </script>
    <script  src="/js/moment.min.js" ></script>
    <script src="/js/daterangepicker2.js"></script>
    <script  src="/js/jsgrid.js" ></script>
    <script src="/js/pagespecific/visitNoVentas.js"></script>


@endsection