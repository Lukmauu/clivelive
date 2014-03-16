<?php
require '_includes/session.php';
require '_includes/clivelive-conn.php';
require '_includes/authentication.php';
require '_includes/func.php';

if (isset($_GET['log'])) {

    if ($_GET['com'] === 'logout') {

        session_destroy();
    }
}

$result = false;

function parseUrl($thing) {

    $temp = parse_url($thing);
    $url = "";

    foreach ($temp as $key => $value) {

        if ($key === "scheme") {

            $temp[$key] = "//";
            $url .= $temp[$key];
        } else {
            $url .= $temp[$key];
        }
    }
    return $url;
}

//$url_array_all = returnArrayQ();

$videoArray = array(0 => '_video/IMG_3281.MOV', 1 => '_video/Movie_0002.mp4');

/* foreach ($url_array_all as $key => $value) {
  if(is_null($value)){
  unset($url_array_all[$key]);
  }else{
  //$url_array_all[$key] = parseUrl($url_array_all[$key]);
  }
  } */
//echoPre( $url_array_all );

$random_num = array_rand($videoArray);

$url_random = $videoArray[$random_num];
require '_includes/header.php';
?>

<div id="geoWrap" style="display: none;">
    <a href="#" id="geo" class="btn btn-default">Current Location</a><a href="#" id="onOff" class="rFalse btn btn-danger">OFF</a>
</div>
<!-- error message for bad request on search    -->

<div id="starting" style="width: 100%;
     margin: 0 auto;
     text-align: center;
     margin-top: 18px;
     display: none;">
    <h4>Starting...</h4>
    <img style="display: inline;" src="_images/465.GIF">
</div>

<h4 class="lead-red error-msg-no-result" style="display: none;margin-bottom: 1.5em;"></h4>

<div style="position: relative; width: 100%;">
    <label class="checkbox-label"><input type="checkbox" id="checkbox" />use current location</label>
</div>
<!-- the loading .gif -->
<div class="loading"
     style="width: 100%;
     margin: 0 auto;
     text-align: center;
     margin-top: 18px;
     display: none; ">
    <h4>Searching...</h4>
    <img style="display: inline; " src="_images/ajax-loader.gif" />
</div>

<!-- START .center -->

<div class="center cl-container not-search-div" style="padding-top: 1.5em;">

    <div class="row">    
        <h1 class="medium-text-background col-xs-12 page-header">Clive's Pick</h1>

        <div class="hold-center-video container">
            <div class="row">
                <!-- //www.youtube.com/embed/RBumgq5yVrA -->
                <iframe class="col-xs-12 video-container" 
                        style="min-height: 400px; display: none;"
                        src="<?php //echo $url_random;  ?>" 
                        frameborder="0" 
                        allowfullscreen>

                </iframe>
                <!-- autoplay -->
                <video controls preload style="width: 100%; margin: 0 auto;">
                    <source src="<?php echo $url_random; ?>" type="video/mp4" />
                    <source src="<?php echo $url_random; ?>" type="video/mov" />
                </video>



            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<h4 class="lead-red error-msg" style="
    display: none;

    margin-bottom: 1.5em;">

</h4>


<div style="margin: 0 auto; margin-bottom: 1em; max-width: 250px" class="row">

    <a style="display: none;" href="#" class="col-xs-7 col-sm-6 hideSearchButton btn btn-default btn-success">Display result on the Map</a>
    <a style="display: none;" href="#" class="col-xs-7 col-sm-6 closeMapButton btn btn-default btn-danger">Hide Map</a>

</div>

<div class="clearfix"></div>

<div id="map_wrap" style="display: none;">

    <div id="map_canvas" >  </div>
    <h4 class="lead-blue msg-no-geo" style="display: none;margin-bottom: 0.5em;"></h4>
</div>

<div class="cl-container search-div row">

</div>

<!-- END .center -->
<?php require '_includes/footer.php'; ?>

<script>
    //<![CDATA[

    //$( document ).on( 'click', '#geo', getGeo );
        
    function getGeo ( e ) {
    
        var text = (  $( '#onOff:contains("ON")').length > 0 ) ? 'OFF' : 'ON';
    
        if ( typeof e !== 'undefined' ) {
        
            e.preventDefault();
        
            window.flag__ = false;
            window.get_geo_coords = false;
            window.get_geo_lat = false;
            window.get_geo_lng = false;
        }
    
        var text = (  $( '#onOff:contains("ON")').length > 0 ) ? 'OFF' : 'ON';
    
        if ( !navigator.geolocation || typeof navigator.geolocation === 'undefined' ) {

            return false;
        }
    
        if ( typeof e !== 'undefined' ) {
            navigator.geolocation.getCurrentPosition( function( position ) {
            
                if ( text === 'ON' ) { 
                
                    c('inside on')
                    window.get_geo_coords = true;
                    window.get_geo_lat = position.coords.latitude;
                    window.get_geo_lng = position.coords.longitude;
                    window.flag__ = true;
                } else {
                
                    window.get_geo_coords = false;
                    window.get_geo_lat = false;
                    window.get_geo_lng = false;
                    window.flag__ = true;
                }
            }, 
            function( error ) {

                window.get_geo_coords = false;
                window.get_geo_lat = false;
                window.get_geo_lng = false;
                window.flag__ = true;
            }, {
            
                enableHighAccuracy : true,
                timeout : 1000,
                maximumAge : 600000
            } );
        }
    
        function bad_ ( error ) {

            window.get_geo_coords = false;
            window.get_geo_lat = false;
            window.get_geo_lng = false;
            window.flag__ = true;
        }
    
        if ( !window.flag__ ) {
        
            setTimeout( function() {
            
                getGeo();
            }, 1);
            return;
        }
        var text = (  $( '#onOff:contains("ON")').length > 0 ) ? 'OFF' : 'ON';
    
    
        $( '#onOff' ).text( text );
        
        if ( text === 'ON' ) {
            
            $( '#onOff' ).removeClass( 'btn-danger' ).addClass( 'btn-info' );
        } else {
            
            $( '#onOff' ).removeClass( 'btn-info' ).addClass( 'btn-danger' );
        }
    
    
        //btn-danger
        //btn-info
    
        return window.afterGeo = {
            result : window.get_geo_coords,
            coords : {
                lat : window.get_geo_lat,
                lng : window.get_geo_lng
            }
        };
    }
        
    function deserialize ( array_ ) {
        
        var city;
        for ( var i = 0; i < array_.length; i++  ) {

            if ( $.inArray( 'locality', array_[i].types ) === 0 ) {

                city = array_[i].long_name;
            }
        }
        return city;
    }
    
    $( document ).on( 'submit', '#FORM', getThisCity );
        
    /*$.get( 'bed.php' )
            .done( function ( done ) {
              
                CITIES_OBJ = $.parseJSON( done );
              
            } )
            .fail( function ( fail ) {
              
              
              
            } );
     */
    var CITIES_OBJ = false;
    var array_ = [];
    var i = 0;
    function getThisCity ( e ) {
            
        e.preventDefault();
            
        if( CITIES_OBJ === false ) {
                
            setTimeout( function ( e ) {
                    
                getThisCity( e ); 
            }, 1 );
        }
            
         
            
        var form = $( this );
        
        var formObj = form.serializeObject();
            
        formObj.number = parseFloat( formObj.number );
            
        var send = CITIES_OBJ[formObj.number] + ',+WA';

        var url = 'http://maps.googleapis.com/maps/api/geocode/json?address=+' + send + '&sensor=false';
        
        $.get( url )
        .done( function ( done ) {
                    
                    
            console.log( done );
            if(done.status === 'OK'){
                        
                var city = deserialize( done.results[0].address_components );
                var lat = done.results[0].geometry.location.lat;
                var lng = done.results[0].geometry.location.lng;
                        
                array_.push({
                    'city' : city,
                    'cityLat' : lat,
                    'cityLng' : lng,
                    'state' : 'WA'
                });
                        
                        
                if ( formObj.number < 211 ) {
                            
                    var num = parseFloat( formObj.number ) + 1;
                            
                    $( '#number' ).val( num );
                            
                    setTimeout( function () {
                                
                        $( '#FORM' ).trigger( 'submit' );
                    }, 3000 );
                }
            } else {
                        
                console.log( 'It was not OK ' +  formObj.number );
            }
        }).fail( function ( fail ) {

            console.log( fail );
        });
        
    }
        
        
    $(document).on('click', '#thing', function(e){
        e.preventDefault();
            
        function toObject ( array ) {
            var rv = {};
                    
            for ( ; i < array.length; ++i ) {
                        
                rv[i] = array[i];
            }
            return rv;
        }
                
        var ar = toObject( array_ );
            
            
        console.log( ar );
        $.post( 'bed2.php', ar )
        .done(function(done){
            $( 'body' ).prepend(done);
            console.log(done);});
    });
    $( function () {
    
        //$( document ).on( 'click', '#geo', getGeo );
    
    
    } );
    //]]>
</script>
<?php require '_includes/endOfAll.php'; ?>
<?php
//<h1 style="text-align: center;">Click here to watch <a href="http://lucas3.srfms.com:2075/live/livestream/playlist.m3u8">Live Streaming</a></h1>
//<script type="text/javascript"> 
//var so = new SWFObject('http://www.serverroom.net/jwplayer5/player.swf','mpl',560,400,'9'); 
//so.addParam('allowscriptaccess','always'); 
//so.addParam('allowfullscreen','true'); 
//so.addParam('flashvars','&autostart=true&streamer=rtmp://lucas3.srfms.com:2075/live&stretching=uniform&skin=http://www.serverroom.net/jwplayer5/classic.zip&file=livestream.flv'); 
//so.write('player_preview'); 
//<script>
?>
  
