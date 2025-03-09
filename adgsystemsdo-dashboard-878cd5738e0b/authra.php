<?php


use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
use App\Legacy\Credentials;

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
$header->Initialize(0, "Autorización de reimpresión");

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
                    <li><a href="#">Autorizar Docs</a></li>
                    <li class="active"><span>Recibos</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Autorizar reimpresión de recibos de adelanto</h1>

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box no-header clearfix">
                    <div class="main-box-body clearfix" id="main-content">
                        <div class="tabs-wrapper">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-crear" data-toggle="tab">Crear Autorización</a></li>
                                <li><a href="#tab-historico" data-toggle="tab">Histórico</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab-crear">

                                    <?php

                                    if (isset($_POST['permission_id'])){
                                        echo '
                                            
                                              <div class="alert alert-success fade in alert-dismissable">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                            ';
                                        echo $general_functions->CancelPermission($_POST['permission_id']);
                                        echo '
                                        </div>
                                        ';

                                    }

                                    ?>

                                    <div class="alert alert-info fade in alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                        <strong>Info</strong> Seleccione un usuario y un recibo de la tabla.
                                    </div>


                                    <div class="row">

                                        <form action="" id="form" method="POST">
                                            <input type="hidden" name="receivable" id="receivable_input" value="">
                                            <input type="hidden" name="user" id="user_input" value="">
                                            <div class="col-sm-3">
                                                <div class="form-group" style="margin-top: 27px">
                                                    <label for="user_account">Usuario</label>
                                                    <select name="seller_code" id="user_account" class="form-control">
                                                        <option value="0">Seleccione</option>
                                                        <?php
                                                        foreach (json_decode($general_functions->GetUserList()) as $o){
                                                            echo '
                                                                             <option data-userid="'.$o->user_id.'" value="'.$o->seller_code.'"> '.$o->seller_code.' - '.$o->user_name.' ('.$o->user_nickname.' )</option>
                                                                    ';
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="form-group text-center" style="margin-top: 20px">
                                                        <input type="submit" value="Autorizar Reimpresión" class="btn btn-primary" id="submitbtn">
                                                    </div>
                                                </div>
                                                <?php
                                                if (isset($_POST['receivable']) &&  isset($_POST['user']) ){
                                                    $general_functions->UserPermission($_POST['user'],$_POST['receivable'],'RA');
                                                }
                                                ?>

                                            </div>
                                            <div class="col-sm-9">
                                                <table class="table user-list footable" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
                                                    <thead>
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>No. de Recibo</th>
                                                        <th data-type="html">Cliente</th>
                                                        <th data-type="html" style="text-align: right">Total Monto</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="receivables-table-body">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="tab-historico">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <table class="table user-list footable" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Fecha</th>
                                                    <th data-type="html">Cliente</th>
                                                    <th>No. de Recibo</th>
                                                    <th data-type="html">Usuario</th>
                                                    <th data-type="html" style="text-align: center">¿Impreso?</th>
                                                    <th data-type="html">Cancelar</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $general_functions->AdvancedReceivablePermissionTable()
                                                ?>
                                                </tbody>
                                            </table>

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


<script>
    var selected = 0;
    var confirm = 0;
    $('#submitbtn').on('click',function (e) {
        e.preventDefault();
        if (confirm  < 1 ){
            $(this).prop('value', 'Confirmar');
            confirm = 1;
        }else{
            console.log('sub');
            $('#form').submit();
        }
    });


    $("#user_account").change(function () {
        $('#user_input').val($("#user_account option:selected").data('userid'));
        $.ajax({
            method: 'POST',
            url: '/legacy/ajax/authra',
            data: {collector: $("#user_account").val()},
            success: function(response){
                $('#receivables-table-body').html(response);
                $('.selectable').on('click',function () {
                    selected  = $(this).data('id');
                    $('#receivable_input').val(selected);
                    $('#receivables-table-body > tr').each(function() {
                        $(this).removeClass('selectedrow');
                    });
                    $(this).addClass('selectedrow');
                });
                $('.footable').footable({
                    "on": {
                        "after.ft.paging": function(e, ft){
                            $('#receivables-table-body > tr').each(function() {
                                if ($(this).data('id')!== selected)
                                    $(this).removeClass('selectedrow');
                            });
                        }
                    },
                    "paging": {
                        "countFormat": "{CP} de {TP}"
                    }
                });

            },
            error: function (response){
                alert('Hubo un error al enviar la solicitud');
            }
        });

    });

</script>
</body>
</html>