<?php

/* VARS */
$itself = htmlspecialchars( $_SERVER['PHP_SELF'] );     

$msg_user_login = '';

$_part_path = partPath( $_SERVER['SCRIPT_NAME']) ;


$_part_href = '';
/* VARS */

function itSet ( $has ) {
    
    if ( isset( $has ) && strlen( $has ) > 1 ) {
        return true;
    }
    return false;
}
function cmp ( $a, $b ) {   
    $distA = $a['distance'];
    $distB = $b['distance'];
    if ( $distA == $distB ) {
        
        return 0;
    }
    return ( $distA < $distB ) ? -1 : 1;
    //return ($b < $a) ? -1 : 1;
}

function in_array_case_insensitive ( $needle, $haystack ) {
    
    return in_array( strtolower( $needle ), array_map( 'strtolower', $haystack ) );
}

function updateLatLng ( $array ) {
    
    $id = $array['wa_id'];
    $lat = $array['lat'];
    $lng = $array['lng'];
    $q = "UPDATE search SET lat='{$lat}', lng='{$lng}' WHERE wa_id='{$id}'";
    
    queryMysql($q);
}

function breakOnSpace ( $thing ) {
    
    return preg_split( '/\s+/', $thing );
}

function returnArrayQ () {
    
    $sel = "SELECT url FROM login WHERE user_name='tandem'";
    $url_array_all = array();
    $q = mysql_query( $sel );
    $c = 0;
    while ( $row = mysql_fetch_row( $q ) ) {
        
        if ( $c === 0 ) {
            
            $url_array_all[$c] = $row[0];
            $c = 1;
        } else {
            $url_array_all[$c] = $row[0];
            $c++;
        }    
    }
    
    return $url_array_all;
}

function selectQuery ( $array, $username, $table ) {
    
    $array = sanitizePost( $array );
    $username = sanitizeString( $username );
    $table = sanitizeString( $table );
    
    $query = "SELECT ";
    $c = 1;
    foreach ( $array as $key => $value ) {
        
        if ( count( $array ) > $c ) {
            
            $query .= $key . ", ";
        } else {
            
            $query .= $key;
        }
        $c++;
    }
    $query .= " FROM myclivelive WHERE user_name='{$username}'";

    $result = false;
    $result = fetchAssoc( $query );
    
    return $result;
}

function returnUser ( $thing ) {
    
    $query = "SELECT user_name FROM login WHERE user_name='{$thing}'";
    $result = mysql_fetch_assoc( mysql_query( $query ) );
    
    if ( !$result ) {
        
        return false;
    }
    return $result['user_name'];
}

function renameUser ( $olduser, $newuser ) {
    
    $oldUser = sanitizeString( $olduser );
    $newUser = sanitizeString( $newuser );
    $query1 = "UPDATE login SET user_name='{$newUser}' WHERE user_name='{$oldUser}'";
    $query2 = "UPDATE myclivelive SET user_name='{$newUser}' WHERE user_name='{$oldUser}'";
    $query3 = "UPDATE search SET user_name='{$newUser}' WHERE user_name='{$oldUser}'";
    
    if ( !mysql_query( $query1 ) ) {
        
        mysql_query( "UPDATE login SET user_name='{$oldUser}' WHERE user_name='{$oldUser}'" );
        return false;
    }
    
    if ( !mysql_query( $query2 ) ) {
        
        mysql_query( "UPDATE myclivelive SET user_name='{$oldUser}' WHERE user_name='{$oldUser}'" );
        return false;
    }
    
    if ( !mysql_query( $query3 ) ) {
        
        mysql_query( "UPDATE search SET user_name='{$oldUser}' WHERE user_name='{$oldUser}'" );
        return false;
    }
    
    $_SESSION['user_name'] = $newuser;
    return true;
}

function compName ( $thing1, $thing2 ) {
    
    $return = false;
    $result = strcasecmp( $thing1, $thing2 );
    
    if ( $result === 0 ) {
        
        $result = true;
    }
    return $return;
}

function userTaken ( $thing ) {
    
    $return = false;
    $q = "SELECT * FROM login WHERE user_name='{$thing}'";
    $result = fetchRow( $q );
    
    if ( count( $result ) > 1 && $result !== false ) {
        
        $return = true;
    }
    return $return;
    /* IF TRUE THERE IS SAME NAME ON DATABASE */    
    /* IF FALSE THERE IS NOT SAME NAME ON DATABASE */        
}

function echoPre ( $thing, $flag = false ) {
    
    if ( is_array( $thing ) ) {
        
        if ( !$flag ) {
            
            echo '<pre>'; print_r( $thing ); echo '</pre>'; exit;
        } else {
            
            echo '<pre>'; print_r( $thing ); echo '</pre>';
        }
    } else {
        
        if ( !$flag ) {
            
            echo '<h1>' . $thing . '</h1>'; exit;
        } else {
            echo '<h1>' . $thing . '</h1>';
        }
    }
}

function checkAjax ( $username, $password , $member = 'member' ) {
    
    $return = false;
    $ajaxQ = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$member}'";
    $fetched = fetchRow( $ajaxQ );
   
    if ( count( $fetched ) > 1  && $fetched !== false ) {
        
        $return = TRUE;
    }
    return $return;
}

function returnCheckAjax ( $username, $password , $member = 'member' ) {
    
    $ajaxQ = "SELECT * FROM login WHERE user_name='{$username}' AND password='{$password}' AND level='{$member}'";
    $fetched = fetchAssoc( $ajaxQ );
   
    if ( count( $fetched ) > 1  && $fetched !== false && is_array( $fetched ) ) {
        
        return $fetched;
    }
    return false;
}

function sanitizePost ( $post ) {
    
    if ( !is_array( $post ) ) {
        
        return false;
    }
    
    foreach ( $post as $key => $value ) {
        
        $post[$key] = sanitizeString( $value );
    }
    return $post;
}

function desanitizePost( $string ) {
    
    if ( !is_array( $string ) ) {
        
        return false;
    }
    
    foreach ( $string as $key => $value ) {
        
        $string[$key] = desanitizeString( $value );
    }
    return $string;
}

function buildInsert ( $keyArray, $duplicate = false, $searchQ = false ) {
    
    foreach ( $keyArray as $key => $value ) {
        
        if ( is_null( $value ) || $value === "" ) {
            
            $keyArray[$key] = NULL;
            /* unset ($keyArray[$key]); */
        }
    }
    
    $c = 1;
    $query = ""; 
    $query .= ( !$searchQ ) ? "INSERT INTO myclivelive " : "INSERT INTO search ";
    $query .= " ( ";
    
    foreach ( $keyArray as $key => $value ) {
        
            if ( count( $keyArray ) > $c ) {
                
                $query .= $key . ", ";
            } else {
                $query .= $key;
            }
        $c++;
    }
    $query .= " ) VALUES ( ";
    $v = 1;
    
    foreach ( $keyArray as $key => $value ) {
        
        if ( count( $keyArray ) > $v ) {
            
            if ( !is_numeric( $value ) ) {
                
                $query .= "'" . sanitizeString( $value ) . "', ";
            } else {
                
                $query .= "'" . sanitizeString( $value ) . "', ";
            }
        } else {
            
            if ( !is_numeric( $value ) ) {
                
                $query .= "'" . sanitizeString( $value ) . "'";
            } else {
                
                $query .= "'" . sanitizeString( $value ) . "'";
            }
        }
        $v++;
    }
    $query .= " )"; 
    $d = 1;
    
    if ( !$duplicate ) {
        
        $query .= " ON DUPLICATE KEY UPDATE ";
        
        foreach ( $keyArray as $key => $value ) {
            
            if ( count( $keyArray ) > $d ) {
                
                $query .= $key ." = values( " . $key . " ) ". ", ";
            } else {
                
                $query .= $key ." = values( " . $key . " ) ";
            }
            $d++;
        }
    }
    return $query;
}

function lastLogin ( $user ) {
    
    /* $date = date('l jS \of F Y h:i:s A'); */
    $query = "UPDATE login SET last_time_login=NOW() WHERE user_name='{$user}'";
    queryMysql( $query );
}

function fetchRow ( $thing ) {
    
    return mysql_fetch_row( queryMysql( $thing ) );
}

function fetchAssoc ( $thing ) {
    
    return mysql_fetch_assoc( queryMysql( $thing ) );
}

function returnUrl ( $url, $flag = false ) {
    
    $neddle = !$flag ? '/' : '@';
    //$var = explode( $neddle, $url );
    //echoPre( $var . '     ' . count( $var ) );
    return explode( $neddle, $url );
}

function partPath ( $url ) {
    
    $var = returnUrl( $url );
    
    $path = '';
    if ( count( $var ) > 3 ) {
        
        $path = '../../';
    } else if ( count( $var ) > 2 ) {
        
        $path = '../';
    } else if ( count( $var ) > 1 ){
        
        $path = '/';
    }
    return $path;
}

class msgError {
    protected $error = array();
    
    function msgError ( $msg = '' ) {
    
        if ( !strlen( $msg ) > 1 ) {
            
            /* special msg */
        } else {
            
            $this->error[count( $this->error )] = '<p>' . $msg . '</p>';
            return '';
        }
    }
    
    function returnMsgError () {
        
        return $this->error;
    }
    
    function eraseMsgError () {
        
        $this->error = array();
    }
}

function validate ( $input, $type, $len = null, $chars = null ) {
    
    /*  validate user input
     *  $input: variable to be validated
     *  $type: nofilter, alpha, numeric, alnum, email, url, ip
     *  $len: maximum length
     *  $chars: array  any non alpha-numeric characters to allow
     */
    $tmp = str_replace( ' ', '', $input );
    
    if ( !empty( $tmp ) ) {
        
        if ( isset( $len ) ) {
            
            if ( strlen( $input ) > $len ) {
                
                return false;
            }
        }
        
        if ( isset( $chars ) ) {
            
            $input = str_replace( $chars, '', $input );
        }
        $input = str_replace( ' ', '', $input );
 
        switch ( $type ) {
            
            case 'alpha' :
                
                if ( !ctype_alpha( $input ) ) {
                    
                    return false;
                }
            break;
 
            case 'numeric' :
                
                if ( !ctype_digit( $input ) ) {
                    
                    return false;
                }
            break;
 
            case 'alnum' :
                
                if ( !ctype_alnum( $input ) ) {
                    
                    return false;
                }
            break;
 
            case 'email' :
                
                if ( !filter_var( $input, FILTER_VALIDATE_EMAIL ) ) {
                    
                    return false;
                }
            break;
 
            case 'url' :
                
                return true;
                /*  This url validation does not work   */
                $input = preg_replace("
                    #((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
                    "'<a href=\"$1\" target=\"_blank\">$3</a>$4'", $input);
                /* !filter_var($input, FILTER_VALIDATE_URL) */
                
                if ( !$input ) {
                    //return FALSE;
                    return true;
                }
            break;
 
            case 'ip' :
                
                if ( !filter_var( $input, FILTER_VALIDATE_IP ) ) {
                    
                    return false;
                }
            break;
 
            case 'nofilter' :
                
                return true;
            break;
        }
        
        return true;
    } else {
        
        return false;
    }
}
/* 
 
    example use:
 
    $phone = isset($_POST['phone']) &amp;&amp; validate($_POST['phone'], 'numeric', 20, array('(',')','-')) ? $_POST['phone'] : null;
    $email_addr = isset($_POST['email_addr']) &amp;&amp; validate($_POST['email_addr'], 'email', 255) ? $_POST['email_addr'] : null;
    $msg = isset($_POST['msg']) &amp;&amp; validate($_POST['msg'], 'nofilter') ? $_POST['msg'] : null;
 
 */

function urlCheck ( $post ) {
    
    if ( isset( $post ) && strlen( $post ) > 10 ) {
        
        if ( validate( $post, 'nofilter' ) ) {
            
            return true; 
        } else {
            
            return false;
        }
    } else {
        
        return false;
    }
}

function queryMysql ( $query ) {
    
    $result = mysql_query( $query );
    
    if ( !$result || count( $result ) <= 0 ) {

        /*
            *      MYSELF **** I HAVE mysql_error() ONLY FOR DEBBUG,
            *      WHEN LIVE REMOVE IT.
            */

        //echoPre( 'THE QUERY DID NOT WORKED <br/> ' . mysql_error() . '<br/>' . mysql_errno() );
    }
    return $result;
}

function destroySession () {
    
    $_SESSION = array();

    if ( session_id() !== "" || isset( $_COOKIE[session_name()] ) ) {
        
        setcookie( session_name(), '', time() - 2592000, '/' );
        
        session_destroy();
    }
}

function sanitizeString ( $var ) {

    $var = strip_tags( $var );
    $var = htmlentities( $var );
    $var = stripslashes( $var );
    return mysql_real_escape_string( $var );
}

function desanitizeString ( $var ) {
    
    $var = html_entity_decode( $var );
    return stripcslashes( $var );
}
?>