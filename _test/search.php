<?php

$time_start = microtime( true );

function isLocal () { 
    
    if ( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) { 
        
        return true;
    }
    return false;
} 

require '_includes/clivelive-conn.php';
require '_includes/func.php';

function merge ( $array1, $array2 ) {
    
    $length = count( $array1 );
    foreach ($array2 as $value) {
        $array1[$length] = $value;
        $length++;
    }
    return $array1;
}


function getBusinessName ( $_business_name_ ) {
    
    $search = "business name: ";
    $array = explode( "/", $_business_name_ );
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

// main function to calc distance
function distance ( $lat1, $lon1, $lat2, $lon2 ) {
    
    $theta = $lon1 - $lon2;
    return rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)))) * 60 * 1.1515;
}


function getResultsRandom () {
    
    $array = array();
    $i = 0;
    $temp = mysql_query( "SELECT * FROM search" );
    while ( $row = mysql_fetch_assoc( $temp ) ) {
        
        $array[$i] = $row;
        $i++;
    }
    return $array;
}

$_REL = false;
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

$_TRACK = $get['track'] === 'false' ? false : true;
$_NOVALUE = $get['track'] === 'false' ? false : true;
$_GEO = $get['sortBy'] === "geo" ? $get['sortBy'] : false;

function isDistance () {
    
    global $get;
    return ( ( $get['noValue'] !== 'true' || $get['sortBy'] === 'geo' ) && strlen( $get['lat'] ) > 5 );
}

if ( !$_NOVALUE ) {    
    
    $_temp1 = breakOnSpace( $get['near'] );
    $_temp2 = breakOnSpace( $get['q'] );
    $tokens = merge( $_temp1, $_temp2 );
    
    //array_map( 'trim', $tokens );
    
    /* CLEAN NULL */
    foreach ( $tokens as $key => $value ) {
        
        if ( $value === " " || $value === "" || $value === "  " ) {
            
            unset( $tokens[$key] );
        } else if ( is_null( $value ) ) {
            
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
            
            $match .= sanitizeString( ( !$_GEO && $get['sortBy'] === 'near' ) ? "+" . $token . "* " : $token . "* " );
            $match2 .= sanitizeString( "" . $token . " " );
        } else {
            
            array_push( $like, sanitizeString( "%" . $token . "%" ) );
        }
    }

    if( strlen( $match ) > 0 ) {
        
        $match = substr( $match, 0, -1 );
        $match2 = substr( $match2, 0, -1 );
        $matchQ = "SELECT *, MATCH (page_content) 
                    AGAINST ('{$match}') 
                    AS relevance FROM search WHERE MATCH (page_content) 
                    AGAINST ('{$match2}' IN BOOLEAN MODE)
                    ORDER BY relevance DESC";
    }

    if ( count( $like ) > 0 ) {
        
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

    if ( $matchQ ) {                

        $matchQ_prep = mysql_query( $matchQ );
        
        if ( $matchQ_prep ) {
            
            while ( $row = mysql_fetch_assoc( $matchQ_prep ) ) {
                
                array_push( $_array_match, $row );
            }
        } else {
            
            $_REL = true;
            $searchNoReturn = true;
        }
    }

    if ( $likeQ ) {
        
        $likeQ_prep = mysql_query( $likeQ );

        if ( $likeQ_prep ) {
            
            while( $row3 = mysql_fetch_assoc( $likeQ_prep ) ) {
                
                array_push( $_array_like , $row3 );
            }
        } else {
            
            $searchNoReturn = true;
        }
    }

    if ( count( $_array_match ) <= 0 && count( $_array_like ) <= 0 ) {
        
        $searchNoReturn = true;
    } else {
        
        $_ARRAY_RESULTS = merge( $_array_match, $_array_like );
        $searchNoReturn = false;
    }
    
    /* if there is no result and no GEO do a random search */
    if ( $searchNoReturn ) {
        
        $_ARRAY_RESULTS = getResultsRandom();
        shuffle( $_ARRAY_RESULTS );
    }
} else {
    
    $_ARRAY_RESULTS = getResultsRandom();
    shuffle( $_ARRAY_RESULTS );
}


$_DUPLICATE = array();

$darkvader = fetchAssoc( "SELECT * FROM search WHERE user_name='darkvader'" );

if ( $_GEO ) {
        
    $darkvader["distance"] = $_TRACK ? haversineDistance( $latUser, $lngUser, $darkvader["lat"], $darkvader["lng"]) : false; 
}

foreach ( $_ARRAY_RESULTS as $key => $value ) {
    
    if ( 
            !empty( $_DUPLICATE) && 
            in_array( $value["wa_id"], $_DUPLICATE ) || 
            $value['user_name'] === 'darkvader' 
        ) {
        
        unset($_ARRAY_RESULTS[$key]);
        continue;
    }
    
    // add unique value 
    $_DUPLICATE["wa_id"] = $value["wa_id"];
    
    // add distance 
    if ( $_GEO ) {
        
        $_ARRAY_RESULTS[$key]["distance"] = $_TRACK ? haversineDistance($latUser, $lngUser, $_ARRAY_RESULTS[$key]["lat"], $_ARRAY_RESULTS[$key]["lng"]) : false; 
    }
}




if ( $_GEO ) {
    
    if ( $get['sortBy'] === 'near' && $get['q'] === '' ) {

        usort( $_ARRAY_RESULTS, 'cmp' );
    } else if ( !$_GEO && $get['sortBy'] === 'near' && $get['q'] !== '' ) {

        if ( $_REL ) {

            array_multi_sort( $_ARRAY_RESULTS, 'relevance', 'distance' );  
        } else {
            
            usort( $_ARRAY_RESULTS, 'cmp' );
        }
        
    } 
} else if ( $_REL ) {

    usort( $_ARRAY_RESULTS, 'cmpR' );
}

$location = array();

array_unshift( $_ARRAY_RESULTS, $darkvader );

foreach ( $_ARRAY_RESULTS as $key => $value ) {
    
    $_ARRAY_RESULTS[$key] = desanitizePost( $value );
}

foreach ( $_ARRAY_RESULTS as $value ) {
    
    $businessName = $value['business_name'];

    array_push( $location, array(
        "businessName" => $businessName,
        "address" => $value['full_address'],
        "lat" => $value['lat'], 
        "lng" => $value['lng']
    ));
}

//echoPre($_SERVER);
$time_end = microtime( true );
$time = $time_end - $_SERVER['REQUEST_TIME'];;

    
// $return
$_RETURN = array("get" => $get, "result" => $_ARRAY_RESULTS, "time" => $time, "locationArray" => $location);
//$searchNoReturn 
if ( count( $_RETURN['result'] ) > 10 ) {
    
    $_RETURN["searchNoReturn"] = false;
    echo json_encode( $_RETURN );
} else {
    $_RETURN["searchNoReturn"] = true;
    echo json_encode( $_RETURN );
}

?>