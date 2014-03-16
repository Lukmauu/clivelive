<?php
ob_start();
require '_includes/session.php';
require '_includes/clivelive-conn.php';
require '_includes/func.php';
    
    function main( $username, $password, $member='member' ) {
        
        $query = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$member}'";
        
        $queryQuery = mysql_query( $query );
        
        $fetchedReturn = mysql_fetch_assoc( $queryQuery );
        
        if( !$fetchedReturn ) {
            
            return false;
        } else {
            
            return $fetchedReturn;
        }
    }    
    
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['password'] ) ) {
        
        $post = sanitizePost( $_POST );
        $ajax = isset( $post['ajax'] ) === true ? true : false;
        $return = array( 'result' => false );
        $result = isset( $post['member'] ) ? 
                            main( $post['user_name'], $post['password'], $post['member'] ) : 
                            main( $post['user_name'], $post['password'] );
                    
        if( !$result ) {
            
            $_SESSION = array();
            $return['result'] = false;
            echo json_encode( $return );
        } else {
            
            $_SESSION = $result;
            if ( $ajax ) {
                
                $return['result'] = true;
                echo json_encode( $return );
            } else {
                
                if ( isset( $post['member'] ) ) {
                    
                    header("Location: login.php"); exit;
                } else {
                    
                    header("Location: login-admin.php"); exit;
                }
            }
        }
    }
?>
    