<?php
/* http://127.0.0.1:8080/search.php?q=vader&near=kirkland&noValue=false&sortBy=rel&lat=&lng= */
/* url search sample */
function getSearchPhp(){
    $urlQuery = false;
    if(isset($_GET) && count($_GET) > 1){
        $get = sanitizePost($_GET);
        
        $_url_q         = isset($get['q']) ? $get['q'] : false;
        $_url_near      = isset($get['near']) ? $get['near'] : false;
        $_url_noValue   = (isset($get['noValue']) && ($get['noValue'] === "true" || $get['noValue'] === "false")) 
                            ? (($get['noValue'] === "true") ? true : false) : "bad";
        
        $_url_sortBy    = isset($get['sortBy']) ? $get['sortBy'] : false;
        $_url_lat       = isset($get['lat']) ? $get['lat'] : false;
        $_url_lng       = isset($get['lng']) ? $get['lng'] : false;
        if($_url_noValue === "bad"){
            echo json_encode($urlQuery);
        }else{
            $return = array(
                        "q" => $_url_q, 
                        "near" => $_url_near,
                        "noValue" => $_url_noValue,
                        "sortBy" => $_url_sortBy,
                        "lat" => $_url_lat,
                        "lng" => $_url_lng );
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
        foreach ($return as $key => $value) {

            if (!empty($duplicate) && in_array_case_insensitive($value["city"], $duplicate)){
                unset($return[$key]);
                continue;
            }
            $duplicate[$c] = $value["city"];

            $c++;
        }
        usort($return, "sortCitiesAtFooter");


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

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script type="text/javascript" src="<?php echo $_part_path; ?>_assets/js/jquery.1.9.1.un-min.js"><\/script>')</script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false">
    </script>

    <?php echo '<script type="text/javascript" src="' . $_part_path . '"_assets/js/bootstrap.js"></script>'; ?>
    <?php echo '<script type="text/javascript" src="' . $_part_path . '"_assets/js/plugins.js"></script>'; ?>
    <?php echo '<script type="text/javascript" src="' . $_part_path . '"_assets/js/__main.js"></script>'; ?> 

    
    <script type="text/javascript">
        /*var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
        (function (d, t) {
            var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
            g.src = '//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g, s)
        }(document, 'script'));*/
    
    var r = function(m){ return console.log(m); },
        searchNearArray = [],
        helperNearArray,
        not_search_div = $('.not-search-div'),
        search_div = $('.search-div'),
        error_msg = $('.error-msg'),
        $loading = $('.loading'),
        streetAddress = false;
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
    jQuery.extend({
        loading : function(){ $loading.toggle('fast'); }   
    });
    
    (function(w){
        var searchUrl   = <?php getSearchPhp(); ?>;
        
        var citiesObj   = <?php getMeCities(); ?>;
            citiesObj.request = true;
        
        var cityBounds  = {
            "cityLat" : false,
            "cityLng" : false
        };
        w.searchUrl = searchUrl;
        w.citiesObj = citiesObj;
        w.cityBounds = cityBounds;

    })(window);
    
    var haveCoords  = false
        lat         = false,
        lng         = false,
        Success = function(position){
            haveCoords  = true;
            lat         = position.coords.latitude;
            lng         = position.coords.longitude;
        }
        R = function(error){
            haveCoords  = false;
            lat         = false;
            lng         = false;
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
        },
        options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };
         
        if(navigator.geolocation){
           navigator.geolocation.getCurrentPosition( Success, R );
        }else{
            haveCoords = false;
        }
        
        /* use here pushStack
         * 
         *  http://stackoverflow.com/questions/824349/modify-the-url-without-reloading-the-page */
        
    
    if(!searchUrl){
        
        alert("NO, the searchUrl is false");
        
    }else{
        if(searchUrl != false && searchUrl.sortBy === "near"){
            searchNearArray = searchUrl.near.split(" ");
            checkObjPlus(searchNearArray[0].toLowerCase(), "WA");
            finalSearch(searchUrl);
        }
    }
    
    window.result = false;
    
    function checkObjPlus(city, state){
        if (!window.citiesObj){
            setTimeout(function(){
                checkObjPlus(city, state);
            }, 1);
            return;
        }
        if(!window.citiesObj.request){
            return false;
        }
            
            var flag_ = false
            $.each(window.citiesObj, function(key, value){
                if(value.city.toLowerCase() === city){
                    window.cityBounds.cityLat = value.cityLat;
                    window.cityBounds.cityLng = value.cityLng;
                    flag_ = true;
                }
            });
            /*if(searchUrl != false && searchUrl.sortBy === "near" && flag_){
                window.searchUrl.lat = window.cityBounds.cityLat;
                window.searchUrl.lng = window.cityBounds.cityLng;
            }*/
            if(!flag_){
                pushToCitiesArray(city, state);
            }
    }
    
    function pushToCitiesArray(city_, state_){
        
        var send = { 
                "city" : city_,
                "fullCity" : this.city + ", " + this.stateShort,
                "stateShort" : state_
            },
            insideUrl = "http://maps.googleapis.com/maps/api/geocode/json?address=+"+send.city+",+"+send.stateShort+"&sensor=false";
            //1600+Amphitheatre+Parkway,+Mountain+View,+CA
            $.get(insideUrl)
                .done(function(done){
                    if(done.status === "OK"){
                        window.cityBounds.cityLat = done.results[0].geometry.location.lat;
                        window.cityBounds.cityLng = done.results[0].geometry.location.lng;
                    }else{
                        var msg_INSIDE = "<h1>\n\
                            We could not find that ("+send.fullCity+") in that state, please check \n\
                            and try again</h1><br><button class='btn btn-default close-error-msg'>CLOSE MESSAGE</button>"
                        $('.error-msg').html(msg_INSIDE).show('slow');
                        throw "error message";
                    }
                })
                .fail(function(){
                    window.cityBounds.cityLat = "fail";
                    window.cityBounds.cityLng = "fail";
                });
            
    }


    
    
    /* *** length *** */
    /* *** throw "empty"; *** */
    
    function checkStreetAddress(){
        if (!streetAddress) {
            setTimeout(checkStreetAddress,50);
            return;
        }
        /*
         * i need the user point 
         * and the sibligs of this li or div i do not know
         *
         */
        var obj = { "lat" : window.result.geometry.location.lat, "lng" : window.result.geometry.location.lng, "user" : nowUser };
        $.get("_adv/pushLatLng.php", obj, function(data){console.log(data);});
        
        var fullObj = window.result;
        func(obj, fullObj); 
    };
    
   /* STORE SEARCH ON THE DATABASE */
    /* TIME */
    function microtime (get_as_float) {
    /*  From: http://phpjs.org/functions
        +   original by: Paulo Freitas
        *   example 1: timeStamp = microtime(true);
        *   results 1: timeStamp > 1000000000 && timeStamp < 2000000000 */
        var now = new Date().getTime() / 1000,
            s = parseInt(now, 10);

        return (get_as_float) ? now : (Math.round((now - s) * 1000) / 1000) + ' ' + s;
    }
    /* COORDS FUNCTIONS */
   
    /* COORDS FUNCTIONS */
    var searchFlag = <?php  if(isset($_Q)){  
                                echo $_Q; 
                            } else {
                                echo 'false';
                            }
                            ?>;

    var jsonPhp = false;
    if(searchFlag){
        jsonPhp = <?php if(isset($_Q_GET)){  
                            echo $_Q_GET; 
                        } else {
                            echo 'false';
                        }
                        ?>;
    }
    var jsonJs = jsonPhp; //jQuery.parseJSON(jsonPhp);
    
    var searchObj = false;
    
    function checkObj(){
        if (!searchObj) {
            setTimeout(checkObj,50);
            return;
        }
        show(searchObj);
    }
    
        
    
    function getSearch(e){
        
        var start = microtime(true);
            e.preventDefault();
        
        var $t = $(this),
            $form = $t.closest('form').serializeObject();
            $form.q = $.trim($form.q);
            $form.near = $.trim($form.near);
            
            $form.noValue   = false;
            $form.sortBy    = "";/* ["geo", "near", "rel"] */
            $form.lat       = "";
            $form.lng       = "";
            
            
            /*
             *  I NEED TO FIX THE SORTBY 
             **/
            if($form.q === "" && $form.near === ""){
                $form.noValue = true;
                $form.sortBy = "geo";
                
            }else if($form.q != "" && $form.near === ""){
                $form.sortBy = "geo";
                
            }else if($form.q === "" && $form.near != ""){
                
                $form.sortBy = "near";
            }
            if(haveCoords && !searchUrl){
                $form.lat = lat;
                $form.lng = lng;
                $form.sortBy = $form.sortBy === "near" ? $form.sortBy : "geo";
            }else if(searchUrl && !haveCorrds){
                $form.sortBy = $form.sortBy === "near" ? $form.sortBy : "rel";
            }else{
                $form.sortBy = $form.sortBy === "near" ? $form.sortBy : "rel";
            }
    
            $.loading();
            
            finalSearch($form, $t);
            var end = microtime(true);
            var over = end - start;
            console.log(over);
    }
    
    function finalSearch(form, elem){
        
        //window.history.pushState(form);
        var url;
        if(typeof elem !== "undefined"){
            url = elem.closest('form').attr('action');
        }else{
            url = "search.php";
        }
        if(form.sortBy === "near"){
            if(!window.cityBounds.cityLat){
                console.log("here");
                setTimeout(function(){
                    finalSearch(form, elem);
                },1);
                return;
            }
        }
        if(searchUrl != false && searchUrl.sortBy === "near"){
            alert("pass here");
            form.lat = window.cityBounds.cityLat;
            form.lng = window.cityBounds.cityLng;
        }
            console.log(form);    
        
        $.get(url, form).done(function(response){
            $('body').prepend(response);     
            response = jQuery.parseJSON(response);
            
            console.log(response);
            var $noReturn   = response.searchNoReturn,
                $result     = response.result,
                msg_for_error_msg = "0 results came from => " + form.q + " " + form.near;

                loc = response.location;

                $('.error-msg, .search-div')
                    .html("")
                    .hide(100);
                search_div
                    .html($result);
                not_search_div.show(100);
                
                if($noReturn){
                    not_search_div.slideUp("slow", function(){
                        search_div
                            .slideDown("slow", function(){
                                if(!form.noValue){
                                    error_msg
                                        .html(msg_for_error_msg)
                                        .slideDown("fast");
                                }
                                $.loading();
                            });
                    });
                }else{
                    not_search_div.slideUp("slow", function(){
                        search_div
                            .slideDown("slow", function(){
                                if(form.noValue){
                                    error_msg
                                        .html(msg_for_error_msg)
                                        .slideDown("fast");
                                }
                                $.loading();
                            });
                    });
                }
                
                if(haveCoords && !searchUrl){
                    initialize(document, lat, lng);
                }else if(searchUrl){
                    initialize(document, searchUrl.lat, searchUrl.lng);
                }else{
                    initialize(document, false, false);
                }
               
            });
    }
    
    
    $(document)
        .on('click', '.searchbtn-index, .mob-searchbtn-index, .searchbtn-not-index', getSearch)
        .on('click', '.rFalse', function(e){
            e.preventDefault();
                e.stopPropagation();
                    return false;
        })
        .on('click', '.mob-search-btn-index', function(e){
            e.preventDefault();
                $(this).slideToggle('slow');
                    $('.mob-search-holder-home').slideToggle('slow');
        })
        .on('click', '.error-msg', function(e){
                if($(this).find('a').length){
                }else{
                    e.preventDefault();
                    $(this).slideUp('slow');
                }
        })
        .on('click', '.close-error-msg', function(e){
            $('.error-msg').slideUp('slow');
        })
        .on('change', '.near-home', function(e){
            alert("event CHANGE fired");
            searchNearArray = $(this).val().split(" ");
            alert(searchNearArray[0].toLowerCase());
            checkObjPlus(searchNearArray[0].toLowerCase(), "WA");
        });    
    

/* GOOGLE MAPS */
var poly;
var geodesic;
var map;
var clickcount = 0;

function initialize(d, lat, lng) {
    var myLatlng = new google.maps.LatLng(lat, lng),
        mapCanvas = d.getElementById('map_canvas'),
        mapOptions = { 
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        },
        centerOptions = {
          businessName : "You are here",
          lat : lat,
          lng : lng
        },
        againstCount = "You are here";
        urlPart = 'http://www.google.com/mapfiles/marker.png';
    //  zoom: 10,
    var map = new google.maps.Map(mapCanvas, mapOptions),
        infowindow = new google.maps.InfoWindow(),
        marker, 
        m = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", 
            "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" ],
        latAndLngList = [];
        loc.push(centerOptions);
       
    for (var i = 0; i < loc.length ; i++) {
        
        var latAndLng = new google.maps.LatLng(loc[i].lat, loc[i].lng);
            latAndLngList.push(latAndLng),
            image = new google.maps.MarkerImage('http://www.google.com/mapfiles/marker' + m[i] + '.png',
                      new google.maps.Size(20, 34),
                      new google.maps.Point(0, 0),
                      new google.maps.Point(10, 34));

            marker = new google.maps.Marker({
                position: latAndLng,
                map: map,
                icon: againstCount === loc[i].businessName ? urlPart : image,
                title: loc[i].businessName,
                animation: google.maps.Animation.DROP
            });
            google.maps.event.addListener(marker, 'click', (function(marker) {
                return function() {
                    if (marker.getAnimation() != null) {
                        marker.setAnimation(null);
                    } else {
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                    }
                }
            })(marker));
    }
    
    

    //  Create a new viewpoint bound
    var bounds = new google.maps.LatLngBounds();
    //  Go through each...
    for (var a = 0; a < latAndLngList.length;  a++) {
      //  And increase the bounds to take this point
      bounds.extend(latAndLngList[a]);
    }  
    map.fitBounds(bounds);
    
    var polyOptions = {
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 3
    },
    poly = new google.maps.Polyline(polyOptions);
    poly.setMap(map);

        geodesicOptions = {
            strokeColor: '#CC0099',
            strokeOpacity: 1.0,
            strokeWeight: 3,
            geodesic: true
        },
    geodesic = new google.maps.Polyline(geodesicOptions);
    geodesic.setMap(map);

    // Add a listener for the click event
    //google.maps.event.addListener(map, 'click', addLocation);
   
    
    infowindow.open(map, marker);
    //(optional) restore the zoom level after the map is done scaling
/*var listener = google.maps.event.addListener(map, "idle", function () {
    map.setZoom(3);
    google.maps.event.removeListener(listener);
});*/

  
}

function addLocation(event) {
  clickcount++;
  if (clickcount == 1) {
    addOrigin(event);
  }
  if (clickcount == 2) {
    addDestination(event);
  }
}

function addOrigin(event) {
  clearPaths();
  var path = poly.getPath();
  path.push(event.latLng);
  var gPath = geodesic.getPath();
  gPath.push(event.latLng);
}

function addDestination(event) {
  var path = poly.getPath();
  path.push(event.latLng);
  var gPath = geodesic.getPath();
  gPath.push(event.latLng);
  adjustHeading();
  clickcount = 0;
}

function clearPaths() {
  var path = poly.getPath();
  while (path.getLength()) {
    path.pop();
  }
  var gPath = geodesic.getPath();
  while (gPath.getLength()) {
    gPath.pop();
  }
}

function adjustHeading() {
  var path = poly.getPath();
  var pathSize = path.getLength();
  var heading = google.maps.geometry.spherical.computeHeading(path.getAt(0), path.getAt(pathSize - 1));
  document.getElementById('heading').value = heading;
  document.getElementById('origin').value = path.getAt(0).lat()
      + "," + path.getAt(0).lng();
  document.getElementById('destination').value = path.getAt(pathSize - 1).lat()
      + "," + path.getAt(pathSize - 1).lng();
}

function toggleBounce() {

  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}
        </script>
    </body>
</html>
<?php mysql_close(); ?>