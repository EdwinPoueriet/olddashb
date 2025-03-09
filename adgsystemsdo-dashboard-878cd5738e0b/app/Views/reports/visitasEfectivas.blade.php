@extends('reports.printbase')
@section('reportTitle')
    <h4>REPORTE DE VISITAS EFECTIVAS</h4>
    @if ($filters['rango'])
        <span> COMPAÑÍA {{$session['company_id']}}</span><br>
        <span style="font-size: 12px"> Rango {{$filters['rango']}}</span>
    @endif
@endsection

@section('title','Reporte de Visitas Efectivas')
@section('content')
    <div class="row">

        <div class="col-xs-12">
            <hr>
            <table class="table table-condensed table-bordered" style="margin: auto">
                <thead>
                <tr>
                  <th>Vendedor</th>
                  <th>Total de Visitas</th>
                  <th>Efectivas</th>
                  <th>No Efectivas</th>
                  <th>% Efectividad</th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; $efectivas = 0; $noefectivas = 0; ?>
                @foreach($data as $seller)
                    <?php $total+= $seller['total']; $efectivas+= $seller['efectivas']; $noefectivas+= $seller['noefectivas'];?>
                    <tr>
                        <td>{{$seller['seller_code']}} - {{$seller['seller_name']}}</td>
                        <td>{{$seller['total']}}</td>
                        <td>{{$seller['efectivas']}}</td>
                        <td>{{$seller['noefectivas']}}</td>
                        <td>{{number_format(floatval($seller['efectividad'])*100, 2, '.', '')}}</td>
                    </tr>
                @endforeach
                <tr>
                    <th>
                        Totales
                    </th>
                    <th>{{$total}}</th>
                    <th>
                        {{$efectivas}}
                    </th>
                    <th>{{$noefectivas}}</th>
                    <th>{{number_format($efectivas/($efectivas+$noefectivas)*100,2, '.', '')}}</th>
                </tr>
                </tbody>
            </table>
        </div>

    </div>

@endsection