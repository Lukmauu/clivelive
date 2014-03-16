<?php

// http://127.0.0.1:8080/search.php?q=vader&near=kirkland&noValue=false&sortBy=rel&lat=&lng= 
// url search sample 

function getSearchPhp() {

    $urlQuery = false;

    if (isset($_GET) && count($_GET) > 3) {

        $get = sanitizePost($_GET);

        $_url_noValue = ( isset($get['noValue']) &&
                ( $get['noValue'] === "true" || $get['noValue'] === "false" ) ) ? ( ( $get['noValue'] === "true" ) ? true : false ) : "bad";

        $_url_action = isset($get['action']) ? $get['action'] : false;
        $_url_formCity = isset($get['formCity']) ? $get['formCity'] : false;
        $_url_lat = isset($get['lat']) ? $get['lat'] : false;
        $_url_lng = isset($get['lng']) ? $get['lng'] : false;
        $_url_near = isset($get['near']) ? $get['near'] : false;
        $_url_q = isset($get['q']) ? $get['q'] : false;
        $_url_sortBy = isset($get['sortBy']) ? $get['sortBy'] : false;
        $_track = isset($get['track']) && $get['track'] === 'true' ? true : false;

        if ($_url_noValue === "bad") {
            echo json_encode($urlQuery);
        } else {
            $return = array(
                "q" => $_url_q,
                "near" => $_url_near,
                "action" => $_url_action,
                "noValue" => $_url_noValue,
                "sortBy" => $_url_sortBy,
                "lat" => floatval($_url_lat),
                "lng" => floatval($_url_lng),
                "formCity" => $_url_formCity,
                "track" => $_track);
            echo json_encode($return);
        }
    } else {
        echo json_encode($urlQuery);
    }
}

function sortCitiesAtFooter($a, $b) {

    $distA = $a["city"];
    $distB = $b["city"];
    if ($distA == $distB) {
        return 0;
    }

    return ($distA < $distB) ? -1 : 1;
    //return ($b < $a) ? -1 : 1;
}

function getMeCities() {

    $false = false;
    $return = array();
    $duplicate = array();

    $q = "SELECT city, cityLat, cityLng, state FROM citylatlng";
    //$q = "SELECT city, cityLat, cityLng, state FROM myclivelive";

    $toFetch = mysql_query($q);
    //echoPre(mysql_error());



    if ($toFetch) {

        while ($row = mysql_fetch_assoc($toFetch)) {

            array_push($return, $row);
        }

        $c = 0;
        foreach ($return as $key => $value) {


            if (!empty($duplicate) && in_array_case_insensitive($value["city"], $duplicate)) {

                unset($return[$key]);
                continue;
            }
            $duplicate[$c] = $value['city'];

            $c++;
        }
        //usort($return, "sortCitiesAtFooter");


        echo json_encode($return);
    } else {


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

<?php //echo '<script src="' . $_part_path . '_assets/js/bootstrap.js"></script>';  ?>
<?php //echo '<script src="' . $_part_path . '_assets/js/plugins.js"></script>';  ?>
<?php //echo '<script src="' . $_part_path . '_assets/js/__main.js"></script>'; ?> 
    <!--<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>-->


<!-- <![CDATA[ -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo $_part_path; ?>_assets/js/jquery.1.9.1.un-min.js"><\/script>')</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<?php echo '<script src="' . $_part_path . '_assets/js/jquery.browser.js"></script>'; ?>
<?php echo '<script src="' . $_part_path . '_assets/js/detectmobilebrowser.js"></script>'; ?>
<?php echo '<script src="' . $_part_path . '_assets/js/browserstate-history.js/scripts/bundled-uncompressed/html4+html5/jquery.history.js"></script>'; ?>
<!-- ]]> -->    

<script>
    //<![CDATA[


    //var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
    //(function (d, t) {
    //    var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
    //    g.src = '//www.google-analytics.com/ga.js';
    //    s.parentNode.insertBefore(g, s)
    //}(document, 'script'));
    //switch(error.code)  
    //    {  
    //        case error.PERMISSION_DENIED: alert(error.message + "\n user did not share geolocation data");  
    //        break;  
    //        case error.POSITION_UNAVAILABLE: alert(error.message + "\n could not detect current position");  
    //        break;  
    //        case error.TIMEOUT: alert(error.message + "\n retrieving position timedout");  
    //        break;  
    //        default: alert(error.message + "\n unknown error");  
    //        break;  
    //    }


    //    use here pushStack
    //    http://stackoverflow.com/questions/824349/modify-the-url-without-reloading-the-page

    // MAIN VARS
    var searchNearArray = [];
    var not_search_div = $( '.not-search-div' );
    var search_div = $( '.search-div' );
    var error_msg = $( '.error-msg' );
    var loading = $( '.loading' );
    var find_home = $( '.find-home' );
    var near_home = $( '.near-home' );
    var streetAddress = false;
    var searchObj = false;
    var map_wrap = $( '#map_wrap' );
    var hideSearchBtn = $( '.hideSearchButton' );
    var checkbox = $( '#checkbox' );
    var checkbox_label = $( '.checkbox-label' );
    var error_msg_no_result = $( '.error-msg-no-result' );
    var msg_no_geo = $( '.msg-no-geo' );
    var searchButtom = $( '.searchbtn-index, .mob-searchbtn-index, .searchbtn-not-index' );
    var searchButtonArray = [ '.searchbtn-index', '.mob-searchbtn-index', '.searchbtn-not-index' ];
    var starting = $( '#starting' );
    // MAIN VARS
    
    
    
    ( function ( window, undefined ) {

        History.Adapter.bind( window, 'statechange', function () {
            
        } );

    })( window );
    
    function deserializeGoogleFullAddress ( array_ ) {
        
        var city = '';
        var shortState = '';
        var longState = '';
        var neighborhood = '';
        var postalCode = '';
        var has = [ 'locality', 'administrative_area_level_1', 'postal_code', 'neighborhood' ];
        
        for ( var i = 0; i < array_.length; i++  ) {
            
            if ( $.inArray( has[0], array_[i].types ) === 0 ) {
                
                city = array_[i].long_name;
            }
            if ( $.inArray( has[1], array_[i].types ) === 0 ) {
            
                shortState = array_[i].short_name;
                longState = array_[i].long_name;
            }
            if ( $.inArray( has[2], array_[i].types ) === 0 ) {
                
                postalCode = array_[i].long_name;
            }
            if ( $.inArray( has[3], array_[i].types ) === 0 ) {
                
                neighborhood = array_[i].long_name;
            }
        }
        
        return {
            'city' : city,
            'shortState' : shortState,
            'longState' : longState,
            'postalCode' : postalCode,
            'neighborhood' : neighborhood 
        };
    }
    
    function doScrollTop ( time ) {
        
        time = time || 300;
        
        $( 'html' ).animate( { 
                
            scrollTop : 0 
        }, 300 );
        if ( document.body.scrollTop ) {
            
            $( 'body' ).animate( { 
                
                scrollTop : 0 
            }, 300 );
        }
    }
    
    function withoutProtocol ( value ) {
    
        return /(((ftp|http|https):\/\/)|(\/\/))?(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test( value );
    }

    function withProtocol ( value ) {

        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test( value );
        }

        function checkUrl ( url_ ) {

            if( url_.indexOf( 'http:' ) >= 0 || url_.indexOf( 'https:' ) >= 0 || url_.indexOf( 'http' ) >= 0 || url_.indexOf( 'https' ) >= 0 ) {

                if( withProtocol( url_ ) ) { 

                    return url_.replace( /.*?:\/\//g, '//' );
                } else { 

                    return false;
                }
            } else if ( url_.indexOf( '//' ) === 0 && url_.indexOf( '://' ) === -1 ) {

                if( withoutProtocol( url_ ) ) { 

                    return url_;
                } else { 

                    return false;
                }
            } else {

                return false;
            }
        }
    
    
        $.fn.visible = function () {
        
            return this.is( ':visible' ); 
        };
    
        // afterShow only works if there is a callback if not use beforeShow
        var _oldShow = jQuery.fn.show;
        $.fn.show = function ( speed, oldCallback ) {
        
            return this.each( function () {
            
                var obj = $( this ),
                newCallback = function () {
                    
                    if ( $.isFunction( oldCallback ) ) {
                        
                        oldCallback.apply( obj );
                    }
                    obj.trigger( 'afterShow' );
                };
                
                obj.trigger( 'beforeShow' );
            
                if ( !obj.visible() ) {
                
                    _oldShow.apply( obj, [speed, newCallback] );
                }
            });
        };
    
        // afterHide only works if there is a callback if not use beforeHide
        var _oldHide = jQuery.fn.hide;
        $.fn.hide = function ( speed, oldCallback ) {
        
            return this.each( function () {
            
                var obj = $( this ),
                newCallback = function () {
                    
                    if ( $.isFunction( oldCallback ) ) {
                        
                        oldCallback.apply( obj );
                    }
                    obj.trigger( 'afterHide' );
                };
                
                obj.trigger( 'beforeHide' );
            
                if ( obj.visible() ) {
                
                    _oldHide.apply( obj, [speed, newCallback] );
                }
            });
        };
     
        $.fn.serializeObject = function () {
        
            var o = {};
            var a = this.serializeArray();
            $.each( a, function () {
            
                if ( o[this.name] !== undefined ) {
                
                    if ( !o[this.name].push ) {
                    
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push( this.value || '' );
                } else {
                
                    o[this.name] = this.value || '';
                }
            });
        
            return o;
        };
    
        $.fn.hasVal = function () {
        
            return this.length && this.val().length; 
        };
    
        $.fn.isVisible = function ( _callback_ ) {
        
            return this.visible() ? this.hide( _callback_ ) : this; 
        };
    
        $.fn.notVisible = function ( _callback_ ) {
        
            return this.visible() ? this : this.show( _callback_ ); 
        };
        
        $.fn.Cshow = function ( _callback_ ) {
            
            this.css( { 'display' : 'block' } );

            if ( $.isFunction( _callback_ ) ) {

                _callback_.call( this );
            }
        };
        
        $.fn.Chide = function ( _callback_ ) {
            
            this.css( { 'display' : 'none' } );

            if ( $.isFunction( _callback_ ) ) {

                _callback_.call( this );
            }
        };
    
        jQuery.extend( {
        
            loading : function () { 
                    
                loading.toggle( 'fast' ); 
            },
            flip : function ( _mode_, _callback_ ) {
            
                var normalModeHide = normalModeHide || $( '.error-msg-no-result, .search-div, .loading' );
                var normalModeShow = normalModeShow || $( '.not-search-div' );
                var searchModeHide = searchModeHide || $( '.error-msg-no-result, .not-search-div' );
                var searchModeShow = searchModeShow || $( '.loading, search-div' );

                if ( _mode_ === 'search' ) {
                
                    searchModeHide.hide()
                    searchModeShow.show();
                } else if ( _mode_ === 'normal' ) {
                
                    init.setKillSearch( false );
                    normalModeHide.hide();
                    normalModeShow.show();
                } else {
                
                    return false;
                }

                if ( $.isFunction( _callback_ ) ) {
                    
                    _callback_.call( this );
                }
            }
        } ); 
    
        function getVisibleButtonNow () {
        
            var result = false;
        
            $.each( searchButtonArray, function ( key_, value_ ) {
        
        
                if ( $( value_ ).visible() ) {
                
                    result = value_;
                }
            } );
        
            return !result ? false : result;
        }
    
        function showAllSearchButton () {
        
            $.each( searchButtonArray, function ( key_, value_ ) {
        
        
                $( value_ ).show();
            } );
        }
    
        function hideAllSearchButton () {
        
            $.each( searchButtonArray, function ( key_, value_ ) {
        
        
                $( value_ ).hide();
            } );
        }
    
        ( function () {
        
            return window.c = function () {
            
                this.c.history = this.c.history || []; 
                this.c.history.push( arguments );
            
                if ( this.console ) { 
                
                    console.log( Array.prototype.slice.call( arguments ) );
                }
            }
        } )( window );
    
        //deserializeGoogleFullAddress
        var getCityGoogle = function ( form_, city_, state_, _flag_ ) {
        
            var send = { 
            
                "city" : city_,
                "fullCity" : city_ + ", " + state_,
                "stateShort" : state_
            };
        
            var msg_in_get_city_google = msg_in_get_city_google || "We could not find that place (" + send.city + "), please check \n\
                        and try again<br><button class='btn btn-default close-error-msg'>CLOSE MESSAGE</button>"
        
        
            var flag = typeof _flag_ === 'boolean' && _flag_ === true ? true : false;
            var form = form_;
            var insideUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=+"+send.city+",+"+send.stateShort+"&sensor=false";
            //1600+Amphitheatre+Parkway,+Mountain+View,+CA
            $.ajax( insideUrl )
            .done( function ( done, textStatus, jqXHR ) {
                
                if ( done.status === "OK" ) {
                    
                    console.log( done.results[0].address_components );
                    console.log( typeof done.results[0].address_components );
                    
                    var objFromGoogle = deserializeGoogleFullAddress( done.results[0] );
                    var in_state = objFromGoogle.shortState.toUpperCase();
                    var in_city = objFromGoogle.city;
                    
                    console.log( objFromGoogle );
                    
                    console.log( init );
                    
                    if ( in_state === 'WA' ) {
                        
                        if ( flag ) {
                   
                            form.cityLat = done.results[0].geometry.location.lat;
                            form.cityLng = done.results[0].geometry.location.lng;
                            finalSendProfile( form );
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
                        
                        init.killSearch( 'We did not find ' + send.city + ' in Washington state, Please try again' );
                        $( init.getMainBtn() ).show();
                        return false;
                    } 
                } else {
                    
                    init.killSearch( msg_in_get_city_google );
                    $( init.getMainBtn() ).show();
                    return false;
                }
            }).fail( function ( jqXHR, textStatus, errorThrown ) {
                
                if ( textStatus ) {
                    
                    if ( textStatus === 'error' ) {
                        
                        init.killSearch( msg_in_get_city_google );
                        $( init.getMainBtn() ).show();
                    } 
                }
                return false;
            });
        };
    
        var r = function ( m ) {
    
            return console.log( m );
        };
    
        jQuery.extend({
        
            isBoolean : function ( _boolean_ ) {
            
                return typeof _boolean_ === 'boolean' ? true : false;
            },
        
            buildSearchHtml : function () {
            
                return true;
            }
        });
    
        // initfunc
        var init = function ( j ) {
        
            var searchUrl = <?php getSearchPhp(); ?>;
        
            var _search_url_flag_ = searchUrl ? true : false;
        
            var citiesObj = <?php getMeCities(); ?>;
        
            var pathPart = '<?php echo $_part_path; ?>';
        
            var mainButton = false;
        
            var cityBounds = {
                "cityLat" : false,
                "cityLng" : false
            },
        
            _flag_ = false,
        
            firstState_ = false,
        
            _KILL_SEARCH_ = false,
        
            isMobile_ = {

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
                
                    return navigator.userAgent.match( /IEMobile/i ) || navigator.userAgent.match( /Windows Phone/i );
                },
                //navigator.userAgent.match( /Windows NT/i )
                any: function() {
                    return ( j.mobile || isMobile_.Android() || isMobile_.BlackBerry() || isMobile_.iOS() || isMobile_.Opera() || isMobile_.Windows() ) ? true : false;
                }
            };          
        
            var isApple_ = /iPhone|iPad|iPod/i.test( navigator.userAgent ) || false;
            
            var isAndroid_ = /Android/i.test( navigator.userAgent ) || false;
        
            //Windows NT/i.test( navigator.userAgent ) || 
            var isWindows_ = ( /Windows Phone/i.test( navigator.userAgent ) || /IEMobile/i.test( navigator.userAgent ) ) || false;

            var _mob_sys_ = function () { 
            
                return isApple_ ? "apple" : isAndroid_ ? "android" : isWindows_ ? "windows" : false; 
            }();
        
                
            // SEARCH FLAGS
            var _SEARCH_FLAG_ = false;
            var loc_ = false;
            // SEARCH FLAGS     
        
        
            function setCityBounds_ ( lat, lng ) {
            
                cityBounds.cityLat = lat;
                cityBounds.cityLng = lng;
            }
        
            function objLength ( obj ) {
            
                if ( !jQuery.isPlainObject( obj )  && typeof obj === 'boolean' ) { 
                
                    return false;
                }
            
                if ( !Object.keys ) {
                
                    var keys = [],
                    k;
                
                    for ( k in obj ) {
                    
                        if ( Object.prototype.hasOwnProperty.call( obj, k ) ) {
                      
                            keys.push( k );
                        }
                    }
                    return keys.length;
                } else {
              
                    return Object.keys( obj ).length;
                }
            }
        
            return {
            
                pathPart : pathPart,
            
                getSearchFlag : function () {
                
                    return _SEARCH_FLAG_ || false;
                },
                setSearchFlag : function ( _setter_ ) {
                
                    _SEARCH_FLAG_ = typeof _setter_ === 'bollean' ? _setter_ : _SEARCH_FLAG_
                },
            
                killSearch : function ( _msg_ ) {
    
                    _KILL_SEARCH_ = false;
                    error_msg_no_result
                    .text( 'Please revise your search entry,' + '\n' 
                        + 'it timeout, we are sorry' )
                    
                    $.flip( 'normal' );
                    if ( _msg_ ) {
                            
                        if ( $.isBoolean( _msg_ ) ) {
                                
                            error_msg_no_result.show();
                        } else {
                                
                            error_msg_no_result.html( _msg_ ).slideDown( 'slow' );
                        }
                            
                    }

                },
            
                setKillSearch : function ( _setter_ ) {
             
                    _KILL_SEARCH_ = typeof _setter_ === 'boolean' ? _setter_ : _KILL_SEARCH_;
                },
            
                getKillSearch : function () {
                
                    return _KILL_SEARCH_;
                },
            
                mob : isMobile_.any(),
            
                mob_sys : _mob_sys_,
            
                // functions
            
                isMobApple : isApple_,
            
                isMobAndroid : isAndroid_,
            
                isMobWindows : isWindows_,
            
                // main function
                isMobile : isMobile_,
                // main function
             
                // functions
            
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
                
                    var index = objLength( citiesObj );
                    index++;
                
                    if ( jQuery.isPlainObject( source ) ) {
                    
                        citiesObj[index] = source;
                    } else { 
                    
                        return false;
                    } 
                },
            
                isSearchUrl : function () {
                
                    return _search_url_flag_;
                },
            
                getSearchUrl : function () {
                
                    return searchUrl;
                },
            
                setFlag : function ( setter ) {
                
                    _flag_ = setter;
                },
            
                isFlag : function () {
                
                    return _flag_ || false;
                },
            
                firstState : function () {
                
                    return ( !firstState_ ) ? true : false;  
                },
            
                setFirstState : function ( state ) {
                
                    firstState_ = firstState_ || state;
                },
            
                getLocation : function () {
                
                    return loc_;
                },
            
                setLocation : function ( _setter_ ) {
                
                    if ( $.isBoolean( _setter_ ) ) {
                    
                        if ( _setter_ ) {
                        
                            checkbox.attr( 'checked', 'checked' );
                            loc_ = _setter_;
                        } else {
                        
                            checkbox.removeAttr( 'checked' );
                            loc_ = _setter_;
                        }
                    } else {
                    
                        return false;
                    }
                },
            
                getMainBtn : function () {
                
                    return mainButton;
                },
            
                setMainBtn : function ( _setter_ ) {
                
                    mainButton = typeof _setter_ === 'string' ? _setter_ : false;
                } 
            };
        }( jQuery.browser );
    
    
        function hideButton () {
    
            $( init.getMainBtn() ).hide();
        }
    
        // no geo message
        msg_no_geo
        .text( 'Your geolocation is diseabled, please input city for better search results.' );
    
        var geo_ = function () {
        
            var haveCoords = false;
            var coordsLat = false;
            var coordsLng = false;
            function good ( position ) {
            
                haveCoords = true;
                init.setLocation( true );
                //checkbox.attr( 'checked', 'checked' );
                coordsLat = position.coords.latitude;
                coordsLng = position.coords.longitude;
                //checkbox_label.css( { 'display' : 'block' } );
                msg_no_geo.css( { 'display' : 'none' } );
            }
            function bad ( error ) {
            
                init.setLocation( false );
                //checkbox.removeAttr( 'checked' );
                haveCoords = false;
                coordsLat = false;
                coordsLng = false;
                checkbox_label.css( { 'display' : 'none' } );
                msg_no_geo.css( { 'display' : 'block' } );
            }
            if(navigator.geolocation || typeof haveCoords === "undefined"){
                //alert('here 1')
                navigator.geolocation.getCurrentPosition( good, bad, {
                    enableHighAccuracy: true,
                    timeout: 1000,
                    maximumAge: 600000
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
        
        };
    
        var geo = new geo_();
    
        jQuery.extend( {
    
            searchTimeout : function () {
    
                // passing true to show the Timeout message;
                init.killSearch( true );
                $( init.getMainBtn() ).show();
            },
        
            userAutoGeo : function () {
            
                return geo.result() && init.getLocation() ? true : false;
            },
        
            isUndefined : function ( _value_ ) {
            
                return typeof _value_ === 'undefined' ? true : false;
            }
        
        } );
    
        function hasCity( form, city, state, _flag_ ) {
            
            var flag = typeof _flag_ === 'boolean' && _flag_ === true ? true : false;
            var obj_ = obj_ || init.getCitiesObj();
            init.setFlag( false );
            
            $.each( obj_, function( key, value ) {
                
                if ( typeof value === 'boolean' ) {
                
                    return;
                }
                    
                if( value.city.toLowerCase() === city ) {
                    
                    init.setFlag( true );
                    
                    if ( flag ) {
                        
                        form.cityLat = parseFloat( value.cityLat );
                        form.cityLng = parseFloat( value.cityLng );
                    } else {
                        
                        form.lat = parseFloat( value.cityLat );
                        form.lng = parseFloat( value.cityLng );
                    }
                    return false;
                }
            });
            if ( init.isFlag() ) {
                
                return form;
            } else {
                
                return false;
            }
        }
    
        function addressWithoutHash ( address ) {
                
            return address.replace( '#', 'Apt' );
        }
            
        function microtime ( get_as_float ) {
        
            var now = new Date().getTime() / 1000,
            s = parseInt(now, 10);

            return get_as_float ? now : ( Math.round( ( now - s ) * 1000 ) / 1000) + ' ' + s;
        }
    
        function getSearch ( e ) {
        
            var $t = $( this );
            $t.css( { 'display' : 'none' } );
        
            e.preventDefault();
        
            var classArray = $t.attr( 'class' ).split( ' ' );
        
            init.setMainBtn( '.' + classArray[0] );
            
        
        
            if ( init.getKillSearch() ) {
            
                return false;
            }
        
            //init.setKillSearch( true );
        
            if ( e.done ) {
            
                return false;
            }
        
            e.done = true;
        
            map_wrap.hide();
            error_msg_no_result.css( { 'display' : 'none' } );
        
            var result;
            var jQueryForm = $t.closest( 'form' );
            var $form = jQueryForm.serializeObject();
            
            $form.action = jQueryForm.attr( 'action' );
            $form.q = $form.q.length > 2 ? $.trim( $form.q ) : '';
            $form.near = $form.near.length > 2 ? $.trim( $form.near ) : '';
            
            if( init.mob ) {
                
                $form.mob_sys = init.mob_sys;
                $form.mob = true;
                //alert($form.mob_sys);
            }
            
            // FORM PROPS 
            $form.noValue = false;
            $form.sortBy = "";/* ["geo", "near", "rel"] */
            $form.lat = "";
            $form.lng = "";
            $form.formCity = $form.near.split(" ");
             
            $form.formCity = $form.formCity.length > 1 ? 
                $form.formCity[0].toLowerCase() + ' ' + $form.formCity[1].toLowerCase() :
                $form.formCity[0].toLowerCase();
            // FORM PROPS 
            
            if ( $form.q === "" && $form.near === "" ) {
                
                $form.noValue = true;   
                if( $.userAutoGeo() ) {
                    
                    $form.sortBy = "geo";
                } else {
                    
                    $form.sortBy = "rel"
                }
            } else if ( $form.q !== "" && $form.near === "" ) {
                
                $form.noValue = false;
                if ( $.userAutoGeo() ) {
                    
                    $form.sortBy = "geo";
                } else {
                    
                    $form.sortBy = "rel"
                }
            } else if ( $form.q === "" && $form.near !== "" ) {
                
                $form.noValue = false;
                $form.sortBy = $.userAutoGeo() ? "geo" : "near";
                
            } else if ( $form.q !== "" && $form.near !== "" ) {
                
                $form.noValue   = false;
            
                $form.sortBy = $.userAutoGeo() ? "geo" : "near";
            }
            $.loading();
                
            if ( $form.sortBy === "near" ) {
                    
                result = hasCity( $form, $form.formCity, "WA" ); 
                
                if( !result ) {
                        
                    // inside google getCityGoogle insert new city on the object 
                    var getCityGoogleReturn = getCityGoogle( $form, $form.formCity, "WA" );
                        
                    if ( !getCityGoogleReturn ) { $t.show(); return false; }
                } else {
                        
                    finalSearch( result );
                }
            } else if ( $form.sortBy === "geo" ) {
                    
                $form.lat = geo.coords().lat;
                $form.lng = geo.coords().lng;
                    
                finalSearch( $form );
            } else if ( $form.sortBy === "rel" ) {
                    
                $form.lat = $.userAutoGeo() && !init.isSearchUrl() ? geo.coords().lat : "";
                $form.lng = $.userAutoGeo() && !init.isSearchUrl() ? geo.coords().lng : "";
                finalSearch( $form );
            } else {
                    
                console.log( 'returning false from getSearch()' );
                $.loading();
                $t.show(); 
                return false;
            }
        }
    
        function finalSearch ( form ) {
        
            map_wrap.hide();
        
            if( find_home.is( ':visible' ) && near_home.is( ':visible' ) ) {
            
                if ( find_home.val() === "" || near_home.val() === "" ) {
                
                    find_home.val( form.q ); 
                    near_home.val( form.near );
                }
            }
        
            //var formButtomSelector = form.formButtomSelector;
        
            //delete form.formButtomSelector;

            if ( !form.hasOwnProperty( 'track' ) ) {
            
                form.track = init.getLocation();
            }
                
            var param_ = jQuery.param( form );

            var title_ = $('title').text();

            var baseUrl_ = window.location.host === '127.0.0.1:8080' ? 
                'http://127.0.0.1:8080' + window.location.pathname + '?' : 
                'http://clivelive.com' + window.location.pathname + '?';

            var state_ = History.getState();

            var baseUrlAndParam = baseUrl_ + param_;
                
            var o = {
                'title' : title_,
                'baseAndParam' : baseUrlAndParam,
                'baseUrl' : baseUrl_,
                'state' : state_
            }

            //History.pushState( { state_ :  baseUrl_ }, title_, baseUrlAndParam );
            init.setFirstState( state_ );
                
            form.path = init.pathPart;
        
            $.ajax( { 
                url : form.action, 
                data : form, 
                timeout : 15000
            } )
            .done( function( response ) {
            
                try {
                
                    response = jQuery.parseJSON( response );
                } catch ( e ) {
                
                    $( 'body' ).prepend( response );
                    
                    init.killSearch();
                    $( init.getMainBtn() ).show()
                    //searchButtom.show();    
                    return false;
                }
            
                console.log( response );
            
                var $result = response.result,
            
                loc = response.locationArray,
                
                noReturne = response.searchNoReturn;
                
                if ( noReturne ) {
                    
                    init.killSearch( 'We did not find any match for this, please try again' );
                    $( init.getMainBtn() ).show();
                    //searchButtom.show();    
                    return false;
                }
                
                error_msg.hide();
                search_div.hide();
                
                //search_div.html( $result );
                
                not_search_div.slideUp( function () {
                    search_div
                        .slideDown( function () {
                            
                            loading.hide();
                            $( init.getMainBtn() ).show();

                        });
                });

                
                
                
                
                if ( !$.isUndefined( o ) ) {
                    
                    var stateIn = o.state;
                    History.pushState( { stateIn :  o.baseUrl }, o.title, o.baseAndParam );
                }


                if ( $.userAutoGeo() || form.formCity.length > 3 ) {
                        
                    msg_no_geo.css( { 'display' : 'none' } );
                } else {
                        
                    msg_no_geo.css( { 'display' : 'block' } );
                }
                    
                var centerMarkerInfo = [];
                    
                if ( form.sortBy === 'geo' ) {
                        
                    centerMarkerInfo.push( 'geo', 'You are here' );
                } else if ( form.sortBy === 'near' ) {
                        
                    centerMarkerInfo.push( 'near', form.formCity )
                }
                        
                if ( $.userAutoGeo() && !init.isSearchUrl() && form.sortBy !== 'near' ) {
                        
                    //initialize( geo.coords().lat, geo.coords().lng, loc );
                    hideSearchBtn.data({
                        "lat" : geo.coords().lat,
                        "lng" : geo.coords().lng,
                        "loc" : loc,
                        "sortBy" : centerMarkerInfo
                    });
                } else if ( init.isSearchUrl() ) {
                        
                    //initialize( form.lat, form.lng, loc );
                    hideSearchBtn.data({
                        "lat" : form.lat,
                        "lng" : form.lng,
                        "loc" : loc,
                        "sortBy" : centerMarkerInfo
                    });
                } else {
                        
                    //initialize( form.lat, form.lng, loc );
                    hideSearchBtn.data({
                        "lat" : form.lat,
                        "lng" : form.lng,
                        "loc" : loc,
                        "sortBy" : centerMarkerInfo
                    });
                }
                    
                    
                hideSearchBtn.show();
                    
                
                    
            }).fail( function ( jqXHR, textStatus, errorThrown ) {
                    
                if ( textStatus === 'timeout' ) {
                    
                    $.searchTimeout();
                    return false;
                } else {
                        
                    init.killSearch();
                        
                    var msg_INSIDE = "We could not find that PROBLEM, please check \n\
                            and try again<br><button class='btn btn-default close-error-msg'>CLOSE MESSAGE</button>"
                    error_msg_no_result.html(msg_INSIDE).show('slow');
                    $( init.getMainBtn() ).show();
                }
                return false;
            });
            return false;
        }
    
    
        $(document)
        .on( 'click', '.searchbtn-index, .mob-searchbtn-index, .searchbtn-not-index', getSearch )
        .on( 'click', '.rFalse', function ( e ) {
            
            e.preventDefault();
            e.stopPropagation();
            return false;
        } )
        .on( 'click', '.mob-search-btn-index', function ( e ) {
            
            e.preventDefault();
            $( this ).slideToggle( 'slow' );
            $( '.mob-search-holder-home' ).slideToggle( 'slow' );
        } )
        .on( 'click', '.error-msg', function ( e ) {
                
            if ( $( this ).find( 'a' ).length ) {
                
                // do nothing;
            } else { 
                    
                e.preventDefault();
                $( this ).slideUp( 'slow' );
            }
        } )
        .on( 'click', '.close-error-msg', function ( e ) {
            
            $( '.error-msg' ).slideUp( 'slow' );
        } )
        .on( 'beforeShow', '.hideSearchButton', function () {
            
            $( '#map_wrap' ).hide();
            $( '.closeMapButton' ).hide();
        })
        .on( 'beforeHide', '.hideSearchButton', function () {
            
            $( '.closeMapButton' ).show();
        })
        .on( 'click', '.hideSearchButton', function ( e ) {
            
            e.preventDefault();
            var hsBtn = hideSearchBtn.data();
            hideSearchBtn.hide();
            map_wrap.show();
            initialize( hsBtn.lat, hsBtn.lng, hsBtn.loc, hsBtn.sortBy );
        } )
        .on( 'click', '.closeMapButton', function ( e ) {
            
            e.preventDefault();
            map_wrap.hide();
            hideSearchBtn.show();
        } )
        .on( 'change', '#checkbox', function ( e ) {
            
            if ( $( this ).is( ':checked' ) ) {

                init.setLocation( true );

                if ( init.getMainBtn() ) {

                    $( init.getMainBtn() ).trigger( 'click' );
                } else {

                    var buttonNow = getVisibleButtonNow();
                    
                    if ( init.getSearchUrl() ) {
                        
                        $( buttonNow ).trigger( 'click' );
                    } 
                }
            } else {

                init.setLocation( false );

                if ( init.getMainBtn() ) {

                    $( init.getMainBtn() ).trigger( 'click' );
                } else {

                    var buttonNow = getVisibleButtonNow();
                    
                    if ( init.getSearchUrl() ) {
                        
                        $( buttonNow ).trigger( 'click' );
                    } 
                }
            }
        } )
        .on( 'beforeShow', '.loading', function () {
            
            //alert( 'This is before show() loading' );
        } )
        .on( 'beforeHide', '.loading', function () {
            
            //alert( 'This si before hide() loading' );
        } )
        .on( 'beforeHide', '.search-div', function () {
            
            $( '.closeMapButton' ).css( 'display', 'none' );
        } )
        .on( 'click', '.error-msg-no-result', function () {
           
            $( this ).css( { 'display' : 'none' } );
        } )
        .on( 'beforeShow', '.error-msg-no-result', function () {
            
            loading.hide();
            $( init.getMainBtn() ).show();
            hideSearchBtn.css( { 'display' : 'none' } );
        } );
        
        

        // GOOGLE MAPS
        var poly;
        var geodesic;
        var map;
        var clickcount = 0;

        function returnCheck ( value ) {
    
            return value ? value : '';
        }

        function initialize( _lat_, _lng_, loc_, sort_ ) {
    
            var sort = !$.isUndefined( sort_ ) ? sort_ : false;
    
            var business_name = sort[1] !== 'You are here' && $.userAutoGeo ? sort[1] : 'You are here'; 
            business_name = false
            //r(business_name);
            //r(sort);
            var loc = loc_;
            var lat_ = typeof _lat_ !== 'undefined' && typeof _lat_ !== 'boolean' ? parseFloat( _lat_ ) : false;
            var lng_ = typeof _lng_ !== 'undefined' && typeof _lng_ !== 'boolean' ? parseFloat( _lng_ ) : false;
    
            var lastIndex = loc.length - 1;
    
            var myLatlng = new google.maps.LatLng( lat_, lng_ );
            var mapCanvas = document.getElementById( 'map_canvas' );
            var mapOptions = { 
                mapTypeId : google.maps.MapTypeId.ROADMAP
            };
        
            //var centerOptions = {
            //      businessName : business_name,
            //      lat : lat_,
            //      lng : lng_
            //    };
    
            if ( lat_ ) {
        
                mapOptions.center = myLatlng;
                //loc.push( centerOptions );
            }
    
    
            var map = new google.maps.Map( mapCanvas, mapOptions );
            var infowindow = new google.maps.InfoWindow();
            var againstCount = "You are here";
    
            var urlPart = new google.maps.MarkerImage( '../_markers/MapMarker_Marker_Outside_Chartreuse.png' );
            //var urlPart = new google.maps.MarkerImage( 'http://www.google.com/mapfiles/marker.png' );
                    
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
                    icon : business_name === loc[i].businessName ? urlPart : image,
                    title : business_name === 'You are here' ? business_name : returnCheck( loc[i].businessName ) + '\n' + returnCheck( loc[i].address ),
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
            }// end of the for loop 
    
            //  Create a new viewpoint bound
            var bounds = new google.maps.LatLngBounds();
    
    
            for ( var a = 0; a < latAndLngList.length;  a++ ) {
                // And increase the bounds to take this point 
                bounds.extend( latAndLngList[a] );
            }
    
    
            //map.setCenter( bounds.getCenter() );
    
            map.fitBounds( bounds );
            //map.fitBounds( bounds );
            //map.setCenter(myLatlng);
    
            console.log( map );
            var polyOptions = {
            
                strokeColor : '#FF0000',
                strokeOpacity : 1.0,
                strokeWeight : 3
            };
            poly = new google.maps.Polyline( polyOptions );
            //poly.setMap( map );

            var geodesicOptions = {
            
                strokeColor : '#CC0099',
                strokeOpacity : 1.0,
                strokeWeight : 3,
                geodesic : true
            };
            geodesic = new google.maps.Polyline( geodesicOptions );
            //geodesic.setMap( map );
            
            var homeControlDiv = document.createElement( 'div' );
            var center_ = bounds.getCenter();
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
        
        function HomeControl ( controlDiv, map, center, zoom ) {

            // Set CSS styles for the DIV containing the control
            // Setting padding to 5 px will offset the control
            // from the edge of the map
            controlDiv.style.padding = '5px';

            // Set CSS for the control border
            var controlUI = document.createElement( 'div' );
            controlUI.style.backgroundColor = 'white';
            controlUI.style.borderStyle = 'solid';
            controlUI.style.borderWidth = '2px';
            controlUI.style.cursor = 'pointer';
            controlUI.style.textAlign = 'center';
            controlUI.title = 'Click to set the map to Home';
            controlDiv.appendChild( controlUI );

            // Set CSS for the control interior
            var controlText = document.createElement( 'div' );
                controlText.className = 'btn btn-default btn-block';
            
            // controlText.style.fontFamily = 'Verdana,sans-serif';
            // controlText.style.fontSize = '12px';
            // controlText.style.paddingLeft = '4px';
            // controlText.style.paddingRight = '4px';
            controlText.style.border = '0px';
            controlText.innerHTML = '<b>Initial Position</b>';
            controlUI.appendChild(controlText);

            // Setup the click event listeners: simply set the map to
            // Chicago
            google.maps.event.addDomListener( controlUI, 'click', function () {
 
                map.setZoom(9);
                map.setCenter( center );
                
            } );
        }

        window.onload = function () {
    
            //starting.Chide();
            
            function afterLoad () {

                if ( $.isUndefined( init.isSearchUrl() ) ) {
            
                    afterLoad();
                    return;
                }
        
                if ( init.isSearchUrl() ) {
            
                    if ( init.getSearchUrl().track ) {
                
                        init.setLocation( true );
                    } else {
                
                        init.setLocation( false );
                    }
                        
                    finalSearch( init.getSearchUrl() );
                } else {
                    
                    //starting.Chide( function () { 
                        
                      //   not_search_div.show();
                    //} );
                      not_search_div.show();
                             
                    
                } 
            }
    
            afterLoad();
        }


        //;ErrorDocument 404 http://127.0.0.1:8080/Clivelive/_base/red.php?404
        //;ErrorDocument 500 http://127.0.0.1:8080/Clivelive/_base/red.php?500
        //;<IfModule mod_rewrite.c>
        //    ;RewriteEngine On
        //    ;RewriteBase /
        //    ;RewriteRule ^index\.html$ - [L]
        //    ;RewriteCond %{REQUEST_FILENAME} !-f
        //    ;RewriteCond %{REQUEST_FILENAME} !-d
        //    ;RewriteRule . /index.html [L]
        //;</IfModule>


        //  DIA 02 13 2014
        // ACERTEI A QUINZENA
        // PAGUEI 300 DA MAQUINA 
        // RECEBE MOVEOUT DE KEMMORE 127 E 
        // E DE KIRKLAND 75 

        //]]>
</script>
