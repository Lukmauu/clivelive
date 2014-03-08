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
$lat = sanitizeString($_GET['lat']);
$lng = sanitizeString($_GET['lng']);
$user = sanitizeString($_GET['user']);

$q = "UPDATE search SET lat='{$lat}', lng='{$lng}' WHERE user_name='{$user}'" ;
$r = mysql_query($q);
echo $r;
?>