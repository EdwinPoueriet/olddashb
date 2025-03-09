@extends('mails.base')

@section('topContent')
    <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">
        Estimado cliente,

    </p>
    <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px; text-align: justify">
        Hemos detectado una solicitud de acceso de un equipo que usted no ha autorizado. Por su seguridad, necesitamos
        que identifique y autorice este equipo para futuras solicitudes de acceso.
    </p>

    <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">
        <b>IP: </b> {{$data['ip']}} <br>
        <b>Plataforma: </b> {{$data['plataforma']}} <br>
        <b>Navegador: </b> {{$data['navegador']}} <br>
        <b>País: </b> {{$data['pais']}} <br>
    </p>
@endsection

@section('buttonText', 'Autorizar este Equipo')
@section('buttonLink', 'http://sdm.adgsystems.do/public/authorizeaccess?token='.$token)
@section('bottomContent')
    Si no reconoce este equipo, puede ignorar este mensaje.
@endsection