<?php

namespace App\Legacy;


class Report extends Session
{

    function __construct()

    {
      parent::__construct();

    }

   function GetBrands()       
   {       

    $color = 0;

    if ($_POST['excluding'] != "")

        $condition = "NOT";            

    else 

        $condition = "";

    $ventasmarcas = self::$con->prepare("SELECT SUM(x.order_item_amount) as importe, p.product_name
       FROM ".self::$user_database.".orders_details x
       INNER JOIN ".self::$user_database.".orders_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
       INNER JOIN ".self::$user_database.".products p ON (p.company_id = :cataloguecompany AND p.product_reference = x.product_reference AND p.brand_id = p.brand_id)
       INNER JOIN ".self::$user_database.".sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
       WHERE x.company_id = :cid AND x.order_item_date BETWEEN :startdate AND :enddate
       AND o.seller_code ".$condition." IN('".implode("','", $_POST["sellers"])."') AND p.brand_id = :bid  
       GROUP BY p.product_name ORDER BY 1 DESC LIMIT 10");
    $ventasmarcas->bindParam(':cid', self::$default_company);
    $ventasmarcas->bindParam(':bid', $_POST['brand']);
    $ventasmarcas->bindParam(':startdate', $_POST['startdate']);
    $ventasmarcas->bindParam(':enddate', $_POST['enddate']); 
    $ventasmarcas->bindParam(':cataloguecompany', self::$catalogue_company);
    $ventasmarcas->execute();
    $ventasmarcas = $ventasmarcas->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($ventasmarcas as $row) {

       if ($color == 0) {

          $color = $color + 1 ;
          $style = "#f4f4f4"; 

      }

      else {
          $color = $color - 1;
          $style = "#FFFFF";

      }


      if ($row["product_name"] != '') {
          $row["producto"] = $row["product_name"];
      }
      else
      {
          $row["producto"] = "Producto sin nombre";
      }
      echo '
      <div style="background-color:'.$style.'; padding: 10px 5px 10px 5px" class="row"><div class="col-md-9 col-sm-4">'.ucwords(strtolower($row['producto'])).'</div>
      <div style="text-align:right; float: right" class="col-xs-6 col-sm-3">'.number_format($row["importe"], 2, '.', ',').'</div></div>';

  }

}

function GetProducts()      
{       

   $color = 0; 

    if ($_POST['excluding'] != "")

        $condition = "NOT";            

    else 

        $condition = "";

    $ventasproductos = self::$con->prepare("SELECT SUM(x.order_item_amount) as importe, c.customer_name 
        FROM ".self::$user_database.".orders_details x
        INNER JOIN ".self::$user_database.".orders_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
        INNER JOIN ".self::$user_database.".sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
        INNER JOIN ".self::$user_database.".customers c on (c.customer_code = o.customer_code and c.company_id = x.company_id)
        WHERE x.company_id = :cid AND x.order_item_date BETWEEN :startdate AND :enddate
        AND o.seller_code ".$condition." IN('".implode("','", $_POST["sellers"])."') AND x.product_reference = :pid  
        GROUP BY c.customer_name ORDER BY 1 DESC LIMIT 10");
    $ventasproductos->bindParam(':cid', self::$default_company);
    $ventasproductos->bindParam(':pid', $_POST['product']);
    $ventasproductos->bindParam(':startdate', $_POST['startdate']);
    $ventasproductos->bindParam(':enddate', $_POST['enddate']); 
    $ventasproductos->execute();
    $ventasproductos = $ventasproductos->fetchAll();
    foreach ($ventasproductos as $row) {

        if ($color == 0) {

            $color = $color + 1 ;
            $style = "#f4f4f4"; 

        }

        else {
            $color = $color - 1;
            $style = "#FFFFF";

        }

        echo '
        <div style="background-color:'.$style.'; padding: 10px 5px 10px 5px" class="row"><div class="col-md-9 col-sm-4">'.ucwords(strtolower(preg_replace('/[^a-zA-Z\s]/', '', $row['customer_name']))).'</div>
        <div style="text-align:right; float: right" class="col-xs-6 col-sm-3">'.number_format($row["importe"], 2, '.', ',').'</div></div>';

    }
}

function GetEffiency()
{

    $color = 0;

    $efectividad =  self::$con->prepare("SELECT c.customer_name FROM ".self::$user_database.".orders_header oh   
        INNER JOIN ".self::$user_database.".customers c ON(c.customer_code = oh.customer_code AND c.company_id = :cid AND c.day_id = :did)
        WHERE oh.seller_code = :sid AND oh.company_id = :cid AND oh.order_date BETWEEN :startdate AND :enddate");
    $efectividad->bindParam(':cid', self::$default_company);
    $efectividad->bindParam(':sid', $_POST['seller']);
    $efectividad->bindParam(':startdate', $_POST['startdate']);
    $efectividad->bindParam(':enddate', $_POST['enddate']);
    $efectividad->bindParam(':did', $_POST['did']); 
    $efectividad->execute();
    $efectividad = $efectividad->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($efectividad as $row) {

        if ($color == 0) {

            $color = $color + 1 ;
            $style = "#f4f4f4"; 

        }

        else {
            $color = $color - 1;
            $style = "#FFFFF";

        }

        echo '
        <div style="background-color:'.$style.'; padding: 10px 5px 10px 5px" class="row"><div class="col-md-9 col-sm-4">'.ucwords(strtolower(preg_replace('/[^a-zA-Z\s]/', '', $row['customer_name']))).'</div></div>';

    }

}
}    

if (isset($_POST['brand']))
    
    {
    
      $report = new Report;
      $report->GetBrands();
    
    }

if (isset($_POST['product']))

    {
    
      $report = new Report;
      $report->GetProducts();
    
    }

if (isset($_POST['did']))
    
    {
    
      $report = new Report;
      $report->GetEffiency();
    
    }


?>

