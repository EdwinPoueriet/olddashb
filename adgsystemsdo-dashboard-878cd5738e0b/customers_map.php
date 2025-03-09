<?php

use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;
use App\Legacy\Scripts;
use App\Legacy\General;
use App\Legacy\Credentials;



  $general_functions = new General;

include('config/datetime_parameters.php');
include('config/home_parameters.php');

/* 

	Structure and stuff. 

*/

  $credentials_obj = new Credentials;

  $credentials_json = $credentials_obj->SelectedCompanyCredentials();             

  $credentials = json_decode($credentials_json);

  if($credentials[0]->maps_customers == 0)
    header('Location: home');

/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(1, "Mapa de los clientes");

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
										<li class="active"><span>Mapas</span></li>
									</ol>
									
									<h1>Mapa Geográfico de Clientes</h1>
								</div>
							</div>
							
                            <div class="row">
                                <div class="col-lg-12">

                                  <div class="main-box clearfix">
                                    <header class="main-box-header clearfix">
                                      <h2>Filtros actuales</h2>
                                    </header>
                                    <div class="main-box-body">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div style="display: inline-block;margin-right: 15px">
                                            <span class="label label-success">Fecha</span>
                                            <span class="label label-primary"><?php  echo date_create_from_format('Y-m-d',$startdate)->format('d-m-Y') ?></span>
                                          </div>

                                            <?php

                                            foreach (json_decode($general_functions->GetSellers()) as $row)
                                            {
                                                if (isset($_POST["sellers"]))
                                                    if (in_array($row->seller_code, $_POST["sellers"], true))
                                                        echo '
                                             <div style="display: inline-block;margin-right: 15px">
                                        <span class="label label-success">Vendedor</span>
                                      
                                        <span class="label label-primary">'.$row->seller_code.' - '.$row->seller_name.'</span>
                                    </div>
                                        ';


                                            }

                                            ?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>



                                  <div style="width: 100%; padding: 20px; padding-top: 5px;">
                                    <div id="map-canvas" style="height: 650px; box-shadow: 0 0 7px 3px rgba(0,0,0,.25);"></div>
                                  </div>


                                    <div class="main-box clearfix" >
                                        <div class="row">
                                            <div class="col-md-12">
                                              <div  style="padding: 15px" >
                                                <table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
                                                  <thead>
                                                  <tr>
                                                    <th>Cliente</th>
                                                    <th>Vendedor</th>
                                                    <th data-type="html" style="text-align: center">¿Ordenes? </th>
                                                    <th data-type="html" style="text-align: center">¿Recibos? </th>
                                                    <th data-type="html" style="text-align: center">¿Facturas? </th>
                                                    <th data-type="html">Balance</th>
                                                    <th data-type="html" style="text-align: center">Ubicación</th>
                                                  </tr>

                                                  </thead>
                                                  <tbody>

                                                  <?php

                                                  $obj = json_decode($general_functions->CustomersMap());
                                                  $array = "";
                                                  foreach ($obj as $row) {

                                                      $details = json_decode($general_functions->CustomersMapDetail($row, $startdate));

                                                      $array .= '{customer:"'.$row->customer_code.'", data: {seller_code: "'.$row->seller_code.'",
            customer_name: "'.htmlentities($row->customer_name).'", customer_latitude: '.$row->customer_latitude.',
            customer_longitude: '.$row->customer_longitude.', seller_name: "'.$row->seller_name.'", 
            customer_balance: '.$row->customer_balance.'';

                                                      if ($details[0]->orders >= 1)
                                                          $array.= ',orders: 1';

                                                      else
                                                          $array.=  ',orders: 2';

                                                      if ($details[0]->receipts >= 1)
                                                          $array.=  ',receipts: 1';
                                                      else
                                                          $array.=  ',receipts: 2';

                                                      if ($details[0]->invoices >= 1)
                                                          $array.=  ',invoices: 1';

                                                      else
                                                          $array.=  ',invoices: 2';

                                                      $array.=  '} },';



                                                      echo '
                                                            <tr>
                                                              <td>'.$row->customer_code.' - '.$row->customer_name.'</td>
                                                              <td>'.$row->seller_code.' - '.$row->seller_name.'</td>';

                                                      if ($details[0]->orders >= 1 )
                                                          echo '<td style="text-align: center"><span class="label label-success">Si</span></td>';
                                                      else
                                                          echo '<td style="text-align: center"><span class="label label-danger">No</span></td>';
                                                      if ($details[0]->receipts >= 1 )
                                                          echo '<td style="text-align: center"><span class="label label-success">Si</span></td>';
                                                      else
                                                          echo '<td style="text-align: center"><span class="label label-danger">No</span></td>';
                                                      if ($details[0]->invoices >= 1 )
                                                          echo '<td style="text-align: center"><span class="label label-success">Si</span></td>';
                                                      else
                                                          echo '<td style="text-align: center"><span class="label label-danger">No</span></td>';

                                                      echo '<td ><span class="label label-primary">$ '.number_format($row->customer_balance, 2, '.', ',').'</span></td>';

                                                      if ($row->customer_latitude !== '' && $row->customer_latitude !== '0' && $row->customer_longitude !== '' && $row->customer_longitude !== '0')
                                                          echo'        <td style="text-align: center">
        <a href="#map-canvas" class="verenmapa" data-customer="'.$row->customer_code.'"> <b>Ver </b> </a> </td>

                                                        ';
                                                      else
                                                          echo'   <td style="text-align: center">No disponible</td>

                                                        ';

                                                      echo '       </tr>
                                                                                      ';


                                                  }
                                                  ?>

                                                  </tbody>
                                                </table>

                                              </div>
                                           </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
							
                        </div>
                    </div>

<?php

/*	
	Shows document modal for filters 
	@include('config/structure/footer.php') 
	must be included for this function to run 

*/
  $footer = new Footer;

  $footer->Modal($startdate, $enddate, array(
    "sellers" => 1,
    "collectors" => 0,
    "products" => 0,
    "producttypes" => 0,
    "routes" => 0,
    "areas" => 0,
    "brands" => 0,
    "classifications" => 0,
    "subclassifications" => 0,
    "families" => 0,
    "warehouses" => 0,
    "costcenters" => 0,
    "excludeseller" => 1,
    "didntbuy" => 0,
    "datepicker" => 1,
    "dayfilter"=> 0
    ));
/*	
	Shows document footer 
	@include('config/structure/footer.php') 
	must be included for this function to run 

*/

	$footer->Initialize();

/*	
	Shows document scripts 
	@include('config/structure/scripts.php') 
	must be included for this function to run 

*/

	Scripts::Initialize();

?>

	  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apikey; ?>"></script>
<script type="text/javascript"  src="js/markerclusterer.js"></script>

      <script>

        var originalMarkerList = [];
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow({
          content: ''
        });
    // Our markers
    originalMarkerList =[
        <?php

  echo $array;

    ?>] ;

        function initialize() {
            var mapOptions = {
                zoom: <?php echo $zoom; ?>,
                center: new google.maps.LatLng(<?php echo $lat.', '.$long; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            markerList = originalMarkerList.map(function(marker) {
                var markerToBeAdded = getMarker(marker);

                var venta = '';
                var cobro = '';
                var factura = '';
                if (marker.data.orders == '1')
                    venta =  'Si';
                else
                    venta = 'No';

                if (marker.data.receipts == '1')
                    cobro = 'Si';
                else
                    cobro = 'No';

                if (marker.data.invoices == '1')
                    factura = 'Si';
                else
                    factura = 'No';

                var content = '<div id="iw-container">' +
                    '<div class="iw-title">' + marker.customer + ' - ' + marker.data.customer_name + '</div>' +
                    '<div class="iw-content">' +
                    '<div class="iw-subTitle"> Vendedor: '+ marker.data.seller_code +' - '+ marker.data.seller_name+ '</div>' +
                    '<div class="iw-subTitle"> Balance: '+ marker.data.customer_balance + '</div>' +
                    '<div class="iw-subTitle"> ¿ Se le realizaron ventas ? '+ venta + '</div>' +
                    '<div class="iw-subTitle"> ¿ Se le realizaron cobros ? '+ cobro + '</div>' +
                    '<div class="iw-subTitle"> ¿ Se le realizaron facturas ? '+ factura + '</div>';

                var geo;
                google.maps.event.addListener(markerToBeAdded, 'click', (function (marker1, content) {
                    return function () {
                        geocoder.geocode({'latLng': new google.maps.LatLng(marker.data.customer_latitude, marker.data.customer_longitude)}, function (marker) {
                            geo = marker[0].formatted_address;
                            infowindow.setContent(content + '<div class="iw-subTitle"> ' + 'Dirección: ' + geo + '</div>' + '</div>');
                        });
                        infowindow.open(map, markerToBeAdded);
                        map.panTo(this.getPosition());
                        map.setZoom(17);
                    }
                })(markerToBeAdded, content));

                return markerToBeAdded;
            });
            var markerCluster = new MarkerClusterer(map, markerList, { imagePath: 'img/m'});

        }

        function getMarker(marker) {
            var iconBase = "";
            if ((marker.data.orders == '1' || marker.data.receipts == '1') || marker.data.invoices == '1'){
                iconBase = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_green.png';
            }
            else {
                iconBase = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red.png';
            }
            var pos = new google.maps.LatLng(marker.data.customer_latitude, marker.data.customer_longitude);

            var category = marker.customer;
            var title = marker.data.customer_name;
           return markerToBeAdded = new google.maps.Marker({
                title: title,
                position: pos,
                category: category,
                icon: iconBase
            });


        }

        $('.footable').on('after.ft.paging',function () {
            console.log('paging');
            $('.verenmapa').on('click',function () {

                tableClick(this)
            });
        });
        $('.footable').on('after.ft.sorting',function () {
            console.log('sorting');
            $('.verenmapa').on('click',function () {
                tableClick(this)
            });
        });
        $('.verenmapa').on('click',function () {
            tableClick(this)
        });

        function tableClick(_this) {
            var customer = originalMarkerList.filter(function (value) {
                return value.customer === $(_this).data('customer')
            });
            map.setZoom(17);
            map.panTo({lat: customer[0].data.customer_latitude,lng:customer[0].data.customer_longitude})
        }


  // *
  // START INFOWINDOW CUSTOMIZE.
  // The google.maps.event.addListener() event expects
  // the creation of the infowindow HTML structure 'domready'
  // and before the opening of the infowindow, defined styles are applied.
  // *
  google.maps.event.addListener(infowindow, 'domready', function() {

    // Reference to the DIV that wraps the bottom of infowindow
    var iwOuter = $('.gm-style-iw');

    /* Since this div is in a position prior to .gm-div style-iw.
     * We use jQuery and create a iwBackground variable,
     * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
    */
    var iwBackground = iwOuter.prev();

    // Removes background shadow DIV
    iwBackground.children(':nth-child(2)').css({'display' : 'none'});

    // Removes white background DIV
    iwBackground.children(':nth-child(4)').css({'display' : 'none'});

    // Moves the infowindow 115px to the right.
    iwOuter.parent().parent().css({left: '115px'});

    // Moves the shadow of the arrow 76px to the left margin.
    iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

    // Moves the arrow 76px to the left margin.
    iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

    // Changes the desired tail shadow color.
    iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});

    // Reference to the div that groups the close button elements.
    var iwCloseBtn = iwOuter.next();

    // Apply the desired effect to the close button
    iwCloseBtn.css({opacity: '1', right: '60px', top: '28px', 'border-radius': '13px', 'box-shadow': '0 0 5px #3990B9'});

    // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
    if($('.iw-content').height() < 140){
      $('.iw-bottom-gradient').css({display: 'none'});
    }

    // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
    iwCloseBtn.mouseout(function(){
      $(this).css({opacity: '1'});
    });
  });


    initialize();
  </script>
  <script>

    $('.sel2Multi').select2({
//  placeholder: 'Select a Country',
allowClear: true
});

    //datepicker
    $('#datepickerDate').datepicker({
      format: 'mm-dd-yyyy'
    });

    $('#datepickerDateComponent').datepicker();
    

</script>
   <style type="text/css">
    #map-canvas { 
      min-height: 500px;
    }

.gm-style-iw {
    min-width: 300px !important;
    max-width: 300px;
    width: 300px;
    top: 15px !important;
    left: 0px !important;
    background-color: #fff;
    box-shadow: 0 1px 6px rgba(178, 178, 178, 0.6);
    border: 1px solid rgba(72, 181, 233, 0.6);
}
#iw-container {
    margin-bottom: 10px;
}
#iw-container .iw-title {
    font-size: 13px;
    font-weight: 700;
    padding: 10px;
    background-color: #48b5e9;
    color: white;
    margin: 0;
    font-family: 'Open Sans', sans-serif;
}
#iw-container .iw-content {
    font-size: 13px;
    line-height: 18px;
    font-weight: 400;
    margin-right: 1px;
    padding: 10px 5px 05px 10px;
    max-height: 210px;
    overflow-y: auto;
    overflow-x: hidden;
}
.iw-content img {
    float: right;
    margin: 0 5px 5px 10px; 
    font-family: 'Open Sans', sans-serif;
}
.iw-subTitle {
    font-size: 12px;
    font-weight: 700;
    padding: 5px 0;
    color: #1F1F1F;
    font-family: 'Open Sans', sans-serif;
}
.iw-bottom-gradient {
    position: absolute;
    width: 326px;
    height: 25px;
    bottom: 10px;
    right: 18px;
    background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
    background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
}
 </style>
</body>
</html>