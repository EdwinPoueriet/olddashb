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
$header->Initialize(0, "Generador de Códigos QR");

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
                    <li class="active"><span>Generar Códigos QR</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Generar Códigos QR</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 ">
                <div class="main-box no-header clearfix">
                    <div class="main-box-body clearfix" id="main-content">
                        <div class="alert alert-info fade in alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                            <strong>Info</strong> Seleccione un usuario para generar el código QR correspondiente al serial del dispositivo
                        </div>
                        <div class="col-md-4 col-md-offset-1">

                            <div class="row">
                                <div class="form-group" style="margin-top: 27px">
                                    <h2>Usuario</h2>
                                    <select name="seller_code" id="user_account" class="form-control">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach (json_decode($general_functions->GetUserListAndSerials()) as $o){
                                            echo '
                                          <option value="'.$o->user_serial_number.'">'.$o->seller_code.' - '.$o->user_name.' ('.$o->user_nickname.')</option>
                                        ';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-md-12">
                                    <h2>Serial</h2>
                                    <span id="serialtext"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4" >
                                    <div id="qrcode" ></div>
                                </div>
                            </div>

                        </div>
                            <div class="col-md-4 col-md-offset-1">
                                <div class="row">
                                    <h2> Serial de la compañia</h2>
                                    <?php echo \App\Legacy\Session::$client_details['client_serial_number']?>
                                    <div id="companyserial" style="margin-top: 10px"></div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>




<?php

/*
	Shows document footer
	@include('config/structure/footer.php')
	must be included for this function to run

*/

Footer::Initialize();

/*
	Shows document scripts
	@include('config/structure/scripts.php')
	must be included for this function to run

*/

Scripts::Initialize();

?>
<script src="js/qrcode.min.js"></script>

<script>

  console.log('asdasdasd')
    $("#user_account").change(function () {

        var a = $("#user_account").val();
        if (a !== '0'){
            $('#qrcode').html('');

            $('#serialtext').html(a);
            var qrcode = new QRCode("qrcode", {
                text: a,
                width: 128,
                height: 128,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }else{
            $('#qrcode').html('');
            $('#serialtext').html('Seleccione');
        }
    });

    $(document).ready(function () {
        var qrcode = new QRCode("companyserial", {
            text:  '<?php echo \App\Legacy\Session::$client_details['client_serial_number']?>',
            width: 128,
            height: 128,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });


</script>
</body>
</html>