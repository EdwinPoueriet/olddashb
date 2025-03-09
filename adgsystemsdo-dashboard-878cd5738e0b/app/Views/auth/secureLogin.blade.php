<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Autenticación | SDM Dashboard</title>
    <!-- bootstrap -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.css" />

    <link rel="stylesheet" type="text/css" href="/css/compiled/theme_styles.css" />

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
    <!-- Favicon -->
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="&nbsp;"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="/mstile-310x310.png" />

    <style>
        .popover {
            max-width: 400px;
        }
    </style>
</head>
<body id="login-page-full">
<div id="login-full-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="login-box">
                    <div id="login-box-holder">
                        <div class="row">
                            <div class="col-xs-12">
                                <header id="login-header">
                                    <div id="login-logo" style="padding-bottom: 10px">
                                        <img src="img/logo1.png" style="height: 85px;" class="normal-logo logo-white">
                                        <span style="margin-top: 10px; font-weight: 500; font-size: 13px">Acceso al SDM Dashboard</span>
                                    </div>
                                </header>
                                <div id="login-box-inner" style="padding-bottom: 10px;">
                                    <form method="post" action="/securelogin">
                                        @if ($firstTime)
                                            <input type="hidden" name="key" value="{{$key}}">
                                            <div class="row" style="text-align: center; ">
                                                <div class="row" style="color: #4d5154">
                                                    <div class="col-xs-12">
                                                        <p style="font-size: 13px">La autenticación de dos factores ha sido habilitada por el administrador.
                                                            <br> Para obtener instrucciones de como acceder, haga click <a
                                                                    href="/public/twofactortutorial" target="_blank">Aquí</a>
                                                        </p>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12" >
                                                    <div> Escanee su código usando el autenticador de Google</div>
                                                    <img src="{{ $QrCode  }}" alt="">
                                                </div>
                                            </div>
                                            <hr style="margin-top: 0">
                                        @endif

                                        <div class="form-group" style="text-align: center">
                                                <Label>Autenticación de dos factores</Label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                                    <input type="text" class="form-control" placeholder="Código de Seguridad" name="user_key">
                                                </div>
                                                <p class="form-text text-muted" style="font-size: 12px">
                                                    <a href="#" data-container="body" data-toggle="popover" data-placement="top"
                                                       title="Two Factor Authentication - Info"
                                                       data-content="Se ha habilidato la autenticación de dos factores para aumentar el nivel de seguridad
                                                        de su cuenta. Si desea conocer como utilizarla, haga click
                                                        <a href='/public/twofactortutorial    '><b> Aquí.</b></a>" >
                                                        ¿Qué es esto?
                                                    </a>
                                                </p>
                                            </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" name="login" class="btn btn-success col-xs-12">Accesar</button>
                                               </div>
                                        </div>
                                    </form>
                                    <div style="color: red" class="row text-center vert-offset-top-1">
                                        @if (isset($errors))
                                            <b> {{$errors}} </b>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="login-box-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="/">ADGSystems E.I.R.L
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script>
  $(document).ready(function(){
    $('[data-toggle="popover"]').popover({
      html: true,
      template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3>' +
      '<div class="popover-content" style="text-align: justify"></div></div>'
    });
  });
</script>
</body>
</html>