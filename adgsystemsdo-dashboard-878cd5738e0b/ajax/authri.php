<?php
namespace App\Legacy;

$general = new General();

if (isset($_POST['seller'])){

 echo $general->ReceivablesTableMini($_POST['seller']);

}

?>