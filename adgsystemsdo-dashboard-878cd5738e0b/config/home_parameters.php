<?php

(isset($_POST['startrange']) != '' ? $startdate = $_POST['startrange'] : $startdate = date('Y-m-d') );

(isset($_POST['endrange']) != '' ? $enddate = $_POST['endrange'] : $enddate = date('Y-m-d'));


$sdate = date("w", strtotime($startdate));
$sdate = $sdate + 1;
$edate = date("w", strtotime($enddate));
$edate = $edate + 1;

if ($startdate == $enddate) {
  $cdates = "AND day_id = '".$sdate."'";
  $ordersdates = "AND x.order_date = '".$startdate."'";
  $efdates = "AND cs.day_id = '".$sdate."'";
}
else {
  $cdates = "";
  $ordersdates = "AND x.order_date BETWEEN '".$startdate."' AND '".$enddate."'";
  $efdates = "";
}

if (isset($_POST['route']) != '') 
  $routes = "AND cs.route_code = '".$_POST["route"]."'"; 

else 
  $routes = "";

/*

    Sellers

*/

if ((isset($_POST["sellers"]))) 
  {
  
  $sellerlist = "'".implode("','", $_POST["sellers"])."'";

  $sellers_array = $_POST['sellers'];

  } else {

  $sellers_array = array();

  foreach (json_decode($general_functions->GetSellers()) as $row) {

  array_push($sellers_array, $row->seller_code);

  }

  $sellerlist = "'".implode("','", $sellers_array)."'";

}

if (isset($_POST['excludeseller']))
  $seller_condition = "NOT";

else
  $seller_condition = "";


/* 
    
    Collectors 

*/

if (isset($_POST["collectors"])) 
  $collectorlist = "'".implode("','", $_POST["collectors"])."'";

else {

  $collectors_array = array();

  foreach (json_decode($general_functions->GetCollectors()) as $row) {

  array_push($collectors_array, $row->collector_code);

  }

  $collectorlist = "'".implode("','", $collectors_array)."'";

}

if (isset($_POST['excludecollector']))
  $collector_condition = "NOT";

else
  $collector_condition = "";

?>