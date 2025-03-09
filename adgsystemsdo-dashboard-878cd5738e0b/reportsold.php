<?php
use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
use App\Legacy\Credentials;
include('config/datetime_parameters.php');

$general_functions = new General;

$credentials_obj = new Credentials;

$credentials_json = $credentials_obj->SelectedCompanyCredentials();

$credentials = json_decode($credentials_json);

if($credentials[0]->authorization_ri == 0)
    header('Location: home');


/*
  Shows document header
  @include('config/structure/header.php')
  must be included for this function to run

*/

$header = new Header;
$header->Initialize(0, "Reportes del SDM Dashboard");

/*
  Shows navigation menu
  @include('config/structure/navigation_menu.php')
  must be included for this function to run

*/
$navigation = new Navigation;
$navigation->Initialize();

?>


<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Reportes</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Reportes</h1>
<!--                  <div class="alert alert-warning pull-right ">-->
<!--                    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button>-->
<!--                    <i class="fa fa-info-circle fa-fw fa-lg"></i>-->
<!--                    <strong>Aviso</strong> Sección en Desarollo y Mantenimiento-->
<!--                </div>-->

            </div>
        </div>
      <script>
        <?php $general_functions->ReportsInitialJsData() ?>
      </script>
     <div id="reportswrapper">
         <report></report>
     </div>
    </div>
  </div>
</div>

<?php


Footer::Initialize();

/*
	Shows document scripts
	@include('config/structure/scripts.php')
	must be included for this function to run

*/

Scripts::Initialize();

?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>
<script src="js/pagespecific/reportsdist.js" type="text/javascript"></script>

</body>
</html>