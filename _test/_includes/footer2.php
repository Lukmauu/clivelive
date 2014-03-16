<?php
/* http://127.0.0.1:8080/search.php?q=vader&near=kirkland&noValue=false&sortBy=rel&lat=&lng= */
/* url search sample */

function getSearchPhp(){
    $urlQuery = false;
    if(isset($_GET) && count($_GET) > 3){
        $get = sanitizePost($_GET);
        
        $_url_noValue   = (isset($get['noValue']) && ($get['noValue'] === "true" || $get['noValue'] === "false")) 
                            ? (($get['noValue'] === "true") ? true : false) : "bad";
        
        $_url_action    = isset($get['action']) ? $get['action'] : false;
        $_url_formCity  = isset($get['formCity']) ? $get['formCity'] : false;
        $_url_lat       = isset($get['lat']) ? $get['lat'] : false;
        $_url_lng       = isset($get['lng']) ? $get['lng'] : false;
        $_url_near      = isset($get['near']) ? $get['near'] : false;
        $_url_q         = isset($get['q']) ? $get['q'] : false;
        $_url_sortBy    = isset($get['sortBy']) ? $get['sortBy'] : false;
        
        if ( $_url_noValue === "bad" ) {
            echo json_encode( $urlQuery );
        } else {
            $return = array(
                        "action" => $_url_action,
                        "formCity" => $_url_formCity,
                        "lat" => floatval($_url_lat),
                        "lng" => floatval($_url_lng), 
                        "near" => $_url_near,        
                        "noValue" => $_url_noValue,
                        "q" => $_url_q, 
                        "sortBy" => $_url_sortBy );
            echo json_encode($return);
        }
    }else{
        echo json_encode($urlQuery);
    }
}


function sortCitiesAtFooter($a, $b)
{   
    
    $distA = $a["city"];
    $distB = $b["city"];
    if ($distA == $distB) {
        return 0;
    }
    
    return ($distA < $distB) ? -1 : 1;
    //return ($b < $a) ? -1 : 1;
}
function getMeCities(){
    $false = false;
    $return = array();
    $duplicate = array();
    
    $q = "SELECT city, state, cityLat, cityLng FROM myclivelive";
    
    $toFetch = mysql_query($q);
    if($toFetch){
        while($row = mysql_fetch_assoc($toFetch)){
            array_push($return, $row);
        }

        $c = 0;
        foreach ( $return as $key => $value ) {
            
            if (!empty($duplicate) && in_array_case_insensitive($value["city"], $duplicate)){
                unset($return[$key]);
                continue;
            }
            $duplicate[$c] = $value["city"];

            $c++;
        }
        
        usort($return, "sortCitiesAtFooter");
        foreach ( $value as $value ) {
            
        }

        echo json_encode($return);
    }else{
        echo json_encode($false);
    }
}
?>

</div>
<!-- END .outer-container -->

        <!-- START footer -->
        <footer>
            <!-- START .footer-wrapper -->
            <div class="footer-wrapper cl-container">
                <div class="container">
                    <div class="row padding-left15">
                        <div class="col-xs-6 col-md-3">
                            <h4>CliveLive</h4>
                            <a href="#">Get Listed</a>
                            <a href="#">Advertise</a>
                            <a href="#">About Us</a>
                            <a href="#">Contact Us</a>
                            <a href="#">Jobs</a>
                        </div>

                        <div class="col-xs-6 col-md-3">
                            <h4>Products</h4>
                            <a href="#">Mobile Apps</a>
                            <a href="#">Production Services</a>
                        </div>

                        <div class="col-xs-6 col-md-3">
                            <h4>Support Center</h4>
                            <a href="#">Safety Tips</a>
                            <a href="#">Developers</a>
                            <a href="#">Media Kit</a>
                            <a href="#">Bootleggers</a>
                        </div>
                        
                        <div class="col-xs-6 col-md-3">
                            <h4>Follow Us</h4>
                            <a href="#">Twiter</a>
                            <a href="#">Facebook</a>
                            <a href="#">Contact Us</a>
                            <a href="#">Jobs</a>
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-12 copyright">
                            <p class="copyright">&copy; 2013 Clive Corp. All rights reserved. Copyright Policy | Privacy Policy | Terms of Use</p>
                        </div>
                    </div>
                </div>
                <!-- END .container -->
            </div>
            <!-- END .footer-wrapper -->
        <div class="clearfix"></div>
        </footer>
        <!-- END footer -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $_part_path; ?>_assets/js/jquery.1.9.1.un-min.js"><\/script>')</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>

    <?php echo '<script src="' . $_part_path . '_assets/js/jquery.browser.js"></script>'; ?>
    <?php echo '<script src="' . $_part_path . '_assets/js/detectmobilebrowser.js"></script>'; ?>
    <?php echo '<script src="' . $_part_path . '_assets/js/bootstrap.js"></script>'; ?>
    <?php echo '<script src="' . $_part_path . '_assets/js/plugins.js"></script>'; ?>
    <?php echo '<script src="' . $_part_path . '_assets/js/__main.js"></script>'; ?> 

    <?php echo '<script src="' . $_part_path . '_assets/js/browserstate-history.js/scripts/bundled-uncompressed/html4+html5/jquery.history.js"></script>'; ?>
    
    <script>
        /*var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
        (function (d, t) {
            var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
            g.src = '//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g, s)
        }(document, 'script'));*/
    /*switch(error.code)  
            {  
                case error.PERMISSION_DENIED: alert(error.message + "\n user did not share geolocation data");  
                break;  
                case error.POSITION_UNAVAILABLE: alert(error.message + "\n could not detect current position");  
                break;  
                case error.TIMEOUT: alert(error.message + "\n retrieving position timedout");  
                break;  
                default: alert(error.message + "\n unknown error");  
                break;  
            } */
        /* use here pushStack
         * 
         *  http://stackoverflow.com/questions/824349/modify-the-url-without-reloading-the-page */
        
    (function ( window, undefined ) {

        // Bind to StateChange Event
        History.Adapter.bind( window, 'statechange', function () {/* Note: We are using statechange instead of popstate */
            
            //var State = History.getState();/* Note: We are using History.getState() instead of event.state */
        });
        
    })( window );
     
    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    
    $.fn.hasVal = function () {
        
        return this.length && this.val().length; 
    };
    $.fn.isVisible = function () {
        
        return this.is( ':visible' ) ? this.hide() : this; 
    };
    $.fn.notVisible = function () {
        
        return this.is( ':visible' ) ? this : this.show(); 
    };
    jQuery.extend({
        loading : function () { 
                    $loading.toggle('fast'); 
                }   
    });
    
    var r = function (m) { 
        console.log(m); 
    };
    var searchNearArray = [];
    var not_search_div = $('.not-search-div');
    var search_div = $('.search-div');
    var error_msg = $('.error-msg');
    var $loading = $('.loading');
    var find_home = $('.find-home');
    var near_home = $('.near-home');
    var streetAddress = false;
    var searchObj = false;
    var map_wrap = $('#map_wrap');
    
    
    var getCityGoogle = function ( form_, city_, state_, _flag_ ) {
        
        var send = { 
                "city" : city_,
                "fullCity" : this.city + ", " + this.stateShort,
                "stateShort" : state_
            };
        
        var flag = typeof _flag_ === 'boolean' && _flag_ === true ? true : false;
        var form = form_;
        var insideUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=+"+send.city+",+"+send.stateShort+"&sensor=false";
            //1600+Amphitheatre+Parkway,+Mountain+View,+CA
        $.ajax( insideUrl )
            .done( function ( done ) {
                if ( done.status === "OK" ) {
                    
                    if ( flag ) {
                        console.log( 'The flag was true' );
                        form.cityLat = done.results[0].geometry.location.lat;
                        form.cityLng = done.results[0].geometry.location.lng;
                        finalSendProfile( form );
                    /* HERE I WILL PUSH CITY TO THE DATABASE */
                    } else {
                        
                        form.lat = done.results[0].geometry.location.lat;
                        form.lng = done.results[0].geometry.location.lng;
                        finalSearch( form );
                    }
                    init.pushToCitiesObj( { 
                            "city" : send.city,
                            "cityLat" : flag ? parseFloat( form.cityLat ) : parseFloat( form.lat ),
                            "cityLng" : flag ? parseFloat( form.cityLng ) : parseFloat( form.lng ),
                            "state" : "WA"
                    } );
                } else {
                    var msg_INSIDE = "<h1>\n\
                        We could not find that ("+send.fullCity+"), please check \n\
                        and try again</h1><br><button class='btn btn-default close-error-msg'>CLOSE MESSAGE</button>"
                    $('.error-msg').html(msg_INSIDE).show('slow');
                    return false;
                    //throw "error message";
                }
            }).fail( function () {
                var msg_INSIDE = "<h1>\n\
                        We could not find that ("+send.fullCity+"), please check \n\
                        and try again</h1><br><button class='btn btn-default close-error-msg'>CLOSE MESSAGE</button>"
                    $('.error-msg').html(msg_INSIDE).show('slow');
                    return false;
                    //throw "error message";
            });
    };
    
    var geo = function () {
        
        var haveCoords = false;
        var coordsLat = false;
        var coordsLng = false;
        function good ( position ) {
            
            haveCoords = true;
            coordsLat = position.coords.latitude;
            coordsLng = position.coords.longitude;
        }
        function bad ( error ) {
            
            haveCoords = false;
            coordsLat = false;
            coordsLng = false;
        }
        if(navigator.geolocation || typeof haveCoords === "undefined"){
            
            navigator.geolocation.getCurrentPosition( good, bad, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        }
        return {
            result : function () {
                return haveCoords;
            },
            coords : function () {
                return {
                    lat : coordsLat,
                    lng : coordsLng
                }
            }
        };
    }();
    
    var init = function (j) {
        var searchUrl = <?php getSearchPhp(); ?>;
        
        var citiesObj = <?php getMeCities(); ?>;
            citiesObj.request = true;
        
        var cityBounds  = {
            "cityLat" : false,
            "cityLng" : false
        };
        
        var _flag_ = false;
        
        var firstState_ = false;

        var isMobile_ = {

            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return ( j.mobile || isMobile_.Android() || isMobile_.BlackBerry() || isMobile_.iOS() || isMobile_.Opera() || isMobile_.Windows() ) ? true : false;
            }
        };          
        
        var isApple_ = ( /iPhone|iPad|iPod/i.test( navigator.userAgent ) ) ? true : false;
            
        var isAndroid_ = ( /Android/i.test( navigator.userAgent ) ) ? true : false;
        
        var isWindows_ = ( /Windows Phone/i.test( navigator.userAgent ) || /IEMobile/i.test( navigator.userAgent ) ) ? true : false;

        var _mob_ = isMobile_.any();
        var _mob_sys_ = function () { 
            
            return isApple_ ? "apple" : isAndroid_ ? "android" : isWindows_ ? "windows" : false; 
        };
            
        function setCityBounds_ ( lat, lng ) {
            
            cityBounds.cityLat = lat;
            cityBounds.cityLng = lng;
        }
        
        
        return {
            
            mob : _mob_,
            
            mob_sys : _mob_sys_,
            
            isMobApple : isApple_,
            
            isMobAndroid : isAndroid_,
            
            isMobWindows : isWindows_,
            
            isMobile : isMobile_,
            
            getCityBounds : function () {
                return cityBounds;
            },
            setCityBounds : function ( lat, lng ) {
                 setCityBounds_( lat, lng );
            },
            getCitiesObj : function () {
                return citiesObj;
            },
            pushToCitiesObj : function ( source ) {
                if ( jQuery.isPlainObject( source ) ) {
                    citiesObj.push( source );
                } else { 
                    return false;
                } 
            },
            isSearchUrl : function () {
                return !searchUrl ? false : true;
            },
            getSearchUrl : function () {
                return searchUrl;
            },
            setFlag : function ( x ) {
                _flag_ = x;
            },
            isFlag : function () {
                if( _flag_ === true ) {
                    return true;
                } else {
                    return false;
                }
            },
            firstState : function () {
                return ( !firstState_ ) ? true : false;  
            },
            setFirstState : function ( state ) {
                firstState_ = state;
            }
        };
    }( jQuery.browser );
       
    function hasCity( form, city, state, _flag_ ) {
            
            var flag = typeof _flag_ === 'boolean' && _flag_ === true ? true : false;
            var obj_ = init.getCitiesObj();
            init.setFlag( false );
            $.each( obj_, function( key, value ) {
                if( value.city.toLowerCase() === city ) {
                    
                    init.setFlag( true );
                    if ( flag ) {
                        
                        form.cityLat = parseFloat( value.cityLat );
                        form.cityLng = parseFloat( value.cityLng );
                    } else {
                        
                        form.lat = parseFloat( value.cityLat );
                        form.lng = parseFloat( value.cityLng );
                    }
                }
            });
            if ( init.isFlag() ) {
                
                return form;
            } else {
                
                return false;
            }
    }
    
    function microtime (get_as_float) {
        var now = new Date().getTime() / 1000,
            s = parseInt(now, 10);

        return ( get_as_float ) ? now : ( Math.round( ( now - s ) * 1000 ) / 1000) + ' ' + s;
    }
    
    function getSearch ( e ) {
        
        if ( e.done ) {
            return;
        }
        e.preventDefault();
        
        e.done = true;
        
        map_wrap.isVisible();
        
        var $t = $(this),
            result,
            jQueryForm = $t.closest('form'),
            $form = $t.closest('form').serializeObject();
            $form.action = jQueryForm.attr('action');
            $form.q = $.trim($form.q);
            $form.near = $.trim($form.near);
            
            if( init.mob ) {
                
                $form.mob_sys = init.mob_sys();
                $form.mob = true;
            }
            
            /* FORM PROPS */
            $form.noValue = false;
            $form.sortBy = "";/* ["geo", "near", "rel"] */
            $form.lat = "";
            $form.lng = "";
            $form.formCity = $form.near.split(" ");
            $form.formCity = $form.formCity[0].toLowerCase();
            
            if ( $form.q === "" && $form.near === "" ) {
                
                $form.noValue = true;   
                if( geo.result() ){
                    $form.sortBy = "geo";
                }else{
                    $form.sortBy = "rel"
                }
                /* Q and NEAR no values */
            } else if ( $form.q !== "" && $form.near === "" ) {
                
                $form.noValue = false;
                $form.sortBy = "rel"
                /* yes Q with not NEAR *** then sortBy REL */
            } else if ( $form.q === "" && $form.near !== "" ) {
                
                $form.noValue = false;
                $form.sortBy = "near";
                /* not Q and yes NEAR */
            } else if ( $form.q !== "" && $form.near !== "" ) {
                
                $form.noValue   = false;
                /* here also we deciced to sortBy NEAR */
                $form.sortBy = "near";
                /* we got both */
            }
                /* call loading function */
                $.loading();
                
                if ( $form.sortBy === "near" ) {
                    
                    result = hasCity( $form, $form.formCity, "WA" ); 
                    //console.log(result  )
                    if( !result ) {
                        
                        /* inside google getCityGoogle insert new city on the object */
                        getCityGoogle( $form, $form.formCity, "WA" );
                    } else {
                        
                        finalSearch( result );
                    }
                } else if ($form.sortBy === "geo") {
                    
                    $form.lat = geo.coords().lat;
                    $form.lng = geo.coords().lng;
                    finalSearch( $form );
                } else if ( $form.sortBy === "rel" ) {
                    
                    $form.lat = "";
                    $form.lng = "";
                    finalSearch( $form );
                } else {
                    
                    console.log( "returning false from getSearch()" );
                    $.loading();
                    return false;
                }
    }
    
    function finalSearch ( form ) {
        
        if( find_home.is( ':visible' ) && near_home.is( ':visible' ) ) {
            if( find_home.val() === "" || near_home.val() === "" ) {
                
                find_home.val( form.q ); 
                near_home.val( form.near );
            }
        }
        
        var param_ = jQuery.param( form );
        var title_ = $('title').text();
        var baseUrl_ = ( init.firstState() ) ? window.location.href + "?" : window.location.origin + "/?";
        var state_ = History.getState();
        var baseUrlAndParam = baseUrl_ + param_;
        init.setFirstState( state_ );
        
        /* Change our States */
        History.pushState( { state_ :  baseUrl_ }, title_, baseUrlAndParam );

        History.log( state_ );
        
        $.ajax( { 
                url : form.action, 
                data : form } )
            .done( function( response ) {
            
            try {
                response = jQuery.parseJSON(response);
            } catch ( e ) {
                $('body').prepend(response);
                $.error("It was a bad response");
            }
            
            //console.log(response);
            var $result = response.result,
                loc = response.location;
                //msg_for_error_msg = "0 results came from => " + form.q + " " + form.near,
                //$noReturn = response.searchNoReturn,
                
                $('.error-msg, .search-div').hide(100);
                
                search_div.html( $result );
                //not_search_div.show( 100 );
                
                
                not_search_div.slideUp( '100', function () {
                    search_div
                        .slideDown( '100', function () {
                            /*if( form.noValue ) {
                                error_msg
                                    .html(msg_for_error_msg)
                                    .slideDown( 'fast' );
                            }*/
                            if( $loading.is( ':visible' ) ) {
                                $loading.hide();
                            }
                        });
                });
               
                    map_wrap.notVisible();
                    
                    if ( geo.result() && !init.isSearchUrl() && form.sortBy !== 'near' ) {
                        
                        initialize( geo.coords().lat, geo.coords().lng, loc );
                    } else if ( init.isSearchUrl() !== false ) {
                        
                        initialize( init.getSearchUrl().lat, init.getSearchUrl().lng, loc );
                    } else {
                        
                        initialize( form.lat, form.lng, loc );
                    }
                    
            }).fail( function () {
                alert("fail");
                var msg_INSIDE = "<h1>\n\
                        We could not find that PROBLEM, please check \n\
                        and try again</h1><br><button class='btn btn-default close-error-msg'>CLOSE MESSAGE</button>"
                    $('.error-msg').html(msg_INSIDE).show('slow');
                    throw "error message";
            });
            
    }
    
    
    $(document)
        .on( 'click', '.searchbtn-index, .mob-searchbtn-index, .searchbtn-not-index', getSearch )
        .on('click', '.rFalse', function ( e ) {
            e.preventDefault();
                e.stopPropagation();
                    return false;
        } )
        .on('click', '.mob-search-btn-index', function ( e ) {
            e.preventDefault();
                $( this ).slideToggle( 'slow' );
                    $( '.mob-search-holder-home' ).slideToggle( 'slow' );
        } )
        .on( 'click', '.error-msg', function ( e ) {
                if( $( this ).find( 'a' ).length ) {
                
                } else { 
                    e.preventDefault();
                        $( this ).slideUp( 'slow' );
                }
        } )
        .on( 'click', '.close-error-msg', function ( e ) {
            $( '.error-msg' ).slideUp( 'slow' );
        } );

/* GOOGLE MAPS */
var poly;
var geodesic;
var map;
var clickcount = 0;

function initialize( _lat_, _lng_, loc_ ) {
     
    alert( _lat_ + "  " + _lng_); 
    var loc = loc_;/* array */
    var lat_ = typeof _lat_ !== 'undefined' && typeof _lat_ !== 'boolean' ? parseFloat( _lat_ ) : false;/* double */
    var lng_ = typeof _lng_ !== 'undefined' && typeof _lng_ !== 'boolean' ? parseFloat( _lng_ ) : false;/* double */
    
    var myLatlng = new google.maps.LatLng( lat_, lng_ );
    var mapCanvas = document.getElementById( 'map_canvas' );
    var mapOptions = { 
            mapTypeId : google.maps.MapTypeId.ROADMAP
        };
        
    var centerOptions = {
          businessName : "You are here",
          lat : lat_,
          lng : lng_
        };
    
    if ( lat_ ) {
        
        mapOptions.center = myLatlng;
        loc.push( centerOptions );
    }
    
    
    var map = new google.maps.Map( mapCanvas, mapOptions );
    var infowindow = new google.maps.InfoWindow();
    var againstCount = "You are here";
    var urlPart = new google.maps.MarkerImage( 'http://www.google.com/mapfiles/marker.png' );
                    
    var marker;
    var m = [ "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", 
            "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" ];
    var latAndLngList = [];
    
    for ( var i = 0; i < loc.length ; i++ ) {
        
        var latAndLng = new google.maps.LatLng( loc[i].lat, loc[i].lng );
            
            latAndLngList.push( latAndLng );
        
        var image = new google.maps.MarkerImage( 'http://www.google.com/mapfiles/marker' + m[i] + '.png',
                    new google.maps.Size( 20, 34 ),
                    new google.maps.Point( 0, 0 ),
                    new google.maps.Point( 10, 34 ) );

            marker = new google.maps.Marker( {
                
                position : latAndLng,
                map : map,
                icon : againstCount === loc[i].businessName ? urlPart : image,
                title : loc[i].businessName,
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
    }/* end of the for loop */
    
    //  Create a new viewpoint bound
    var bounds = new google.maps.LatLngBounds();
    
    console.log( latAndLngList );
    for ( var a = 0; a < latAndLngList.length;  a++ ) {
        /* And increase the bounds to take this point */
        bounds.extend( latAndLngList[a] );
    }
    
    
    //map.setCenter( bounds.getCenter() );
    
    map.fitBounds( bounds );
    //map.fitBounds( bounds );
    //map.setCenter(myLatlng);
    
   
    var polyOptions = {
            
            strokeColor : '#FF0000',
            strokeOpacity : 1.0,
            strokeWeight : 3
    };
    var poly = new google.maps.Polyline( polyOptions );
    //poly.setMap( map );

    var geodesicOptions = {
            
            strokeColor : '#CC0099',
            strokeOpacity : 1.0,
            strokeWeight : 3,
            geodesic : true
        };
    var geodesic = new google.maps.Polyline( geodesicOptions );
    //geodesic.setMap( map );
   
    infowindow.open( map, marker ); 
}

function toggleBounce () {

    if ( marker.getAnimation() !== null ) {
        
        marker.setAnimation( null );
    } else {
        
        marker.setAnimation( google.maps.Animation.BOUNCE );
    }
}

function addLocation( event ) {
    clickcount++;
    if ( clickcount == 1 ) {
        addOrigin( event );
    }
    if ( clickcount == 2 ) {
        addDestination( event );
    }
}

function addOrigin( event ) {
    clearPaths();
    var path = poly.getPath();
    path.push( event.latLng );
    var gPath = geodesic.getPath();
    gPath.push( event.latLng );
}

function addDestination ( event ) {
    var path = poly.getPath();
    path.push( event.latLng );
    var gPath = geodesic.getPath();
    gPath.push( event.latLng );
    adjustHeading();
    clickcount = 0;
}

function clearPaths () {
    var path = poly.getPath();
    while ( path.getLength() ) {
        
        path.pop();
    }
    var gPath = geodesic.getPath();
    while ( gPath.getLength() ) {
        
        gPath.pop();
    }
}

function adjustHeading () {
    var path = poly.getPath();
    var pathSize = path.getLength();
    var heading = google.maps.geometry.spherical.computeHeading( path.getAt( 0 ), path.getAt( pathSize - 1 ) );
    document.getElementById( 'heading' ).value = heading;
    document.getElementById( 'origin' ).value = path.getAt( 0 ).lat()
        + "," + path.getAt( 0 ).lng();
    document.getElementById( 'destination' ).value = path.getAt( pathSize - 1 ).lat()
        + "," + path.getAt( pathSize - 1 ).lng();
}

$( function () {
    
    var _url_ = init.isSearchUrl();
    
    if ( _url_ !== false ) {
        
        finalSearch( init.getSearchUrl() );
    } 
} );
/*
 *
 *
;ErrorDocument 404 http://127.0.0.1:8080/Clivelive/_base/red.php?404
;ErrorDocument 500 http://127.0.0.1:8080/Clivelive/_base/red.php?500
;<IfModule mod_rewrite.c>
    ;RewriteEngine On
    ;RewriteBase /
    ;RewriteRule ^index\.html$ - [L]
    ;RewriteCond %{REQUEST_FILENAME} !-f
    ;RewriteCond %{REQUEST_FILENAME} !-d
    ;RewriteRule . /index.html [L]
;</IfModule>


 *
 **/
    
        </script>
    </body>
</html>

<?php mysql_close(); ?>
