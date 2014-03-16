<?php


if(!isLocal()){
    $hostname   = "clivelive.db.9415436.hostedresource.com";
    $username   = "clivelive";
    $dbname     = "clivelive";
    $password   = "Lucas@006024";
    
    $conn = mysql_connect($hostname, $username, $password) 
                or die("Unable to connect! Please try again later.");
    
    mysql_select_db($dbname)
        or die("Unable to connect to database! Please try again later.");
} else {
    //$dbName = 'clivelive_members';
    //$conn = mysql_connect('127.0.0.1', 'lmovvt', '/Maid_006024')
                //or die("Unable to connect! Please try again later.");
    
    //mysql_select_db($dbName) 
        //or die("Unable to connect to database! Please try again later.");
    
    define( 'CONN', mysqli_connect( '127.0.0.1', 'lmovvt', '/Maid_006024' ) );
    
    if ( !$_CONN ) { die( "Unable to connect! Please try again later." ); }
    
    mysqli_select_db($dbName) 
        or die( "Unable to connect to database! Please try again later." );
    
    
                //or die("Unable to connect! Please try again later.") )
}
?>


