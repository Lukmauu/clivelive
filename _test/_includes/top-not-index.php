<?php 

$userNameOnSession = '<a href="'.$_part_path.'signup.php" class="link-user-login">Sign Up</a>';
if(isset( $_SESSION['user_name'] ) ) {
    
    $userNameOnSession = '<a href="'.$_part_path.'profile.php" class="link-user-login">'.ucfirst( $_SESSION['user_name'] ).'</a>';
}
?>    
<?php //<!--<![CDATA[--> ?>
<header class="header hidden-xs visible-sm">

    <div class="cl-container">
        <div class="row">
            <div class="login-not-index">
            <?php echo '<img src="'.$_part_path.'_images/icon_user.png" align="absmiddle" />'; ?>
                <?php echo $userNameOnSession; ?>
            <?php echo '<img src="'.$_part_path.'_images/divider_username.png" align="absmiddle" />'; ?>
                <?php 
                
                if( isset( $_SESSION['password'] ) ) {
                    
                    echo '<a href="'.$_part_path.'session_destroy.php" class="link_user_login">Logout</a>';
                } else { 
                    echo '<a href="'.$_part_path.'login.php" class="link_user_login">Login</a>'; 
                } 
                ?>
        </div>
            </div>
        <div class="row">
        <div class="logo-not-index">
        <?php 
            echo '<a href="'.$_part_path.'"><img src="'.$_part_path.'_images/clive_live_logo.png" alt="CliveLive" /></a>'; 
        ?>
        </div>
            
        <div class="search-holder-not-index">
            <?php echo '<form class="search-inner-not-index" action="'.$_part_path.'search.php">'; ?>
                <div class="find-wrapper">
                    <input name="q" class="find" type="text">
                </div>

                <div class="near-wrapper">
                    <input name="near" class="near" type="text">
                </div>
                <div class="search-results-btn">
                    <input type="submit" value="GO" class="searchbtn-not-index">
                        
                    
                </div>
            <?php echo '</form>'; ?>
            </div>
        
        </div>
    </div>

</header>
<?php 
////<!--]]>-->  ?>
<?php require 'mob-top.php'; ?>
        <!-- END .top -->


