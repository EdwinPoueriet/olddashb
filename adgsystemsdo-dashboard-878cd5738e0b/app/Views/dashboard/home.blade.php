@extends('layout.mainClean')
@section('title', 'Home')
@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/hint.css/2.4.0/hint.min.css" />

    <link rel="stylesheet" href="/css/daterangepicker.css" type="text/css" />

    <link rel="stylesheet" href="/css/vue-multiselect.min.css" type="text/css" />
    <link rel="stylesheet" href="/css/c3.min.css" />
    <link rel="stylesheet" href="/css/introjs.css" type="text/css" />
    <link rel="stylesheet" href="/css/animate.css" type="text/css" />

    <style>
        .header-tools {
            float: right;
        }

    </style>

@endsection
@section('content')
    <div id="dashboard"  >
        <dashboard></dashboard>
    </div>
@endsection

@section('scripts')
    <script>
        var baseDataFromServer = {!! $dashData !!}
    </script>


    <script  src="/js/d3.min.js" ></script>
    <script  src="/js/c3.min.js" ></script>
    <script  src="/js/matchheight.js" ></script>
    <script  src="/js/moment.min.js" ></script>
    <script src="/js/daterangepicker2.js"></script>
    <script src="/js/dist/dashboard.js"></script>
    <script  src="/js/intro.js" ></script>
    <script  src="/js/store.js" ></script>

@endsection
