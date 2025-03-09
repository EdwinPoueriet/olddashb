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

  $credentials_obj = new Credentials;

  $credentials_json = $credentials_obj->SelectedCompanyCredentials();             

  $credentials = json_decode($credentials_json);

  if($credentials[0]->maps_routes == 0)
    header('Location: home');


  if (isset($_POST['sellers'][0]))

  $sellers_routes = json_decode($general_functions->GetSellersRoutes($_POST['sellers'][0], $startdate));
 

/*  
  Shows document header 
  @include('config/structure/header.php') 
  must be included for this function to run
*/

  $header = new Header;
  $header->Initialize(1, "Mapa de las rutas");

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
                  
                  <h1>Rutas Puntuales</h1>
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
                  <div class="main-box clearfix">
                      <header class="main-box-header clearfix">

                          <div class="row">
                              <div class="col-md-12">
                                  <div class="alert alert-info fade in">
                                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                      <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                      <strong> Info </strong> Seleccione que informacion filtrar en el mapa, haciendo click en el boton <b>Filtros</b> de la barra superior.
                                  </div>
                              </div>
                          </div>

                      </header>
                    <div class="main-box-body clearfix">

                       <div style="width: 100%; padding-bottom: 10px">
                         <div id="map-canvas" style="height: 700px;padding: 15px;padding-top: 0px"></div>
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
  Array parameters are for the select options 
  @include('config/structure/footer.php') 
  must be included for this function to run 

*/
  
  $footer = new Footer;

  $footer->Modal($startdate, $enddate, array(

    "sellers" => 1,
    "collectors" =>0,
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
    "excludeseller" => 0,
    "didntbuy" => 0,
    "datepicker" => 1,
      "dayfilter"=>0

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

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apikey; ?>&signed_in=true"></script>

       <script>
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow({
          content: '',
          maxWidth: 300
        });

        var markers1 = [];

        var gmarkers1 = [];

        colors = ['#FAEBD7', '#7FFFD4', '#F5F5DC', '#000000', '#0000FF', '#8A2BE2', '#A52A2A', '#DEB887', '#5F9EA0', '#7FFF00', '#D2691E', '#6495ED', '#DC143C', '#00FFFF', '#00008B', '#008B8B', '#B8860B', '#A9A9A9', '#006400', '#BDB76B', '#8B008B', '#556B2F', '#FF8C00', '#9932CC', '#8B0000', '#E9967A', '#8FBC8F', '#483D8B', '#2F4F4F', '#00CED1', '#9400D4', '#FF1493', '#00BFFF', '#696969', '#1E90FF', '#B22222', '#228B22', '#FF00FF', '#DCDCDC', '#F8F8FF', '#FFD700', '#DAA520', '#808080', '#008000', '#ADFF2F', '#FF69B4', '#CD5C5C', '#4B0082', '#F0E68C', '#E6E6FA', '#6B8E23', '#ADD8E6', '#F08080', '#E0FFFF', '#90EE90', '#D3D3D3', '#FF86C1', '#FFA07A', '#20B2AA', '#87CEFA', '#778899', '#00FF00', '#32CD32', '#FAF0E6', '#800000', '#66CDAA', '#0000CD', '#BA55D3', '#9370DB', '#3CB371', '#7B68EE', '#00FA9A', '#48D1CC', '#C71585', '#191970', '#F5FFFA', '#FFE4E1', '#FFDEAD', '#000080', '#FDF5E6', '#808000', '#FFA500', '#FF4500', '#DA70D6', '#EEE8AA', '#98FB98', '#AFEEEE', '#DB7093', '#FFDAB9', '#CD853F', '#FFC0CB', '#DDA0DD', '#B0E0E6', '#800080', '#BC8F8F', '#4169E1', '#8B4513', '#FA8072', '#F4A460', '#2E8B57', '#A0522D', '#C0C0C0', '#87CEEB', '#6A5ACD', '#708090', '#00FF7F', '#4682B4', '#D2B48C', '#008080', '#D88FD8', '#FF6347', '#40E0D0', '#EE82EE', '#F5DEB3', '#F6F6F6', '#F5F5F5', '#9ACD32']; 

        markers1 =[<?php

        if (isset($_POST['sellers'][0]))

        {
          
          foreach ($sellers_routes as $row) 

            {
          
              echo '["'.$row->seller_latitude.'", "'.$row->seller_longitude.'", "'.@date_format(date_create($row->seller_location_datetime), "H:i:s a").'"],';

            } 

        } else

          echo '["", "", "", ""]';

        ?>] ;

        var first = markers1.slice(0)[0]; 
        var last = markers1.slice(-1)[0];

        var globalmap = null;
        function initialize() {
          var mapOptions = {
            zoom: <?php echo $zoom; ?>,
            center: new google.maps.LatLng(18.903,-71.1),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            heading: 90
          };

          map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        globalmap = map;
          var centerControlDiv = document.createElement('div');
          var centerControl = new CenterControl(centerControlDiv, map);
          centerControlDiv.index = 1;
          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);

          for (i = 0; i < markers1.length; i++) {
            addMarker(markers1[i]);
          }    

        }

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

        function addMarker(marker) {
            map = globalmap;
          var latlong = marker[0] + ',' + marker[1] + ',' + marker[2];
          if (latlong == first) {
            var iconBase = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red' + (i + 1) + '.png';
            var condition = 1000;
          }

          else {

            if (latlong == last) {

              var iconBase = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_green' + (i + 1) + '.png';
              var condition = 1000;

            }

            else {

              var iconBase = 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_blue' + (i + 1) + '.png';
              var condition = i;

            }

          }

          var pos = new google.maps.LatLng(marker[0], marker[1]);

          var contentString = '<div id="iw-container">' +
              '<div class="iw-title">Ubicación no.:' + (i + 1) + '</div>' +
              '<div class="iw-content">' +
              '<div class="iw-subTitle">Latitud: ' + parseFloat(marker[0]).toFixed(5) + '</div>' +
              '<div class="iw-subTitle">Longitud:' + parseFloat(marker[1]).toFixed(5) + '</div>' +
              '<div class="iw-subTitle">Hora de actualización: ' + marker[2] + '</div>';

          var content = contentString;

          var geo;

          var markerToBeAdded = new google.maps.Marker({
            position: pos,
            map: map,
            animation: google.maps.Animation.DROP,
            icon: iconBase,
            zIndex: condition
          });

          markerToBeAdded.addListener('click', function () {
            geocoder.geocode({'latLng': pos}, function (marker) {
              geo = marker[0].formatted_address;
              infowindow.setContent(content + '<div > ' + ' <b>Referencia más cercana </b><br> ' + geo + '</div>' + '</div>');
            });
            map.panTo(pos);
            map.setZoom(16);
            map.setCenter(pos);
            infowindow.open(map, markerToBeAdded);
          });

          gmarkers1.push(markerToBeAdded);

//          filterMarkers = function (category) {
//            for (i = 0; i < markers1.length; i++) {
//              markerToBeAdded = gmarkers1[i];
//              if (markerToBeAdded.category == category || category.length === 0) {
//                google.maps.event.trigger(markerToBeAdded, 'click');
//              }
//            }
//          }
        }


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
    max-height: 140px;
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