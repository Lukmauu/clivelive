<?php
ob_start();
/*function isLocal () {
    
    if ( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) {
        
        return true;
    }
    return false;
}*/
require '_includes/session.php';
require '_includes/clivelive-conn.php';
require '_includes/func.php';

if ( $_SERVER['REQUEST_METHOD'] === "POST" && isset( $_POST['password'] ) ) {
    
    $post = sanitizePost( $_POST );
    
    $username = ( isset( $post['user_name'] ) && validate( $post['user_name'], 'nofilter') ) ? 
                    $post['user_name'] : '';
    $password = ( isset( $post['password'] ) && validate( $post['password'], 'nofilter' ) ) ? 
                    $post['password'] : '';
    
           
    $msg = array( 'result' => false);
    $check = returnCheckAjax( $username, $password );
    
    if ( $check !== false && is_array( $check ) ) {
        
        $_SESSION = $check;
        $msg['result'] = true;
    }
    echo json_encode( $msg );
}
?>