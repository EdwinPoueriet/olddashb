 <?php
 use App\Legacy\Header;
 use App\Legacy\Navigation;
 use App\Legacy\Footer;
 use App\Legacy\Scripts;
 use App\Legacy\General;
 use App\Legacy\Credentials;
  include('config/datetime_parameters.php');
  $general_functions = new General;
  include('config/home_parameters.php');

/* 

  Structure and stuff. 

*/
  $credentials_obj = new Credentials;

  $credentials_json = $credentials_obj->SelectedCompanyCredentials();             

  $credentials = json_decode($credentials_json);

  if($credentials[0]->maps_sellers == 0)
    header('Location: dashboard');

  $sellers_locations = json_decode($general_functions->GetSellersLocations());

/*	
	Shows document header 
	@include('config/structure/header.php') 
	must be included for this function to run 

*/

  $header = new Header;
  $header->Initialize(0, "Mapa de Vendedores");

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

									<h1>Vendedores</h1>
								</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box clearfix">
                                        <header class="main-box-header">
                                            <div class="alert alert-info fade in">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                                <strong>Nota</strong>
                                                Puede poner el cursor encima de los marcadores en el mapa para saber rápidamente a que vendedor corresponse la ubicación.
                                            </div>
                                        </header>

                                        <div class="main-box-body clearfix">
                                            <div style="width: 100%; padding-bottom: 10px">
                                                <div style="position: relative;" id="map-canvas"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                    <div class="col-lg-12">
                      <div class="main-box clearfix">
                        <header class="main-box-header clearfix">
                            <div class="row">
                                <h2 class="pull-left">Listado de vendedores</h2>
                            </div>
                            <div class="row">
                                <div class="alert alert-info fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                    <strong>Aviso!</strong> Para que los datos de ubicación de un vendedor esten disponibles, se debe cumplir que:
                                    <ul>
                                        <li>El dispositivo esté encendido.</li>
                                        <li>La aplicación SDM esté ejecutandose.</li>
                                        <li>El dispositivo tenga conexión a internet y servicio de localización activo.</li>
                                    </ul>
                                </div>
                            </div>

                        </header>

                        <div class="main-box-body clearfix">
                        <table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
                            <thead>
                              <tr>
                                <th>Vendedor</th>
                                <th>Teléfono</th>
                                <th>Última actualización</th>
                                <th data-type="html" style="text-align: center">Ubicación</th>
                              </tr>
                            </thead>
                            <tbody>
<!--                            <td>Latitud: '.number_format($row->latitud,4).' Longitud: '.number_format($row->longitud,4).'</td>-->
                            <?php

                            foreach ($sellers_locations as $row) {

                                echo '
                                <tr>
                                  <td>'.$row->seller_code.' - '.$row->seller_name.'</td>
                                  <td>'.$row->seller_phone.'</td>
                                 
                                  <td>
                                  ';
                                if ($row->ult_actualizacion) {
                                    echo date_format(date_create($row->ult_actualizacion), "h:i:s A, d-m-Y");
                                } else {
                                    echo 'N/A';
                                }
                                echo '</td>
                                  <td style="text-align: center">
                                  <button type="button" class="btn btn-primary" onclick="filterMarkers(this.value);" value="'.$row->seller_name.'">Ver</button>
                                  </td>
                                </tr>
                                  ';
                            }
                            ?>
                              </tbody>
                            </table>
                      <ul class="pagination pull-right hide-if-no-paging"></ul>
                          </div>
                        </div>
                      </div>
                    </div>


<?php 
/*	
	Shows document footer 
	@include('config/structure/footer.php') 
	must be included for this function to run 

*/
  $footer = new Footer;
	$footer->Initialize();

/*	
	Shows document scripts 
	@include('config/structure/scripts.php') 
	must be included for this function to run 

*/

	Scripts::Initialize();

?>

	  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apikey; ?>&signed_in=true"></script>

                <script>

                  var gmarkers1 = [];
                  var markers1 = [];
                  var geocoder = new google.maps.Geocoder();
                  var infowindow = new google.maps.InfoWindow({
                    content: '',
                    maxWidth: 300
                  });

  markers1 =[<?php
 
  foreach ($sellers_locations as $row) {
      echo '
      ["'.$row->seller_code.'", "'.$row->seller_name.'", "'.$row->latitud.'", "'.$row->longitud.'", "'.$row->localizacion.'"],';
    }

  ?>] ;



   function CenterControl(controlDiv, map) {

                    // Set CSS for the control border.
                    var controlUI = document.createElement('div');
                    controlUI.style.backgroundColor = '#fff';
                    controlUI.style.border = '2px solid #fff';
                    controlUI.style.borderRadius = '3px';
                    controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
                    controlUI.style.cursor = 'pointer';
                    controlUI.style.textAlign = 'center';
                    controlUI.style.marginTop = '10px';
     controlUI.style.marginRight = '10px';

     controlUI.title = 'Click para centrar';

     controlDiv.appendChild(controlUI);

                    // Set CSS for the control interior.
                    var controlText = document.createElement('div');
                    controlText.style.color = 'rgb(25,25,25)';
                    controlText.style.fontSize = '13px';
                    controlText.style.lineHeight = '25px';
                    controlText.style.paddingLeft = '5px';
                    controlText.style.paddingRight = '5px';
                    controlText.innerHTML = 'Centrar Mapa';
                    controlUI.appendChild(controlText);

                    // Setup the click event listeners: simply set the map to Chicago.
                    controlUI.addEventListener('click', function() {
                      map.setCenter(thecenter);
                      map.setZoom(thezoom);
                    });

                  }

    var thezoom = <?php echo $zoom; ?>;
    var globalmap = null;
    var thecenter = new google.maps.LatLng(<?php echo ''.$lat.', '.$long.''; ?>);



    function initialize() {
    var mapOptions = {
      zoom: <?php echo $zoom; ?>,

      center: new google.maps.LatLng(<?php echo ''.$lat.', '.$long.''; ?>),
      heading: 90
    };

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
      var centerControlDiv = document.createElement('div');
      var centerControl = new CenterControl(centerControlDiv, map);

      centerControlDiv.index = 1;
      map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);
      globalmap = map;
    for (i = 0; i < markers1.length; i++) {
      addMarker(markers1[i]);
    }    

  }

    function addMarker(marker) {

    var category = marker[1];

    var title = marker[1];
   
    var iconBase = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_orange.png';

    var pos = new google.maps.LatLng(marker[2], marker[3]);

    var content =   '<div id="iw-container">' +
      '<div class="iw-title">' + marker[0] + '-' + marker[1] + '</div>' +
      '<div class="iw-content">' +
      '<div > <b>Latitud</b><br>  '+ parseFloat(marker[2]).toFixed(5)  + '</div>' +
      '<div > <b>Longitud</b><br>  '+ parseFloat(marker[3]).toFixed(5)  + '</div>';

    var geo;

    var markerToBeAdded = new google.maps.Marker({
      title: title,
      position: pos,
      category: category,
      map: map,
      animation: google.maps.Animation.DROP,
      icon: iconBase
    });

      markerToBeAdded.addListener('click', function() {

        geocoder.geocode({'latLng': pos}, function (marker) {
          geo = marker[1].formatted_address;
          infowindow.setContent(content + '<div > ' + ' <b>Referencia más cercana </b><br> ' + geo + '</div>' + '</div>');
        });
        map.setZoom(16);
        map.setCenter(markerToBeAdded.getPosition());
        infowindow.open(map, markerToBeAdded);
      });

      gmarkers1.push(markerToBeAdded);


   filterMarkers =  function (category) {

    for (i = 0; i < markers1.length; i++) {
      markerToBeAdded = gmarkers1[i];
  if (markerToBeAdded.category == category || category.length === 0) {
   google.maps.event.trigger(markerToBeAdded, 'click');
    $(window).scrollTop(0); 
            }
        }
      } 

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

  // Init map

  initialize();
 
  </script>
</script>
   <style type="text/css">
    #map-canvas { 
      min-height: 700px;
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
    max-height: 170px;
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