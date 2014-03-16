<?php 
    require '_includes/session.php'; 
    require '_includes/clivelive-conn.php' ;
    require '_includes/authentication.php'; 
    require '_includes/func.php'; 
    require '_includes/specialFunction.php'; 

$return = array( 'result' => false );

function checkFirstTime ( $username ) {
    
    $thing = "SELECT * FROM myclivelive WHERE user_name='{$username}'";
    
    $result = fetchRow( $thing );
    
    if( count( $result ) > 1 && $result !== false ) {
        
        return true;
    }
    return false;
}

$IN = array();
$auto = new authentication();
$MY_CLIVE_LIVE = false;
$ERROR = new msgError();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    
    foreach ( $_POST as $key => $value ) {
        
        $_POST[$key] = sanitizeString( $value );
        
        if ( $key === ( 'facebook' || 'googleplus' || 'twitter' ) ) {
            
            if ( !empty( $_POST[$key] ) ) {
                
                if ( !validate( $_POST[$key], 'nofilter' ) && !strlen( $_POST[$key] ) > 10 ) {
                    
                    $_POST[$key] = array( 'msg'=>'error', 'value'=>$value );
                }
            }
        }
    }
    
    
    $emailResult = returnUrl( $_POST['email'], true );
    
    $resultFF = checkFirstTime( $emailResult[0] );
    
    if ( !$resultFF ) {
        
        $MY_CLIVE_LIVE = true;
    }
    
    $neddleAddress = explode( ",", $_POST['business_address'] );
    
    $_allAddress = ( count( $neddleAddress ) > 1 ) ?
                    $_POST['business_address'] : 
                    $_POST['business_address'] . 
                    ", " .
                    $_POST['business_address_city'] .
                    ", " .
                    $_POST['state'];
    
    
    $IN['member_id'] = null;
    $IN['wa_id'] = $_POST['id'];
    $IN['first_name'] = $_POST['first_name'];
    $IN['last_name'] = $_POST['last_name'];
    $IN['user_name'] = $emailResult[0];
    
    $IN['business_phone'] = ( isset( $_POST['business_phone'] ) && 
                            ( !empty( $_POST['business_phone'] ) && strlen( $_POST['business_phone'] ) > 9 ) ) ?
                            $_POST['business_phone'] : 
                            $ERROR->msgError( 'THIS IS NOT VALIDADE PHONE NUMBER FORMAT<br />'.$_POST['business_phone'] );
    
    $IN['business_name'] = ( isset( $_POST['business_name'] ) && 
                            validate( $_POST['business_name'], 'nofilter' ) ) ?
                            $_POST['business_name'] : 
                            $ERROR->msgError( 'THE BUSINESS NAME MUST BE PROVIDE, STRANGE OR NOT GIVE VALUE<br />'.$_POST['business_name'] );
    
    $IN['business_address'] = ( isset( $_POST['business_address'] ) && 
                            validate($_POST['business_address'], 'nofilter')) ?
                            $_allAddress : 
                            $ERROR->msgError( 'THE BUSINESS ADDRESS MUST BE PROVIDE, STRANGE OR NOT GIVE VALUE<br />'.$_POST['business_address'] );
    
    $IN['city'] = ( !empty( $_POST['business_address_city'] ) && 
                    strlen( $_POST['business_address_city'] ) > 2 ) ? 
                    $_POST['business_address_city'] : 
                    $ERROR->msgError( 'THE BUSINESS CITY MUST BE PROVIDE, STRANGE OR NOT GIVE VALUE<br />'.$_POST['business_address_city'] );    
    
    $IN['state'] = $_POST['state'];
     
    $IN['lat'] = isset( $_POST['lat'] ) ? 
                $_POST['lat'] : null;
    
    $IN['lng'] = isset( $_POST['lng'] ) ? 
                $_POST['lng'] : null;
    
    $IN['cityLat'] = isset( $_POST['cityLat'] ) ? 
                $_POST['cityLat'] : null;
    
    $IN['cityLng'] = isset( $_POST['cityLng'] ) ? 
                $_POST['cityLng'] : null;
    
    $IN['cuisine'] = ( isset( $_POST['cuisine'] ) && 
                    validate( $_POST['atmosphere'], 'nofilter') ) ?
                    $_POST['cuisine'] : '';
    
    $IN['atmosphere'] = ( isset( $_POST['atmosphere'] ) && 
                        validate( $_POST['atmosphere'], 'nofilter') ) ?
                        $_POST['atmosphere'] : '';
    
    $IN['parking'] = ( isset( $_POST['parking'] ) && 
                    validate( $_POST['parking'], 'nofilter' ) ) ?
                    $_POST['parking'] : '';
    
    $IN['serving'] = ( isset( $_POST['serving'] ) && 
                    validate( $_POST['serving'], 'nofilter' ) ) ?
                    $_POST['serving'] : '';
    
    $IN['h_h_average_price'] = ( isset( $_POST['h_h_average_price'] ) && 
                            validate( $_POST['h_h_average_price'], 'nofilter' ) ) ?
                            $_POST['h_h_average_price'] : '';
    
    $IN['reservation'] = ( isset( $_POST['reservation'] ) && 
                        validate( $_POST['reservation'], 'nofilter' ) ) ?
                        $_POST['reservation'] : '';
    
    $IN['neighborhood'] = ( isset( $_POST['neighborhood'] ) && 
                            validate( $_POST['neighborhood'], 'nofilter' ) ) ? 
                            $_POST['neighborhood'] : '';
    
    $IN['b_b_title'] = ( isset( $_POST['b_b_title'] ) && 
                        validate( $_POST['b_b_title'], 'nofilter' ) ) ?
                        $_POST['b_b_title'] : '';
    
    $IN['b_b_text'] = ( isset( $_POST['b_b_text'] ) && 
                        validate( $_POST['b_b_text'], 'nofilter' ) ) ?
                        $_POST['b_b_text'] : '';
    
    $IN['main_image'] = ( isset( $_FILES['main_image'] ) ) ? 
                        $_FILES['main_image'] : '';
    
    $IN['main_email'] = $_POST['email'];
    
    $IN['second_email'] = '';
    
    $IN['social_facebook'] = ( !is_array( $_POST['facebook'] ) ) ? 
                            $_POST['facebook'] : 
                            $ERROR->msgError( 'THE URL TYPED IN FACEBOOK FIELD IS NOT VALIDADE<br />'.$_POST['facebook'] );
    
    $IN['social_googleplus'] = ( !is_array( $_POST['googleplus'] ) ) ?
                                $_POST['googleplus'] : 
                                $ERROR->msgError( 'THE URL TYPED IN GOOGLE PLUS FIELD IS NOT VALIDADE<br />'.$_POST['googleplus'] );
    
    $IN['social_twitter'] = ( !is_array( $_POST['twitter'] ) ) ?
                            $_POST['twitter'] : 
                            $ERROR->msgError( 'THE URL TYPED IN TWITTER FIELD IS NOT VALIDADE<br />'.$_POST['twitter'] );
    
    $IN['description'] = ( is_null( $_POST['description'] ) || strlen( $_POST['description'] ) < 50 || strlen( $_POST['description'] ) > 250 ) ?
                        $ERROR->msgError( 'THE MINIMUM CHARS IS 50 AND MAX 150<br />'.$_POST['description'] ) :                                    
                        $_POST['description'];
   
    
    $re = buildInsert( $IN );
    //updateLatLng($IN);
    
    
    if ( count( $ERROR->returnMsgError() ) <= 1 ) {
        
        $select = fetchRow( "SELECT * FROM myclivelive WHERE wa_id='{$IN['wa_id']}'" );
        
        if ( $select ) {
            
            $ERROR->eraseMsgError();

            $insert = mysql_query( $re );
            
            if ( !$insert ) {
                
                $return['result'] = false;
                $return['msg'] = "<h4 class='lead-red'>Dear user something weird happened. Please double check your data and try again!</h4>
                        <br /><a href='' class='close-msg btn-lg btn btn-default'>CLOSE MSG</a>";
                
                echo json_encode( $return );
            } else {
                
                if ( buildFulltext( $IN['user_name'], $IN['wa_id'] ) ) {
                    
                    $return['result'] = true;
                    $return['msg'] = "<h4 class='lead-blue'>Thanks for submit your information, our server will 
                                    process this information. Thank you.</h4>
                                    <a href='' class='close-msg btn-lg btn btn-default'>CLOSE MSG</a>";
                    
                    echo json_encode( $return );
                }
            }
        } else {
            
            $ERROR->eraseMsgError();

            $insert = queryMysql($re);

            if ( !$insert ) {

                $return['result'] = false;
                $return['msg'] = "<h4 class='lead-red'>Dear user, something happined in our Database, or the Data that you entreted
                                is invalided or ilegal. So we could not process your information.
                                Please double check your input and try again!</h4>
                                <a href='' class='close-msg btn-lg btn btn-default'>CLOSE MSG</a>";
                
                echo json_encode( $return );
            } else {
                
                if ( buildFulltext( $IN['user_name'], $IN['wa_id'] ) ) {
                    
                    $return['result'] = true;
                    $return['msg'] = 'first';
                    
                    echo json_encode( $return );
                }
            }
            
        }
    } else {
        
            $build = "<div class='lead-red'><h4 >Please Enter Valide Data...</h4>";
            foreach ( $ERROR->returnMsgError() as $key => $value ) {
                
                $build .= "<p>".$value."</p>";
            }
            $build .= "</div>";
            
            $return['result'] = false;
            $return['msg'] = $build;
            
            echo json_encode( $return );
        }
    
    }
?>
