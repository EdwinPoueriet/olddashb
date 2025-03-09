<?php

namespace App\Legacy;

class Header extends Session
{

	function __construct()

	{

		parent::__construct();
	}

	function Initialize($filter, $title)

	{

		echo '<!DOCTYPE html>

		<html>
		<head>
			<meta charset="UTF-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

			<title>ADGSystems E.I.R.L | '; echo $title . '</title>

			<!-- bootstrap -->
			<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
			
			<link rel="stylesheet" href="/css/toastr.min.css" type="text/css" />

			<script src="js/pace.min.js"></script>
			<!-- RTL support - for demo only -->
			<script src="js/demo-rtl.js"></script>
			<!-- 
			If you need RTL support just include here RTL CSS file <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
			And add "rtl" class to <body> element - e.g. <body class="rtl"> 
		-->

		<!-- libraries -->
		<link rel="stylesheet" type="text/css" href="css/libs/font-awesome.css" />
		<link rel="stylesheet" type="text/css" href="css/libs/nanoscroller.css" />


		<!-- global styles -->
		<link rel="stylesheet" type="text/css" href="css/compiled/theme_styles.css" />

		<!-- this page specific styles -->
		<link rel="stylesheet" href="css/libs/datepicker.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/daterangepicker.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/jquery-jvectormap-1.2.2.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/weather-icons.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/xcharts.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/morris.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/select2.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/footable.bootstrap.min.css" type="text/css" />
				<!--	<link rel="stylesheet" href="css/libs/footable.core.css" type="text/css" /> -->
		<link rel="stylesheet" type="text/css" href="css/compiled/wizard.css">

		<style>
			.xchart .errorLine path {
				stroke: #C6080D;
				stroke-width: 3px;
			}
		</style>

		<!-- Favicon -->
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="mstile-310x310.png" />

		<!-- google font libraries -->
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300" rel="stylesheet" type="text/css">

		<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
		<![endif]-->

	</head>
	<body>
		<div id="theme-wrapper">
			<header class="navbar" id="header-navbar">
				<div class="container">
					<a href="/" id="logo" class="navbar-brand">
				   <img src="/img/logo1.png" alt="" class="normal-logo logo-white"/>
                <img src="/img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
					</a>

					<div class="clearfix">
						<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
							<span class="sr-only">Toggle navigation</span>
							<span class="fa fa-bars"></span>
						</button>

						<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
							<ul class="nav navbar-nav pull-left">
								<li>
									<a class="btn" id="make-small-nav">
										<i class="fa fa-bars"></i>
									</a>
								</li>
								 ';
			if ($filter == 1)
			    $this->Filters();
								echo '
								</ul>
							</div>

							<div class="nav-no-collapse pull-right" id="header-nav">
								<ul class="nav navbar-nav pull-right">
									<li class="dropdown profile-dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">';

											$this->ProfileInfo("profile_picture");

											echo '<span class="hidden-xs">'; $this->ProfileInfo("first_name");

											echo '</span><b class="caret"></b>
										</a>
										<ul class="dropdown-menu dropdown-menu-right">
											<!-- li><a href="user-profile.html"><i class="fa fa-user"></i>Profile</a></li>
											<li><a href="#"><i class="fa fa-cog"></i>Settings</a></li>
											<li><a href="#"><i class="fa fa-envelope-o"></i>Messages</a></li -->
											<li><a href="logout"><i class="fa fa-power-off"></i>Logout</a></li>
										</ul>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
				</header>
				';
			}

			function ProfileInfo($info)

			{

				$profilesql = self::$con->prepare("SELECT user_profile_picture, user_first_name FROM adg_users WHERE user_id = :user_id");

				$profilesql->bindParam(':user_id', self::$user_id);

				$profilesql->execute();

				$profilesql = $profilesql->fetchAll(\PDO::FETCH_ASSOC);

				if ($info == "profile_picture")
					echo '<img src="img/avatar/'.$profilesql[0]['user_profile_picture'].'" alt=""/>';

				if ($info == "first_name")
					echo $profilesql[0]['user_first_name'];

			}

			function Filters()

			{

				echo '<li>
				<a class="btn" data-toggle="modal" href="#myModal">
					<b>Filtros</b>
				</a>
			</li>';

		}




		function newInitialize ($filter, $title) {
            echo '<!DOCTYPE html>

		<html>
		<head>
			<meta charset="UTF-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

			<title>ADGSystems E.I.R.L | '; echo $title . '</title>

			<!-- bootstrap -->
			<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />

			<script src="js/pace.min.js"></script>
			<!-- RTL support - for demo only -->
			<script src="js/demo-rtl.js"></script>
			<!-- 
			If you need RTL support just include here RTL CSS file <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
			And add "rtl" class to <body> element - e.g. <body class="rtl"> 
		-->

		<!-- libraries -->
		<link rel="stylesheet" type="text/css" href="css/libs/font-awesome.css" />
		<link rel="stylesheet" type="text/css" href="css/libs/nanoscroller.css" />


		<!-- global styles -->
		<link rel="stylesheet" type="text/css" href="css/compiled/theme_styles.css" />

		<!-- this page specific styles -->
		<link rel="stylesheet" href="css/libs/datepicker.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/daterangepicker.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/jquery-jvectormap-1.2.2.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/weather-icons.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/xcharts.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/morris.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/select2.css" type="text/css" />
		<link rel="stylesheet" href="css/libs/footable.bootstrap.min.css" type="text/css" />
				<!--	<link rel="stylesheet" href="css/libs/footable.core.css" type="text/css" /> -->
		<link rel="stylesheet" type="text/css" href="css/compiled/wizard.css">

		<style>
			.xchart .errorLine path {
				stroke: #C6080D;
				stroke-width: 3px;
			}
		</style>

		<!-- Favicon -->
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="mstile-310x310.png" />

		<!-- google font libraries -->
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300" rel="stylesheet" type="text/css">
		 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
		 

		<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
		<![endif]-->
';

        }


        function endNewInitialize () {
         echo '   </head>
	<body>
		<div id="theme-wrapper">
			<header class="navbar" id="header-navbar">
				<div class="container">
					<a href="/" id="logo" class="navbar-brand">
						<img src="img/logo.png" alt="" class="normal-logo logo-white"/>
						<img src="img/logo-black.png" alt="" class="normal-logo logo-black"/>
						<img src="img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
					</a>

					<div class="clearfix">
						<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
							<span class="sr-only">Toggle navigation</span>
							<span class="fa fa-bars"></span>
						</button>

						<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
							<ul class="nav navbar-nav pull-left">
								<li>
									<a class="btn" id="make-small-nav">
										<i class="fa fa-bars"></i>
									</a>
								</li>
								 
								
								</ul>
							</div>

							<div class="nav-no-collapse pull-right" id="header-nav">
								<ul class="nav navbar-nav pull-right">
									<li class="dropdown profile-dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">';

											$this->ProfileInfo("profile_picture");

											echo '<span class="hidden-xs">'; $this->ProfileInfo("first_name");

											echo '</span><b class="caret"></b>
										</a>
										<ul class="dropdown-menu dropdown-menu-right">
											<!-- li><a href="user-profile.html"><i class="fa fa-user"></i>Profile</a></li>
											<li><a href="#"><i class="fa fa-cog"></i>Settings</a></li>
											<li><a href="#"><i class="fa fa-envelope-o"></i>Messages</a></li -->
											<li><a href="logout"><i class="fa fa-power-off"></i>Logout</a></li>
										</ul>
									</li>
									
								</ul>
							</div>
						</div>
					</div>
				</header>
            ';
        }
	}


	?>
