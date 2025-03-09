@extends('layout.mainClean')
@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/hint.css/2.4.0/hint.min.css" />
    <link rel="stylesheet" href="/css/daterangepicker.css" type="text/css" />
    <link rel="stylesheet" href="/css/vue-multiselect.min.css" type="text/css" />
    <style>
        .tablewrapper td {
            font-size: 12px !important;
            font-weight: 500 !important;
        }
        .textalignright { text-align:right !important; }
        .textalignleft { text-align:left  !important; }
        .textalignright div { padding-right: 5px; }
        .textalignleft div { padding-left: 5px; }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div id="content-header" class="clearfix">
                <div class="pull-left">
                    <ol class="breadcrumb">
                        <li><a href="/">Home</a></li>
                        <li class="active"><span>Reporte de Duración por Venta</span></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="reportswrapper">
        <ventas-report></ventas-report>
    </div>

@endsection

@section('scripts')

    <script>
        <?php $general = new \App\Legacy\General();  $general->ReportsInitialJsData() ?>
    </script>
    <script  src="/js/moment.min.js" ></script>
    <script src="/js/daterangepicker2.js"></script>
    <script src="/js/pagespecific/reportventasduracion.js"></script>

@endsection