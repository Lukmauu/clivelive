<?php
ob_start();
require '_includes/session.php';
require '_includes/clivelive-conn.php';
require '_includes/func.php';
    
    function main( $username, $password, $member='admin' ) {
        
        $thing = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$member}'";
        
        $result = fetchAssoc( $thing );
        
        return $result;
    }
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['password'] ) ) {
        
        $post = array();
        $post = sanitizePost( $_POST );
        $ajax = false;
        if ( isset( $post['ajax'] ) && $post['ajax'] === 'yes' ) {
            
            $ajax = true;
        }
        
        $result = main( $post['user_name'], $post['password'] );
        
        if( !$result && !is_array($result) ) {
            
            $_SESSION = array();
            echo 'FALSE';
        } else {
            
            $_SESSION = $result;
            if ( $ajax ) {
                
                echo 'TRUE';
            } else {
                
                header("Location: login-admin.php");
            }
        }
    }
?>
    