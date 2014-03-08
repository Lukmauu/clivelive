<?php
error_reporting(E_ALL);

function isLocal(){
    $_return = FALSE;
    if( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) {
        $_return = TRUE; 
    }
    return $_return;
}

require '_includes/clivelive-conn.php';
require '_includes/func.php';

/*foreach ( $_POST as $value ) {
    
    
    $n = null;
    $s = 'WA';
    $city = isset( $value['city'] ) ? $value['city'] : null;
    $lat = isset( $value['cityLat'] ) ? $value['cityLat'] : null;
    $lng = isset( $value['cityLng'] ) ? $value['cityLng'] : null;
    
    $string = "INSERT INTO cityLatLng 
            VALUES ('{$n}', '{$city}', '{$lat}', '{$lng}', '{$s}')"; 
    
    mysql_query( $string );
}*/
$c = 1;

$string = '';

$userName = $_GET['user'];

$query = "SELECT cuisine, atmosphere, parking, serving, h_h_average_price, reservation, neighborhood FROM myclivelive WHERE user_name='{$userName}' LIMIT 1";

$quered = mysql_query( $query );
if ( !$quered ) {
    
    echoPre( mysql_error() . '    ' . mysql_errno() );
}


$feched = mysql_fetch_assoc( $quered );
    

if ( !$feched ) {
    
    echoPre( mysql_error() . '    ' . mysql_errno() );
}

foreach ( $feched as $key => $value ) {
        
        if ( is_null( $value ) || $value === '' ) {

            unset ( $feched[$key] );
        }
        
}

foreach ( $feched as $key => $value ) {
        
            if ( count( $feched ) > $c ) {
                
                if ( $key === 'h_h_average_price' ) {
            
                    $string .= 'Happy Hours' . ': ' . $value . '  /  ';
                } else {
                    
                    $string .= ucfirst( $key ) . ': ' . $value . '  /  ';
                }
                
            } else {
                $string .= ucfirst( $key ) . ': ' . $value;
            }
        $c++;
}

mysql_query( "UPDATE myclivelive SET description='{$string}' WHERE user_name='{$userName}'" );
mysql_query( "UPDATE search SET real_content='{$string}' WHERE user_name='{$userName}'" );

echoPre( mysql_error() . '    ' . mysql_errno() . ' final stage', true );
echoPre( $string );
/*
 * 

 * somebody
 * mynewbar
 * mustardy
 * 520bar
 * blue
 * redbar
 * 
 */










