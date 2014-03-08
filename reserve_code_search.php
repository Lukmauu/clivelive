<?php


$time_start = microtime(true);

if ( $_SERVER['REQUEST_METHOD'] !== "GET" ) {
    
    header( "location: index.php" );
}

function isLocal () { 
    
    $_return = FALSE;
    if ( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) { 
        $_return = TRUE;
    }
    
    return $_return;
}

require '_includes/clivelive-conn.php';
require '_includes/authentication.php';
require '_includes/func.php';

function getBusinessName ( $thing ) {
    
    $search = "business name: ";
    $array = explode("/", $thing);
    foreach ( $array as $key => $value ) {
        if ( strpos($value, $search) !== false ) {
            $return = str_replace( $search, "", $value );
            return trim( $return );
        }
    }
}

function array_multi_sort ( $array, $one, $two, $order=SORT_DESC ) {
    
    foreach ( $array as $key => $value ) {
        
        $relevance[$key] = $value[$one];
        $distance[$key] = $value[$two];
    }
    
    array_multisort( $relevance, $order, $distance, $order, $array );
}
/* HAVERSINE FORMULA */
function haversineDistance ( $latFrom, $lngFrom, $latTo, $lngTo, $earthRadius = 3960.00 ) {
    
    $latDelta       = $latTo - $latFrom;
    $lonDelta       = $lngTo - $lngFrom;
    $alpha          = $latDelta / 2;
    $beta           = $lonDelta / 2;

    $firstCalc      = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) +
                      cos(deg2rad($latFrom)) * cos(deg2rad($latTo)) *
                      sin(deg2rad($beta)) * sin(deg2rad($beta));
    $secondCalc     = asin(min(1, sqrt($firstCalc)));

    $distance       = round( 2 * $earthRadius * $secondCalc, 2 );

    return $distance;
}

function distance( $lat1, $lon1, $lat2, $lon2 ) {
  $theta = $lon1 - $lon2;
  $dist = rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))));
  
  $miles = $dist * 60 * 1.1515;
  
  
        return $miles;
  
}

function haversineFormula( 
                        $latitudeFrom, 
                        $longitudeFrom, 
                        $latitudeTo, 
                        $longitudeTo, 
                        $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom  = deg2rad($latitudeFrom);
  $lonFrom  = deg2rad($longitudeFrom);
  $latTo    = deg2rad($latitudeTo);
  $lonTo    = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle    = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  
  $return   = round( $angle * $earthRadius , 4);
  
  return $return;
}

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
    // convert from degrees to radians
    $latFrom    = deg2rad($latitudeFrom);
    $lonFrom    = deg2rad($longitudeFrom);
    $latTo      = deg2rad($latitudeTo);
    $lonTo      = deg2rad($longitudeTo);

    $latDelta   = $latTo - $latFrom;
    $lonDelta   = $lonTo - $lonFrom;

    $angle      = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    
    return $angle * $earthRadius;
    
}
/* HAVERSINE FORMULA */

/* vincentyGreatCircleDistance */
function vincentyGCD($lat_from, $lng_from, $lat_to, $lng_to, $earth_radius=6371000)
{
  // convert from degrees to radians
  $latFrom  = deg2rad($lat_from);
  $lngFrom  = deg2rad($lng_from);
  $latTo    = deg2rad($lat_to);
  $lngTo    = deg2rad($lng_to);

  $lngDelta = $lngTo - $lngFrom;
  $a        = pow(cos($latTo) * sin($lngDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lngDelta), 2);
  $b        = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lngDelta);

  $angle = atan2(sqrt($a), $b);
  return round($angle * $earth_radius, 4);
}
/* vincentyGreatCircleDistance */

function getResultsRandom(){
    $array = array();
    $q = "SELECT * FROM search ORDER BY RAND()";
    $q_prep = queryMysql($q);
    while($row4 = mysql_fetch_assoc($q_prep)){
        array_push($array , $row4);
    }
    return $array;
}




$i = 0;
$m = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
$searchNoReturn = false;
$_ARRAY_RESULTS = array();
$return = "";
$tokens = "";
$_temp1 = "";
$_temp2 = "";
$get = sanitizePost($_GET);
$latUser = $get['lat'];
$lngUser = $get['lng'];


$_GEO = $get['sortBy'] === "geo" ? $get['sortBy'] : false;

if($get['noValue'] === "false"){    
    
    $getQ       = (strlen($get['q']) > 2)  ? $get['q'] : NULL;
    $getNear    = (strlen($get['near']) > 2) ? $get['near'] : NULL;
    
    if ( is_null( $getQ ) && is_null( $getNear ) ) {
        
        $back = array( 'result' => false );
        echo json_encode( $back ); exit;
    }else if(!is_null($getQ) && !is_null($getNear)){
        $_temp1 = breakOnSpace($getNear);
        $_temp2 = breakOnSpace($getQ);
        $tokens = array_merge($_temp1, $_temp2);
    }else if(!is_null($getNear)){
        $tokens = breakOnSpace($getNear);
    }else{
        $tokens = breakOnSpace($getQ);
    }
    /* TRIM TOKENS */
    array_map('trim', $tokens);
    /* CLEAN NULL */
    foreach ($tokens as $key => $value) {
        if(is_null($value)){
            unset($tokens[$key]);
        }else if($value == " " || $value == "" || $value == "  "){
            unset($tokens[$key]);
        }
    }

    $_array_match   = array();
    $_array_like    = array();
    $like           = array();
    $match          = "";
    $match2         = "";
    $matchQ         = FALSE;
    $matchQ2        = FALSE;
    $likeQ          = FALSE;

    foreach($tokens as $token){

        if(strlen($token) >= 4){
            $match .= sanitizeString("+" . $token . ", ");
            $match2 .= sanitizeString($token . ", ");
        }else{
            array_push($like, sanitizeString("%" . $token . "%"));
        }
    }

    if(strlen($match) > 1){
        $match = substr($match, 0, -2);
        $match2 = substr($match2, 0, -2);
        $matchQ = "SELECT *, MATCH (page_content) 
                    AGAINST ('{$match}' IN BOOLEAN MODE) 
                    AS relevance FROM search WHERE MATCH (page_content) 
                    AGAINST ('{$match}' IN BOOLEAN MODE)
                    ORDER BY relevance DESC";
        $matchQ2 = "SELECT *, MATCH (page_content) 
                    AGAINST ('{$match2}' IN BOOLEAN MODE) 
                    AS relevance FROM search WHERE MATCH (page_content) 
                    AGAINST ('{$match2}' IN BOOLEAN MODE)
                    ORDER BY relevance DESC";
    }

    if(count($like) >= 1){
        $count = 1;
        $likeQ = "SELECT * FROM search WHERE ";
        foreach ($like as $key => $value) {
            if(count($like) > $count){
                $likeQ .= "page_content LIKE '{$value}' OR ";
            }else{
                $likeQ .= "page_content LIKE '{$value}'";
            }
            $count++;
        }
    }

    if($matchQ != FALSE){                

        $matchQ_prep = queryMysql($matchQ);
        $matchQ2_prep = queryMysql($matchQ2);
        while ($row = mysql_fetch_assoc($matchQ_prep)){
            if(count($row) > 0){
                array_push($_array_match, $row);
            }else{
                $searchNoReturn = false;
                //echoPre('There is no results from MATCH query');
            }
        }
        while ($row2 = mysql_fetch_assoc($matchQ2_prep)){
            if(count($row2) > 0){
                array_push($_array_match, $row2);
            }else{
                $searchNoReturn = false;
                //echoPre('There is no results from MATCH2 query');
            }
        }
    }

    if($likeQ != FALSE){
        $likeQ_prep = queryMysql($likeQ);

        while($row3 = mysql_fetch_assoc($likeQ_prep)){
            if(count($row3) > 0){
                array_push($_array_like , $row3);
            }else{
                $searchNoReturn = false;
                //echoPre('There id no results from LIKE query');
            }
        }
    }

    if ( count( $_array_match ) <= 0 && count( $_array_like ) <= 0 ) {
        
        $searchNoReturn = false;
    } else if ( count( $_array_match ) > 0 && count( $_array_like ) > 0 ) {
        
        $_array_match = array_unique( $_array_match,SORT_REGULAR );
        $_ARRAY_RESULTS = array_merge( $_array_match, $_array_like );
        $searchNoReturn = true;
    } else if ( count( $_array_match ) > 0 && count( $_array_like <= 0 ) ) {
        
        $_array_match = array_unique( $_array_match,SORT_REGULAR );
        foreach ( $_array_match as $key => $value ) {
            array_push( $_ARRAY_RESULTS, $value );
        }
        $searchNoReturn = true;
    } else if ( count( $_array_like > 0 ) ) {
        
        foreach ( $_array_like as $key => $value ) {
            array_push( $_ARRAY_RESULTS, $value );
        }
        $searchNoReturn = true;
    }
    /* if there is no result and no GEO do a random search */
    if ( !$_GEO && !$searchNoReturn ) {
        
        $_ARRAY_RESULTS = getResultsRandom();
    }
} else {
    
    if ( $_GEO ) {
        
        if ( $get['noValue'] === "true" ) {
            $_ARRAY_RESULTS = getResultsRandom();
        }
    } else if ( $get['sortBy'] === "rel" ) {
        
        $_ARRAY_RESULTS = getResultsRandom();
    } else {
        
        $_ARRAY_RESULTS = getResultsRandom();
    }
}


$_DUPLICATE = array();

foreach ($_ARRAY_RESULTS as $key => $value) {
    
    if (!empty($_DUPLICATE) && in_array($value["wa_id"], $_DUPLICATE)){
        unset($_ARRAY_RESULTS[$key]);
        continue;
    }
    /* add unique value */
    $_DUPLICATE["wa_id"] = $value["wa_id"];
    /* add distance */
    if ( ( $get['noValue'] !== 'true' || $get['sortBy'] === 'geo' ) && strlen( $get['lat'] ) > 5 ) {
        
        $_ARRAY_RESULTS[$key]["distance"] = haversineDistance($latUser, $lngUser, $_ARRAY_RESULTS[$key]["lat"], $_ARRAY_RESULTS[$key]["lng"]); 
    }
}

if ( $get['sortBy'] !== "rel" && strlen( $get['lat']) > 3 ) {
    
    usort( $_ARRAY_RESULTS, "cmp" );
} /*else if ( $get['sortBy'] !== "rel" && strlen( $get['lat']) > 3 ) {
    
    array_multi_sort( $_ARRAY_RESULTS, "relevance", "distance" );
} */
//echoPre($_ARRAY_RESULTS);

$location = array();
foreach ($_ARRAY_RESULTS as $value) {
    if ( !is_numeric( $value ) ) {
        
    
$value['distance'] = ( $value['distance'] > 0 ) ? $value['distance'] . ' miles' : 'Very close'; 
        
$businessName = getBusinessName($value['real_content']);

$return .= '<div class="divPre row searchPadding searchMargin">';
    $return .= '<div class="col-xs-12 col-sm-10">';

$return .= '<a href="'.$value['page_url'].'?lat='.$latUser.'&lng='.$lngUser.'">';
    $return .= '<img src="_images/video_thumb_homepage_01.png" class="col-xs-6 col-sm-5 img-responsive" /></a>';
        $return .= '<h2 class="col-xs-12 col-sm-7">'.$businessName.'</h2>';
        $return .= '<div><address>'.$value['full_address'].'</address></div>';
        $return .= '<div><p>'.$value['phone'].'</p></div>';
        
        $return .= '<p class="col-xs-12 col-sm-7"><p>'.$value['real_content'].'</p></div>';
        $return .= '<div class="col-sm-2">';
        $return .= ( ( $get['noValue'] !== 'true' || $get['sortBy'] === 'geo' ) && strlen( $get['lat'] ) > 5 ) ? '<h4>' . $value['distance'] . '</h4>' : '';
        $return .= '<img src="http://www.google.com/mapfiles/marker' . $m[$i] . '.png"></div><br />';

$return .= '<div class="row fixRow"><div>';
$return .= '<div class="col-xs-4"><a href="tel:'.$value['phone'].'">Call</a></div>';                    
$return .= '<div class="col-xs-4" style="text-align: center;"><a href="adr:'.$value['full_address'].'">Directions</a></div>';        
$return .= '<div class="col-xs-4" style="text-align: right;"><a href='.$value['page_url'].'>Website</a></div>';
$return .= '</div></div>';




$return .= '</div>';



array_push($location, array(
    "businessName" => $businessName, 
    "lat" => $value['lat'], 
    "lng" => $value['lng']
));
$i++;
    }
}

$time_end = microtime(true);
$time = $time_end - $time_start;

    

$_RETURN = array("get" => $get, "result" => $return, "time" => $time, "location" => $location);

if ( $searchNoReturn ) {
    $_RETURN["searchNoReturn"] = false;
    print_r(  json_encode( $_RETURN ) );
} else {
    $_RETURN["searchNoReturn"] = true;
    print_r(  json_encode( $_RETURN ) );
}

?>




<?php
// Find unique user_ids
/*
$uniques = array_unique($_ARRAY_RESULTS);
echoPre($uniques);
// Keep only the uniques
foreach ($group_array as $key => $value) {
    $_ARRAY_RESULTS[$key] = array_intersect_key($value, $uniques);
}
*/
?>
