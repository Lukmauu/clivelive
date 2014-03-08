<?php 

ob_start();
require '_includes/session.php';
require '_includes/clivelive-conn.php';
require '_includes/func.php'; 
require '_includes/authentication.php';

if ( isset( $_SESSION['password'] ) && isset( $_SESSION['email'] ) ) {
    
    header("Location: profile.php"); exit;
}

if ( isset( $_POST['password'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    
        $post = sanitizePost( $_POST );
        $get_user_name  = isset( $post['user_name'] ) ? $post['user_name'] : null;
        $get_password   = $post['password'];
    
    if ( !is_null( $get_user_name ) ) {
        
        $get_result = fetchAssoc( "SELECT * FROM login WHERE user_name='{$get_user_name}' AND password='{$get_password}'" );
       
        if ( count( $get_result) > 1 ) {
            
            $_SESSION = $get_result;
            
            lastLogin( $get_user_name );
            
            header("Location: profile.php"); exit;
            
        } else {
            
            $_SESSION = array();
        }
    } else {
        
        $_SESSION = array();
    }
}

require '_includes/header-not-index.php'; 
?>
<div class="center container">

    <h1 class="page-header">User Login</h1>
    
    <?php if ( isset( $_GET['user_changed'] ) && isset( $_GET['new_name'] ) ) { ?>
        <h4 class="lead-blue error-msg">The username was update <br />
        Please login again with your new username</h4>
    <?php } ?>
    <!-- ERROR MSG -->    
        <h4 class="lead-red error-msg" style="display: none;"></h4>
    <!-- ERROR MSG --> 
    
    <form id="login-form" action="login_check.php" method="POST" style="padding: 1.5em;">
        <div class="input-profile-form left lead">
            <label style="width: 135px;">User name</label>
                <input class="user-name" type="text" required name="user_name" 
                    value="<?php if ( isset( $_GET['new_name'] ) ) { echo $_GET['new_name']; } else { echo ''; }?>">
        </div>

        <div class="input-profile-form left lead">
            <label style="width: 135px;">Password</label>
                <input class="password" type="password" required name="password">
        </div>
        
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
        
</div>

<?php include '_includes/footer.php' ?>
<script type="text/javascript">
//<![CDATA[
$(document)
    .on('click', '#mainSubmitButtom', function (e) {
        
        var buttom = $(this);
        var btnLoading = $('.btn-loading');
        var errorMsg = $('.error-msg');
        var form = buttom.closest('form');
        var toWhere = form.attr('action');
        var method = form.attr('method');
        var formObj = form.serializeObject();

        e.preventDefault();
        
        buttom.hide();
        btnLoading.show();
        formObj.ajax = true;
    
        $.ajax({
            url : toWhere,
            type : method,
            data : formObj
        })
            .done(function (done) {
                try {

                    done = jQuery.parseJSON(done);
                } catch (e) {
                    
                    //$( 'body' ).prepend( done );
                    errorMsg.text('Authention failed! Something happened in our server, please try again later.')
                        .slideDown();
                    buttom.show();
                    btnLoading.hide();
                }

                if (typeof done.result === 'boolean' && done.result) {

                    if (location.href.indexOf('127.0.0.1:8080') > 0) {

                        location.href = 'http://127.0.0.1:8080/profile.php';    
                    } else {

                        location.href = 'http://clivelive.com/profile.php';    
                    }

                } else {

                    errorMsg.text('Authention failed! Please try again.').slideDown('slow');
                    buttom.show();
                    btnLoading.hide();
                }
            })
            .fail(function (fail) {

                errorMsg
                    .text('We are sorry your authention failed! Please try again.')
                    .slideDown('slow');
                buttom.show();
                btnLoading.hide();
            });
});
//]]>
</script>
<?php require '_includes/endOfAll.php'; ?>