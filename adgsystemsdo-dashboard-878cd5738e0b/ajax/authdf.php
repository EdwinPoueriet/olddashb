<?php
namespace App\Legacy;

$general = new General();

if (isset($_POST['seller'])){

 echo $general->DfTableMini($_POST['seller']);

}

?>