<?php

ob_start();
require '_includes/session.php';
require '_includes/clivelive-conn.php';

require '_includes/func.php';

$return = array( 'result' => false, 'msg' => false );

if ( isset( $_POST['lat'] ) && $_POST['lng'] ) {
    
    $return_ = array( 'return' => 'none' );
    
    if ( !insertGeo( $_POST['lat'], $_POST['lng'], $_POST['user']) ) {
        
        $return['return'] = false;
        echo json_encode( $return );
    }else{
        
        $return['return'] = true;
        echo json_encode( $return );
    }
    exit;
}

function insertGeo ( $lat, $lng, $user ) {
    
    $lat = sanitizeString( $lat );
    $lng = sanitizeString( $lng );
    $user = sanitizeString( $user );
    
    $q2 = "UPDATE myclivelive SET lat='{$lat}', lng='{$lng}' WHERE user_name='{$user}'";
    $in = queryMysql( $q );
    
    if ( $in ) {
        
        return true;
    }
    return false;
}    

if( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
    
    header("Location: profile.php");exit;
}

if ( !isset( $_POST['new_password'] ) && !isset( $_POST['user_name'] ) ) {
    
    header("Location: index.php");exit;
}

require '_includes/authentication.php';

$mainFolder = isLocal() ? '' : '';
$post = sanitizePost( $_POST );
$user = $post['user_name'];

if ( isset( $post['url'] ) ) {
    
    $urlQ  = "UPDATE login 
                SET url='{$post['url']}' 
                WHERE user_name='{$post['user_name']}'";
    $result = mysql_query( $urlQ );
    
    if ( !$result ) {
        
        $return['result'] = false;
    } else {
        
        $return['result'] = true;
    }
    
    echo json_encode( $return );exit;
}

if ( isset( $post['new_password'] ) ) {
    
    $newPassword = $post['new_password'];
    
    $updatePasswordQuery = "UPDATE login SET password='{$newPassword}' WHERE user_name='{$user}'";
    
    $selectPasswordQuery = "SELECT * FROM login WHERE user_name='{$user}'";

    $fetchedPasswordQuery = fetchAssoc( $selectPasswordQuery );
    
    $oldPassword = $fetchedPasswordQuery['password'];
    
    if ( $newPassword !== $oldPassword ) {
        
        $result = mysql_query( $updatePasswordQuery );
        
        if ( !$result ) {
            
            $return['result'] = false;
            $return['msg'] = 'WRONG';
        } else {
            
            $return['result'] = true;
            $return['msg'] = 'TRUE';
        }
    } else {
        
        $return['result'] = false;
        $return['msg'] = 'FALSE';
    }
    
    echo json_encode( $return );exit;
}

if ( isset( $post['new_username_two'] ) ) {
    
    $oldName = $user;
    
    $databaseName = returnUser( $oldName );
    
    $newName = $post['new_username_two'];
    
    $root = isLocal() ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'] . '/';
    
    $newPath = $root . $newName;
    
    $oldPath =  $root . $oldName;
    
    chmod( $oldPath, 777 );
    if ( !$databaseName ) {
        
        $return['result'] = false;
        $return['msg'] = 'FALSE';    
    } else if ( !userTaken( $newName ) && compName( $databaseName, $newName ) ) {
        
        $return['result'] = false;
        $return['msg'] = 'TOOK';
    } else if ( compName( $databaseName, $newName ) ) {
        
        $return['result'] = false;
        $return['msg'] = 'TOOK';    
    } else if ( !rename( $oldPath, $newPath ) ) {
        
        chmod( $oldPath, 644 );
        $return['result'] = false;
        $return['msg'] = 'FAIL';
    } else {
       
        if ( renameUser( $oldName, $newName ) ) {
            
            chmod( $newPath, 644 );
            $return['result'] = true;
            $return['msg'] = 'TRUE';
            $return['user'] = $newName;
        } else {
            
            if ( !rename( $newPath, $oldPath ) ) {
                
                chmod( $newPath, 644 );
                $return['result'] = false;
                $return['msg'] = 'FAIL';    
            }
        }
    }
    
    echo json_encode( $return );exit;
}

?>
<?php mysql_close( $conn ); ?>