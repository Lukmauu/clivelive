<?php
function isLocal(){
    $_return = FALSE;
    if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
        $_return = TRUE; 
    }
    return $_return;
}



?>
<?php require '_includes/authentication.php'; ?>
<?php require '_includes/func.php'; ?>
<?php require '_includes/header-not-index.php'; ?>
<div class="center container">
    <!-- join members START -->
            <div class="container">
                <div class="row">
                    <iframe
                        class="iframe"
                        src="http://clivelive.wildapricot.org/widget/default.aspx?pageId=1686803"></iframe>
                    
                    <?php
                    //SUDO CODE
                    /*
                     *  onload="tryToEnableWACookies('http://clivelive.wildapricot.org');"
                     * <script type="text/javascript" src="http://clivelive.wildapricot.org/Common/EnableCookies.js"></script>
                     */
                    ?>
                </div>
            </div>
        <!-- join members END -->
<a href="#" class="btn btn-default btn-warning btn-clean">CLEAN</a>
</div>


<?php require '_includes/footer.php' ?>
<script type="text/javascript">
$(document).on('click', '.btn-clean', deleteAllCookies);
	

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
    	var cookie = cookies[i];
    	var eqPos = cookie.indexOf("=");
    	var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    	document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
    location.reload();
}


</script>