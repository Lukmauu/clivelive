<?php
function isLocal(){
    $_return = FALSE;
    if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
        $_return = TRUE; 
    }
    return $_return;
}

require '../_includes/clivelive-conn.php';
require '../_includes/func.php';
require '../_includes/authentication.php';

$get = sanitizePost($_GET);
$return = false;
$return = fetchAssoc("SELECT * FROM myclivelive WHERE user_name='{$get['user']}'");
if($return){
    
    echo json_encode($return);
    
}
?>