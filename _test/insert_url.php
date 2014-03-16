<?php
function isLocal () {

    if ( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) {
        
        return true;
    }
    return false;
}

require '_includes/clivelive-conn.php';
require '_includes/func.php';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST ) ) {
    
    $return = array( 'result' => false );
    
    $post = sanitizePost($_POST);
    
    $q  = "UPDATE clivelive_members.login 
            SET url='{$post['url']}' 
            WHERE user_name='{$post['user_name']}'";
    
    $result = mysql_query($q);
    
    if ( $result ) {
        
        $return['result'] = true;
        
    } else {
        
        $return['result'] = false;
    }
    
    echo json_encode( $return );
    
} else {
    
    header("Location: index.php");exit;
}
?>

