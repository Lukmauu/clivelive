<?php
error_reporting(E_ALL);


function isLocal(){
    $_return = FALSE;
    if( $_SERVER['SERVER_NAME'] === '127.0.0.1' ) {
        $_return = TRUE; 
    }
    return $_return;
}

/*"/home/users/web/b1593/ipg.lucasoliveira200com/cgi-bin/tmp/"; */
/*"C:\Windows\/Temp\/"*/
$_SESSION_URL = ( isLocal() ) ? 
        "C:\wamp\/tmp" : 
        "/tmp";
session_save_path( $_SESSION_URL ); 
session_start();
?>