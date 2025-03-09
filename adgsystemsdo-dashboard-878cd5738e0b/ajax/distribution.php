<?php

namespace App\Legacy;

class Distribution extends General
{

  static function GetDistribution($con, $dbcustomer, $cataloguecompany, $cid, $startdate, $enddate)
  {

  /* 


    Verifies if a seller was posted or not, if it wasnt posted, it looks for all the sellers


  */
    if (isset($_POST['sellers']))

      {
       
        if (in_array("all", $_POST["sellers"], true))
          $sellers_array = json_decode(General::GetSellers($con, $cid, $dbcustomer));
        
        else
          $sellers_array = json_decode(General::GetSpecificSeller($con, $dbcustomer, $cid, $_POST['sellers']));
      
      }

    else 

      {
        
        echo "Por favor seleccione un vendedor.";

        return false;
      
      }

  /* 

      Verifies if a product was posted or not, if it wasnt posted, it looks for all the products

  */
      if (isset($_POST['products']))

      {

        if (in_array("all", $_POST["products"], true))

          {

              $products_array = json_decode(General::GetProducts($con, $cataloguecompany, $dbcustomer));

              $customer_routes = (isset($_POST['routes']) ? "AND cc.route_code IN('".implode("','",$_POST["routes"])."')" : "");

              $customer_zones = (isset($_POST['areas']) ? "AND cc.area_code IN('".implode("','",$_POST["areas"])."')" : "");

              $order_warehouse = (isset($_POST['warehouses']) ? "AND od.warehouse_code IN('".implode("','",$_POST["warehouses"])."')" : "");

              $order_costcenter = (isset($_POST['cost_centers']) ? "AND od.cost_center_code IN('".implode("','",$_POST["cost_centers"])."')" : "");

          } else 

          {

              $products_array = json_decode(General::GetSpecificProduct($con, $dbcustomer, $cataloguecompany, $_POST['products']));

              $customer_routes = "";

              $customer_zones = "";

              $order_warehouse = "";

              $order_costcenter = "";

          }


      } else 

      {

        echo "Por favor seleccione un producto.";

        return false;

      }

    /* 


          Verifies if wants customers that bought the product or not 


    */
          if (isset($_POST['didntbuy']))

            $SQL = "SELECT 
          customer_name, 
          customer_code FROM ".$dbcustomer.".customers WHERE customer_code NOT IN (SELECT 
          oh.customer_code
          FROM ".$dbcustomer.".orders_header oh
          INNER JOIN ".$dbcustomer.".orders_details od ON (od.order_id = oh.order_id AND od.company_id = oh.company_id)
          INNER JOIN ".$dbcustomer.".customers cc ON (cc.customer_code = oh.customer_code AND cc.company_id = oh.company_id)
          WHERE oh.company_id = :company_id  
          AND oh.order_date BETWEEN :start_date AND :end_date
          AND oh.seller_code = :seller_code
          AND TRIM(od.product_reference) = TRIM(:product_reference)
          ".$customer_routes." 
          ".$customer_zones."
          ".$order_warehouse." 
          ".$order_costcenter."
          GROUP BY oh.customer_code) 
          AND company_id = :company_id 
          AND seller_code = :seller_code 
          ";

          else  

            $SQL = "SELECT 
          oh.customer_code,
          cc.customer_name,
          SUM(od.order_item_amount) as importe, 
          SUM(od.order_item_quantity) as cantidad 
          FROM ".$dbcustomer.".orders_header oh
          INNER JOIN ".$dbcustomer.".orders_details od ON (od.order_id = oh.order_id AND od.company_id = oh.company_id)
          INNER JOIN ".$dbcustomer.".customers cc ON (cc.customer_code = oh.customer_code AND cc.company_id = oh.company_id)
          WHERE oh.company_id = :company_id  
          AND oh.order_date BETWEEN :start_date AND :end_date
          AND oh.seller_code = :seller_code
          AND TRIM(od.product_reference) = TRIM(:product_reference)
          ".$customer_routes." 
          ".$customer_zones."
          ".$order_warehouse." 
          ".$order_costcenter."
          GROUP BY oh.customer_code
          ";



          foreach ($sellers_array as $sellers_row) 

          {

            echo '<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#s'.trim($sellers_row->seller_code).'">
                                '.$sellers_row->seller_code . '-' .$sellers_row->seller_name.'
                              </a>
                            </h4>
                          </div>
                          <div id="s'.trim($sellers_row->seller_code).'" class="panel-collapse collapse">
                            <div class="panel-body">
                            <div class="row">
                                <div class="col-md-7"></div>
                                <div class="col-md-3" style="text-align: right">Importe</div>
                                <div class="col-md-1" style="text-align: right">Cantidad</div>
                                <div class="col-md-1" style="text-align: right">Total</div>
                            </div>';

      /*
      
          For each product, it does a query to the database to verify which customer bought it, amount and quantity

      */ 

          foreach($products_array as $products_row)    

          {         

            $customers_sql = $con->prepare($SQL);

            $customers_sql->execute(array(

              ':company_id' => $cid,
              ':product_reference' => $products_row->product_reference,
              ':seller_code' => $sellers_row->seller_code,
              ':start_date' => $startdate,
              ':end_date' => $enddate

              ));

            $customers_array = $customers_sql->fetchAll(\PDO::FETCH_ASSOC);

          /* 

            Prints the product and array if the customer code is not empty 

          */

            if (@$customers_array[0]['customer_code'] != '')

            {

              echo '<div class="row">
                      <div class="col-md-12" style="padding: 1% 1% 1% 1%">
                        <div class="col-md-7"> 
                          <a class="accordion-toggle" data-toggle="collapse" href="#'.trim($products_row->product_reference).'">' 
                          .$products_row->product_reference . ' - ' . $products_row->product_name. 
                          '</a>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-1" style="text-align: right">'.count($customers_array).'</div>
                        <div class="col-md-1"></div>
                      </div>
                    </div>
                    <div id="'.trim($products_row->product_reference).'" class="collapse">';

              foreach ($customers_array as $row) 

              {

                echo '<div class="row">
                        <div class="col-md-12">
                          <div class="col-md-7">'. $row['customer_code'] .' - '. $row['customer_name'] . '</div>
                          <div class="col-md-3" style="text-align: right">'. @$row['importe'] . '</div>
                          <div class="col-md-1" style="text-align: right">'. @$row['cantidad'] . '</div>
                          <div class="col-md-1" style="text-align: right">'. @$row['importe'] * @$row['cantidad'] . '</div>
                        </div>
                      </div>
                      ';

              }

              echo '</div>';

            }

          }
              echo '</div>
                  </div>
                </div>';

        }

      }
    }

    ?>