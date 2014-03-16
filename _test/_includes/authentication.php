<?php
// Description of authentication
// @author lmovvt


    

class checkSession {
    function ifresult($param) {
        /*
         * count() WILL RETURN 1, IF COMES EMPTY
         */
        if( count($param) > 1) {
            $_SESSION = $param;
            //lastLogin($_SESSION['user_name']);
            header("location: profile.php"); 
        }
        else {
            $_r_msg = "Authentication failed";
            return $_r_msg;
        }
    }
    
};
class authentication {
    function ajaxCheck($username, $password, $level='member'){
        $thing = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$level}'";
        $result = fetchAssoc($thing);
        if(count($result) > 1){
            return;
        } else {
            echo "FALSE";
        }
    }
    function highCheck($username, $password, $level='member', $ajax='no'){
        
        $result = fetchAssoc( "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$level}'" );
        
        $this->ifresult($result, $ajax);
        
        return $this;
    }
    function ifresult($param, $ajax) {
        
        if( count($param) > 1 ){
            if($ajax === 'no'){
                $_SESSION = array();
                $_SESSION = $param;
                lastLogin($param['user_name']);
                header("location: profile.php");exit;
            } else {
                return TRUE;
            }
        }
        else {
            return FALSE;
        }
    }
    public function quickCheck ( $username, $password ) {
        
        $result = mysql_query( "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}'" );

        if( !$result ) {
            
            return false;
        } else {
            
            return true;
        }
    }
    public function checkReturn ( $username, $password, $id ) {
        
        $toFetch = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND id='{$id}'";

        $result = fetchAssoc( $toFetch );
    
        if ( $result && count( $result ) > 1 ) {
            
            return $result;
        } else {
            
            return false;
        } 
    }
}

class lowAuthentication {
    function main($username, $password, $member='member'){
        
        $thing = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$member}'";
        
        $result = fetchAssoc( $thing );
        
        return $result;
    }
    function query($dbnane, $tbname, $fieldname, $fieldvalue, $flag=FALSE){
        $dbnane = mysql_real_escape_string($dbnane);
        $tbname = mysql_real_escape_string($tbname);
        $fieldname = mysql_real_escape_string($fieldname);
        
        if(!$flag){
            $fetch = fetchAssoc("SELECT * FROM $tbname WHERE $fieldname='{$fieldvalue}'");
        
            if(count($fetch) > 1){
                return $fetch;
            } else { 
                return FALSE;
            }
        }
        
        
    }
}
class bringProfile {
    function test($value){
        if($value){
            return true;
        }else{
            return false;
        }
    }
    function bringProfile($email){
        $result = mysql_fetch_assoc(queryMysql("SELECT * FROM myclivelive WHERE email='{$email}'"));
    
        if($this->test($result)){
            return $result;
        }else{
            return '';
        }
    }
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$autolow = new lowAuthentication();
?>
