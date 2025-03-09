<?php
namespace App\Legacy;

$general = new General();

if (isset($_POST['seller'])){

 echo $general->ReturnsTableMini($_POST['seller']);

}


?>