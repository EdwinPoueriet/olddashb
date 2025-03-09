<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>SDM Dashboard - Tutorial Dos Factores</title>

    <!-- bootstrap -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.css" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/css/compiled/theme_styles.css" />

    <!-- this page specific styles -->
    <link rel="stylesheet" type="text/css" href="/css/libs/timeline.css">

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
    <meta name="msapplication-TileImage" content="mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="mstile-310x310.png" />

    <!-- google font libraries -->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

    <style>
        ol {
            margin: 1em 0;
        }
         li {

            line-height: 1.6;
            color: #7f8c97;
            mar
        }
    </style>
</head>
<body>
<div id="theme-wrapper">
    <div id="page-wrapper" class="container">
        <div class="row">
            <div id="content-wrapper" style="margin: 0">
                <div class="row" style="text-align: center;">
                    <div>
                        <img src="/img/logo.png" alt="">
                    </div>
                    <div>
                        <h4> <strong>Guía para utilizar authenticación de dos factores</strong></h4>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <section id="cd-timeline" class="cd-container">
                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-picture">
                                    <i class="fa fa-android fa-2x"></i>
                                </div>

                                <div class="cd-timeline-content">
                                    <h2>Descargar aplicación Google Authenticator</h2>
                                    <p>Google Authenticator genera un token de seguridad único que utilizará para autenticarse en el SDM Dashboard.
                                        Descarguela para su Smartphone Android o Apple
                                    </p>
                                    <div class="clearfix" style="text-align: right">
                                        <a style="margin-right: 20px"  target="_blank"
                                           href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=es">
                                            <i class="fa fa-android fa-fw"></i>  Play Store   </a>
                                        <a  target="_blank"
                                                href="https://itunes.apple.com/do/app/google-authenticator/id388497605?mt=8">
                                            <i class="fa fa-apple fa-fw"></i>  Apple Store</a>
                                    </div>
                                    <span class="cd-date">Primer Paso</span>
                                </div>
                            </div>

                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-movie">
                                    <i class="fa fa-cog fa-2x"></i>
                                </div>

                                <div class="cd-timeline-content">
                                    <h2>Configurando Google Authenticator</h2>
                                    <ol>
                                        <li>Luego de descargado, inicie el autenticador de Google y pulse 'Comenzar'. </li>
                                        <li>En la pantalla siguiente, pulse 'Escanear código de barras'.</li>
                                        <li>En su PC, diríjase a la <a href="https://sdm.adgsystems.do/login">página de acceso del SDM Dashboard</a>. </li>
                                        <li>Autentíquese normalmente con su usuario y clave de acceso.</li>
                                        <li>Escaneé con su smartphone el código QR presentado en la página siguiente.</li>
                                    </ol>
                                    <span class="cd-date">Segundo Paso</span>
                                </div>
                            </div>

                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-picture">
                                    <i class="fa fa-check-square fa-2x"></i>
                                </div>

                                <div class="cd-timeline-content">
                                    <h2>Estás Listo</h2>
                                    <p>  Luego de completados estos pasos, podrá ingresar el codigo presentado en el Autenticador de Google para acceder al SDM Dashboard.
                                    </p>
                                    <div class="clearfix">
                                        <a class="btn btn-primary pull-right" href="http://sdm.adgsystems.do">Ir al SDM Dashboard</a>
                                    </div>
                                    <span class="cd-date">Tercer Paso</span>
                                </div>
                            </div>

                            {{--<div class="cd-timeline-block">--}}
                                {{--<div class="cd-timeline-img cd-location">--}}
                                    {{--<i class="fa fa-map-marker fa-2x"></i>--}}
                                {{--</div>--}}

                                {{--<div class="cd-timeline-content">--}}
                                    {{--<h2>Title of section 4</h2>--}}
                                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>--}}
                                    {{--<div class="clearfix">--}}
                                        {{--<a class="btn btn-primary pull-right">Read more</a>--}}
                                    {{--</div>--}}
                                    {{--<span class="cd-date">20:48</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="cd-timeline-block">--}}
                                {{--<div class="cd-timeline-img cd-location">--}}
                                    {{--<i class="fa fa-map-marker fa-2x"></i>--}}
                                {{--</div>--}}

                                {{--<div class="cd-timeline-content">--}}
                                    {{--<h2>Title of section 5</h2>--}}
                                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum.</p>--}}
                                    {{--<div class="clearfix">--}}
                                        {{--<a class="btn btn-primary pull-right">Read more</a>--}}
                                    {{--</div>--}}
                                    {{--<span class="cd-date">21:22</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="cd-timeline-block">--}}
                                {{--<div class="cd-timeline-img cd-movie">--}}
                                    {{--<i class="fa fa-video-camera fa-2x"></i>--}}
                                {{--</div>--}}

                                {{--<div class="cd-timeline-content">--}}
                                    {{--<h2>Final Section</h2>--}}
                                    {{--<p>This is the content of the last section</p>--}}
                                    {{--<span class="cd-date">23:59</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </section>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/jquery.nanoscroller.min.js"></script>

<!-- this page specific scripts -->
<script src="/js/modernizr.js"></script>
<script src="/js/timeline.js"></script>

</body>
</html>