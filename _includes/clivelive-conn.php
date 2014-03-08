
<?php
if(!isLocal()){
    
    /* box648.bluehost.com */
    $hostname   = "localhost";
    $username   = "cliveliv_admin";
    $dbname_server     = "cliveliv_main";
    $password   = "Lucas@006024";
    $conn_server = mysql_connect($hostname, $username, $password) 
                or die("Unable to connect! Please try again later." . mysql_error());
    
    mysql_select_db($dbname_server)
        or die("Unable to connect to database! Please try again later.");
} else {
    
    $dbName = 'clivelive_members';
    $conn = mysql_connect('127.0.0.1', 'lmovvt', '/Maid_006024')
                or die("Unable to connect! Please try again later.");
    
    mysql_select_db($dbName) 
        or die("Unable to connect to database! Please try again later.");
}
?>
