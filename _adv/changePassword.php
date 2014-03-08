<?php

if ( count( $_GET ) < 1 ) {
    
    header("Location: ../index.php");exit;
}

require '../_includes/session.php';
require '../_includes/clivelive-conn.php';
require '../_includes/func.php';
require '../_includes/authentication.php';

$get = sanitizePost( $_GET );

$user = isset( $_GET['user'] ) ? $get['user'] : null;



require '../_includes/header-not-index.php'; 
?>
<div class="center cl-container">
    <div class="row left" style="padding: 1.5em">
    <h1 class="page-header">Advance Settings</h1>
    <small class="class-small">*</small><small>The requirement are least on upper case letter a lower case 
        letter and a number, and also minimum 8 characters</small>
     
    
        <p class="lead border-bottom">Insert your new password</p>
        <div class="error-msg" style="display: none;"></div>
    <form class="formContainer" 
                method="POST" 
                action="<?php echo $_part_path; ?>adv_settings.php" style="padding: 1.5em;float: left;border: dotted 2px #FF6600;">
        <h4 style="display: none;"></h4>
    <div style="display: none;" class="row left lead">
        <label class="col-xs-6">User Name</label>
        <input class="col-xs-12 col-md-6" type="hidden" required name="user_name" value="<?php if ( !is_null( $user ) ) { echo $user; } ?>">
    </div>
    <div class="row left lead">
        <label class="col-xs-6">New password</label>
            <input class="col-xs-12 col-md-6" type="password" required name="ignore">
    </div>
    <div class="row left lead">
        <label class="col-xs-6">Retype password</label>
            <input class="col-xs-12 col-md-6" type="password" required name="new_password">
    </div>
        <input id="change-password" class="btn btn-default btn-warning" type="submit" value="CHANGE">
        <img src="<?php echo $_part_path; ?>_images/btn-loader.GIF" style="display: none;" class="change-password-loader">
    </div>
</div>
<?php require '../_includes/footer.php'; ?>
<script type="text/javascript">
    
    
    var error = $( '.error-msg' );
    var btn = $( 'input[type="submit"]' );

function checkForm( passField01, passField02 ) {
    
    passField01 = $.trim(passField01);
    passField02 = $.trim(passField02);
    
    var msg = '';
    var re = /^\w+$/; 
    
    if ( passField02 === '' ) {
        
        msg = "Username cannot be blank!"; 
        return msg; 
    } 
    
    if ( !re.test( passField02 ) ) {
        
        msg = "Username must contain only letters, numbers and underscores!"; 
        return msg; 
    } 
    
    if ( passField01 === passField02 ) {
        
        if ( passField02.lenght < 8 ) {
            
            msg = "Password must contain at least eight characters!"; 
            return msg; 
        }
        
        re = /[0-9]/; 
        
        if ( !re.test( passField02 ) ) {
            
            msg = "Password must contain at least one number (0-9)!"; 
            return msg; 
        }
        
        re = /[a-z]/; 
        
        if ( !re.test( passField02 ) ) {
            
            msg = "Password must contain at least one lowercase letter (a-z)!"; 
            return msg;
        }
        
        re = /[A-Z]/; 
        
        if ( !re.test( passField02 ) ) {
            
            msg = "Password must contain at least one uppercase letter (A-Z)!"; 
            return msg; 
        } 
    } 
    msg = 'TRUE'
    return msg; 
}
    
    
    $( document )
        .on( 'click', '#change-password', function ( e ) {
            
            var buttom = $( this );
            var form = buttom.closest( 'form' );
            var sender = form.serializeObject();
            var h4 = form.find( 'h4' );
            var img = $('.change-password-loader');
            var action = form.attr( 'action' );
            
            e.preventDefault();
            
            function allWrong ( msg_ ) {
                
                var msg = msg_ ? msg_ : 'Something happened into our server, and could not process your actions';
                h4
                    .html( msg )
                    .removeClass( 'lead-red' )
                    .addClass( 'lead-blue' )
                    .slideDown();
                buttom.toggle();
                img.toggle();
            }

            buttom.toggle();
            img.toggle();
            h4.hide();
            
            h4.on( 'click', function ( e ) {
                
                $( this ).hide();
            } );
                
                $.ajax( {
                    cache : false,
                    url : action,
                    type : 'POST',
                    data : sender
                } )
                    .done( function ( done ) {
                        
                        try {

                            done = jQuery.parseJSON( done );
                        } catch ( insert_url_profile ) {

                            //$( 'body' ).prepend( done );
                            console.log( done );
                            allWrong();
                        }

                        if ( done.result ) {

                            if ( done.msg === 'TRUE' ) {
                                
                                h4
                                    .html('The password was update successfully.')
                                    .removeClass( 'lead-red' )
                                    .addClass( 'lead-blue' )
                                    .slideDown();
                                buttom.toggle();
                                img.toggle();
                            }
                        } else {
                            
                            if ( done.msg === 'WRONG' ) {
                                
                                allWrong();
                            } else if ( done.msg === 'FALSE' ) {
                                
                                allWrong( 'The password is same as old, or did not validate.' )
                            }  else {
                                
                                allWrong();
                            }
                        }
                    } )
                    .fail( function ( fail ) {

                        allWrong();
                    } )
            
        } )
        .on( 'keyup', 'input[type="password"]:eq(1)', function ( e ) {
            
            var $t = $( this ),
                val = $t.val(),
                brother = $('input[type="password"]').eq(0).val(),
                checkPass = false;
                
            if ( val !== brother ) {
                
                error.html('<h4 class="lead-red">\n\
                    Entered passwords are not the same, or you did not meet the \n\
                    requirements. Please type again.</h4>').slideDown('slow');
                btn.fadeOut('slow');
            } else {
                
                checkPass = checkForm( brother, val );
            
                if ( checkPass === 'TRUE' ) {
                    
                    error
                        .slideUp('slow');
                    btn
                        .fadeIn('slow');
                } else {
                    
                    error
                        .html('<h4 class="lead-red">'+checkPass+'</h4>')
                        .slideDown('slow');
                }
            }
        })
        .on( 'submit', '.formContainer', function ( e ) {
            
            var $this = $( this );
            var senderArray = $this.serializeArray();
            var action = $this.attr( 'action' );
            
            e.preventDefault();
            
            $.ajax( { 
                cache : false,
                url : action,
                method : 'POST',
                data : senderArray
            } )
                .done( function( done ) {
            
                
             
            });
        });
</script>
<?php require $_part_path . '_includes/endOfAll.php'; ?>