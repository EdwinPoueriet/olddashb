<?php
namespace App\Legacy;


$general = new General();

if (isset($_POST['collector'])){

 echo $general->AdvancedReceivablesTableMini($_POST['collector']);

}


?>