
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
										<span style="margin-top: 10px; font-weight: 500; font-size: 13px">Acceso al SDM Dashboard</span>
									</div>
								</header>
								<div id="login-box-inner" style="padding-bottom: 10px;">
									<form method="post" action="/login">
                    <input id="finger" type="hidden" name="fingerprint" value="">
                    <input id="fingerdata" type="hidden" name="fingerprint_data" value="">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<input class="form-control" type="text" placeholder="Usuario o email" name="username">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-key"></i></span>
											<input type="password" class="form-control" placeholder="Contraseña" name="password">
										</div>
										<div class="row">
											<div class="col-xs-12">
												<button type="submit" class="btn btn-success col-xs-12">Acceder</button>
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
	
	<!-- this page specific scripts -->

	
	<!-- theme scripts -->
	<script src="/js/scripts.js"></script>
	
	<!-- this page specific inline scripts -->

<script src="/js/fingerprint.js"></script>
<script>
  new Fingerprint2({
    excludeScreenResolution: true,
    excludeSessionStorage: true,
    excludeIndexedDB: true,
    excludeAddBehavior :true,
    excludeOpenDatabase: true,
    excludeDoNotTrack: true,
    excludeJsFonts: true,
    excludeFlashFonts: true,
    excludePlugins: true,
    excludeIEPlugins: true,
    excludeWebGL: true,
    excludeAdBlock: true,
	  excludeColorDepth: true,
	  excludePixelRatio: true,
	  excludeHasLiedOs: true,
      excludeHasLiedBrowser: true,
	  excludeTouchSupport: true,
	  excludeTimezoneOffset:true,
    excludeHasLiedLanguages: true,
    excludeHasLiedResolution: true,
    excludeAvailableScreenResolution: true,
    excludeCanvas: true
  }).get(function(result, components){
      console.log(components);
    var params = components.reduce (function (prev, act) {
	  prev[act['key']] = act['value'];
	  return prev;
	},{})
    $.getJSON('https://ipapi.co/json', function(data) {
      params.ip = data.ip;
      params.country  = data.country_name;
      $('#finger').val(result);
      $('#fingerdata').val(JSON.stringify(params));
    })
  });

</script>
</body>
</html>