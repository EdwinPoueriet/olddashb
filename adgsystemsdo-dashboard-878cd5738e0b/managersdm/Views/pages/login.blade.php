<!DOCTYPE html>
<html>
<head   >
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>SDM Manager - Login </title>

    <!-- bootstrap -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="/css/libs/nanoscroller.css" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/css/compiled/theme_styles.css" />

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

    <link type="image/x-icon" href="/favicon.png" rel="shortcut icon"/>


</head>
<body id="login-page-full">
<div >
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="login-box">
                    <div id="login-box-holder">
                        <div class="row">
                            <div class="col-xs-12">
                                <header id="login-header">
                                    <div id="login-logo">
                                        <img src="/img/logo.png" alt="" class="normal-logo logo-white">
                                        <span>Acceso al SDM Manager</span>
                                    </div>
                                </header>
                                <div id="login-box-inner" style="padding-bottom: 10px;">
                                    <form method="post" action="/manager/login">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input class="form-control" type="text" placeholder="Usuario" name="username">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                            <input type="password" class="form-control" placeholder="Contraseña" name="password">
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" name="login" class="btn btn-success col-xs-12">Acceder</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row text-center vert-offset-top-1">
                                        @if(isset($errors) )
                                            <span style="color: red;">{{$errors}}</span>
                                        @endif
                                    </div>
                                </div>
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

<script src="js/scripts.js"></script>

</body>
</html>