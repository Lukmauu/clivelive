<?php
function isLocal(){
    $_return = FALSE;
    if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
        $_return = TRUE; 
    }
    return $_return;
}
require '_includes/clivelive-conn.php';
require '_includes/authentication.php';
require '_includes/func.php';

function latLonInsert($username){
    $q = "INSERT INTO myclivelive SET lat=$lat, lon=$lon WHERE user_name='{$username}'";
}
    $sel = "SELECT lat, lon FROM myclivelive WHERE user_name='tandem'";
    $result = fetchAssoc($sel);
    echo json_encode($result);

?>
