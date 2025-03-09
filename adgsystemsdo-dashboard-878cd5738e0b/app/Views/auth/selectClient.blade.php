
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>ADGSystems E.I.R.L | SDM Dashboard</title>

    <!-- bootstrap -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="/css/libs/nanoscroller.css" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/css/compiled/theme_styles.css" />

    <!-- this page specific styles -->

    <!-- google font libraries -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

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
                                        <span style="margin-top: 10px; font-weight: 500; font-size: 13px">Master - Seleccionar Cliente</span>
                                    </div>
                                </header>
                                <div id="login-box-inner" style="padding-bottom: 10px;">
                                    <form method="post" action="/public/selectclient">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <select name="client" class="form-control">
                                                        <option value="" disabled selected>Seleccione</option>
                                                        @foreach ($clients as $client)
                                                            <option value="{{$client['client_id']}}">{{$client['client_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success col-xs-12">Seleccionar</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row text-center vert-offset-top-1">
                                        <h5 class="col-xs-12" style="color: red">
                                            @if (isset($info))
                                                <div class="alert alert-info fade in" style="font-size: 13px">
                                                    <i class="fa fa-fw fa-info-circle fa-fw fa-lg"></i>

                                                    {!! $info !!}
                                                </div>
                                            @endif
                                            @if (isset($errors))
                                                <b> {{$errors}} </b>
                                            @endif
                                        </h5>
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
<!-- global scripts -->

<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/jquery.nanoscroller.min.js"></script>

<script src="/js/demo.js"></script> <!-- only for demo -->

<!-- theme scripts -->
<script src="/js/scripts.js"></script>

</body>
</html>