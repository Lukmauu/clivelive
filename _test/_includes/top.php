<?php

$userNameOnSession = '<a href="signup.php" class="link-user-login">Sign Up</a>';
if( isset( $_SESSION['user_name'] ) ) {
    
    //$userNameOnSession = '<a href="profile.php" class="link-user-login">'.ucfirst($_SESSION['last_name']).', '.ucfirst($_SESSION['first_name']).'</a>';
    $userNameOnSession = '<a href="profile.php" class="link-user-login">'.ucfirst( $_SESSION['user_name'] ).'</a>';
}
?>
<div class="top cl-container hidden-xs visible-sm">
            <div class="top-wrapper">
                <div class="row user-login-wrapper">
                    <div class="user-login">
                       <img src="_images/icon_user.png" />
                    <?php echo $userNameOnSession; ?>
                        <img src="_images/divider_username.png" />
                    <?php 
                    if( isset( $_SESSION['password'] ) ) {
                        
                        echo '<a href="session_destroy.php" class="link_user_login">Logout</a>';
                    } else {
                        
                        echo '<a href="login.php" class="link-user-login">Login</a>';
                    }
                    ?>
                    </div>
                </div>

                <form class="search-holder-home" action="search.php">
                    <div class="find-home-wrapper">
                        <input class="find-home" name="q"/>
                    </div>
                    <div class="near-home-wrapper">
                        <input class="near-home" name="near" />
                    </div>
                    <div class="search-results-btn">
                      <input type="submit" value="SEARCH" class="searchbtn-index btn btn-default btn-danger">
                    </div>
                </form>
            </div>
        </div>
<div class="mob-top cl-container visible-xs hidden-sm hidden-md hidden-lg" style="
        background-image: url('_images/bg.png');
        background-repeat: repeat">
    <div class="top-wrapper container">
                <div class="row user-login-wrapper">
                    <div class="user-login">
                       <img src="_images/icon_user.png" />
                    <?php echo $userNameOnSession; ?>
                        <img src="_images/divider_username.png" />
                    <?php 
                    if( isset( $_SESSION['password'] ) ) {
                        
                        echo '<a href="session_destroy.php" class="link_user_login">Logout</a>';
                    } else {
                        
                        echo '<a href="login.php" class="link-user-login">Login</a>';
                    }
                    ?>
                    </div>
                </div>
        <div class="row">
            <div class="col-xs-4"><a href="index.php"><img class="img-responsive mob-logo" src="_images/logo_clivelive.png" /></a></div>
            <div style="display: none;" class="col-xs-8"><h1 class="medium-text-background">The place to go when looking for a place to go</h1></div>
        </div>
        <div class="row">
        <form class="mob-search-holder-home" action="search.php" style="display: none;">
                    <div class="mob-find-home-wrapper">
                        <label>Find</label>
                        <input name="q" class="mob-find-home" placeholder="Find eg.: bar, night club, restarant" />
                    </div>
                    <div class="mob-near-home-wrapper">
                        <label>Near</label>
                        <input name="near" class="mob-near-home" placeholder="Near eg.: Seatlle, Redmond, etc" />
                    </div>
                    <div class="mob-search-results-btn">
                      <input type="submit" value="SEARCH" class="mob-searchbtn-index btn btn-default btn-danger">
                    </div>
        </form>
        </div>
        
        <div class="mob-search-results-btn">
            <button type="button" class="mob-search-btn-index btn btn-default btn-danger">SEARCH</button>
        </div>
            </div>
        </div>

        <!-- END .top -->
