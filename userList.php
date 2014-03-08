<?php
error_reporting(E_ALL);

function isLocal(){
    
    if ( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) {
        
        return true;
    }
    return false;
}

require '_includes/clivelive-conn.php'; 
require '_includes/authentication.php';
require '_includes/func.php';

echoPre( 'List of users', true );

        $q = "SELECT user_name FROM login";
        
        $array = array();
        $query = mysql_query( $q );
        
        while ( $row = mysql_fetch_assoc( $query ) ) {
            
            array_push($array, $row);
        }
        
        $string = '';
        $c = 1;
        foreach ( $array as $value ) {
            
        
            $string .= '    <h1><strong>' . $c . ':  </string>' . $value['user_name'] . '</h1>';
            $c = $c + 1;
        }
        
        echo $string;
?>