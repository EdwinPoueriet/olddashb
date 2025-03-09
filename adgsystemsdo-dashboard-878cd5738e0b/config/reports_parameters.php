<?php

  if (isset($_POST['date'])) {
        $startdate = substr($_POST['date'], 0, 10);
        $enddate = substr($_POST['date'], 13, 10);
    }
        else { 
        $startdate = date('Y-m-d');
        $enddate = date('Y-m-d');
        } 
    $cdates = "";
?>               