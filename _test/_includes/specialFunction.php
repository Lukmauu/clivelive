<?php
/*
if(isset($_GET['funcName']) || $_SERVER['REQUEST_METHOD'] === "GET"){
    getMeCities();
    exit;
}
function cmp3($a, $b)
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
    function isLocal(){
        $_return = FALSE;
        if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
            $_return = TRUE; 
        }
        return $_return;
    }
    require 'clivelive-conn.php';
    require 'func.php';
    $return = array();
    $duplicate = array();
    
    $q = "SELECT city, state, cityLat, cityLng FROM myclivelive ";
    
    $toFetch = mysql_query($q);
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
   usort($return, "cmp3");
  
    mysql_close();
    print_r( json_encode($return) );
}*/
function real_content($_user, $_url){
    
    $string = str_replace(" ::", ":", $_url);
    $string2 = str_replace(";;;;", "/", $string);
    
    return $string2;
}

function createArrayForRealContent ( $array_ ) {
    $firstArray = array(
        'cuisine',
        'atmosphere',
        'parking',
        'serving',
        'h h average price',
        'reservation',
        'neighborhood',
    );
    $secondArray = array();
    foreach ( $firstArray as $value ) {
        if ( isset( $array_[$value] ) ) {
            array_push( $secondArray, $array_[$value] );
        }
    }
}

function getRealContent ( $user_ ) { 
    
    $query_ = "SELECT cuisine, atmosphere, parking, serving, h_h_average_price, reservation, neighborhood
                FROM myclivelive WHERE user_name='{$user_}'";
    
    $result = fetchAssoc( $query_ );
    
    if ( !$result ) {
        
        exit( 'getRealContent did not work' );
    }
    
    foreach ( $result as $key => $value ) {
        
        if( is_null( $value ) || $value === '' ) {
            
            unset( $result[$key] );
        } 
    }
    
    $c = 1;
    $string = '';
    
    foreach ( $result as $key => $value ) {
        
        if ( count( $result ) > $c ) {
            
            $string .= $key . ' : ' . $value . ' / ';
        } else {
            
            $string .= $key . ' : ' . $value;
        }
        $c++;
    }
    echoPre( $string );
    return $string;
}

function buildFulltext($username, $id){
    
                    
    $details =  array(
                    0 => 'business_phone',
                    1 => 'business_name', 
                    2 => 'business_address', 
                    3 => 'cuisine', 
                    4 => 'atmosphere',
                    5 => 'parking',
                    6 => 'serving',
                    7 => 'h_h_average_price',
                    8 => 'reservation',
                    9 => 'neighborhood',
                    10 => 'description'
                );
    
    $_ID = sanitizeString( $id );
    $_user = sanitizeString( $username );
    $_url = mysql_real_escape_string( 'http://clivelive.com/' . $_user );
    
    $select = "SELECT * FROM myclivelive WHERE user_name='{$_user}'";
    $result = fetchAssoc( $select );
    $new_details = array();
    
    foreach ( $result as $key1 => $value1 ) {
        
        if ( !empty( $value1 ) && strlen( $value1 ) > 1 ) {
            
            foreach ( $details as $key2 => $value2 ) {
                
                if( $key1 == $value2 ) {
                    
                    $new_details[$key1] = $value1;
                }
            }
        } else {
            
            continue;
        }    
    }
    
    $result2 = selectQuery( $new_details, $result['user_name'], 'myclivelive' );
    
    if ( $result2 ) {
        
        foreach ( $result2 as $key => $value ) {
            
            if ( is_null($value) || $value == "" ) {
                
                unset( $result2[$key] );
            }
        }
     
        
        $fullText = '';
        $c = 1;
        foreach ( $result2 as $key => $value ) {
            
            $key = str_replace("_", " ", $key);
            
            if ( !is_null( $value ) && $value !== "" ) {
                
                if ( count( $result2 ) > $c ) {
                    
                    $fullText .= $key . ' :: ' . $value . ' ;;;; ';
                } else {
                    
                    $fullText .= $key . ' :: ' . $value;
                }
                $c++;
            } else {
                
                $c++;
                continue;
            }
        }
        
        $fullTextLast = sanitizeString($fullText);
    /* 
     *  wa_id, 
     *  user_name
     *  page_url, 
     *  page_content, 
     *  tags, 
     *  extra, 
     *  lat, 
     *  lng, 
     *  full_address, 
     *  img_src, 
     *  real_content 
     */
        // Passing a fake URL
        /* 
         * 
         *  NEED TO SHIP $result2 ARRAY
         * 
         *  */
        $IN = array(
            'wa_id' => $_ID,
            'user_name' => $_user,
            'page_url' => $_url,
            'page_content' => $fullTextLast,
            'tags' => $result['b_b_text'],
            'extra' => null,
            'lat' => $result['lat'],
            'lng' => $result['lng'],
            'full_address' => $result['business_address'],
            'img_src' => "_dummies/featured-3.jpg",
            'real_content' => $result['description'],
            'phone' => $result['business_phone'],
            'business_name' => $result['business_name']
        );
        
        $IN = desanitizePost( $IN );
        
        $RE = buildInsert( $IN, false, true );   
        
        try {
            
            $INq = queryMysql( $RE );
            
        } catch ( Exception $e ) {
            
            return false;
        }
        
        if ( $INq ) {
            
            return true;
        } else {
            
            return false;
        }
    }
}

?>