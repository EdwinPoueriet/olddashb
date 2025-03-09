<?php
namespace App\Legacy;

use App\Common\CompatibilityChecker;
use App\Services\CobrosReportService;
use App\Services\SalesReportService;

class General extends Session
{
    function __construct()
    {
        parent::__construct();

    }


    function GetReturns($startdate, $enddate, $condicion, $sellerlist){

        //nombre del cliente, factura, fecha devolucion, id devolucion, nombre vendedor
        $returns_sql = self::$con->prepare("SELECT 
			        r.return_id,
			        customer_name,
			        c.customer_code,
			        invoice_code,
			        seller_name,
			        s.seller_code,
			        return_date,
			        return_total, 
			        reason,
			        note
			        FROM ".self::$user_database.".returns_header r
			        LEFT JOIN ".self::$user_database.".sellers s ON s.seller_code = r.seller_code AND s.company_id = r.company_id
			        JOIN ".self::$user_database.".customers c ON c.customer_code = r.customer_code  AND r.company_id = c.company_id
			        JOIN (SELECT SUM(item_price*item_quantity) as return_total,return_id 
			               FROM ".self::$user_database.".returns_details rd GROUP BY return_id) as t ON t.return_id = r.return_id
			        WHERE return_date BETWEEN :start_date AND :end_date
				    AND r.company_id = :company_id 
				    AND (
				        CASE WHEN COALESCE(s.seller_code,'') <> '' THEN
				             s.seller_code ".$condicion." IN(".$sellerlist.")
				        ELSE TRUE
				        END
				        )") ;

        $returns_sql->bindParam(':start_date', $startdate);

        $returns_sql->bindParam(':end_date', $enddate);

        $returns_sql->bindParam(':company_id', self::$default_company);

        $returns_sql->execute();

        $returns_sql =  $returns_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($returns_sql);


    }

    function ReturnsTable($returns_object)

    {

        echo '<table class="table user-list footable table-hover" data-paging-size="12"
                                   data-paging-position="right" data-paging="true" data-filtering="true"
                                   data-sorting="true"
                                   data-filter-text-only="true">
		<thead>
			<tr>
				<th>Id</th>
				<th data-type="html">Cliente</th>
				<th data-type="html">Vendedor</th>
				<th>Código Factura</th>	
				<th>Fecha</th>
				<th class="text-center" data-type="html" >Monto</th>
				<th  data-type="html" data-title="Detalle">Detalle</th>
			</tr>
		</thead>
		<tbody>';

        foreach ($returns_object as $row) {

            $dt = \DateTime::createFromFormat('Y-m-d',$row->return_date);
            echo '<tr>
                <td data-sort-value="'.$row->return_id.'">'.$row->return_id.'</td>
                <td><b>'.trim($row->customer_code).' - '.trim($row->customer_name).'</b></td>
                <td><b>'.trim($row->seller_code).' - '.trim($row->seller_name).'</b></td>
                 <td data-sort-value="'.$row->invoice_code.'">'.$row->invoice_code.'</td>
				<td>'.$dt->format('d-m-Y').'</td>
				<td style="text-align: right"><span class="label label-primary"> $ '.number_format($row->return_total, 2, '.', ',').'</span></td>
				';

            echo'
						<td>
							<form method="post" action="return" target="_blank">
								<input type="hidden" id="customer_code" name="customer_code" value="'.$row->customer_code.'">
								<input type="hidden" id="return_id" name="return_id" value="'.$row->return_id.'">
	                            <input type="hidden" id="seller_code" name="seller_code" value="'.$row->seller_code.'">
								<input type="hidden" id="seller_name" name="seller_name" value="'.$row->seller_name.'">
								<input type="hidden" id="return_total" name="return_total" value="'.$row->return_total.'">
								<input type="hidden" id="invoice_code" name="invoice_code" value="'.$row->invoice_code.'">
								<input type="hidden" id="return_date" name="return_date" value="'.$dt->format('d-m-Y').'">
								<input type="hidden" id="reason" name="reason" value="'.$row->reason.'">
								<input type="hidden" id="note" name="note" value="'.$row->note.'">
								<button style="background: transparent; border: none; color: #337ab7" type="submit"><b>Ver</b></button>
							</form>
						</td>
					</tr>
					';
        }


        echo'</tbody>
			 </table>
			';

    }

    function GetOrders($condicion, $sellerlist, $startdate, $enddate, $type)
    {

        if ($type == "cantidad")
        {
            $SQL = $orders_sql = "SELECT 
			order_id 
			FROM ".self::$user_database.".orders_header 
			WHERE company_id = :cid 
			AND order_date BETWEEN :start_date AND :end_date 
			AND seller_code ".$condicion." IN(".$sellerlist.")";

        } else if ($type == "monto")

        {

            $SQL = "SELECT 
			SUM((order_gross_amount + order_tax_amount)-order_discount_amount) as totals 
			FROM ".self::$user_database.".orders_header 
			WHERE company_id = :cid 
			AND order_date BETWEEN :start_date AND :end_date 
			AND seller_code ".$condicion." IN(".$sellerlist.")";

        } else if ($type == "grafico-cantidad")

        {

            $SQL = "SELECT 
			x.seller_code as label,
			COALESCE((SELECT COUNT(vd.order_gross_amount) 
			FROM ".self::$user_database.".orders_header vd 
			WHERE vd.seller_code = x.seller_code 
			AND vd.order_date BETWEEN :start_date AND :end_date 
			AND vd.company_id= :cid),0) as value 
			FROM ".self::$user_database.".sellers x 
			WHERE x.company_id = :cid 
			AND x.seller_code ".$condicion." IN(".$sellerlist.") 
			ORDER BY x.seller_code ";

        } else if ($type == "grafico-monto")

        {

            $SQL = "SELECT 
			x.seller_code as x,
			COALESCE((SELECT SUM(vd.order_gross_amount + vd.order_tax_amount) 
			FROM ".self::$user_database.".orders_header vd 
			WHERE vd.seller_code = x.seller_code 
			AND vd.order_date BETWEEN :start_date AND :end_date 
			AND vd.company_id='".self::$default_company."'),0) as y 
			FROM ".self::$user_database.".sellers x 
			WHERE x.company_id = :cid 
			AND x.seller_code ".$condicion." IN(".$sellerlist.") 
			ORDER BY x.seller_code ";

        } else

            return 0;

        $orders_sql = self::$con->prepare($SQL);

        $orders_sql->bindParam(':start_date', $startdate);

        $orders_sql->bindParam(':end_date', $enddate);

        $orders_sql->bindParam(':cid', self::$default_company);

        $orders_sql->execute();

        $orders_sql = $orders_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($orders_sql);

    }

    function ReportsInitialJsData()
    {
        echo 'var collectorsList = ' . $this->GetCollectors() . '; var sellersList = ' . $this->GetSellers() .
            ';var sellersListInactive = '.$this->GetInactiveSellers().';  var companyList = ' . $this->GetCompaniesCxc() . '; var warehouses = '. $this->getHouse() .'; var families = '.$this->getFamilies() . '; var brands = '.$this->getBrandsdd()
            .'; var classifications = '. $this->getClassificationss(). '; var subclassifications = '. $this->getSubclassificationss()
            .'; var customers = '. $this->getCustomerss();
    }
    function getCustomerss(){
        $sql = self::$con->prepare('SELECT customer_code, customer_name
                FROM '.self::$user_database.'.customers
                WHERE company_id = '.self::$default_company);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }

    function getClassificationss(){
        $sql = self::$con->prepare('SELECT classification_code, classification_name
                FROM '.self::$user_database.'.classifications
                WHERE company_id = '.self::$default_company);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);


    }
    function getSubclassificationss(){
        $sql = self::$con->prepare('SELECT subclassification_code, subclassification_name
                FROM '.self::$user_database.'.subclassifications
                WHERE company_id = '.self::$default_company);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);


    }

    function getBrandsdd(){
        $sql = self::$con->prepare('SELECT * 
                FROM '.self::$user_database.'.brands');

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }

    function getHouse(){
        $sql = self::$con->prepare('SELECT warehouse_code 
                FROM '.self::$user_database.'.warehouses
                where company_id = '.self::$default_company.' GROUP BY warehouse_code');

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }
    function getFamilies(){
        $sql = self::$con->prepare('SELECT family_code, family_name 
                FROM '.self::$user_database.'.product_families
                where company_id = '.self::$default_company);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);



    }

    function GetCompanies () {
        $sql = self::$con->prepare('SELECT company_name, company_id
			FROM '.self::$user_database.'.companies');
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);
    }

    function GetCompaniesCxc () {
        $sql = self::$con->prepare('
          SELECT company_name, company_id
		  FROM '.self::$user_database.'.companies 
		  WHERE company_id IN ('.self::documentCompaniesString().')');
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);
    }


    function DevolucionesDetallado ($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $vendedor
         * @var $company
         * @var $consolidado_group
         */


        $verify = "SELECT * FROM  ".self::$user_database.".returns_header LIMIT 1";


        $sql = self::$con->prepare($verify);
        $sql->execute();
        $verify = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $text = "SELECT";
        if (isset($verify[0]['start_date'])){
            $text .= " rh.start_date date";
        }else{
            $text .= " rh.return_date date";
        }
        $text .= " ,rh.company_id, rh.customer_code, c.customer_name, rh.return_id, rh.invoice_code, rh.status, rh.reason, 
                rh.return_code, rh.seller_code, s.seller_name, 
                (
                  SELECT SUM(item_price*item_quantity)
                  FROM ".self::$user_database.".returns_details rd
                  WHERE rh.return_id = rd.return_id
                ) TOTAL
                FROM ".self::$user_database.".returns_header rh
            INNER JOIN ".self::$user_database.".sellers s ON (rh.seller_code = s.seller_code AND s.company_id = ".self::$company_cxc.")
                INNER JOIN ".self::$user_database.".customers c ON (rh.customer_code = c.customer_code AND c.company_id = ".self::$company_cxc.")
               	WHERE rh.company_id IN (".self::documentCompaniesString().") AND rh.return_date BETWEEN :start_date AND :end_date";


        if ($vendedor != 'todos')
            $text .= " AND rh.seller_code = ".$vendedor;

        if ($company != 'consolidado') {
            $text.= " AND rh.company_id = :cid ";
        }

        $text .= " ORDER BY  date ASC";




        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

//        if ($company != 'consolidado' || $consolidado_group == 'separado') {
//            $array = array_group_by($sql,'company_id','seller_code');
//            ksort($array);
//        }else {
        if ($consolidado_group == 'vendedor'){
            $array = array_group_by($sql,'seller_code');
            return json_encode($array);
        }
        return json_encode($sql);

//        }


    }

    function VentasDetallado ($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $vendedor
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT  oh.company_id, s.seller_name, oh.seller_code, oh.customer_code, order_reference,
                  CASE WHEN oh.customer_code IS NULL OR oh.customer_code = \"\" THEN cc.customer_name ELSE c.customer_name END as customer_name, 
                  order_code,oh.order_id, order_gross_amount,order_discount_amount ,oh.status, oh.order_date,oh.order_date_time,
                  order_discount_percent, order_tax_amount, (order_gross_amount-order_discount_amount+order_tax_amount) total,
                  oh.order_latitud, oh.order_longitud, c.customer_longitude, c.customer_latitude
                FROM ".self::$user_database.".orders_header oh
                INNER JOIN ".self::$user_database.".sellers s ON 
                (oh.seller_code COLLATE latin1_spanish_ci = s.seller_code COLLATE latin1_spanish_ci AND s.company_id = ".self::$company_cxc.")
                LEFT JOIN ".self::$user_database.".customers c ON 
                (oh.customer_code COLLATE latin1_spanish_ci =  c.customer_code COLLATE latin1_spanish_ci AND c.company_id = ".self::$company_cxc.")
                LEFT JOIN ".self::$user_database.".customer_cash cc ON ( cc.order_id = oh.order_id  AND cc.company_id = ".self::$company_cxc.")
               	WHERE oh.order_date BETWEEN :start_date AND :end_date
               	AND oh.company_id IN (".self::documentCompaniesString().")
               	";

        if ($vendedor != 'todos')
            $text .= " AND oh.seller_code = '".$vendedor."'";

        if ($company != 'consolidado') {
            $text.= " AND oh.company_id = :cid ";
        }

        $text.=" ORDER BY oh.order_date_time ASC";

        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $service = new SalesReportService();
        $result = [];
        foreach ($sql as $row) {
            $in = $service->checkWasInLocation(
                $row['order_latitud'], $row['order_longitud'], $row['customer_latitude'],$row['customer_longitude'], 500);
            $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

            if ($row['order_latitud'] !== '0' && $row['order_latitud'] !== '0' ) {
                $row['view_location'] =  $row['order_latitud'].','.$row['order_longitud'].'|'.$row['customer_latitude'].','.$row['customer_longitude'];
            } else {
                $row['view_location'] = null;
            }

            array_push($result, $row);
        }



        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($result,'company_id','seller_code');
            ksort($array);
            return json_encode($array);
        }
        return json_encode($result);
    }

    function VentasDetallado2($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $vendedor
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT  oh.company_id, s.seller_name, oh.seller_code, oh.customer_code, order_reference,
                  CASE WHEN oh.customer_code IS NULL OR oh.customer_code = \"\" THEN cc.customer_name ELSE c.customer_name END as customer_name, 
                  order_code,oh.order_id, order_gross_amount,order_discount_amount ,oh.status, oh.order_date,oh.order_date_time,
                  order_discount_percent, order_tax_amount, (order_gross_amount-order_discount_amount+order_tax_amount) total,
                  oh.order_latitud, oh.order_longitud, c.customer_longitude, c.customer_latitude
                FROM ".self::$user_database.".orders_header oh
                INNER JOIN ".self::$user_database.".sellers s ON 
                (oh.seller_code COLLATE latin1_spanish_ci = s.seller_code COLLATE latin1_spanish_ci AND s.company_id = ".self::$company_cxc.")
                LEFT JOIN ".self::$user_database.".customers c ON 
                (oh.customer_code COLLATE latin1_spanish_ci =  c.customer_code COLLATE latin1_spanish_ci AND c.company_id = ".self::$company_cxc.")
                LEFT JOIN ".self::$user_database.".customer_cash cc ON ( cc.order_id = oh.order_id  AND cc.company_id = ".self::$company_cxc.")
               	WHERE oh.order_date BETWEEN :start_date AND :end_date
               	AND oh.company_id IN (".self::documentCompaniesString().")
               	";

        if ($vendedor != 'todos')
            $text .= " AND oh.seller_code = '".$vendedor."'";

        if ($company != 'consolidado') {
            $text.= " AND oh.company_id = :cid ";
        }

        $text.=" ORDER BY oh.order_date_time ASC";

        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $service = new SalesReportService();
        $result = [];
        foreach ($sql as $row) {
            $in = $service->checkWasInLocation(
                $row['order_latitud'], $row['order_longitud'], $row['customer_latitude'],$row['customer_longitude'], 100);
            $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

            if ($row['order_latitud'] !== '0' && $row['order_latitud'] !== '0' ) {
                $row['view_location'] =  $row['order_latitud'].','.$row['order_longitud'];
            } else {
                $row['view_location'] = null;
            }

            array_push($result, $row);
        }



        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($result,'company_id','seller_code');
            ksort($array);
        }else {
            $array = array_group_by($result,'seller_code');

        }
        return json_encode($array);
    }

    function VentasReportCompact($params){
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $vendedor
         * @var $company
         * @var $consolidado_group
         */



        $text = "SELECT ";

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $text.= " oh.company_id,";
        }


        $text .= " s.seller_name, oh.seller_code, COUNT(*) as COUNT, SUM(order_gross_amount) as bruto, SUM(order_discount_amount) discount, sum(order_tax_amount) tax, (SUM(order_gross_amount)-SUM(order_discount_amount)+sum(order_tax_amount)) total
FROM ".self::$user_database.".orders_header oh
INNER JOIN  ".self::$user_database.".sellers s ON 
                (oh.seller_code COLLATE latin1_spanish_ci = s.seller_code COLLATE latin1_spanish_ci AND s.company_id = 0001)
        WHERE oh.order_date BETWEEN :start_date AND :end_date
               	 AND oh.company_id IN (".self::documentCompaniesString().") 
        ";

        if ($vendedor != 'todos'){

            $text .=" AND oh.seller_code = ".$vendedor;

        }






        if ($company != 'consolidado') {
            $text.= " AND oh.company_id = :cid GROUP BY oh.company_id, s.seller_name, oh.seller_code ORDER BY ".$order_by." ".$order_method;

        }else{
            if ($consolidado_group == 'separado') {
                $text.= " GROUP BY oh.company_id, s.seller_name, oh.seller_code ORDER BY ".$order_by." ".$order_method;

            } elseif ($consolidado_group == 'consolidado') {
                $text.= " GROUP BY s.seller_name, oh.seller_code ORDER BY ".$order_by." ".$order_method ;
            }
        }




        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($sql,'company_id','seller_code');
            ksort($array);
            return json_encode($array);
        }else {
            return json_encode(array_group_by($sql,'seller_code'));
        }






    }

    function VentasProductos($params){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 180); //3 minutes
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         * @var $warehouse
         * @var $classification_code
         * @var $subclassification_code
         * @var $family_code
         * @var $brand_id
         * @var $order_type
         * @var $company
         * @var $consolidado_group
         */


        $other = 'SELECT order_id FROM '.self::$user_database.'.orders_header WHERE order_date BETWEEN :start_date AND :end_date';

        if ($company != 'consolidado') {
            $other .= ' AND company_id = '.$company;
        }

        if($order_type != 'Todos'){
            $other .= ' AND order_type = '.$order_type;
        }
        if($seller_code != 'Todos'){
            $other .= ' AND seller_code = '.$seller_code;
        }
        if ($customer_code != 'Todos'){
            $other .= ' AND customer_code = '.$customer_code;
        }
        $id = [];

        $sql = self::$con->prepare($other);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);



        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if (count($sql) == 0){
            return json_encode([]);

        }
        foreach ($sql as $item){
            array_push($id, $item['order_id']);
        }

        $text = 'SELECT';

        if($consolidado_group != '0'){
            if ($consolidado_group == "vendedor"){
                $text .= ' sl.seller_name, sl.seller_code,';
            }else{
                $text .= ' cm.customer_name, cm.customer_code,';
            }
        }




        $text .= ' pr.product_reference, pr.product_name,  pr.product_code, SUM(ord.order_item_discount_amount) descuento, sum(ord.order_item_quantity) cantidad, (SUM(ord.order_item_amount)-SUM(ord.order_item_discount_amount)+sum(ord.order_item_tax_amount)) neto, SUM(ord.order_item_amount) bruto  
                FROM '.self::$user_database.'.orders_details ord   
                INNER JOIN '.self::$user_database.'.products pr
                ON pr.product_reference = ord.product_reference AND pr.company_id = ord.company_id';

        if($warehouse != 'Todas'){
            $text .= ' INNER JOIN '.self::$user_database.'.product_warehouses pw
                       ON pw.product_reference = pr.product_reference AND pw.warehouse_code = '.$warehouse;
        }

        if($consolidado_group != '0'){
            if ($consolidado_group == "vendedor"){
                $text .= ' INNER JOIN '.self::$user_database.'.orders_header oh
                           ON oh.order_id = ord.order_id  AND oh.company_id =  ord.company_id
                           INNER JOIN '.self::$user_database.'.sellers sl
                           ON sl.seller_code = oh.seller_code AND sl.company_id = oh.company_id';
            }else{
                $text .= ' INNER JOIN '.self::$user_database.'.orders_header oh 
                           ON oh.order_id = ord.order_id AND oh.company_id = ord.company_id
                           INNER JOIN '.self::$user_database.'.customers cm
                           ON cm.customer_code = oh.customer_code AND cm.company_id = oh.company_id';
            }
        }

        $text .= ' WHERE ord.order_id IN ('.implode(',',$id).')';

        if($classification_code != 'Todas'){
            $text .= ' AND pr.classification_code = '.$classification_code;

        }
        if($subclassification_code != 'Todas'){
            $text .= ' AND pr.subclassification_code = '.$subclassification_code;
        }
        if($family_code != 'Todas'){
            $text .= ' AND pr.family_code = '.$family_code;
        }
        if($brand_id != 'Todas'){
            $text .= ' AND pr.brand_id = '.$brand_id;
        }



        if($consolidado_group != '0'){
            if ($consolidado_group == "vendedor"){
                $text .= ' GROUP BY pr.product_reference, pr.product_code, pr.product_name, sl.seller_code, sl.seller_name';
            }else{
                $text .= ' GROUP BY pr.product_reference, pr.product_code, pr.product_name, cm.customer_code, cm.customer_name';
            }
        }else{
            $text .= ' GROUP BY pr.product_reference, pr.product_code, pr.product_name';
        }






        $sql2 = self::$con->prepare($text);
        $sql2->execute();
        $sql2 = $sql2->fetchAll(\PDO::FETCH_ASSOC);
        if ($consolidado_group != "0") {
            if ($consolidado_group == "vendedor"){
                $array = array_group_by($sql2,'seller_code');
                ksort($array);

            }else{
                $array = array_group_by($sql2,'customer_code');
                ksort($array);
            }


            return json_encode($array);
        }else {
            return json_encode($sql2);
        }




    }

    function visitNoEfective($params){
        extract($params);
        /**
         * @var $sellers
         * @var $customer
         * @var $rango
         */


        $verify = "SELECT * FROM  ".self::$user_database.".visits LIMIT 1";


        $sql = self::$con->prepare($verify);
        $sql->execute();
        $verify = $sql->fetchAll(\PDO::FETCH_ASSOC);
        $text = "SELECT";

        if (isset($verify[0]['latitude']) && isset($verify[0]['longitude'])){
            $text .= " v.latitude, v.longitude,";
        }

        $text .= " v.visit_id, v.customer_code, v.visit_note, vt.visit_type_name, v.seller_code, cm.customer_name, sl.seller_name, v.visit_date_time, cm.customer_latitude, cm.customer_longitude
                 FROM ".self::$user_database.".visits v
           
                 LEFT JOIN  ".self::$user_database.".customers cm
                 ON cm.customer_code = v.customer_code AND cm.company_id = v.company_id
                 INNER JOIN  ".self::$user_database.".sellers sl 
                 ON sl.seller_code = v.seller_code AND sl.company_id = v.company_id
                 INNER JOIN ".self::$user_database.".visit_types vt
                 ON vt.visit_type_id = v.visit_type_id
                 WHERE v.company_id = '".self::$company_cxc."' AND v.visit_type_id > 0 AND
                 visit_date BETWEEN :start_date AND :end_date";

        if ($sellers != '0'){
            $text .= " AND sl.seller_code = '".$sellers."'";

        }

        if ($customer != '0'){
            $text .= " AND cm.customer_code = '".$customer."'";

        }
        $text .= " ORDER BY visit_date asc";


        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);



        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);


        $service = new SalesReportService();
//        return json_encode($sql);
        if (isset($verify[0]['latitude']) && isset($verify[0]['longitude'])){
            $result = [];


            foreach ($sql as $row) {
                $in = $service->checkWasInLocation(
                    $row['latitude'], $row['longitude'], $row['customer_latitude'],$row['customer_longitude'], 100);
                $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

                if ($row['latitude'] !== '0' && $row['latitude'] !== '0' ) {
                    $row['view_location'] =  $row['latitude'].','.$row['longitude'];
                } else {
                    $row['view_location'] = null;
                }

                array_push($result, $row);
            }

            return json_encode($result);


        }

        $result = [];
        foreach ($sql as $row) {

            $row['in_location'] =  'N/A';


                $row['view_location'] = null;


            array_push($result, $row);
        }

        return json_encode($result);

//        return json_encode($sql);


    }


    function DepositosReportCompact ($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT ";

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $text.= " dh.company_id,";
        }

        $text .= "s.seller_name, 
                  dh.seller_code, 
                SUM(dh.total_amount) TOTAL,
                COUNT(*) as COUNT
                FROM ".self::$user_database.".deposit_of_invoices_header dh, ".self::$user_database.".sellers s
                WHERE dh.seller_code = s.seller_code
                AND s.company_id = ".self::$company_cxc."
                AND dh.date BETWEEN :start_date AND :end_date
                AND dh.company_id IN (".self::documentCompaniesString().")
                ";

        if ($company != 'consolidado') {
            $text.= " AND dh.company_id = :cid 
                      GROUP BY dh.company_id,s.seller_name,dh.seller_code
                      ORDER BY ".$order_by." ".$order_method;

        }else{
            if ($consolidado_group == 'separado') {
                $text.= " GROUP BY dh.company_id,s.seller_name,dh.seller_code
                          ORDER BY ".$order_by." ".$order_method;

            } elseif ($consolidado_group == 'consolidado') {
                $text.= " GROUP BY s.seller_name,dh.seller_code
                          ORDER BY ".$order_by." ".$order_method;
            }
        }



        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($sql,'company_id','seller_code');
            ksort($array);
            return json_encode($array);
        }else {
            return json_encode(array_group_by($sql,'seller_code'));
        }

    }

    function DepositosReportFull ($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $montos
         * @var $vendedor
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT ";
        //Si es consolidado, no necesito companyId
        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $text.= " dh.company_id,";
        }

        $text .= "s.seller_name, s.seller_code, c.customer_name, c.customer_code,
                  dh.cost_center_code, dh.deposit_of_invoices_id, dh.date,dh.total_amount
                FROM ".self::$user_database.".deposit_of_invoices_header dh, 
                     ".self::$user_database.".customers c,
                     ".self::$user_database.".sellers s
                WHERE dh.customer_code = c.customer_code
                AND dh.seller_code = s.seller_code      
                AND dh.date BETWEEN :start_date AND :end_date";

        if ($company != 'consolidado') {
            $text.= " AND dh.company_id = :cid ";
        }

        if ($vendedor != 'todos')
            $text .= " AND dh.seller_code = ".$vendedor;

        $text.=" ORDER BY dh.seller_code, ".$order_by." ".$order_method;

        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }
        $sql->execute();
        $deposits = $sql->fetchAll(\PDO::FETCH_ASSOC);

        $depositsresult = [];
        foreach ($deposits as $deposit) {
            $detail = self::$con->prepare("
            SELECT invoice_code, payment_code,invoice_date,invoice_expiration_date,invoice_amount,invoice_balance
            FROM  ".self::$user_database.".deposit_of_invoices_header dh, 
            ".self::$user_database.".deposit_of_invoices_details dd
            WHERE dh.deposit_of_invoices_id = dd.deposit_of_invoices_id
            AND dh.deposit_of_invoices_id =  ".$deposit['deposit_of_invoices_id']."
            ORDER BY dd.invoice_date DESC
            ");
            $detail->execute();
            $detail = $detail->fetchAll(\PDO::FETCH_ASSOC);
            $deposit['details'] = $detail;
            array_push($depositsresult,$deposit);
        }

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($depositsresult,'company_id','seller_code');
            ksort($array);
        }else {
            $array = array_group_by(    $depositsresult,'seller_code');
        }
        return json_encode($array);

    }


    function CobrosReportCompact ($params ) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT ";

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $text.= " rh.company_id,";
        }

        $text .= "co.collector_name, 
                  rh.collector_code, 
                  SUM(cash_amount) EFECTIVO, 
                    COUNT(*) as COUNT,
                  SUM(case when rh.futuristic_check = 0 then rh.check_amount else 0 end) CHEQUE,
                SUM(case when rh.futuristic_check = 1 then rh.check_amount else 0 end) CHEQUEFUT,
                SUM(receipt_income_amount) TOTAL
                FROM ".self::$user_database.".receivables_header rh, ".self::$user_database.".collectors co
                WHERE rh.collector_code = co.collector_code
                AND rh.receipt_income_date BETWEEN :start_date AND :end_date
                AND rh.company_id IN (".self::documentCompaniesString().")
                ";

        if ($company != 'consolidado') {
            $text.= " AND rh.company_id = :cid 
                      GROUP BY rh.company_id,co.collector_name,co.collector_code
                      ORDER BY ".$order_by." ".$order_method;

        }else{
            if ($consolidado_group == 'separado') {
                $text.= " GROUP BY rh.company_id,co.collector_name,co.collector_code
                          ORDER BY ".$order_by." ".$order_method;

            } elseif ($consolidado_group == 'consolidado') {
                $text.= " GROUP BY co.collector_name,co.collector_code
                          ORDER BY ".$order_by." ".$order_method;
            }
        }


        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($sql,'company_id','collector_code');
            ksort($array);
            return json_encode($array);
        }else {
            return json_encode(array_group_by($sql,'collector_code'));
        }

    }

    function CobrosReportFull ($params) {

        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $montos
         * @var $cobrador
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT ";
        //Si es consolidado, no necesito companyId

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $text.= " rh.company_id,";
        }
        $append = "";


        $discount = "";
        if (CompatibilityChecker::columnExists('discount_amount','receivables_header')) {
            $discount = "discount_amount ,";
        }
        $haslatitud = CompatibilityChecker::columnExists('latitude','receivables_header')
            && CompatibilityChecker::columnExists('longitude','receivables_header');
        if ( $haslatitud) {
            $append = " rh.latitude, rh.longitude, c.customer_latitude, c.customer_longitude, ";
        }
        $text .= "s.seller_name, s.seller_code, customer_name, c.customer_code, receipt_income_code,
                receipt_income_amount, cash_amount, futuristic_check, futuristic_check_date, rh.status,
                check_number, rh.collector_code, co.collector_name,rh.receipt_code,receipt_income_reference, 
                receipt_income_date,".$discount." receipt_income_date_time, 
               ".$append."
                (SELECT check_amount FROM ".self::$user_database.".receivables_header rh2 WHERE 
                rh2.receipt_income_code = rh.receipt_income_code AND rh2.futuristic_check = 1 ) futuristic_check_amount,
                  (SELECT check_amount FROM ".self::$user_database.".receivables_header rh2 WHERE 
                rh2.receipt_income_code = rh.receipt_income_code AND rh2.futuristic_check = 0 )   check_amount
                FROM ".self::$user_database.".receivables_header rh
                INNER JOIN ".self::$user_database.".sellers s ON (rh.seller_code = s.seller_code AND s.company_id = ".self::$company_cxc.")
                INNER JOIN ".self::$user_database.".customers c ON (rh.customer_code = c.customer_code AND c.company_id = ".self::$company_cxc.")
                INNER JOIN ".self::$user_database.".collectors co ON (rh.collector_code =  co.collector_code AND co.company_id = ".self::$company_cxc.")
                WHERE rh.receipt_income_date BETWEEN :start_date AND :end_date
                AND rh.company_id IN (".self::documentCompaniesString().")
                ";

        if ($company != 'consolidado') {
            $text.= " AND rh.company_id = :cid ";
        }

        if ($cobrador != 'todos')
            $text .= " AND rh.collector_code = '".$cobrador."'";

        if ($montos != 'todos'){
            switch ($montos){
                case 'efectivo';
                    $text .= " AND (cash_amount <> 0 AND check_amount = 0)";
                    break;
                case 'cheques';
                    $text .= " AND (check_amount <> 0 AND cash_amount = 0)";
                    break;
                case 'futuristas';
                    $text .= " AND (check_amount <> 0 AND cash_amount = 0 AND futuristic_check = 1)";
                    break;
            }
        }

        $text.= " ORDER BY receipt_income_date_time ASC ";
//        echo $text; die();
        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);




        $service = new CobrosReportService();
        $result = [];


        foreach ($sql as $row) {
            if ($haslatitud) {
                $in = $service->checkWasInLocation(
                    $row['latitude'], $row['longitude'], $row['customer_latitude'],$row['customer_longitude'], 100);
                $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

                if ($row['latitude'] !== '0' && $row['latitude'] !== '0' ) {
                    $row['view_location'] =  $row['latitude'].','.$row['longitude'];
                } else {
                    $row['view_location'] = null;
                }
            } else {
                $row['view_location'] = null;
                $row['in_location'] = 'N/A';
            }


            array_push($result, $row);
        }


        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($result,'company_id','collector_code');
            ksort($array);
        }else {
            $array = $result;
//            return json_encode($result);
        }

        return ['options' => ['montos' => $montos] ,'data' => json_encode($array)];

    }

    function CobrosReportFull2 ($params) {

        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $montos
         * @var $cobrador
         * @var $company
         * @var $consolidado_group
         */

        $text = "SELECT ";
        //Si es consolidado, no necesito companyId

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $text.= " rh.company_id,";
        }
        $append = "";


        $discount = "";
        if (CompatibilityChecker::columnExists('discount_amount','receivables_header')) {
            $discount = "discount_amount ,";
        }
        $haslatitud = CompatibilityChecker::columnExists('latitude','receivables_header')
            && CompatibilityChecker::columnExists('longitude','receivables_header');
        if ( $haslatitud) {
            $append = " rh.latitude, rh.longitude, c.customer_latitude, c.customer_longitude, ";
        }
        $text .= "s.seller_name, s.seller_code, customer_name, c.customer_code, receipt_income_code,
                receipt_income_amount, cash_amount, futuristic_check, futuristic_check_date, rh.status,
                check_number, rh.collector_code, co.collector_name,rh.receipt_code,receipt_income_reference, 
                receipt_income_date,".$discount." receipt_income_date_time, 
               ".$append."
                (SELECT check_amount FROM ".self::$user_database.".receivables_header rh2 WHERE 
                rh2.receipt_income_code = rh.receipt_income_code AND rh2.futuristic_check = 1 ) futuristic_check_amount,
                  (SELECT check_amount FROM ".self::$user_database.".receivables_header rh2 WHERE 
                rh2.receipt_income_code = rh.receipt_income_code AND rh2.futuristic_check = 0 )   check_amount
                FROM ".self::$user_database.".receivables_header rh
                INNER JOIN ".self::$user_database.".sellers s ON (rh.seller_code = s.seller_code AND s.company_id = ".self::$company_cxc.")
                INNER JOIN ".self::$user_database.".customers c ON (rh.customer_code = c.customer_code AND c.company_id = ".self::$company_cxc.")
                INNER JOIN ".self::$user_database.".collectors co ON (rh.collector_code =  co.collector_code AND co.company_id = ".self::$company_cxc.")
                WHERE rh.receipt_income_date BETWEEN :start_date AND :end_date
                AND rh.company_id IN (".self::documentCompaniesString().")
                ";

        if ($company != 'consolidado') {
            $text.= " AND rh.company_id = :cid ";
        }

        if ($cobrador != 'todos')
            $text .= " AND rh.collector_code = '".$cobrador."'";

        if ($montos != 'todos'){
            switch ($montos){
                case 'efectivo';
                    $text .= " AND (cash_amount <> 0 AND check_amount = 0)";
                    break;
                case 'cheques';
                    $text .= " AND (check_amount <> 0 AND cash_amount = 0)";
                    break;
                case 'futuristas';
                    $text .= " AND (check_amount <> 0 AND cash_amount = 0 AND futuristic_check = 1)";
                    break;
            }
        }

        $text.= " ORDER BY receipt_income_date_time ASC ";
//        echo $text; die();
        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);




        $service = new CobrosReportService();
        $result = [];


        foreach ($sql as $row) {
            if ($haslatitud) {
                $in = $service->checkWasInLocation(
                    $row['latitude'], $row['longitude'], $row['customer_latitude'],$row['customer_longitude'], 100);
                $row['in_location'] = !is_null($in) ? ($in ? 'Si' : 'No') : 'N/A';

                if ($row['latitude'] !== '0' && $row['latitude'] !== '0' ) {
                    $row['view_location'] =  $row['latitude'].','.$row['longitude'];
                } else {
                    $row['view_location'] = null;
                }
            } else {
                $row['view_location'] = null;
                $row['in_location'] = 'N/A';
            }


            array_push($result, $row);
        }


        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($result,'company_id','collector_code');
            ksort($array);
        }else {
            $array = array_group_by($result,'collector_code');
        }

        return ['options' => ['montos' => $montos] ,'data' => json_encode($array)];

    }





    function CobrosAdelanto ($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $company
         * @var $cobrador
         * @var $consolidado_group
         */
        $text = "SELECT  ar.company_id, co.collector_name, ar.collector_code, ar.customer_code, ar.advance_receipt_id, advance_receipt_id_reference,
                  c.customer_name, advance_receipt_code, concept_one, amount, cash_amount, check_amount,
                  check_number,card_amount,card_number,ar.status,ar.date
                FROM ".self::$user_database.".advance_receipts ar
                INNER JOIN ".self::$user_database.".collectors co ON
                 (ar.collector_code COLLATE latin1_spanish_ci = co.collector_code COLLATE latin1_spanish_ci AND co.company_id = ".self::$company_cxc.")
                INNER JOIN ".self::$user_database.".customers c ON 
                 (ar.customer_code COLLATE latin1_spanish_ci =  c.customer_code COLLATE latin1_spanish_ci AND c.company_id = ".self::$company_cxc.")
               	WHERE ar.date BETWEEN :start_date AND :end_date
               	 AND ar.company_id IN (".self::documentCompaniesString().") ";

        if ($company != 'consolidado') {
            $text.= " AND ar.company_id = :cid ";
        }

        if ($cobrador != 'todos')
            $text .= " AND ar.collector_code = ".$cobrador;

        $text .= " ORDER BY ".$order_by." ".$order_method;

        $sql = self::$con->prepare($text);
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);


        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($sql,'company_id','collector_code');
            ksort($array);
        }else {
            $array = array_group_by($sql,'collector_code');
        }
        return json_encode($array);
    }

    function CuentaCobrar ($params) {
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $rango_vnc
         * @var $company
         * @var $vendedor
         * @var $consolidado_group
         */

        $fechas = "";
        $sql = "SELECT ri.company_id,  ri.seller_code, s.seller_name, ri.invoice_code, ri.customer_code, c.customer_name, ri.invoice_date, ri.invoice_expiration_date, ri.invoice_balance FROM ".self::$user_database.".receivables_invoices ri INNER JOIN  ".self::$user_database.".sellers s ON ri.seller_code=s.seller_code AND s.company_id = ".self::$company_cxc." INNER JOIN ".self::$user_database.".customers c ON ri.customer_code = c.customer_code and c.company_id = ".self::$company_cxc." WHERE ri.company_id IN (".self::documentCompaniesString().")";

        if($rango != ''){
            $fechas .= " AND ri.invoice_date BETWEEN :start_date AND :end_date";

        }
        if($rango_vnc != ''){

            $fechas .= " AND ri.invoice_expiration_date BETWEEN :start AND :end;";

        }



        $sql.=$fechas;


        if ($company != 'consolidado') {
            $sql.= " AND ri.company_id = :cid ";
        }

        if ($vendedor != 'todos')
            $sql .= " AND ri.seller_code = ".$vendedor;

        $sql .= " ORDER BY ri.invoice_date ASC";
//        return $sql;

        $sql = self::$con->prepare($sql);

        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        if($rango != ''){
            $dates = explode(' ',$rango);
            $start_date = trim($dates[0]);
            $end_date = trim($dates[2]);
            $sql->bindParam(':start_date', $start_date);
            $sql->bindParam(':end_date', $end_date);

        }
        if($rango_vnc != ''){

            $dates = explode(' ',$rango_vnc);
            $start= trim($dates[0]);
            $end = trim($dates[2]);
            $sql->bindParam(':start', $start);
            $sql->bindParam(':end', $end);

        }





        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);


        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($sql,'company_id');
            ksort($array);
        }else {
            $array = array_group_by($sql,'seller_code');
        }
        return json_encode($array);
    }

    public function CuentaCobrarCompact($params){
        extract($params);
        /**
         * @var $order_by
         * @var $order_method
         * @var $rango
         * @var $rango_vnc
         * @var $company
         * @var $consolidado_group
         */


        $fechas = "";


        $sql = "SELECT ";

        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $sql.= " ri.company_id,";
        }

        $sql .= "ri.seller_code, s.seller_name, SUM(ri.invoice_balance) total, COUNT(*) COUNT FROM ".self::$user_database.".receivables_invoices ri INNER JOIN  ".self::$user_database.".sellers s ON ri.seller_code=s.seller_code AND s.company_id = ".self::$company_cxc." WHERE ri.company_id IN (".self::documentCompaniesString().")";





        if($rango != ''){
            $fechas .= " AND ri.invoice_date BETWEEN :start_date AND :end_date";

        }
        if($rango_vnc != ''){

            $fechas .= " AND ri.invoice_expiration_date BETWEEN :start AND :end";

        }
        $sql.=$fechas;




        if ($company != 'consolidado') {
            $sql.= " AND ri.company_id = :cid GROUP BY ri.company_id, ri.seller_code, s.seller_name ORDER BY ri.seller_code ASC";

        }else{
            if ($consolidado_group == 'separado') {
                $sql.= " GROUP BY ri.company_id, ri.seller_code, s.seller_name ORDER BY ri.seller_code ASC";

            } elseif ($consolidado_group == 'consolidado') {
                $sql.= " GROUP BY ri.seller_code, s.seller_name ORDER BY ri.seller_code ASC";
            }
        }




        $sql = self::$con->prepare($sql);



        if ($company != 'consolidado') {
            $sql->bindParam(':cid', $company);
        }

        if($rango != ''){
            $dates = explode(' ',$rango);
            $start_date = trim($dates[0]);
            $end_date = trim($dates[2]);
            $sql->bindParam(':start_date', $start_date);
            $sql->bindParam(':end_date', $end_date);

        }
        if($rango_vnc != ''){

            $dates = explode(' ',$rango_vnc);
            $start= trim($dates[0]);
            $end = trim($dates[2]);
            $sql->bindParam(':start', $start);
            $sql->bindParam(':end', $end);

        }




        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);


        if ($company != 'consolidado' || $consolidado_group == 'separado') {
            $array = array_group_by($sql,'company_id','seller_code');
            ksort($array);
        }else {
            $array = array_group_by($sql,'seller_code');
        }
        return json_encode($array);


    }

    public function GetTableColumn($table,$column) {
        $query  = self::$con->prepare("SHOW COLUMNS FROM ".$table." LIKE '".$column."'");
        try{
            $query->execute();
            if($query->fetchColumn()) { return 1; }else{ return 0; }
        }catch(\Exception $e){die($e->getMessage()."SHOW COLUMNS FROM '".$table."' LIKE '".$column."'");}
    }

    function GetOrderInfo($order_id)

    {

        $sql_text = "SELECT 
			x.order_id, 
			x.order_date_finish, 
			x.order_date_time,";
        if ( $this->GetTableColumn(self::$user_database.".orders_header", "date_sent") ){
            $sql_text.= "COALESCE (x.date_sent,\" \") date_sent,";
        }
        $sql_text .="x.order_created_at, 
			x.order_gross_amount, 
			x.order_tax_amount, 
			x.order_discount_amount, 
			x.company_id, 
			x.order_date, 
			x.customer_code, 
			vd.seller_name, 
			x.seller_code, 
			vd.company_id, 
			cs.customer_name, 
			cs.company_id, 
			cs.customer_owner_or_contact, 
			cs.day_id, x.order_type, 
			x.order_latitud, 
			x.order_longitud, 
			cs.customer_longitude, 
			cs.customer_latitude
			FROM ".self::$user_database.".orders_header x
			LEFT JOIN ".self::$user_database.".customers cs ON(cs.company_id = x.company_id AND cs.customer_code = x.customer_code)
			LEFT JOIN ".self::$user_database.".sellers vd ON(vd.company_id = x.company_id AND vd.seller_code = x.seller_code)
			WHERE x.company_id = :cid 
			AND x.order_id = :order_id
			ORDER BY x.order_created_at DESC
			";

        $sales_details_sql = self::$con->prepare($sql_text);
        $sales_details_sql->bindParam(':cid', self::$default_company);

        $sales_details_sql->bindParam(':order_id', $order_id);

        $sales_details_sql->execute();

        $sales_details_sql = $sales_details_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($sales_details_sql);

    }

    function OrdersTable($orders_object)

    {
        echo '<table class="table user-list footable table-hover" data-filtering="true"
                                   data-sorting="true"
                                   data-filter-text-only="true">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>No. de orden</th>
				<th data-type="html" > Cliente</th>	
				<th data-type="html" > Vendedor</th>
				<th>Duración</th>
				<th class="text-center"  data-type="html">Monto</th>
				<th class="text-right" data-type="html">En ubicación</th>
				<th data-type="html" data-title="Detalle">Detalle</th>
			</tr>
		</thead>
		<tbody>';
        $total = 0;
        foreach ($orders_object as $row) {

            $dt = new \DateTime($row->order_date_finish);
            $st = new \DateTime($row->order_date_time);
            $row->totalventa = ($row->order_gross_amount + $row->order_tax_amount)-$row->order_discount_amount;
            $total+=$row->totalventa;
            echo '<tr data-total="'.$row->totalventa.'">
				<td>'.$st->format('d-m-Y H:i A').'</td>
				<td data-sort-value="'.$row->order_id.'">'.trim($row->order_id).' </td>
				<td><b>'.trim($row->customer_code).' - '.trim($row->customer_name).'</b></td>
				<td>'.trim($row->seller_code).' - '.trim($row->seller_name).'</td>
				<td>'.trim($st->diff($dt)->format("%h:%I:%s")).'</td>
				<td data-sort-value="'.$row->totalventa.'" style="text-align: right"><span class="label label-primary"> $ '.number_format($row->totalventa, 2, '.', ',').'</span></td>
				<td style="text-align: center">';
            if ($row->customer_latitude || $row->customer_longitude !== "")

                if (General::GetDistance($row->customer_latitude, $row->customer_longitude, $row->order_latitud, $row->order_longitud, "M") <= 0.0621371)
                    echo '<span class="label label-success location" data-latitude="'.$row->customer_latitude.'" data-longitude="'.$row->customer_longitude.'" style="cursor:pointer;">Sí</span>';
                else
                    echo '<span class="label label-danger location" data-latitude="'.$row->customer_latitude.'" data-longitude="'.$row->customer_longitude.'" style="cursor:pointer;">No</span>';
            else
                echo '<b class="label label-default">N/A</b>';

            $datesent = '';
            if (isset($row->date_sent)){
                $datesent  =$row->date_sent;
            }
            echo'</td>
						<td><center>
							<form method="post" action="order" target="_blank">                  
								<input type="hidden" id="customer_code" name="customer_code" value="'.$row->customer_code.'">
								<input type="hidden" id="order_id" name="order_id" value="'.$row->order_id.'">
								<input type="hidden" id="amount" name="amount" value="'.$row->order_gross_amount.'">
								<input type="hidden" id="day_id" name="day_id" value="'.$row->day_id.'">  
								<input type="hidden" id="seller" name="seller" value="'.$row->seller_name.'">  
								<input type="hidden" id="date" name="date" value="'.$row->order_date_time.'"> 
								<input type="hidden" id="date" name="date_sent" value="'.$datesent.'"> 
								<input type="hidden" id="order_type" name="order_type" value="'.$row->order_type.'">    
								<input type="hidden" id="order_datef" name="order_datef" value="'.$dt->format('h:i:s A').'">   
								<input type="hidden" id="order_dates" name="order_dates" value="'.$st->format('h:i:s A').'">    
								<button style="background: transparent; border: none; color: #337ab7" type="submit"><b>Ver</b></button>      
							</form>
						</center></td>
					</tr>
					';
        }

        echo'</tbody>
              <tfoot>
             
                <tr id="orderstotal" data-initialtotal="'.$total.'"></tr>    
          
              </tfoot>

			</table>
			';
//        <tr>
//      <td></td> <td></td> <td></td> <td></td> <td><strong>Monto total de Ventas</strong></td>
//       <td style="text-align: right"><span class="label label-primary" id="orderstotaltext"></span></td>
//    </tr>
//  </tfoot>

    }

    function GetReturnDetails($return_id){

        $return_detail_sql = self::$con->prepare("SELECT 
			p.product_name,
			brand_name,
			p.product_reference,
			item_quantity,
			item_price,
			(item_price*item_quantity) as importe
			FROM ".self::$user_database.".returns_details r
			JOIN ".self::$user_database.".products p ON p.product_reference = r.product_reference
			LEFT JOIN  ".self::$user_database.".brands b ON b.brand_id = p.brand_id
			WHERE r.company_id = :company_id 
			AND r.return_id = :return_id
			AND p.company_id = :cataloguecompany");

        $return_detail_sql->bindParam(':return_id', $return_id);

        $return_detail_sql->bindParam(':company_id', self::$default_company);

        $return_detail_sql->bindParam(':cataloguecompany', self::$catalogue_company);

        $return_detail_sql->execute();

        $return_detail_sql = $return_detail_sql->fetchAll(\PDO:: FETCH_ASSOC);

        return json_encode($return_detail_sql);

    }

    function GetReceivables($startdate, $enddate, $condicion, $collectorslist)

    {
        $receivables_sql = self::$con->prepare("SELECT 
                rh.company_id,
				rh.receipt_income_code,
				rh.receipt_income_date_time,
				rh.receipt_income_date,
				rh.customer_code,
				cu.customer_name,
				rh.receipt_income_amount,
				rh.cash_amount,
				rh.check_amount,
				rh.status,
				ba.bank_name,
				rh.futuristic_check,
				rh.payment_note,
				COALESCE (rh.futuristic_check_date,'') as futuristic_check_date,
				rh.check_number,
				rh.collector_code,
				co.collector_name
				FROM ".self::$user_database.".receivables_header rh
				INNER JOIN ".self::$user_database.".collectors co ON co.collector_code = rh.collector_code 
				LEFT JOIN ".self::$user_database.".banks ba ON ba.bank_code = rh.bank_code 
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = rh.customer_code
				WHERE rh.receipt_income_date BETWEEN :start_date AND :end_date
				AND cu.company_id = :company_id 
				AND co.collector_code ".$condicion." IN(".$collectorslist.") 
				ORDER BY rh.receipt_income_date_time DESC
				");

        $receivables_sql->bindParam(':start_date', $startdate);

        $receivables_sql->bindParam(':end_date', $enddate);

        $receivables_sql->bindParam(':company_id', self::$default_company);

        $receivables_sql->execute();

        $receivables_sql = $receivables_sql->fetchAll(\PDO::FETCH_ASSOC);

        self::ReceivablesTable($receivables_sql);

    }

    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    function DfTableMini($seller) {
        $sql = self::$con->prepare("SELECT 
                df.company_id,
                df.deposit_of_invoices_id,
				df.customer_code,
				cu.customer_name,
				df.date,
				id_reference,
				total_amount
				FROM ".self::$user_database.".deposit_of_invoices_header df
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = df.customer_code
				WHERE df.seller_code  = ".$seller."
				ORDER BY df.deposit_of_invoices_id DESC
				");


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($sql as $row){
            echo '    <tr class="selectable" data-id="'.$row['deposit_of_invoices_id'] .'">
                       <td>'.$row['date'].'</td>
					    <td>'.$row['deposit_of_invoices_id'] .'</td>
					    <td><b>'.$row['customer_code'].' - '.$row['customer_name'].'</b></td>
	                    <td style="text-align: right"><span class="label label-success">$ '.number_format($row['total_amount'], 2, '.', ',').'</span></td>
                    </tr>
            ';
        }
    }

    function DfTable () {
        $sql = self::$con->prepare("SELECT 
                df.company_id,
                df.deposit_of_invoices_id,
				df.customer_code,
				cu.customer_name,
				df.date,
				id_reference,
				total_amount,
				ua.user_name,
				ua.user_nickname,
				permission_id,
				rp.user_id,
				rp.printed
				FROM " . self::$user_database . ".deposit_of_invoices_header df
				INNER JOIN " . self::$user_database . ".customers cu ON cu.customer_code = df.customer_code
				INNER JOIN " . self::$user_database . ".reprint_permission rp ON rp.transaction_code = df.deposit_of_invoices_id
				INNER JOIN " . self::$user_database . ".users_accounts ua ON ua.user_id = rp.user_id
				WHERE rp.transaction_type = 'DF'
				ORDER BY df.id_reference DESC
				");

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($sql as $row) {

            $dt = new \DateTime($row['date']);
            echo '    <tr>
                      <td>' . $row['permission_id'] . '</td>
                      <td>' . $dt->format('d-m-Y') . '</td>
                      <td>' . $row['id_reference'] . '</td>
                      	<td><b>' . $row['customer_code'] . ' - ' . $row['customer_name'] . '</b></td>
					
					<td><b>' . $row['user_id'] . ' - ' . $row['user_name'] . ' (' . $row['user_nickname'] . ')</b></td>
	                <td style="text-align: center"> ';
            if ($row['printed'] == true) {
                echo '
                          <span class="label label-success">SI</span>
                          ';
            } else
                echo '<span class="label label-primary">NO</span>';
            echo '</td>
                      <td>';

            if ($row['printed'] == false) {
                echo '<form  style="display: inline-block; float: left" method="POST">
  						<input type="hidden" name="permission_id" value="' . $row['permission_id'] . '"></input>
  					
  							<a class="table-link danger" style="cursor:pointer" onclick="$(this).parent().submit()">
  								<span class="fa-stack">
  									<i class="fa fa-square fa-stack-2x"></i>
  									<i class="fa fa-times fa-stack-1x fa-inverse"></i>
  								</span>
  							</a>
  					</form>';
            }
            echo '</td>
                    </tr>
            ';
        }
    }

    function ReceivablesTableMini($seller){
        $receivables_sql = self::$con->prepare("SELECT 
                rh.company_id,
				rh.receipt_income_code,
				rh.receipt_income_date,
				rh.customer_code,
				cu.customer_name,
				rh.receipt_income_amount
				FROM ".self::$user_database.".receivables_header rh
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = rh.customer_code
				WHERE rh.seller_code  = ".$seller."
				ORDER BY rh.receipt_income_code DESC
				");


        $receivables_sql->execute();
        $receivables_sql = $receivables_sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($receivables_sql as $row){
            echo '    <tr class="selectable" data-id="'.$row['receipt_income_code'] .'">
                       <td>'.$row['receipt_income_date'].'</td>
					<td>'.$row['receipt_income_code'] .'</td>
					<td><b>'.$row['customer_code'].' - '.$row['customer_name'].'</b></td>
	                <td style="text-align: right"><span class="label label-success">$ '.number_format($row['receipt_income_amount'], 2, '.', ',').'</span></td>
                    </tr>
            ';
        }
    }

    function ReturnPermissionTable()
    {
        $sql = self::$con->prepare("SELECT 
                rh.return_code,
				cu.customer_code,
				cu.customer_name,
				rp.date,
				rp.printed,
				ua.user_name,
				ua.user_nickname,
				rp.permission_id
				FROM " . self::$user_database . ".returns_header rh
				INNER JOIN " . self::$user_database . ".customers cu ON cu.customer_code = rh.customer_code
				INNER JOIN " . self::$user_database . ".reprint_permission rp ON rp.transaction_code = rh.return_code
				INNER JOIN " . self::$user_database . ".users_accounts ua ON ua.user_id = rp.user_id
				WHERE rp.transaction_type = 'DV'
				ORDER BY rh.return_code DESC
				");

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($sql as $row) {

            $dt = new \DateTime($row['date']);
            echo '    <tr>
                      <td>' . $row['permission_id'] . '</td>
                      <td>' . $dt->format('d-m-Y') . '</td>
                      	<td><b>' . $row['customer_code'] . ' - ' . $row['customer_name'] . '</b></td>
					<td>' . $row['return_code'] . '</td>
					<td><b>' . $row['user_nickname'] . ' - ' . $row['user_name'] . '</b></td>
	                <td style="text-align: center"> ';
            if ($row['printed'] == true) {
                echo '
                          <span class="label label-success">SI</span>
                          ';
            } else
                echo '<span class="label label-primary">NO</span>';
            echo '</td>
                      <td>';

            if ($row['printed'] == false) {
                echo '<form  style="display: inline-block; float: left" method="POST">
  						<input type="hidden" name="permission_id" value="' . $row['permission_id'] . '"></input>
  					
  							<a class="table-link danger" style="cursor:pointer" onclick="$(this).parent().submit()">
  								<span class="fa-stack">
  									<i class="fa fa-square fa-stack-2x"></i>
  									<i class="fa fa-times fa-stack-1x fa-inverse"></i>
  								</span>
  							</a>
  					</form>';
            }
            echo '</td>
                    </tr>
            ';
        }
    }

    function ReturnsTableMini($seller) {

        $sql = self::$con->prepare("SELECT 
                rh.company_id,
				rh.invoice_code,
				rh.return_date,
				rh.customer_code,
				cu.customer_name,
				rh.return_code
				FROM ".self::$user_database.".returns_header rh
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = rh.customer_code
				WHERE rh.seller_code  = ".$seller."
				ORDER BY rh.return_date DESC
				");


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($sql as $row){
            echo '    <tr class="selectable" data-id="'.$row['return_code'] .'">
                       <td>'.$row['return_date'].'</td>
					<td>'.$row['return_code'].'</td>
					<td><b>'.$row['customer_code'].' - '.$row['customer_name'].'</b></td>
	                <td>'.$row['invoice_code'].'</td>
                    </tr>
            ';
        }

    }

    function AdvancedReceivablesTableMini($collector){
        $receivables_sql = self::$con->prepare("SELECT 
                ar.company_id,
				ar.advance_receipt_id,
				ar.date,
				ar.customer_code,
				cu.customer_name,
				ar.amount
				FROM ".self::$user_database.".advance_receipts ar
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = ar.customer_code
				WHERE ar.collector_code  = ".$collector."
				ORDER BY ar.amount DESC
				");


        $receivables_sql->execute();
        $receivables_sql = $receivables_sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($receivables_sql as $row){
            echo '    <tr class="selectable" data-id="'.$row['advance_receipt_id'] .'">
                       <td>'.$row['date'].'</td>
					<td>'.$row['advance_receipt_id'] .'</td>
					<td><b>'.$row['customer_code'].' - '.$row['customer_name'].'</b></td>
	                <td style="text-align: right"><span class="label label-success">$ '.number_format($row['amount'], 2, '.', ',').'</span></td>
                    </tr>
            ';
        }
    }

    function CancelPermission($receipt){
        $receivables_sql = self::$con->prepare("
                DELETE FROM " . self::$user_database . ".reprint_permission
				WHERE permission_id  = ".$receipt."
				");
        $receivables_sql->execute();
        echo '
            Autorización de Recibo cancelado satisfactoriamente.
        ';
    }

    function UserPermission ($user,$code,$type){
        if ($user && $code){
            self::$con->beginTransaction();
            try {
                $sql = self::$con->prepare("INSERT INTO
				" . self::$user_database . ".reprint_permission
				(
				user_id,
				transaction_type,
				transaction_code,
				printed,
				date
				)
				VALUES 
				(
				:uid,
				:type,
				:code,
				false,
				CURRENT_TIMESTAMP
				)
				");

                $sql->bindParam(':uid', $user);
                $sql->bindParam(':code', $code);
                $sql->bindParam(':type', $type);
                $sql->execute();
            }catch (\Exception $e ){
                echo $e->getMessage();
                self::$con->rollBack();
            }
            self::$con->commit();
            echo 'Recibo autorizado satisfactoriamente.';
        }else{
            echo 'Asegúrese de seleccionar un usuario y un recibo.';
        }
        self::$con->beginTransaction();


    }

    function  AdvancedReceivablePermissionTable(){
        $receivables_sql = self::$con->prepare("SELECT 
                ar.advance_receipt_id,
				cu.customer_code,
				cu.customer_name,
				rp.date,
				rp.printed,
				ua.user_name,
				ua.user_nickname,
				rp.permission_id
				FROM ".self::$user_database.".advance_receipts ar
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = ar.customer_code
				INNER JOIN ".self::$user_database.".reprint_permission rp ON rp.transaction_code = ar.advance_receipt_id
				INNER JOIN ".self::$user_database.".users_accounts ua ON ua.user_id = rp.user_id
				WHERE rp.transaction_type = 'RA'
				ORDER BY ar.advance_receipt_id DESC
				");

        $receivables_sql->execute();
        $receivables_sql = $receivables_sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($receivables_sql as $row){

            $dt =  new \DateTime($row['date']);
            echo '    <tr>
                      <td>'.$row['permission_id'].'</td>
                      <td>'.$dt->format('d-m-Y').'</td>
                      	<td><b>'.$row['customer_code'].' - '.$row['customer_name'].'</b></td>
					<td>'.$row['advance_receipt_id'] .'</td>
					<td><b>'.$row['user_nickname'].' - '.$row['user_name'].'</b></td>
	                <td style="text-align: center"> ';
            if($row['printed']==true) {
                echo '
                          <span class="label label-success">SI</span>
                          ';
            }
            else
                echo '<span class="label label-primary">NO</span>';
            echo '</td>
                      <td>';
            if($row['printed']==false) {
                echo '<form  style="display: inline-block; float: left" method="POST">
  						<input type="hidden" name="permission_id" value="'.$row['permission_id'].'"></input>
  					
  							<a class="table-link danger" style="cursor:pointer" onclick="$(this).parent().submit()">
  								<span class="fa-stack">
  									<i class="fa fa-square fa-stack-2x"></i>
  									<i class="fa fa-times fa-stack-1x fa-inverse"></i>
  								</span>
  							</a>
  					</form>';
            }
            echo '</td>
                    </tr>
            ';
        }
    }

    function ReceivablePermissionTable () {
        try {
            $receivables_sql = self::$con->prepare("SELECT 
                rh.receipt_income_code,
				cu.customer_code,
				cu.customer_name,
				rh.receipt_income_amount,
				rp.date,
				rp.printed,
				ua.user_name,
				ua.user_nickname,
				rp.permission_id
				FROM ".self::$user_database.".receivables_header rh
				INNER JOIN ".self::$user_database.".customers cu ON cu.customer_code = rh.customer_code
				INNER JOIN ".self::$user_database.".reprint_permission rp ON rp.transaction_code = rh.receipt_income_code
				INNER JOIN ".self::$user_database.".users_accounts ua ON ua.user_id = rp.user_id
				WHERE rp.transaction_type = 'RI'
				ORDER BY rh.receipt_income_code DESC
				");

            $receivables_sql->execute();
            $receivables_sql = $receivables_sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($receivables_sql as $row){

                $dt =  new \DateTime($row['date']);
                echo '    <tr>
                      <td>'.$row['permission_id'].'</td>
                      <td>'.$dt->format('d-m-Y').'</td>
                      	<td><b>'.$row['customer_code'].' - '.$row['customer_name'].'</b></td>
					<td>'.$row['receipt_income_code'] .'</td>
					<td><b>'.$row['user_nickname'].' - '.$row['user_name'].'</b></td>
	                <td style="text-align: center"> ';
                if($row['printed']==true) {
                    echo '
                          <span class="label label-success">SI</span>
                          ';
                }
                else
                    echo '<span class="label label-primary">NO</span>';
                echo '</td>
                      <td>';

                if($row['printed']==false) {
                    echo '<form  style="display: inline-block; float: left" method="POST">
  						<input type="hidden" name="permission_id" value="'.$row['permission_id'].'"></input>
  					
  							<a class="table-link danger" style="cursor:pointer" onclick="$(this).parent().submit()">
  								<span class="fa-stack">
  									<i class="fa fa-square fa-stack-2x"></i>
  									<i class="fa fa-times fa-stack-1x fa-inverse"></i>
  								</span>
  							</a>
  					</form>';
                }
                echo '</td>
                    </tr>
            ';
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

    function ReceivablesTable($receivables_array)

    {
        echo '<table class="table user-list footable table-hover" data-filtering="true" data-sorting="true">
			<thead>
				<tr>
					<th >Fecha</th>
					<th >Compañía</th>
					<th>No. de Recibo</th>
					<th data-type="html">Cliente</th>  
					<th>Cobrador</th>
					<th data-type="html" style="text-align: right">Monto Cheque</th>
					<th data-type="html" style="text-align: right">Monto Efectivo</th>
					<th data-type="html" style="text-align: right">Total Monto</th>
					<th data-type="html">Detalle</th>
				</tr>
			</thead>
			<tbody>';
        $total = 0;
        foreach(self::_group_by($receivables_array, "receipt_income_code") as $row)
        {
            $total += $row[0]['receipt_income_amount'];
            echo '<tr data-total="'.$row[0]['receipt_income_amount'].'">
					<td>'.$row[0]['receipt_income_date_time'].'</td>
					<td>'.$row[0]['company_id'].'</td>
					<td>'.$row[0]['receipt_income_code'] .'</td>
					<td><b>'.$row[0]['customer_code'].' - '.$row[0]['customer_name'].'</b></td>
					<td>'.$row[0]['collector_code']. ' - ' .$row[0]['collector_name'].'</td>
					<td  data-sort-value="'.$row[0]['check_amount'].'" style="text-align: right"><span class="label label-primary">$ '.number_format($row[0]['check_amount'], 2, '.', ',').'</span></td>
					<td  data-sort-value="'.$row[0]['cash_amount'].'" style="text-align: right"><span class="label label-primary">$ '.number_format($row[0]['cash_amount'], 2, '.', ',').'</span></td>
					<td  data-sort-value="'.$row[0]['receipt_income_amount'].'" style="text-align: right"><span class="label label-success">$ '.number_format($row[0]['receipt_income_amount'], 2, '.', ',').'</span></td>
					<td>
						<form method="post" action="invoice" target="_blank">                  
							<input type="hidden" id="customer_code" name="customer_code" value="'.$row[0]['customer_code'].'">
							<input type="hidden" id="receipt_code" name="receipt_code" value="'.$row[0]['receipt_income_code'].'">
							<input type="hidden" id="amount" name="amount" value="'.$row[0]['receipt_income_amount'].'">
								<input type="hidden" id="check_amount" name="check_amount" value="'.$row[0]['check_amount'].'">
								<input type="hidden" id="futuristic_check" name="futuristic_check" value="'.$row[0]['futuristic_check'].'">
									<input type="hidden" id="futuristic_check_date" name="futuristic_check_date" value="'.$row[0]['futuristic_check_date'].'">
									<input type="hidden" id="status" name="status" value="'.$row[0]['status'].'">
									<input type="hidden" id="bank_name" name="bank_name" value="'.$row[0]['bank_name'].'">
									<input type="hidden" id="payment_note" name="payment_note" value="'.$row[0]['payment_note'].'">
									<input type="hidden" id="check_number" name="check_number" value="'.$row[0]['check_number'].'">
									<input type="hidden" id="cash_amount" name="cash_amount" value="'.$row[0]['cash_amount'].'">
							<input type="hidden" id="seller" name="collector" value="'.$row[0]['collector_name'].'">  
							<input type="hidden" id="date" name="date" value="'.$row[0]['receipt_income_date'].'">  
							<button style="background: transparent; border: none; color: #337ab7" type="submit"><b>Ver</b></button>      
						</form>
					</td>
				</tr>';

        }

        echo'</tbody>
            <tfoot>
                <tr id="receivabletotal" data-initialtotal="'.$total.'"></tr>    
            </tfoot>
  
		</table>';


    }

    function GetReceivablesDetails($receivable_code)
    {
        $appends = "";
        if (CompatibilityChecker::columnExists('discount_amount', 'receivables_details')) {
            $appends = "rd.discount_amount,";
        }
        $receivables_details_sql = self::$con->prepare("SELECT 
			rh.receipt_income_code,
			rh.receipt_income_date,
			rh.receipt_income_amount,
			rh.cash_amount,
			rh.check_amount,
			rh.collector_code,
			{$appends}
			co.collector_name, 
			rd.invoice_code,
			rd.payment_amount,
			rd.payment_code,  
			rd.invoice_balance
			FROM ".self::$user_database.".receivables_header rh
			INNER JOIN ".self::$user_database.".collectors co ON(co.collector_code = rh.collector_code AND co.company_id = ".self::$company_cxc.")
			INNER JOIN ".self::$user_database.".receivables_details rd ON (rd.receipt_income_code = rh.receipt_income_code AND rd.company_id = rh.company_id)
			WHERE rh.receipt_income_code = :receivable_code
			ORDER BY rh.receipt_income_code, rd.invoice_code, rd.payment_code");

        $receivables_details_sql->bindParam(':receivable_code', $receivable_code);

        $receivables_details_sql->execute();

        $receivables_details_sql = $receivables_details_sql->fetchAll(\PDO:: FETCH_ASSOC);

        return json_encode($receivables_details_sql);

    }

    function GetOrderDetails($order_id)

    {

        $orders_details_sql = self::$con->prepare("SELECT 
			pro.product_name,
			o.product_reference,
			o.order_item_quantity,
			o.order_item_sale_price,
			o.order_item_tax_amount,
			o.order_item_discount_amount,
			o.order_item_amount
			FROM ".self::$user_database.".orders_details o
			INNER JOIN ".self::$user_database.".products pro ON(pro.product_reference = o.product_reference)
			WHERE o.company_id = :company_id 
			AND o.order_id = :order_id
			AND pro.company_id = :cataloguecompany
			ORDER BY pro.product_name
			");

        $orders_details_sql->bindParam(':order_id', $order_id);

        $orders_details_sql->bindParam(':company_id', self::$default_company);

        $orders_details_sql->bindParam(':cataloguecompany', self::$catalogue_company);

        $orders_details_sql->execute();

        $orders_details_sql = $orders_details_sql->fetchAll(\PDO:: FETCH_ASSOC);

        return json_encode($orders_details_sql);

    }

    function Effectiveness($efdates, $routes, $condicion, $sellerlist, $ordersdates)

    {
        $effectiveness_sql = self::$con->prepare("SELECT 
			COUNT(DISTINCT x.customer_code) as pedidos, 
			x.seller_code, 
			s.seller_name, 
			(SELECT COUNT(*) FROM ".self::$user_database.".customers cs 
			WHERE cs.company_id = x.company_id 
			AND cs.seller_code = x.seller_code 
			".$efdates." 
			".$routes."
			) as cantidadclientes
			FROM ".self::$user_database.".orders_header x
			LEFT JOIN ".self::$user_database.".customers c ON (c.seller_code = x.seller_code
			AND c.company_id = x.company_id
			AND c.customer_code = x.customer_code) 
			LEFT JOIN ".self::$user_database.".sellers s ON (s.seller_code = x.seller_code
			AND s.company_id = x.company_id) 
			WHERE
			x.company_id = :cid AND
			x.seller_code ".$condicion." IN(".$sellerlist.")
			".$ordersdates."
			GROUP BY x.seller_code, s.seller_name");

        $effectiveness_sql->bindParam(':cid', self::$default_company);

        $effectiveness_sql->execute();

        $effectiveness_sql = $effectiveness_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($effectiveness_sql);

    }

    function BrandsSales($startdate, $enddate, $condicion, $sellerlist)

    {

        $brands_sales_sql = self::$con->prepare("SELECT SUM(x.order_item_amount) as importe, b.brand_name, x.company_id, b.brand_id 
			FROM ".self::$user_database.".orders_details x
			LEFT JOIN ".self::$user_database.".orders_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
			LEFT JOIN ".self::$user_database.".products p ON (p.product_reference = x.product_reference)
			LEFT JOIN ".self::$user_database.".brands b on (b.brand_id = p.brand_id)
			LEFT JOIN ".self::$user_database.".sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
			WHERE x.company_id = :cid
			AND x.order_item_date BETWEEN :start_date AND :end_date 
			AND o.seller_code ".$condicion." IN(".$sellerlist.") 
			AND p.company_id = :catalogue_company
			GROUP BY b.brand_name ,x.company_id, b.brand_id 
			ORDER BY 1 DESC LIMIT 10");

        $brands_sales_sql->bindParam(':cid', self::$default_company);

        $brands_sales_sql->bindParam(':start_date', $startdate);

        $brands_sales_sql->bindParam(':end_date', $enddate);

        $brands_sales_sql->bindParam(':catalogue_company', self::$catalogue_company);

        $brands_sales_sql->execute();

        $brands_sales_sql = $brands_sales_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($brands_sales_sql);

    }

    function ProductsSales($startdate, $enddate, $condicion, $sellerlist)

    {

        $product_sales_sql = self::$con->prepare("SELECT SUM(x.order_item_amount) as importe, p.product_name, x.company_id, p.product_reference 
			FROM ".self::$user_database.".orders_details x
			LEFT JOIN ".self::$user_database.".orders_header o ON (o.order_id = x.order_id AND o.company_id = x.company_id)
			LEFT JOIN ".self::$user_database.".products p ON (p.product_reference = x.product_reference)
			LEFT JOIN ".self::$user_database.".sellers s on (s.seller_code = o.seller_code and s.company_id = x.company_id)
			WHERE x.company_id = :cid 
			AND x.order_item_date BETWEEN :start_date AND :end_date
			AND o.seller_code ".$condicion." IN(".$sellerlist.")
			AND p.company_id = :catalogue_company
			GROUP BY p.product_name , x.company_id, p.product_reference
			ORDER BY 1 DESC LIMIT 10");

        $product_sales_sql->bindParam(':cid', self::$default_company);

        $product_sales_sql->bindParam(':start_date', $startdate);

        $product_sales_sql->bindParam(':end_date', $enddate);

        $product_sales_sql->bindParam(':catalogue_company', self::$catalogue_company);

        $product_sales_sql->execute();

        $product_sales_sql = $product_sales_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($product_sales_sql);

    }

    function GetCustomerBySeller($sellers)

    {

        $specific_customer_arr = array();

        foreach ($sellers as $seller_code) {

            $customers_list_sql = self::$con->prepare("SELECT *
				FROM ".self::$user_database.".customers 
				WHERE company_id = :company_id
				AND seller_code = :seller_code");

            $customers_list_sql->bindParam(':company_id', self::$default_company);

            $customers_list_sql->bindParam(':seller_code', $seller_code);

            $customers_list_sql->execute();

            $customers_list_sql = $customers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($customers_list_sql as $row) {

                $specific_customer_arr[] = $row;

            }

        }

        return json_encode($specific_customer_arr);

    }

    function GetCustomerByDate($sellerlist, $condicion, $cdates)
    {

        $customers_list_sql = self::$con->prepare("SELECT 
			customer_code 
			FROM ".self::$user_database.".customers 
			WHERE company_id = :cid 
			".$cdates." 
			AND seller_code 
			".$condicion." 
			IN(".$sellerlist.")");

        $customers_list_sql->bindParam(':cid', self::$default_company);

        $customers_list_sql->execute();

        $customers_list_sql = $customers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($customers_list_sql);

    }

    function GetCustomerByCustomerCode($customers)

    {

        $specific_customer_arr = array();

        foreach ($customers as $customer_code) {

            $customers_list_sql = self::$con->prepare("SELECT *
				FROM ".self::$user_database.".customers cs
				INNER JOIN ".self::$user_database.".cities ct
            ON ct.city_id = cs.city_id
				WHERE company_id = :company_id
				AND customer_code = :customer_code");

            $customers_list_sql->bindParam(':company_id', self::$default_company);

            $customers_list_sql->bindParam(':customer_code', $customer_code);

            $customers_list_sql->execute();

            $customers_list_sql = $customers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($customers_list_sql as $row) {

                $specific_customer_arr[] = $row;

            }

        }

        return json_encode($specific_customer_arr);

    }

    function GetCustomers()

    {

        $customers_list_sql = self::$con->prepare("SELECT *
			FROM ".self::$user_database.".customers c
			LEFT JOIN ".self::$user_database.".sellers s ON(c.seller_code = s.seller_code) 
			WHERE c.company_id = :company_id ORDER BY c.customer_code ASC");

        $customers_list_sql->bindParam(':company_id', self::$default_company);
        $customers_list_sql->execute();
        $customers_list_sql = $customers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($customers_list_sql);

    }

    function GetDays(){
        $days_sql = self::$con->prepare("SELECT *
			                             FROM ".self::$user_database.".days");
        $days_sql->execute();
        $days_sql = $days_sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($days_sql);

    }

    function GetInactiveSellers () {
        if (CompatibilityChecker::columnExists('status','sellers')) {
            $sellers_list_sql = self::$con->prepare("SELECT 
			s.seller_id, 
			s.seller_code, 
			seller_name, 
			seller_phone
			FROM ".self::$user_database.".sellers s
			WHERE s.company_id = :company_id 
			AND s.status = 2
			ORDER BY s.seller_code ASC");

            $sellers_list_sql->bindParam(':company_id', self::$default_company);
            $sellers_list_sql->execute();
            $sellers_list_sql = $sellers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

            return json_encode($sellers_list_sql);
        } else return 'null';
    }

    function GetSellers()
    {
        if (CompatibilityChecker::columnExists('status','sellers')) {
            $sellers_list_sql = self::$con->prepare("SELECT 
			s.seller_id, 
			s.seller_code, 
			seller_name, 
			seller_phone
			FROM ".self::$user_database.".sellers s
			WHERE s.company_id = :company_id
			 AND s.status = 1
			 ORDER BY s.seller_code ASC");

            $sellers_list_sql->bindParam(':company_id', self::$default_company);
            $sellers_list_sql->execute();
            $sellers_list_sql = $sellers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

            return json_encode($sellers_list_sql);
        } else  {
            $sellers_list_sql = self::$con->prepare("SELECT 
			s.seller_id, 
			s.seller_code, 
			seller_name, 
			seller_phone
			FROM ".self::$user_database.".sellers s
			WHERE s.company_id = :company_id
			 ORDER BY s.seller_code ASC");

            $sellers_list_sql->bindParam(':company_id', self::$default_company);
            $sellers_list_sql->execute();
            $sellers_list_sql = $sellers_list_sql->fetchAll(\PDO::FETCH_ASSOC);

            return json_encode($sellers_list_sql);
        }
    }

    function GetCollectors()

    {

        $collectors_list_sql = self::$con->prepare("SELECT 
			collector_code, 
			collector_name 
			FROM ".self::$user_database.".collectors 
			WHERE company_id = :cid ORDER BY collector_code ASC");
        $collectors_list_sql->bindParam(':cid', self::$default_company);
        $collectors_list_sql->execute();
        $collectors_list_sql = $collectors_list_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($collectors_list_sql);

    }


    function GetRoutes()

    {

        $routes_list_sql = self::$con->prepare("SELECT 
			route_code, 
			route_name
			FROM ".self::$user_database.".routes
			WHERE company_id = :cid ORDER BY route_code");
        $routes_list_sql->bindParam(':cid', self::$default_company);
        $routes_list_sql->execute();
        $routes_list_sql = $routes_list_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($routes_list_sql);

    }

    function GetAreas()

    {
        $areas_list_sql = self::$con->prepare("SELECT 
			a.area_code, 
			a.area_name,
			s.seller_name
			FROM ".self::$user_database.".areas a 
			LEFT JOIN ".self::$user_database.".sellers s ON (s.seller_code = a.seller_code and s.company_id = a.company_id)
			WHERE a.company_id = :company_id ORDER BY a.area_code");
        $areas_list_sql->bindParam(':company_id', self::$default_company);
        $areas_list_sql->execute();
        $areas_list_sql = $areas_list_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($areas_list_sql);

    }
//    function getCompanySerial(){
//        $sql = self::$con->prepare("
//            SELECT
//			client_serial_number
//			FROM adgsoft_maps.clients
//			WHERE user_company = :cid AND user_id = :uid");
//        $sql->bindParam(':cid', self::$default_company);
//        $sql->bindParam(':uid', self::$user_id);
//        $sql->execute();
//        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
//
//        return json_encode($sql);
//    }

    function GetCountries()

    {

        $getcountrylistsql = self::$con->prepare('SELECT 
			country_id, 
			country_name 
			FROM '.self::$user_database.'.countries ORDER BY country_id');

        $getcountrylistsql->execute();

        $getcountrylistsql = $getcountrylistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getcountrylistsql);

    }


    function GetProvinces()

    {

        $provinces = self::$con->prepare("
			SELECT 
			province_id, province_name  
			FROM ".self::$user_database.".provinces 
			ORDER BY province_name
			");

        $provinces->execute();

        $provinces = $provinces->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($provinces);


    }

    function GetCities()

    {

        $getcitylistsql = self::$con->prepare('SELECT 
			city_id, 
			city_name 
			FROM '.self::$user_database.'.cities 
			ORDER BY city_id');

        $getcitylistsql->execute();

        $getcitylistsql = $getcitylistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getcitylistsql);

    }

    function GetUserListAndSerials(){
        $sql = self::$con->prepare(
            'SELECT 
			ua.user_id, 
			ua.user_name,
			ua.user_nickname,
			uc.seller_code,
			uc.collector_code,
			sn.user_serial_number
			FROM '.self::$user_database.'.users_accounts ua
			JOIN '.self::$user_database.'.users_companies uc ON uc.user_id = ua.user_id
			JOIN '.self::$user_database.'.serial_numbers sn ON sn.user_id = ua.user_id
			WHERE uc.company_id = :cid 
			ORDER BY  uc.seller_code 
	
		
			');
        $sql->bindParam(':cid', self::$default_company);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($sql);
    }

    function GetUserList(){
        $sql = self::$con->prepare(
            'SELECT 
			ua.user_id, 
			ua.user_name,
			ua.user_nickname,
			uc.seller_code,
			uc.collector_code
			FROM '.self::$user_database.'.users_accounts ua, '.self::$user_database.'.users_companies uc
			WHERE uc.company_id = :cid AND uc.user_id = ua.user_id
			ORDER BY uc.seller_code
			');
        $sql->bindParam(':cid', self::$default_company);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($sql);
    }

    function GetSellersLocations()
    {
        $append= "";

        if(CompatibilityChecker::columnExists('status','sellers')){
            $append = " AND v.status = 1";
        }

        $sellers_locations_sql = self::$con->prepare("SELECT 
			v.seller_code, 
			v.seller_name,
			v.seller_phone, 
			COALESCE((SELECT sl.seller_latitude FROM ".self::$user_database.".sellers_locations sl WHERE sl.seller_code = v.seller_code and sl.company_id = v.company_id order by sl.seller_location_datetime DESC limit 1),0) as latitud,
			COALESCE((SELECT ll.seller_longitude FROM ".self::$user_database.".sellers_locations ll WHERE ll.seller_code = v.seller_code and ll.company_id = v.company_id order by ll.seller_location_datetime DESC limit 1),0) as longitud,
			COALESCE((SELECT lo.customer_code_order FROM ".self::$user_database.".sellers_locations lo WHERE lo.seller_code = v.seller_code and lo.company_id = v.company_id order by lo.seller_location_datetime DESC limit 1),0) as localizacion,
			COALESCE((SELECT dt.seller_location_datetime FROM ".self::$user_database.".sellers_locations dt WHERE dt.seller_code = v.seller_code and dt.company_id = v.company_id order by dt.seller_location_datetime DESC limit 1),0) as ult_actualizacion
			FROM ".self::$user_database.".sellers v 
			WHERE v.company_id = :cid {$append}
			ORDER BY ult_actualizacion DESC
			");

        $sellers_locations_sql->bindParam(':cid', self::$default_company);

        $sellers_locations_sql->execute();

        $sellers_locations_sql = $sellers_locations_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($sellers_locations_sql);

    }

    function GetSellersRoutes($seller, $startdate)

    {

        $sellers_routes_sql = self::$con->prepare("
			SELECT 
			v.seller_code,
			v.seller_name, 
			sl.seller_latitude, 
			sl.seller_longitude, 
			sl.location_id, 
			sl.seller_location_datetime
			FROM ".self::$user_database.".sellers v 
			INNER JOIN ".self::$user_database.".sellers_locations sl ON(sl.seller_code = v.seller_code and sl.company_id = v.company_id AND CAST(sl.seller_location_datetime AS DATE) = :start_date)
			WHERE v.company_id = :cid AND v.seller_code = :seller 
			ORDER BY sl.seller_location_datetime ASC ");

        $sellers_routes_sql->bindParam(':seller', $seller);

        $sellers_routes_sql->bindParam(':cid', self::$default_company);

        $sellers_routes_sql->bindParam(':start_date', $startdate);

        $sellers_routes_sql->execute();

        $sellers_routes_sql = $sellers_routes_sql->fetchAll();

        return json_encode($sellers_routes_sql);


    }

    function GetSpecificSeller($company_id, $sellers)

    {

        $specific_seller_arr = array();

        foreach ($sellers as $seller_code) {

            $getspecificsellersql = self::$con->prepare('SELECT 
				seller_code, 
				seller_name 
				FROM '.self::$user_database.'.sellers 
				WHERE company_id = :company_id 
				AND seller_code = :seller_code');

            $getspecificsellersql->bindParam(':company_id', $company_id);

            $getspecificsellersql->bindParam(':seller_code', $seller_code);

            $getspecificsellersql->execute();

            $getspecificsellersql = $getspecificsellersql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($getspecificsellersql as $row) {

                $specific_seller_arr[] = $row;

            }

        }

        return json_encode($specific_seller_arr);


    }

    function GetProducts()

    {

        $getproductslistsql = self::$con->prepare('SELECT 
			product_reference, 
			product_name,
			product_in_stock 
			FROM '.self::$user_database.'.products 
			WHERE company_id = :company_id ORDER BY product_reference');

        $getproductslistsql->bindParam(':company_id', self::$catalogue_company);

        $getproductslistsql->execute();

        $getproductslistsql = $getproductslistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getproductslistsql);

    }


    function GetSpecificProduct($products)

    {

        $specific_product_arr = array();

        foreach ($products as $product_reference) {

            $getspecificproductsql = self::$con->prepare('SELECT 
				product_reference, 
				product_name,
				product_in_stock 
				FROM '.self::$user_database.'.products 
				WHERE company_id = :company_id 
				AND TRIM(product_reference) = TRIM(:product_reference)');

            $getspecificproductsql->bindParam(':company_id', self::$catalogue_company);

            $getspecificproductsql->bindParam(':product_reference', $product_reference);

            $getspecificproductsql->execute();

            $getspecificproductsql = $getspecificproductsql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($getspecificproductsql as $row) {

                $specific_product_arr[] = $row;

            }

        }

        return json_encode($specific_product_arr);


    }

    function GetBrands()

    {

        $getbrandslistsql = self::$con->prepare('SELECT 
			brand_id, 
			brand_name 
			FROM '.self::$user_database.'.brands 
			ORDER BY brand_name');
        $getbrandslistsql->execute();

        $getbrandslistsql = $getbrandslistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getbrandslistsql);

    }

    function GetUnits()

    {

        $getunitslistsql = self::$con->prepare('SELECT 
			unit_id, 
			unit_name 
			FROM '.self::$user_database.'.units 
			ORDER BY unit_id');
        $getunitslistsql->execute();

        $getunitslistsql = $getunitslistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getunitslistsql);

    }

    function GetClassifications()

    {

        $getclassificationssql = self::$con->prepare('SELECT 
			classification_code, 
			classification_name 
			FROM '.self::$user_database.'.classifications
			WHERE company_id = :company_id 
			ORDER BY classification_code');

        $getclassificationssql->bindParam(':company_id', self::$catalogue_company);

        $getclassificationssql->execute();

        $getclassificationssql = $getclassificationssql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getclassificationssql);

    }

    function GetSubclassifications($classification_code)

    {



        $getsubclassificationssql = self::$con->prepare('SELECT 
			subclassification_code, 
			subclassification_name 
			FROM '.self::$user_database.'.subclassifications
			WHERE company_id = :company_id AND
			classification_code = :classification_code
			ORDER BY subclassification_code');

        $getsubclassificationssql->bindParam(':company_id', self::$catalogue_company);

        $getsubclassificationssql->bindParam(':classification_code', $classification_code);

        $getsubclassificationssql->execute();

        $getsubclassificationssql = $getsubclassificationssql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getsubclassificationssql);

    }


    function GetProductTypes()

    {

        $getproducttypessql = self::$con->prepare('SELECT 
			product_type_id,
			product_type_code, 
			product_type_name 
			FROM '.self::$user_database.'.product_types
			WHERE company_id = :company_id
			ORDER BY product_type_code');

        $getproducttypessql->bindParam(':company_id', self::$catalogue_company);

        $getproducttypessql->execute();

        $getproducttypessql = $getproducttypessql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getproducttypessql);

    }

    function GetProductFamilies()

    {

        $getproductfamiliessql = self::$con->prepare('SELECT 
			family_code, 
			family_name 
			FROM '.self::$user_database.'.product_families
			WHERE company_id = :company_id
			ORDER BY family_code');

        $getproductfamiliessql->bindParam(':company_id', self::$catalogue_company);

        $getproductfamiliessql->execute();

        $getproductfamiliessql = $getproductfamiliessql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getproductfamiliessql);

    }

    function GetProductGroups()

    {

        $getproductgroupssql = self::$con->prepare('SELECT 
			group_code, 
			group_name 
			FROM '.self::$user_database.'.product_groups
			WHERE company_id = :company_id
			ORDER BY group_code');

        $getproductgroupssql->bindParam(':company_id', self::$catalogue_company);

        $getproductgroupssql->execute();

        $getproductgroupssql = $getproductgroupssql->fetchAll(\PDO::FETCH_ASSOC);

        return  json_encode($getproductgroupssql);

    }

    function GetWarehouses()

    {
        $getwarehouselistsql = self::$con->prepare('SELECT 
			warehouse_code,
			warehouse_name 
			FROM '.self::$user_database.'.warehouses where company_id = :company_id ORDER BY warehouse_code');

        $getwarehouselistsql->bindParam(':company_id', self::$default_company);

        $getwarehouselistsql->execute();

        $getwarehouselistsql = $getwarehouselistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getwarehouselistsql);

    }



    function ActivityCount($params){
        $order = $this->orderCount($params);
        $quotation = $this->quotationCount($params);
//        $invoice = $this->invoiceCount($params);
        $receivable = $this->receivableCount($params);
        $return = $this->returCount($params);
        $visit = $this->visitCount($params);
        $bank = $this->bankCount($params);

        return [json_decode($order), json_decode($quotation), json_decode($receivable), json_decode($return), json_decode($visit), json_decode($bank)];
    }


    function ranchera($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $rango
         */

        $text = "SELECT sll.seller_name, ih.invoice_code, ih.invoice_tax_amount, ih.box_code, cm.customer_name, ih.invoice_discount_amount, ih.invoice_gross_amount, ih.seller_code, ih.customer_code, ih.invoice_ncf, ih.invoice_date_time, (ih.invoice_gross_amount-ih.invoice_discount_amount+ih.invoice_tax_amount) total
        FROM ".self::$user_database.".invoices_header ih
        INNER JOIN ".self::$user_database.".sellers sll
        ON sll.seller_code = ih.seller_code AND sll.company_id = ih.company_id
        INNER JOIN ".self::$user_database.".customers cm
        ON cm.customer_code = ih.customer_code AND cm.company_id = ih.company_id
        WHERE invoice_date BETWEEN :start_date AND :end_date AND ih.company_id = '".self::$company_cxc."'";

        if ($seller_code != 'todos'){
            $text .= " AND ih.seller_code = '".$seller_code."'";
        }
        $text .= " ORDER BY ih.invoice_date_time ASC";

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

//        return json_encode($text);
        $sql = self::$con->prepare($text);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);


    }




    function SellersActivity($params){

        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $text = 'SELECT seller_code, seller_name FROM '.self::$user_database.'.sellers WHERE status <> 2 AND company_id = '.self::$company_cxc;


        if ($seller_code !='0'){
            $text .= ' AND seller_code = '.$seller_code;

        }
//        return $text;


        $sql = self::$con->prepare($text);

        $sql->execute();

        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);


        $data = [];

        $total = count($sql);
        foreach ($sql as $s){
//            return json_encode($s["seller_name"]);
            $count = $this->SellersActivityCount($params, $s["seller_code"]);

            $list =  [

                $name = $s['seller_code'].'-'.$s['seller_name'],
                $count[0],
                $count[1],
                $count[2],
                $count[3],
                $count[4],
                $count[5]
            ];

            $data[$s['seller_code'].'-'.$s['seller_name']] =  $list;
        }
        ksort($data);

        $info['total'] = $total;
        $info['data'] = $data;

        return $info ;

    }

    function SellersActivityCount($params, $seller_id){
        $order = $this->orderCountByUser($params, $seller_id);
        $quotation = $this->quotationCountByUser($params, $seller_id);

        $receivable = $this->receivableCountByUser($params, $seller_id);
        $return = $this->returCountByUser($params, $seller_id);
        $visit = $this->visitCountByUser($params, $seller_id);
        $banck = $this->banckByUser($params, $seller_id);
        return [$order, $quotation, $receivable, $return, $visit, $banck];
    }

    function bankCount($params){


        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $order = "SELECT COUNT(*) bank FROM ".self::$user_database.".bank_deposit_header WHERE date BETWEEN :start_date AND :end_date AND company_id = ".self::$company_cxc;
        if ($seller_code != '0'){
            $order .= " AND seller_code = ".$seller_code;
        }
//        if ($customer_code != '0'){
//            $order .= " AND customer_code = ".$customer_code;
//
//        }

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($order);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);
    }
    function banckByUser($params, $seller_id){

        extract($params);
        /**
         * @var $rango
         */

        $order = "SELECT COUNT(*) bank FROM ".self::$user_database.".bank_deposit_header where date BETWEEN :start_date AND :end_date AND seller_code = ".$seller_id." AND company_id = ".self::$company_cxc;

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($order);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return $sql[0];


    }

    function orderCount($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $order = "SELECT COUNT(*) orderi FROM ".self::$user_database.".orders_header WHERE order_date BETWEEN :start_date AND :end_date AND company_id = ".self::$company_cxc;
        if ($seller_code != '0'){
            $order .= " AND seller_code = ".$seller_code;
        }
        if ($customer_code != '0'){
            $order .= " AND customer_code = ".$customer_code;

        }

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);

        $sql = self::$con->prepare($order);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);


        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);


    }
    function orderCountByUser($params, $user_id){
        extract($params);
        /**
         * @var $rango
         */

        $order = "SELECT COUNT(*) orderi FROM ".self::$user_database.".orders_header WHERE order_date BETWEEN :start_date AND :end_date AND seller_code = ".$user_id." AND company_id = ".self::$company_cxc;


        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($order);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return $sql[0];


    }

    function quotationCount($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $quotation = "SELECT";

        $quotation .= " COUNT(*) quotation FROM ".self::$user_database.".quotations_header qh WHERE order_date BETWEEN :start_date AND :end_date AND company_id = ".self::$company_cxc;
        if ($seller_code != '0'){
            $quotation .= " AND seller_code = ".$seller_code;
        }
        if ($customer_code != '0'){
            $quotation .= " AND customer_code = ".$customer_code;

        }



        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($quotation);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }

    function quotationCountByUser($params, $user_id){
        extract($params);
        /**
         * @var $rango
         */
        $quotation = "SELECT COUNT(*) quotation FROM ".self::$user_database.".quotations_header qh WHERE order_date BETWEEN :start_date AND :end_date AND seller_code = ".$user_id." AND company_id = ".self::$company_cxc;

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($quotation);
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return $sql[0];

    }

    function invoiceCount($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $invoice = "SELECT COUNT(*) invoices FROM ".self::$user_database.".invoices_header WHERE invoice_date BETWEEN :start_date AND :end_date AND company_id = ".self::$company_cxc;
        if ($seller_code != '0'){
            $invoice .= " AND seller_code = ".$seller_code;
        }
        if ($customer_code != '0'){
            $invoice .= " AND customer_code = ".$customer_code;

        }
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($invoice);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }
//
//    function invoiceCountByUser($params, $seller_id){
//        extract($params);
//        /**
//         * @var $rango
//         */
//        $invoice = "SELECT COUNT(*) invoices FROM ".self::$user_database.".invoices_header WHERE invoice_date BETWEEN :start_date AND :end_date  AND seller_code = ".$seller_id." AND company_id = ".self::$company_cxc;
//
//        $dates = explode(' ',$rango);
//        $start_date = trim($dates[0]);
//        $end_date = trim($dates[2]);
//        $sql = self::$con->prepare($invoice);
//
//        $sql->bindParam(':start_date', $start_date);
//        $sql->bindParam(':end_date', $end_date);
//        $sql->execute();
//        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
//        return $sql[0];
//
//    }

    function receivableCount($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $receivable = "SELECT COUNT(*) receivable FROM ".self::$user_database.".receivables_header WHERE receipt_income_date BETWEEN :start_date AND :end_date AND company_id = ".self::$company_cxc;
        if ($seller_code != '0'){
            $receivable .= " AND seller_code = ".$seller_code;
        }
        if ($customer_code != '0'){
            $receivable .= " AND customer_code = ".$customer_code;

        }
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($receivable);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }
    function receivableCountByUser($params, $seller_id){
        extract($params);
        /**
         * @var $rango
         */
        $receivable = "SELECT COUNT(*) receivable FROM ".self::$user_database.".receivables_header WHERE receipt_income_date BETWEEN :start_date AND :end_date AND seller_code = ".$seller_id." AND company_id = ".self::$company_cxc;

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($receivable);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return $sql[0];


    }

    function returCount($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $return = "SELECT COUNT(*) devo FROM ".self::$user_database.".returns_header WHERE return_date BETWEEN :start_date AND :end_date AND company_id = ".self::$company_cxc;
        if ($seller_code != '0'){
            $return .= " AND seller_code = ".$seller_code;
        }
        if ($customer_code != '0'){
            $return .= " AND customer_code = ".$customer_code;

        }
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($return);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }
    function returCountByUser($params, $seller_id){
        extract($params);
        /**
         * @var $rango
         */
        $return = "SELECT COUNT(*) devo FROM ".self::$user_database.".returns_header WHERE return_date BETWEEN :start_date AND :end_date AND seller_code = ".$seller_id." AND company_id = ".self::$company_cxc;

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($return);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return $sql[0];



    }

    function visitCount($params){
        extract($params);
        /**
         * @var $seller_code
         * @var $customer_code
         * @var $rango
         */
        $visit = "SELECT COUNT(*) visit FROM ".self::$user_database.".visits WHERE visit_date BETWEEN :start_date AND :end_date AND visit_type_id > 0 AND company_id = ".self::$company_cxc;

        if ($seller_code != '0' ){
            $visit .= " AND seller_code = ".$seller_code;
        }
        if ($customer_code != '0'){
            $visit .= " AND customer_code = ".$customer_code;

        }
        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($visit);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($sql);

    }

    function visitCountByUser($params, $seller_id){
        extract($params);
        /**
         * @var $rango
         */
        $visit = "SELECT COUNT(*) visit FROM ".self::$user_database.".visits WHERE visit_date BETWEEN :start_date AND :end_date AND visit_type_id > 0 AND seller_code = ".$seller_id." AND company_id = ".self::$company_cxc;

        $dates = explode(' ',$rango);
        $start_date = trim($dates[0]);
        $end_date = trim($dates[2]);
        $sql = self::$con->prepare($visit);

        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);

        $sql->execute();
        $sql = $sql->fetchAll(\PDO::FETCH_ASSOC);
        return $sql[0];


    }


    function GetCostCenters()

    {

        $getcost_centerlistsql = self::$con->prepare('SELECT 
			cost_center_code, 
			cost_center_name 
			FROM '.self::$user_database.'.cost_center WHERE company_id = :company_id ORDER BY cost_center_code');

        $getcost_centerlistsql->bindParam(':company_id', self::$default_company);

        $getcost_centerlistsql->execute();

        $getcost_centerlistsql = $getcost_centerlistsql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($getcost_centerlistsql);

    }

    function GetDistance($lat1, $lon1, $lat2, $lon2, $unit)

    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }

    }

    static function GetComparison($user_data, $general_data)

    {

        if (str_replace(" ", "",trim($user_data)) == str_replace(" ", "",trim($general_data)))
            $condition = 'selected';

        else
            $condition = '';

        return $condition;

    }

    function GetCheckBoxComparison($user_data, $general_data)

    {

        if (str_replace(" ", "",trim($user_data)) == str_replace(" ", "",trim($general_data)))
            $condition = 'checked';

        else
            $condition = '';

        return $condition;

    }

    static function truncate($text, $length) {
        $length = abs((int)$length);
        if(strlen($text) > $length) {
            $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1', $text);
        }
        return($text);
    }

    function CustomersMap()

    {
        $customer_routes = (isset($_POST['routes']) ? "AND c.route_code IN('".implode("','",$_POST["routes"])."')" : "");

        $customer_zones = (isset($_POST['areas']) ? "AND c.area_code IN('".implode("','",$_POST["areas"])."')" : "");

        $sellers = (isset($_POST['sellers']) ? "AND s.seller_code IN('".implode("','",$_POST["sellers"])."')" : "");

        $days = '';
        if(isset($_POST['days'])){
            if(!in_array("all", $_POST["days"], true)){
                $days = "AND c.day_id IN('".implode("','",$_POST["days"])."')";
            }
        }


        $customers_location_sql = self::$con->prepare("SELECT 
			c.customer_code, 
			c.seller_code, 
			c.customer_name, 
			c.customer_latitude, 
			c.customer_longitude, 
			s.seller_name, 
			c.customer_balance 
			FROM ".self::$user_database.".customers c
			LEFT JOIN ".self::$user_database.".sellers s ON (s.seller_code = c.seller_code and s.company_id = c.company_id)
			WHERE c.company_id = :cid 
			AND c.customer_latitude != '' 
			AND c.customer_longitude != '' 
			".$customer_routes."
			".$customer_zones."
			".$sellers."
			".$days);

        $customers_location_sql->bindParam(':cid', self::$default_company);
        $customers_location_sql->execute();

        $customers_location_sql = $customers_location_sql->fetchAll();

        return json_encode($customers_location_sql);

    }

    function CustomersMapDetail($row, $startdate) {

        $seller_customer_sql = self::$con->prepare("SELECT
				COUNT(oh.customer_code) as orders,
				COUNT(rh.customer_code) as receipts,
				COUNT(ih.customer_code) as invoices
				FROM ".self::$user_database.".orders_header oh
				LEFT JOIN ".self::$user_database.".receivables_header rh ON (rh.seller_code = oh.seller_code AND rh.receipt_income_date = oh.order_date AND rh.customer_code = oh.customer_code AND rh.company_id = oh.company_id)
				LEFT JOIN ".self::$user_database.".invoices_header ih ON (ih.seller_code = oh.seller_code AND ih.invoice_date = oh.order_date AND ih.customer_code = oh.customer_code AND ih.company_id = oh.company_id)
				WHERE
				oh.seller_code = :seller_code
				AND oh.company_id = :company_id
				AND oh.order_date = :start_date
				AND oh.customer_code = :customer_code");

        $seller_customer_sql->bindParam(':seller_code', $row->seller_code);
        $seller_customer_sql->bindParam(':company_id', self::$default_company);
        $seller_customer_sql->bindParam(':start_date', $startdate);
        $seller_customer_sql->bindParam(':customer_code', $row->customer_code);
        $seller_customer_sql->execute();

        $orders_receipts_invoices = $seller_customer_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($orders_receipts_invoices);


    }



    /*

      Import functions

    */

    function CostCenters(){

        $cost_centers_sql = self::$con->prepare("SELECT COUNT(cost_center_code) FROM ".self::$user_database.".cost_center");
        $cost_centers_sql->execute();
        $cost_centers = $cost_centers_sql->fetchColumn();

        echo $cost_centers;

    }

    function Warehouses(){

        $warehouses_sql = self::$con->prepare("SELECT COUNT(warehouse_code) FROM ".self::$user_database.".warehouses");
        $warehouses_sql->execute();
        $warehouses = $warehouses_sql->fetchColumn();

        echo $warehouses;

    }

    function Banks(){

        $banks_sql = self::$con->prepare("SELECT COUNT(bank_code) FROM ".self::$user_database.".banks");
        $banks_sql->execute();
        $banks = $banks_sql->fetchColumn();

        echo $banks;

    }

    function Sellers(){

        $sellers_sql = self::$con->prepare("SELECT COUNT(seller_code) FROM ".self::$user_database.".sellers");
        $sellers_sql->execute();
        $sellers = $sellers_sql->fetchColumn();

        echo $sellers;

    }

    function Collectors(){

        $collectors_sql = self::$con->prepare("SELECT COUNT(collector_code) FROM ".self::$user_database.".collectors");
        $collectors_sql->execute();
        $collectors = $collectors_sql->fetchColumn();

        echo $collectors;

    }

    function Areas(){

        $areas_sql = self::$con->prepare("SELECT COUNT(area_code) FROM ".self::$user_database.".areas");
        $areas_sql->execute();
        $areas = $areas_sql->fetchColumn();

        echo $areas;

    }

    function Routes(){

        $routes_sql = self::$con->prepare("SELECT COUNT(route_code) FROM ".self::$user_database.".routes");
        $routes_sql->execute();
        $routes = $routes_sql->fetchColumn();

        echo $routes;

    }

    function Countries(){

        $countries_sql = self::$con->prepare("SELECT COUNT(country_id) FROM ".self::$user_database.".countries");
        $countries_sql->execute();
        $countries = $countries_sql->fetchColumn();

        echo $countries;

    }

    function Provinces(){

        $provinces_sql = self::$con->prepare("SELECT COUNT(province_id) FROM ".self::$user_database.".provinces");
        $provinces_sql->execute();
        $provinces = $provinces_sql->fetchColumn();

        echo $provinces;

    }

    function Cities(){

        $cities_sql = self::$con->prepare("SELECT COUNT(city_id) FROM ".self::$user_database.".cities");
        $cities_sql->execute();
        $cities = $cities_sql->fetchColumn();

        echo $cities;

    }

    function Sectors(){

        $sectors_sql = self::$con->prepare("SELECT COUNT(sector_id) FROM ".self::$user_database.".sectors");
        $sectors_sql->execute();
        $sectors = $sectors_sql->fetchColumn();

        echo $sectors;

    }

    function Products(){

        $products_sql = self::$con->prepare("SELECT COUNT(product_id) FROM ".self::$user_database.".products");
        $products_sql->execute();
        $products = $products_sql->fetchColumn();

        echo $products;

    }

    function ProductTypes(){

        $producttypes_sql = self::$con->prepare("SELECT COUNT(product_type_id) FROM ".self::$user_database.".product_types");
        $producttypes_sql->execute();
        $producttypes = $producttypes_sql->fetchColumn();

        echo $producttypes;

    }

    function Brands(){

        $brands_sql = self::$con->prepare("SELECT COUNT(brand_id) FROM ".self::$user_database.".brands");
        $brands_sql->execute();
        $brands = $brands_sql->fetchColumn();

        echo $brands;

    }

    function Classifications(){

        $classifications_sql = self::$con->prepare("SELECT COUNT(classification_code) FROM ".self::$user_database.".classifications");
        $classifications_sql->execute();
        $classifications = $classifications_sql->fetchColumn();

        echo $classifications;

    }

    function Subclassifications(){

        $subclassifications_sql = self::$con->prepare("SELECT COUNT(subclassification_code) FROM ".self::$user_database.".subclassifications");
        $subclassifications_sql->execute();
        $subclassifications = $subclassifications_sql->fetchColumn();

        echo $subclassifications;

    }

    function Families(){

        $families_sql = self::$con->prepare("SELECT COUNT(family_code) FROM ".self::$user_database.".product_families");
        $families_sql->execute();
        $families = $families_sql->fetchColumn();

        echo $families;

    }

}


if (isset($_POST['function_type']))

{
    $general_functions = new General;
    if ($_POST['function_type'] == "orders")

    {

        /*
            Get details of each transaction
        */
        $orders_details = array();

        foreach (json_decode(
                     $general_functions->GetOrders(
                         stripslashes(str_replace('\"', '"',$_POST["seller_condition"])),
                         stripslashes(str_replace('\"', '"',$_POST["sellerlist"])),
                         stripslashes(str_replace('\"', '"',$_POST["startdate"])),
                         stripslashes(str_replace('\"', '"',$_POST["enddate"])), "cantidad")
                 ) as $orders)

        {

            $orders_details_json = json_decode($general_functions->GetOrderInfo($orders->order_id));

            $orders_details[] = $orders_details_json[0];

        }

        $general_functions->OrdersTable($orders_details);

    }

    if ($_POST['function_type'] == "invoices")

    {

        /*
            Get invoices
        */

        $general_functions->GetReceivables(
            stripslashes(str_replace('\"', '"',$_POST['startdate'])),
            stripslashes(str_replace('\"', '"',$_POST['enddate'])),
            stripslashes(str_replace('\"', '"',$_POST['collector_condition'])),
            stripslashes(str_replace('\"', '"',$_POST['collectorlist'])));

    }

    if ($_POST['function_type'] == "returns")

    {

        /*
            Get invoices
        */

        $general_functions->ReturnsTable(json_decode($general_functions->GetReturns(
            stripslashes(str_replace('\"', '"',$_POST['startdate'])),
            stripslashes(str_replace('\"', '"',$_POST['enddate'])),
            stripslashes(str_replace('\"', '"',$_POST['seller_condition'])),
            stripslashes(str_replace('\"', '"',$_POST['sellerlist'])))));



    }

}

?>
