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

if($credentials[0]->registers_import == 0)
    header('Location: home');


/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

$header = new Header;
$header->Initialize(0, "Importar");

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
                    <li class="active"><span>Importar</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Importar</h1>
                    <div class="filter-block pull-right">
                        <a class="btn btn-primary pull-right" href="examples/sdm_manual.docx" download>
                            <i class="fa fa-download fa-lg"></i> Descargar manual
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">


            <div class="col-lg-12">
                <div class="main-box no-header clearfix">
                    <div class="main-box-body clearfix" id="main-content">
                        <div class="table-responsive">
                            <table id="thetable" class="table user-list footable table-hover" data-paging-size="20" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
                                <thead>
                                <tr>
                                    <th style="width: 65%">&nbsp;</th>
                                    <th data-type="html" style="width: 10%" class="text-center">Cant. de Registros</th>
                                    <th data-type="html" style="width: 10%" class="text-center">Cargar CSV</th>
                                    <th data-type="html" style="width: 15%" class="text-center">CSV de Ejemplo</th>
                                </tr>
                                </thead>
                                <tbody id="import_body">

                                </tbody>
                            </table>
                            <ul class="pagination pull-right hide-if-no-paging"></ul>
                        </div>
                        <div id="content"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Standard Bootstrap Modal -->
<div class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-labelledby="order-map-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><br></h4>
            </div>
            <br>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
/*
  Shows document footer
  @include('config/structure/footer.php')
  must be included for this function to run

*/

$footer = new Footer;

$footer->Initialize();

/*
  Shows document scripts
  @include('config/structure/scripts.php')
  must be included for this function to run

*/

Scripts::Initialize();

?>

<script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>

<script>

    $(document).on('click', '.upload', function(){

        $("#import_modal .modal-body").html(
            '<div id="images-to-upload">' +
            '<form id="upload_file" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault();">' +
            '<div class="row">' +
            '<div class="form-group col-md-10 col-md-offset-1">' +
            '<input type="hidden" name="upload_type" value="' + this.id + '">' +
            '<input type="file" class="filestyle" name="' + this.id + '" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus">' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="form-group col-md-offset-2">' +
            '<div class="checkbox-nice">' +
            '<input type="checkbox" id="erase" name="erase"><label for="erase">Borrar registros anteriores antes de insertar los nuevos.</label>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="form-group text-center col-md-offset-5">' +
            '<input type="submit" name="submit" class="btn btn-primary col-md-3">' +
            '</div>' +
            '</div>' +
            '</form>' +
            '</div>');

        $(":file").filestyle({buttonName: "btn-primary"});

        $("#import_modal").modal('show');



    });

    function ImportList(){
        console.log('Import List');
        $.ajax({
            method: 'POST',
            url:'/legacy/ajax/import',
            data: {upload_type: "initialize"},
            complete: function (response) {
                console.log(response.responseText);
                $('#import_body').html(response.responseText);
                $('.footable').footable({
                    "paging": {
                        "countFormat": "{CP} de {TP}"
                    }
                });
            },
            error: function () {
                alert("Error");
            }
        });

    };


    $(document).on('submit', '#upload_file', function(){

        var formData = new FormData(this);

        $.ajax({

            method: 'POST',
            url:'/legacy/ajax/import',
            data: formData,
            async: true,
            complete: function (response) {
                $('#import_modal .modal-body').html(response.responseText);
                ImportList();
            },
            error: function () {
                alert("Error");
            },
            cache: false,
            contentType: false,
            processData: false
        });

    });

    ImportList();


</script>


</body>
</html>