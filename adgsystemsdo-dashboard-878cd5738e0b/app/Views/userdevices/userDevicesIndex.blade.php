
@extends('layout.main')
@section('title','Dispositivos')

@section('content')

    <div id="deviceswrapper">
        <user-devices></user-devices>
        @if (!is_null($sdmlink))
            <div class="row">

                <div class="col-xs-12">
                    <div class="alert alert-info fade in alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        Si necesita descargar la última versión del SDM, haga click <a href="{{$sdmlink}}">Aquí</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
    @if (is_null($sdmlink))
      var sdmlink = null;
                @else
      var sdmlink = "{{ $sdmlink }}";
                @endif

        var usersData = {!! $users !!} ;


    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="http://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    <script src="../../../js/pagespecific/devicesdist.js" type="application/javascript"></script>

@endsection