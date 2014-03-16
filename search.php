<?php


$time_start = microtime( true );

if ( $_SERVER['REQUEST_METHOD'] !== "GET" ) {
    
    header( "location: index.php" );
}

function isLocal () { 
    
    if ( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) { 
        
        return true;
    }
    return false;
} 

require '_includes/clivelive-conn.php';
require '_includes/authentication.php';
require '_includes/func.php';


/*function isDistance () {
    
    return true;
}*/

function getBusinessName ( $thing ) {
    
    $search = "business name: ";
    $array = explode( "/", $thing );
    foreach ( $array as $key => $value ) {
        
        if ( strpos( $value, $search ) !== false ) {
            
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

function distance ( $lat1, $lon1, $lat2, $lon2 ) {
    
    $theta = $lon1 - $lon2;
    $dist = rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))));

    return $dist * 60 * 1.1515;
}


function getResultsRandom () {
    
    $array = array();
    $q = "SELECT * FROM search ORDER BY RAND()";
    //$q = "SELECT * FROM search ORDER BY full_address";
    $q_prep = queryMysql( $q );
     
    while ( $row = mysql_fetch_assoc( $q_prep ) ) {
        
        array_push( $array , $row );
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
$get = sanitizePost( $_GET );

$latUser = $get['lat'];
$lngUser = $get['lng'];
$track = $get['track'] === 'false' ? false : true;

$_GEO = $get['sortBy'] === "geo" ? $get['sortBy'] : false;

function isDistance () {
    
    global $get;
    return ( ( $get['noValue'] !== 'true' || $get['sortBy'] === 'geo' ) && strlen( $get['lat'] ) > 5 );
}

//echoPre( $get );

if( $get['noValue'] === "false" ){    
    
    $getQ       = ( strlen( $get['q'] ) > 2 )  ? $get['q'] : null;
    $getNear    = ( strlen( $get['near'] ) > 2 ) ? $get['near'] : null;
    
    if ( is_null( $getQ ) && is_null( $getNear ) ) {
        
        $back = array( 'result' => false );
        
        echo json_encode( $back ); 
        exit;
    } else if ( !is_null( $getQ ) && !is_null( $getNear ) ) {
        
        $_temp1 = breakOnSpace( $getNear );
        $_temp2 = breakOnSpace( $getQ );
        $tokens = array_merge( $_temp1, $_temp2 );
    } else if ( !is_null( $getNear ) ) {
        
        $tokens = breakOnSpace( $getNear );
    } else {
        
        $tokens = breakOnSpace( $getQ );
    }
    
    /* TRIM TOKENS */
    array_map( 'trim', $tokens );
    /* TRIM TOKENS */
    
    /* CLEAN NULL */
    foreach ( $tokens as $key => $value ) {
        
        if ( is_null( $value ) ) {
            
            unset( $tokens[$key] );
        } else if ( $value === " " || $value === "" || $value === "  " ) {
            
            unset( $tokens[$key] );
        }
    }
    /* CLEAN NULL */

    $_array_match   = array();
    $_array_like    = array();
    $like           = array();
    $match          = "";
    $match2         = "";
    $matchQ         = FALSE;
    $matchQ2        = FALSE;
    $likeQ          = FALSE;

    foreach ( $tokens as $token ) {

        if ( strlen( $token ) >= 4 ) {
            
            /*$match .= sanitizeString( "+" . $token . ", " );
            $match2 .= sanitizeString( $token . ", " );*/
            $match .= sanitizeString( ( !$_GEO && $get['sortBy'] === 'near' ) ? "+" . $token . "* " : $token . "* " );
            $match2 .= sanitizeString( "" . $token . " " );
        } else {
            
            array_push( $like, sanitizeString( "%" . $token . "%" ) );
        }
    }

    if( strlen( $match ) > 1 ) {
        
        $match = substr( $match, 0, -1 );
        $match2 = substr( $match2, 0, -1 );
        $matchQ = "SELECT *, MATCH (page_content) 
                    AGAINST ('{$match}') 
                    AS relevance FROM search WHERE MATCH (page_content) 
                    AGAINST ('{$match2}' IN BOOLEAN MODE)
                    ORDER BY relevance DESC";
        //echoPre($matchQ);
        /*$matchQ2 = "SELECT *, MATCH (page_content) 
                    AGAINST ('{$match2}' IN BOOLEAN MODE) 
                    AS relevance FROM search WHERE MATCH (page_content) 
                    AGAINST ('{$match2}' IN BOOLEAN MODE)
                    ORDER BY relevance DESC";*/
    }

    if ( count( $like ) >= 1 ) {
        
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

    if ( $matchQ !== false ) {                

        $matchQ_prep = queryMysql($matchQ);
        //$matchQ2_prep = queryMysql($matchQ2);
        while ($row = mysql_fetch_assoc($matchQ_prep)){
            if(count($row) > 0){
                array_push($_array_match, $row);
            }else{
                $searchNoReturn = false;
                //echoPre('There is no results from MATCH query');
            }
        }
        /*while ($row2 = mysql_fetch_assoc($matchQ2_prep)){
            if(count($row2) > 0){
                array_push($_array_match, $row2);
            }else{
                $searchNoReturn = false;
                //echoPre('There is no results from MATCH2 query');
            }
        }*/
    }

    if ( $likeQ !== false ) {
        
        $likeQ_prep = queryMysql( $likeQ );

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

$darkvader = fetchAssoc("SELECT * FROM search WHERE user_name='darkvader'");


foreach ($_ARRAY_RESULTS as $key => $value) {
    
    if ( !empty($_DUPLICATE) && in_array($value["wa_id"], $_DUPLICATE) || $value['user_name'] === 'darkvader' ) {
        
        unset($_ARRAY_RESULTS[$key]);
        continue;
    }
    /* add unique value */
    $_DUPLICATE["wa_id"] = $value["wa_id"];
    /* add distance */
    if ( ( $get['noValue'] !== 'true' || $get['sortBy'] === 'geo' ) && strlen( $get['lat'] ) > 5 ) {
        
        $_ARRAY_RESULTS[$key]["distance"] = $track ? haversineDistance($latUser, $lngUser, $_ARRAY_RESULTS[$key]["lat"], $_ARRAY_RESULTS[$key]["lng"]) : false; 
    }
}


if ( ( $get['noValue'] !== 'true' || $get['sortBy'] === 'geo' ) && strlen( $get['lat'] ) > 5 ) {
        
    $darkvader["distance"] = $track ? haversineDistance( $latUser, $lngUser, $darkvader["lat"], $darkvader["lng"]) : false; 
}


if ( strlen( $get['lat']) > 3 ) {
    
    if ( ( $_GEO || $get['sortBy'] === 'near' ) && $get['q'] === ''   ) {

        usort( $_ARRAY_RESULTS, 'cmp' );
    } else if ( !$_GEO && $get['sortBy'] === 'near' && ( strlen( $get['q'] ) !== '' || !is_null ( $get[q] ) ) ) {

        if ( isset( $_ARRAY_RESULTS['relevance'] ) ) {

            array_multi_sort( $_ARRAY_RESULTS, 'relevance', 'distance' );  
        } else {
            
            usort( $_ARRAY_RESULTS, 'cmp' );
            shuffle( $_ARRAY_RESULTS );
        }
        
    } 
} else if ( isset( $_ARRAY_RESULTS['relevance'] ) ) {
    
    /* DO BY RELEVANCE HERE */
}

//echoPre($_ARRAY_RESULTS);

$location = array();

array_unshift( $_ARRAY_RESULTS, $darkvader );


foreach ( $_ARRAY_RESULTS as $key => $value ) {
    
    $_ARRAY_RESULTS[$key] = desanitizePost( $value );
}

foreach ( $_ARRAY_RESULTS as $value ) {
    
    if ( !is_numeric( $value ) ) {
        
        if ( isset($value['distance'] ) && $track ) {
    
            $value['distance'] = ( $value['distance'] > 0 ) ? $value['distance'] . ' miles' : 'Very close'; 
        }

$businessName = $value['business_name'];

$return .= '<div class="divPre row">';
    
$return .= '<div class="row searchMargin">';

$return .= '<div class="search-second-content">';
    
    $return .= '<img class="search-miles-img" src="http://www.google.com/mapfiles/marker' . ( ( isset( $_RAND_ ) ) ? '' : $m[$i] )  . '.png">';
    $return .= isDistance() && $track ? '<h4 class="search-miles wordFix">' . $value['distance'] . '</h4>' : '';

$return .= '</div>';    

    $return .= '<div class="search-first-content" style="position: relative;">';
    
    if ( $track ) {
        
        $return .= '<a style="overflow: hidden; content: ""; height: 100%;"
            class="search-img-wrapper " href="'.$value['page_url'].'?lat='.$latUser.'&lng='.$lngUser.'">';
    } else {
        
        $return .= '<a style="overflow: hidden; content: ""; height: 100%;"
            class="search-img-wrapper" href="'.$value['page_url'].'?lat=&lng=">';
    }
    
    
        $return .= '<img src="'.$get['path'].'_images/video_thumb_homepage_01.png" class="search-img" style="float: left;" />';
        $return .= '<img src="'.$get['path'].'_images/rsz_1overlay.png" class="search-img" style="position: absolute;" />';
    $return .= '</a>';

    
    $return .= '<div style="float: left; width: 65%;">';
    
        $return .= '<h2 class="wordFix">'.$businessName.'</h2>';
        
        $return .= '<p class="wordFix">'.$value['full_address'].'</p>';
        $return .= '<p class="wordFix">'.$value['phone'].'</p>';
        
        $return .= '<p class="wordFix">'.$value['real_content'].'</p>';
        //$return .= '<div class="search-clearfix"></div>';
        
        $return .= ( itSet( $value['tags'] ) ) ? '<p style="margin-top: 0.4em; border-bottom: solid 0.5em #ccc; display: inline-block;">Clivelive deals</p><p style="clear: both;">'.$value['tags'].'</p>' : '';
        $return .= '</div>';
    $return .= '</div>';
    
    


$return .= '<div class="clearfix"></div>';
$return .= '<div class="row search-bottom"><div>';
$return .= '<div class="col-xs-4"><a href="tel:'.$value['phone'].'">Call</a></div>';                    
$return .= '<div class="col-xs-4" style="text-align: center;">';

$return .= isDistance() ? '<a href="adr:'. urlencode( $value['full_address'] ) .'">Directions</a>' : '';

$return .= '</div>';        
$return .= '<div class="col-xs-4" style="text-align: right;"><a href='.$value['page_url'].'>Website</a></div>';
$return .= '</div></div>';

$return .= '</div></div>';



array_push($location, array(
    "businessName" => $businessName,
    "address" => $value['full_address'],
    "lat" => $value['lat'], 
    "lng" => $value['lng']
));
$i++;
    }
}

$time_end = microtime( true );
$time = $time_end - $time_start;

    

$_RETURN = array("get" => $get, "result" => $return, "time" => $time, "locationArray" => $location);
//$searchNoReturn 
if ( strlen( $_RETURN['result'] ) > 10 && count( $_RETURN['locationArray'] ) >= 1 ) {
    
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



//$return .= '<div class="divPre row searchPadding searchMargin">';
//    $return .= '<div class="col-xs-12">';
//
//$return .= '<a class="col-xs-5 col-sm-4" href="'.$value['page_url'].'?lat='.$latUser.'&lng='.$lngUser.'">';
//    $return .= '<img src="'.$get['path'].'_images/video_thumb_homepage_01.png" class="col-xs-12 img-responsive" />';
//$return .= '</a>';
//
//
//    
//    $return .= '<div class="col-xs-12 col-sm-6">';
//        $return .= '<h2 style="border-bottom: 0.2em solid #ccc; overflow: hidden; padding-bottom: 0.2em;">'.$businessName.'</h2>';
//        
//        $return .= '<address class="classSearch">'.$value['full_address'].'</address>';
//        $return .= '<p>'.$value['phone'].'</p>';
//        
//        $return .= '<p>'.$value['real_content'].'</p>';
//        $return .= ( itSet( $value['tags'] ) ) ? '<p>'.$value['tags'].'</p>' : '';
//    $return .= '</div>';
//    
//    $return .= '<div class="col-xs-2 col-sm-2">';
//        $return .= isDistance() ? '<h4>' . $value['distance'] . '</h4>' : '';
//        $return .= '<img src="http://www.google.com/mapfiles/marker' . ( ( isset( $_RAND_ ) ) ? '' : $m[$i] )  . '.png">';
//    $return .= '</div>';
//
//
//$return .= '<div class="clearfix"></div>';
//$return .= '<div class="row fixRow style="padding-top: 1.8em;"><div>';
//$return .= '<div class="col-xs-4"><a href="tel:'.$value['phone'].'">Call</a></div>';                    
//$return .= '<div class="col-xs-4" style="text-align: center;">';
//
//$return .= isDistance() ? '<a href="adr:'.$value['full_address'].'">Directions</a>' : '';
//
//$return .= '</div>';        
//$return .= '<div class="col-xs-4" style="text-align: right;"><a href='.$value['page_url'].'>Website</a></div>';
//$return .= '</div></div>';
//
//$return .= '</div></div>';

?>
