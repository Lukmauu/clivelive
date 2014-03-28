<?php //<iframe width="420" height="315" src="http://www.youtube.com/embed/CkMrVBfgdB4" frameborder="0" allowfullscreen></iframe>  ?>
<?php
ob_start();

require '../_includes/session.php';
require '../_includes/clivelive-conn.php';
require '../_includes/func.php';
require '../_includes/authentication.php';

function test ( $_string, $_length = 3 ) {

    return isset( $_string ) && strlen( $_string ) > $_length;
}

if (isset($_GET)) {

    $get = sanitizePost($_GET);
}

$_MEMBER = false;
$_SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
$_NEDDLE = '/';
$_URL_ARRAY = explode( $_NEDDLE, $_SCRIPT_NAME );


$_SEARCH_USER_NAME = $_URL_ARRAY[2]; // ONLYTEST IT WAS NUMBER 1 AND NOW IS NUMBER 2

$_FETCHED_ROW = desanitizePost( fetchAssoc( "SELECT * FROM myclivelive WHERE user_name='{$_SEARCH_USER_NAME}'" ) );


if ( count( $_FETCHED_ROW ) > 1 ) {

    if ( isset( $_SESSION['password'] ) ) {

        if ( $autolow->main( $_SESSION['user_name'], $_SESSION['password'] ) ) {

            $_MEMBER = true;
        }
    }
} else {

    header("Location: " . $_part_path . "404.html?data=user+not+found&user=" . $_SEARCH_USER_NAME);
    exit;
}

$lat = $_FETCHED_ROW['lat'];
$lng = $_FETCHED_ROW['lng'];

$urlQ = "SELECT url FROM login WHERE user_name='{$_SEARCH_USER_NAME}'";
$resultQ = fetchAssoc($urlQ);
$_url_ = $resultQ['url'];

//www.youtube.com/watch?feature=player_detailpage&amp;v=yVdnvQsKyUs
//wrong kind of url
function makeSocialLis () {

    global $_FETCHED_ROW;
    global $_part_path;

    if ( test( $_FETCHED_ROW['social_facebook'] ) ) {

        echo '<li><a href="' . $_FETCHED_ROW['social_facebook'] . '" target="_blank" >
                <img src="' . $_part_path . '_images/facebook_32.png" /></a></li>';
    }

    if ( test( $_FETCHED_ROW['social_twitter'] ) ) {

        echo '<li><a href="' . $_FETCHED_ROW['social_twitter'] . '" target="_blank">
                <img src="' . $_part_path . '_images/twitter_32.png"></a></li>';
    }

    if ( test( $_FETCHED_ROW['social_googleplus'] ) ) {

        echo '<li><a href="' . $_FETCHED_ROW['social_googleplus'] . '" target="_blank">
                <img src="' . $_part_path . '_images/google_plus_32.png" /></a></li>';
    }
}

require $_part_path . '_includes/header-not-index.php';
?>
<!-- START .center --><div class="center cl-container">


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
         display: none;
         border">
        <h4>Searching...</h4>
        <img style="display: inline; " src="<?php echo $_part_path; ?>_images/ajax-loader.gif" />
    </div>



    <div class="row not-search-div">
        <div class="padding-top15 col-xs-12 col-md-8 col-lg-8">

        
  <?php if ( $_FETCHED_ROW['user_name'] === 'darkvader' ) { ?>          
    <script type='text/javascript' src='http://www.serverroom.net/jwplayer6/jwplayer.js'></script>
    <div id='player_preview' 
                class="min-height-box img-responsive"
                
                style="width: 100%; height: auto; float: left;"> 
      <video 
            id='video_tag'
            style="position: relative; width: 100%; height:100%;"
            controls autoplay> 
        <source src='http://clivelive1.srfms.com:2173/rtplive/camera.stream/playlist.m3u8' type='video/mp4'> 
        Your browser does not support the video tag. This text will be replaced. Streaming solutions by <a href='http://www.serverroom.net'>Server Room - Shoutcast hosting, Flash Streaming</a> 
      </video> 
      <a href='rtsp://clivelive1.srfms.com:2173/rtplive/camera.stream' id='android_firefox' style='display:none;'> watch this stream over RTSP for Android Mozilla</a>
    </div> 
    <script type='text/javascript'> 
      if (navigator.userAgent.match(/Android/i) && navigator.userAgent.match(/Firefox/i) ) 
       { document.getElementById('android_firefox').style.display = ''; 
         document.getElementById('video_tag').style.display = 'none'; 
       } 
      jwplayer('player_preview').setup({ 
       playlist: [{ 
        sources: [ 
         {file:'rtmp://clivelive1.srfms.com:2173/rtplive/camera.stream',}, 
         {file:'http://clivelive1.srfms.com:2173/rtplive/camera.stream/playlist.m3u8'} 
        ] 
       }], 
       height: 'auto', 
       width: '100%', 
       primary: 'flash', 
       fallback: false, 
       repeat: true, 
       autostart: true, 
       stretching: 'uniform' 
      }); 
    </script> 
  <?php } else { ?>
        
        <img 
                style="width: 100%; height: auto; float: left;" 
                id="coverimageforplayer" 
                class="min-height-box-wrapper video-base thumbnail img-responsive" 
                src="../_images/temp_videospace.jpg">


    
    
            <iframe 
                class="min-height-box img-responsive"
                id="youtubeplayer" 
                style="width: 100%; height: auto; display: none; float: left;"  
                width="420" 
                src="" frameborder="0" allowfullscreen></iframe>

<?php } ?>

            <div class="clearfix"></div>    

            <ul class="list-inline padding-top15">

                <?php
                
                    makeSocialLis();
                    // CALL THE SOCIAL LINKS;
                ?>

            </ul>

            <hr>
            <!-- line -->

            <ul class="video-bottom-opitions list-inline list-group">
                <?php if (isset($_FETCHED_ROW['business_phone'])) { ?>
                    <li><span class="glyphicon glyphicon-bookmark"></span>&nbsp;<a>
                            <?php echo (strlen($_FETCHED_ROW['business_phone']) > 1) ? $_FETCHED_ROW['business_phone'] : ''; ?>
                        </a></li>
                <?php } ?>
                <?php if (isset($_FETCHED_ROW['business_address']) && strlen($_FETCHED_ROW['business_address']) > 1) { ?>
                    <li><span class="glyphicon glyphicon-hand-right"></span>&nbsp;<a href="//www.google.com/maps?q=<?php echo urlencode($_FETCHED_ROW['business_address']); ?>" target="_blank">Directions</a></li>
                <?php } ?>
                <?php if ($_SERVER['DOCUMENT_ROOT'] == 'ERROR') { ?>
                    <li><span class="glyphicon glyphicon-log-in"></span>&nbsp;<a>Website</a></li>
                <?php } ?>
                <?php if ($_MEMBER) { ?>
                    <li><span class="glyphicon glyphicon-eye-open"></span>&nbsp;<a href="../profile.php">My Clivelive</a></li>
                <?php } ?>
            </ul>
            <div class="container">
                <div class="row">
                    <div class="col-xs-9">
                        <div>
                            <h1><?php echo $_FETCHED_ROW['business_name']; ?></h1>
                            <p><?php echo $_FETCHED_ROW['business_address']; ?></p>
                            <p><?php echo $_FETCHED_ROW['business_phone']; ?></p>
                        </div>
                        <p id="target">
                            <?php if (strlen($_FETCHED_ROW['cuisine']) > 1) { ?>    
                                Cuisine: <?php echo $_FETCHED_ROW['cuisine']; ?>  
                            <?php } ?>
                            <?php if (strlen($_FETCHED_ROW['atmosphere']) > 1) { ?>  
                                / Atmosphere: <?php echo $_FETCHED_ROW['atmosphere']; ?>   
                            <?php } ?>
                            <?php if (strlen($_FETCHED_ROW['parking']) > 1) { ?>
                                / Parking: <?php echo $_FETCHED_ROW['parking']; ?> 
                            <?php } ?>
                            <?php if (strlen($_FETCHED_ROW['serving']) > 1) { ?>
                                / Serving: <?php echo $_FETCHED_ROW['serving']; ?> 
                            <?php } ?>
                            <?php if (strlen($_FETCHED_ROW['h_h_average_price']) > 1) { ?>
                                / Happy Hour Average Price: <?php echo $_FETCHED_ROW['h_h_average_price']; ?> 
                            <?php } ?>
                            <?php if (strlen($_FETCHED_ROW['reservation']) > 1) { ?>
                                / Reservations: <?php echo $_FETCHED_ROW['reservation']; ?> 
                            <?php } ?>
                            <?php if (strlen($_FETCHED_ROW['neighborhood']) > 1) { ?>    
                                <?php // Area served: Seattle, WA 98104 ?>
                                / Neighborhood: <?php echo $_FETCHED_ROW['neighborhood']; ?>
                            <?php } ?>
                        </p>
                    </div>


                    <div class="small-photos col-xs-3" style="text-align: center;">
                        <h4>Photo Gallery</h4>
                        <div class="row">
                            <a href="#" data-hijack="false" style="display: inline-block; margin: 0 auto;">
                                <img src="../_images/thumb_01.png" class="thumbnail img-responsive" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <section class="container">
                <div class="row">
                    <?php if ( test( $_FETCHED_ROW['b_b_text'], 4 ) ) { ?>
                        <h4>The Bill-Board<span class="reduce-size">&#8482;</span></h4>

                        <article class="well happy-hour">
                            <!--<h2><?php //echo $_FETCHED_ROW['b_b_title'];  ?></h2>-->
                            <blockquote><p class="text-info lead">
                                    <?php echo $_FETCHED_ROW['b_b_text']; ?>
                                </p></blockquote>
                        </article>
                    <?php } ?>
                </div>


            </section>

        </div>
        <div class="col-xs-12 col-md-4" id="map_wrap_base">
            
            <div id="map_canvas_base" ></div>
        
        </div>




    </div>
    <!-- END .row -->
</div><!-- END .center -->
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

          <!--<script type="text/javascript" src="http://www.serverroom.net/jwplayer/swfobject.js"></script>
  <div id="player_preview" style="float:left;">This text will be replaced. Streaming solutions by <a href="http://www.serverroom.net">Server Room - Shoutcast hosting, Flash Streaming</a></div>
  <script type="text/javascript"> 
    var so = new SWFObject('http://www.serverroom.net/jwplayer5/player.swf','mpl',560,400,'9'); 
    so.addParam('allowscriptaccess','always'); 
    so.addParam('allowfullscreen','true'); 
    so.addParam('flashvars','&autostart=true&streamer=rtmp://clivelive.srfms.com:2123/live/&stretching=uniform&skin=http://www.serverroom.net/jwplayer5/classic.zip&file=livestream.flv'); 
    so.write('player_preview'); 
  </script>-->




<?php require '../_includes/footer.php'; ?>

<script type="text/javascript">
    
    var businessName = '<?php echo $_FETCHED_ROW['business_name']; ?>';
    var businessAddress = '<?php echo $_FETCHED_ROW['business_address']; ?>';
    
    var base = function () {
        
        var url = <?php echo json_encode($_url_); ?>;
            
        if ( url ) {
                
            $( '#youtubeplayer' ).attr( { 'src' : url } );
        }
            
        return { 'videoUrl' : url };    
    }();
    
    var viewportmeta = document.querySelector( 'meta[name="viewport"]' );
    if ( viewportmeta ) {
        
        viewportmeta.content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0';
        
        if ( navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) ) {
            
            document.body.addEventListener( 'gesturestart', function () {
            
                viewportmeta.content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
            }, false );
        }    
    }
    
    $( document )
        .on( 'click', '#coverimageforplayer', function () {

            if ( base.videoUrl ) {

                $( '#coverimageforplayer' ).replaceWith( $( '#youtubeplayer' ).show() );
            } else {

                return false;
            }
        } );
    
    
    
    function initFromBase () {
     
        var lat_ = parseFloat( <?php echo json_encode($lat); ?> );
        var lng_ = parseFloat( <?php echo json_encode($lng); ?> );
    
        var myLatlng = new google.maps.LatLng( lat_, lng_ );
    
        var mapCanvas = document.getElementById( 'map_canvas_base' );
    
        var mapOptions = { 
            mapTypeId : google.maps.MapTypeId.ROADMAP,
            center : myLatlng,
            zoom : 15
        };
    
        var map = new google.maps.Map( mapCanvas, mapOptions );
    
        var infowindow = new google.maps.InfoWindow();
    
        var urlPart = new google.maps.MarkerImage( 'http://www.google.com/mapfiles/marker.png' );
                    
        var marker = new google.maps.Marker( {
            position : myLatlng,
            map : map,
            icon : urlPart,
            title : returnCheck( businessName ) + '\n' + returnCheck( businessAddress ),
            animation : google.maps.Animation.DROP
        } );
            
        google.maps.event.addListener( marker, 'click', ( function( marker ) {
            return function () {
                if ( marker.getAnimation() !== null ) {
                        
                    marker.setAnimation( null );
                } else {
                        
                    marker.setAnimation( google.maps.Animation.BOUNCE );
                }
            }
        })( marker ));
        var polyOptions = {
            
            strokeColor : '#FF0000',
            strokeOpacity : 1.0,
            strokeWeight : 3
        };
        var poly = new google.maps.Polyline( polyOptions );
        poly.setMap( map );

        var geodesicOptions = {
            
            strokeColor : '#CC0099',
            strokeOpacity : 1.0,
            strokeWeight : 3,
            geodesic : true
        };
        var geodesic = new google.maps.Polyline( geodesicOptions );
        geodesic.setMap( map );
   
        var homeControlDiv = document.createElement( 'div' );
        var center_ = map.getCenter();
        var zoom_ = map.zoom;
        var homeControl = new HomeControl( homeControlDiv, map, center_, zoom_ );

        homeControlDiv.index = 1;
        map.controls[ google.maps.ControlPosition.TOP_RIGHT ].push( homeControlDiv );
        
    
    
        infowindow.open( map, marker );
    }

    function toggleBounce () {

        if ( marker.getAnimation() !== null ) {
        
            marker.setAnimation( null );
        } else {
        
            marker.setAnimation( google.maps.Animation.BOUNCE );
        }
    }

    function initFromGet () {
    
        var lat_ = parseFloat( <?php echo json_encode($lat); ?> );
        var lng_ = parseFloat( <?php echo json_encode($lng); ?> );
        var latGet = parseFloat( <?php echo ( isset($get['lat']) ) ? json_encode($get['lat']) : false; ?> );
        var lngGet = parseFloat( <?php echo ( isset($get['lng']) ) ? json_encode($get['lng']) : false; ?> );
    
        var Center = new google.maps.LatLng( lat_, lng_ );
    
        var directionsService = new google.maps.DirectionsService();
        var map = new google.maps.Map( document.getElementById( 'map_canvas_base' ), properties );
        var directionsDisplay = new google.maps.DirectionsRenderer();
    
        var properties = {
            center:Center,
            zoom:20,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };


        directionsDisplay.setMap(map);

        var marker = new google.maps.Marker({
            position:Center,
            animation:google.maps.Animation.BOUNCE
        });

//        var homeControlDiv = document.createElement( 'div' );
//        var center_ = map.getCenter();
//        var zoom_ = map.zoom;
//        var homeControl = new HomeControl( homeControlDiv, map, center_, zoom_ );
//
//        homeControlDiv.index = 1;
//        map.controls[ google.maps.ControlPosition.TOP_RIGHT ].push( homeControlDiv );
//            
        //marker.setMap(map);
        Route( lat_, lng_, latGet, lngGet, directionsService, directionsDisplay );

    }

    function Route ( latTo, lngTo, latFrom, lngFrom, directionsService, directionsDisplay ) {

        var start = new google.maps.LatLng( latFrom, lngFrom );
        var end = new google.maps.LatLng( latTo, lngTo );

        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        };
        
        directionsService.route( request, function( result, status ) {
        
            if ( status === google.maps.DirectionsStatus.OK ) {
                
                result.routes[0].legs[0].start_address = 'You are here ' + '\n' + '<br>'  +  result.routes[0].legs[0].start_address;
                result.routes[0].legs[0].end_address = businessName + '\n' + '<br>' + result.routes[0].legs[0].end_address; 
                
                directionsDisplay.setDirections( result );
            }
        });
    } 


<?php if ( isset( $get['lat'] ) && test( $get['lat'], 5 ) ) { ?>

        google.maps.event.addDomListener( window, 'load', initFromGet );
<?php } else { ?>
    
        google.maps.event.addDomListener( window, 'load', initFromBase );
<?php } ?>    

//    $(function(){
//            $("#videoHolder").html(
//                            '<video width="640" height="264" autoplay>' +
//                                '<source src="//clivelive.srfms.com/live/livestream.flv" type="video/mp4"></source>' +
//                            '</video>');
//
//    });
//     '<source src="http://video-js.zencoder.com/oceans-clip.webm" type="video/webm"></source>' +
//        '<source src="http://video-js.zencoder.com/oceans-clip.ogv" type="video/ogg"></source>' +
     
//    <video width="320" height="240" controls="controls">
//  <source src="http://www.myweb.com/video.flv" type="video/mp4" />
//  <source src="http://www.myweb.com/video.mp4" type="video/mp4" />
//  <source src="http://www.myweb.com/video.avi" type="video/mp4" />
//  Your browser does not support the video tag.
//</video>
//
//      
//      upon back arrow from bar page it takes to 
//previous search page but if it clear search
//field an input new entry the search is 
//looping infinitely. in url there is undefined

      
</script>
<?php require '../_includes/endOfAll.php'; ?>


