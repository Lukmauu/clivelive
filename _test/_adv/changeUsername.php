<?php
if ( count ( $_GET ) < 1 ) {
    
    header("Location: ../index.php");exit;
}

require '../_includes/session.php';
require '../_includes/clivelive-conn.php';
require '../_includes/func.php';
require '../_includes/authentication.php';

//echoPre( $_SERVER );
$_GET = sanitizePost( $_GET );
$user = isset( $_GET['user'] ) ? $_GET['user'] : null;


require '../_includes/header-not-index.php'; 
?>
<div class="center cl-container">
    <div class="row left" style="padding: 1.5em">
    <h1 class="page-header">Advance Settings</h1>
    <small class="class-small">*</small><small>
        All fields are required, please consider that this username will appear at address bar on the browser.</small>
     
    
        <p class="lead border-bottom">Insert your new Username</p>
        <div class="error-msg" style="display: none;"></div>
    <form class="formContainer col-xs-12 col-md-6" 
                method="POST" 
                action="../adv_settings.php" style="padding: 1.5em;float: left;border: dotted 2px #FF6600;">
    <div class="row left lead">
        <label class="col-xs-6">Actual User Name</label>
        <input id="hook-user-name" class="col-xs-12 col-md-6" type="text" required name="user_name" value="<?php if ( !is_null( $user ) ) { echo $user; } ?>">
    </div>
    <div class="row left lead">
        <label class="col-xs-6">New username</label>
            <input class="col-xs-12 col-md-6 firstInput" type="text" required name="new_username_one">
    </div>
    <div class="row left lead">
        <label class="col-xs-6">Retype username</label>
            <input class="col-xs-12 col-md-6 secondInput" type="text" required name="new_username_two">
    </div>
        <input class="btn btn-default btn-warning" type="submit" value="CHANGE USERNAME">
    </div>
</div>
<?php require '../_includes/footer.php'; ?>
<script type="text/javascript">


/* VARS */
var error = $( '.error-msg' );
var btn = $( 'input[type="submit"]' );
var userNameFromInput = $( '.hook-user-name' );
/* VARS */
    
    $( document )
        
        .on('submit', '.formContainer', function(e){
            
            btn.hide();
            
            e.preventDefault();
            
            var $this = $( this );
            var toPost = $this.serializeObject();
            
            $.ajax({
                cache :  false,
                method :  'POST',
                url : '../adv_settings.php',
                data : toPost
            })   
                .done( function ( done ) {
                    
                    try {
                        
                        done = jQuery.parseJSON( done );
                    } catch ( changeUsername ) {
                        
                        error
                            .html('<h4 class="lead-red">code::001 => Something happened into our server we could not process, please try again later.</h4>');
                        btn.show();
                    }
                    if ( done.result ) { 
                    
                        if( done.msg === 'FALSE' ) {

                            error
                                .html('<h4 class="lead-red">The username you are trying to change does not exists in our DATABASE.</h4>')
                                .slideDown('slow');
                                btn.show();
                        } else if ( done.msg === 'TRUE' ) {
                            
                            userNameFromInput.val( done.user );
                            location.href = '../login.php?user_changed=yes&new_name=' + done.user;
                            error
                                .html('<h4 class="lead-blue">The username was update.</h4>')
                                .slideDown('slow');
                            btn
                                .slideDown();
                        } else if ( response === 'TOOK' ) {
                            
                            error
                                .html('<h4 class="lead-red">\n\
                                    The new user name is already taken by another member \n\
                                    or the new name is same as the old name. </h4>')
                                .slideDown('slow');
                                btn.show();
                        } else if ( response === 'FAIL' ) {
                            
                            error
                                .html('<h4 class="lead-red">\n\
                                    Could not process, or could not rename the user name, please try again later, or get in touch with our support.</h4>')
                                .slideDown('slow');
                                btn.show();
                        } else {
                            error
                                .html('<h4 class="lead-red">Something happened into our server we could not process, please try again later.</h4>');
                                btn.show();
                        }
                    } else {

                        error
                            .html('<h4 class="lead-red">Something happened into our server we could not process, please try again later.</h4>');
                            btn.show();
                    }
                })
                .fail( function( fail ) {
                    
                    error
                        .html('<h4 class="lead-red">Something happened into our server we could not process, please try again later.</h4>');
                });
        })
        .on( 'keyup', '.secondInput', function ( e ) {
            
            var $t = $(this),
                second = $t.val(),
                first = $('.firstInput').val(),
                checkPass = 'FALSE';
            
            e.preventDefault();
            
            if ( first !== second ) {
                
                error.html('<h4 class="lead-red">\n\
                    Entered user names are not the same, or you did not meet the \n\
                    requirements. Please type again.</h4>').slideDown('slow');
                btn.fadeOut('slow');
            } else if ( $(this).val().length < 3 ) {
                
                error.html('<h4 class="lead-red">\n\
                    The user names must be least three characters long. \n\
                    Please type again.</h4>').slideDown('slow');
                btn.fadeOut('slow');
            } else {
                error
                    .slideUp('slow');
                btn
                    .fadeIn('slow');
            }
        })
        .on( 'click', '.lead-blue', function () {
            
            $(this).hide('slow');
        });
            
</script>
<?php require $_part_part.'_includes/endOfAll.php'; ?>