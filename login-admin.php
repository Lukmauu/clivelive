<?php

error_reporting( E_ALL );

ini_set( 'display_errors', 1 );

require '_includes/session.php';

if ( isset( $_SESSION['password'] ) && $_SESSION['level'] === 'member' ) {
    
    header("Location: session_destroy.php");
}

//SOFIA CODE 5589 
require '_includes/clivelive-conn.php';
require '_includes/func.php';
require '_includes/authentication.php';


if ( $_SERVER['REQUEST_METHOD'] === "POST" ) {
    
    
    $SPOST = sanitizePost( $_POST );
    
    $emailArray = explode( '@', $SPOST['member_email'] );
    
    $MAIN_FOLDER = isLocal() ? '' : '';
    
    $ROOT = ( isLocal() ) ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['DOCUMENT_ROOT'] . '/';
    
    $USER_NAME = ( !userTaken( strtolower( $emailArray[0] ) ) ) ?
                        strtolower( $emailArray[0] ) :
                        strtolower( $emailArray[0] ) . generateRandomString(4);
    
    
    
    $folder_name = $USER_NAME;
    
    $content = '<?php require "../_base/_base.php"; ?>';
    
    $php_file_name = $ROOT . $folder_name . '/index.php';

    $member_id = $SPOST['member_id'];
    $member_first_name = $SPOST['member_first_name'];
    $member_last_name = $SPOST['member_last_name'];

    $member_email = $SPOST['member_email'];

    $member_password = 123; //isLocal() ? 123 : generateRandomString(8);

    $member_level = ( isset( $SPOST['member_level'] ) && $SPOST['member_level'] === 'admin' ) ? 
                        $SPOST['member_level'] : 'member';

    $member_last_login = date('l jS \of F Y h:i:s A');
    
    $member_time = date('l jS \of F Y h:i:s A');

    $main_query = "INSERT INTO login 
            ( id, first_name, last_name, email, user_name, password, level, last_time_login, creation_time, url)
     VALUES ( '{$member_id}', '{$member_first_name}', '{$member_last_name}', '{$member_email}', '{$USER_NAME}', '{$member_password}', '{$member_level}','{$member_last_login}', '{$member_time}', null )";
    
    //echoPre( $main_query );
    
    $return = array( 'result' => false, 'msg' => false );
    
    if ( !mkdir( $ROOT . $folder_name ) ) {
        
        $return['result'] = false;
        $return['msg'] = 'CAN NOT CREATE THE FOLDER';
        echo json_encode( $return );exit; 
    }

    if ( !$return['msg'] && !fopen( $php_file_name, 'w+' ) ) {
        
        $return['result'] = false;
        $return['msg'] = 'CAN NOT CREATE THE FILE' . '   ' . $php_file_name;
        echo json_encode( $return );exit; 
    }

    if ( !$return['msg'] && !file_put_contents( $php_file_name, $content ) ) {
        
        $return['result'] = false;
        $return['msg'] = 'CAN NOT PUT CONTENT ON THE FILE';
        echo json_encode( $return );exit; 
    }
    
    

    $insert = mysql_query( $main_query );

    
    
    if ( !$insert ) {
        
        $return['result'] = false;
        $return['msg'] = "<h4 class='lead-red'>Dear user something weird happened. Please double check your data and try again!</h4>
                        <br /><a href='' class='close-msg finish-btn btn btn-default'>CLOSE MSG</a>
                        <br />" . mysql_error() . "    " . mysql_errno();
                
    } else {
        
        $return['result'] = true;
        $return['msg'] = "<h4 class='lead-blue'>Thanks for submit your information, our server will 
                        process this information. Thank you.</h4>
                        <a href='' class='close-msg finish-btn btn btn-default'>CLOSE MSG</a>";
        // !mail($to, $subject, $message, $headers) 
    }
    
    echo json_encode( $return );exit; 
}

require '_includes/header-not-index.php';
?>

<div class="center container">
    
    <?php if ( isset( $_SESSION['password'] ) && isset( $_SESSION['user_name'] ) ) { ?>

        <div>
            <h1 class="page-header">Please insert a new member</h1>
            <p class="lead"></p>
            <?php echo '<form class="formContainer" 
                method="POST" 
                action="' . $itself . '" style="padding: 1.5em;float: left;">'; ?>
            <div class="input-profile-form left lead">
                <label style="width: 200px;">member id</label>
                <input type="text" required name="member_id">
            </div>
            <div class="input-profile-form left lead">
                <label style="width: 200px;">member first name</label>
                <input type="text" required name="member_first_name">
            </div>
            <div class="input-profile-form left lead">
                <label style="width: 200px;">member last name</label>
                <input type="text" required name="member_last_name">
            </div>
            <div class="input-profile-form left lead">
                <label style="width: 200px;">member email</label>
                <input type="text" required name="member_email">
            </div>
            <div class="input-profile-form left lead" style="border: 1px #FF6600 dotted;padding: 8px;margin-bottom: 0.4em;">
                <label style="width: 192px;">member level</label>
                <input type="text" name="member_level"><br /><small>* Needed only if is a new Admin</small>
            </div>

            <input class="btn btn-default btn-warning" id="insert-member-form" type="submit" value="INSERT MEMBER">
            <img src="<?php echo $_part_path; ?>_images/btn-loader.GIF" style="display: none;" class="insert-user-admin-loader">

    <?php echo '</form>'; ?>
        </div>
        <br>
        <div style="float: right;border: 1px #39b3d7 dotted; padding: 0.5em;">
            <h1>Insert URL here</h1>

            <form action="insert_url.php" method="POST" class="formContainer" style="padding: 1.5em;float: left;">
                <h4 style="display: none;"></h4>
                <div class="input-profile-form">
                    <label class="lead left" style="width: 200px;"> User Name
                        <input type="text" name="user_name">
                    </label>
                </div>
                <div class="input-profile-form" style="width: 500px;">
                    <label class="lead left" style="width: 200px;"> Broadcast URL
                        <input type="text" name="url" style="width: 400px;">
                    </label>
                </div>
                <input class="btn btn-default btn-warning" type="submit" id="insert-url-admin" value="INSERT URL">
                <img src="<?php echo $_part_path; ?>_images/btn-loader.GIF" style="display: none;" class="insert-url-admin-loader">

            </form>
        </div>






        <div class="clearfix" style="height: 1.5em;"></div>


<?php } else { ?>
        <h1 class="page-header">Admin Login</h1>
        <!-- ERROR MSG -->    
            <h4 class="lead-red error-msg" style="display: none;"></h4>
        <!-- ERROR MSG --> 
        <form id="login-admin-form" action="login_check.php" method="POST" style="padding: 1.5em;">

            <div class="input-profile-form left lead">
                <label style="width: 135px;">User name</label>
                    <input type="text" required name="user_name">
            </div>
            <div class="input-profile-form left lead">
                <label style="width: 135px">Password</label>
                    <input type="password" required name="password">
            </div>
            <input type="hidden" value="no" name="ajax">
            <input id="mainSubmitButtom" class="btn btn-default btn-warning" type="submit" value="SUBMIT">
            <div class="btn-loading"
                    style=" width: 100%;
                            margin: 0 auto;
                            text-align: center;
                            margin-top: 18px;
                            display: none;">
                <h4>Logging...</h4>
                <img style="display: inline; " src="_images/ajax-loader.gif" />
            </div>
        </form>

    <?php } ?>
    </div>

<?php require '_includes/footer.php'; ?>
<script>
//<![CDATA[
$( document )
    .on( 'click', '#insert-member-form', function ( e ) {
      
        var buttom = $( this );
        var form = buttom.closest( 'form' );
        var action = form.attr( 'action' );
        var sender = form.serializeObject();
        var h4 = form.find( 'h4' );
        var img = $( '.insert-user-admin-loader' );
        
        e.preventDefault();
        
        buttom.toggle();
        img.toggle();
        h4.hide();
        
            $.ajax( {
                cache : false,
                url : action,
                type : 'POST',
                data : sender
            } )
                .done( function ( done ) {
                    
                    $( 'body' ).prepend( done );
                    console.log( done );
                } )
        
        
    } )
    .on( 'click', '#insert-url-admin', function ( e ) {
        
        var buttom = $( this );
        var form = buttom.closest( 'form' );
        var action = form.attr( 'action' );
        var sender = form.serializeObject();
        var h4 = form.find( 'h4' );
        var img = $( '.insert-url-admin-loader' );
        
        e.preventDefault();
        
        sender.url = checkUrl( sender.url );
        buttom.toggle();
        img.toggle();
        h4.hide();
                
        if ( sender.url ) {
            
            form.find( 'input[name="url"]' ).val( sender.url );
            
        } else {
            
            h4
                .html( 'URL was not accepted, please try again. <br />e.g. https://www.facebook.com/' )
                .show();
            
            buttom.toggle();
            img.toggle();
            return false;
        }
        
            $.ajax( {
                cache : false,
                url : action,
                type : 'POST',
                data : sender
            } )
                .done( function ( done ) {

                    console.log( done );
                    try {

                        done = jQuery.parseJSON( done );
                    } catch ( insert_url_admin ) {

                        $( 'body' ).prepend( done );
                        buttom.toggle();
                        img.toggle();
                        h4
                            .html( 'URL was not accepted, please try again. <br />e.g. https://www.facebook.com/' )
                            .show();
                    }

                    if ( done.result ) {

                        h4
                            .html( 'The URL was insert sucessufully.' )
                            .show();
                        buttom.toggle();
                        img.toggle();
                        form.find( 'input[name="url"]' ).val( '' )
                        form.find( 'input[name="user_name"]' ).val( '' );
                    } else {

                        h4.html( 'The URL was not insert, <br />\n\
                                    maybe the user does not exist or, <br />\n\
                                    URL was not accepted, please try again. <br />\n\
                                    e.g. https://www.facebook.com/' )
                            .show();
                        buttom.toggle();
                        img.toggle();
                    }
                } )
                .fail( function ( fail ) {

                    buttom.toggle();
                    img.toggle();
                } )
    } );
    
    $( document )
        .on( 'click', '#mainSubmitButtom', function ( e ) {
                
            var buttom = $( this );
            var btn_loading = $( '.btn-loading' );
            var form = buttom.closest( 'form' );
            var toWhere = form.attr( 'action' );
            var method = form.attr( 'method' );
            var formObj = form.serializeObject();
            var error_msg = $('.error-msg');

            e.preventDefault();
        
            error_msg.hide();
            btn_loading.show();
            buttom.hide();
            formObj.ajax = true;
            formObj.member = 'admin';

            $.ajax( {
                url : toWhere,
                type : method,
                data : formObj
            } )
                .done( function ( done ) {
            
                    try {
                        
                        done = jQuery.parseJSON( done );
                    } catch ( e ) {
                        
                        error_msg
                            .text('Authention failed! Something happened in our server, please try again later.')
                            .slideDown();
                        buttom.show();
                        btn_loading.hide();
                    }
            
                    if ( typeof done.result === 'boolean' && done.result === true ) {
                
                        location.reload();    
                    } else {
                        
                        error_msg.text('Authention failed! Please try again.').slideDown('slow');
                        buttom.show();
                        btn_loading.hide();
                    }
                } )
                .fail( function ( fail ) {
            
                    $('.error-msg').text('We are sorry your authention failed! Please try again.').slideDown('slow');
                    buttom.show();
                    btn_loading.hide();
                } );
        });
//]]>
</script>
<?php  require '_includes/endOfAll.php'; 
        //www.youtube.com/embed/yVdnvQsKyUs?feature=player_detailpage

?>

