@extends('reports.printbase')
@section('reportTitle')
    <h4>REPORTE DE VENTAS</h4>
    @if ($filters['date'])
        <span>DURACIÓN Y MONTO - COMPAÑÍA {{$session['company_id']}}</span><br>
        <span style="font-size: 12px"> Fecha {{$filters['date']}}</span>
    @endif
@endsection

@section('title','Reporte de Ventas')
@section('styles')
    <style>
        table {
            table-layout: fixed ;
            width: 100% ;
        }
    </style>
@endsection
@section('content')
    <div class="row">

        <div class="col-xs-12">
            <hr>

            @foreach($sales as $seller => $seller_sales)
                <i>Vendedor</i>
                <h5>{{$seller_sales[0]['seller']}}</h5>

                <table class="table" >
                    <tr>
                        <th>FECHA</th>
                        <th>CLIENTE</th>
                        <th>DURACIÓN</th>
                        <th style="text-align: right">MONTO</th>
                        <th><i style="font-size: 16px" class="fa fa-map-marker"></i></th>
                    </tr>
                    <tbody>
                    @foreach($seller_sales as $sale)
                        <tr>
                            <td style="width: 105px">{{$sale['order_date']}}</td>
                            <td>{{$sale['customer']}}</td>
                            <td style="width: 80px">{{$sale['duracion']}}</td>
                            <td style="text-align: right">{{$sale['monto']}}</td>
                            <td>{{$sale['in_location']}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="width: 105px"><b>Totales</b></td>
                        <td style="width: 80px"><b>{{$totals[$seller]['total_duracion'] or '00:00:00'}}</b></td>
                        <td style="text-align: right"><b>{{$totals[$seller]['total_monto'] or '0.0'}}</b></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <hr>
            @endforeach

        </div>

    </div>
@endsection