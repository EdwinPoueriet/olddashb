@extends('reports.printbase')
@section('reportTitle')
    <h4>REPORTE DE HORAS TRABAJADAS POR VENDEDOR</h4>
    @if ($filters['date'])
        <span> COMPAÑÍA {{$session['company_id']}}</span><br>
        <span style="font-size: 12px"> Fecha {{$filters['date']}}</span>
    @endif
@endsection

@section('title','Reporte de Horas Trabajadas')
@section('content')
    <div class="row">

        <div class="col-xs-12">
            <hr>
            <table class="table table-condensed table-bordered" style="margin: auto">
                <thead>
                <tr st>
                    <th rowspan="2">VENDEDOR</th>
                    <th colspan="3" style="text-align: center">VISITAS</th>

                </tr>
                <tr>
                    <th>Cantidad</th>
                    <th class="text-center">TIEMPO TOTAL</th>
                </tr>
                </thead>

                <tbody>
                @foreach(json_decode($sellers) as  $seller_code =>  $seller)
                    <?php
                    $second = 0;

                    ?>
                    <tr>
                        <td>{{$seller_code }} - {{$seller[0]->seller_name }}</td>
                        <td>{{ count($seller) }}</td>
                    @foreach($seller as  $s)

                        <?php

                                $second += ( strtotime($s->end_date) - strtotime($s->start_date))

                        ?>


                    @endforeach
                        <?php
                        $hours = floor($second / 3600);
                        $minutes = floor(($second / 60) % 60);
                        $seconds = $second % 60;

                        ?>

                        <td class="text-center">{{ $hours }}:{{ $minutes }}:{{ $seconds  }}</td>
                    </tr>




                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <div class="row" style="margin-top: 10px">
        <div class="col-xs-12">
                <span style="font-size: 12px">
                 * Estos datos están sujetos al tiempo de inicio y término de las transacciones realizadas por el vendedor.
            </span>
        </div>
    </div>
@endsection