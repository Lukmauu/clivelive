<div class="mob-top cl-container visible-xs hidden-sm hidden-md hidden-lg" 
     style="
        background-image: url('<?php echo $_part_path ?>_images/bg.png');
        background-repeat: repeat">
    <div class="top-wrapper container">
        <div class="row user-login-wrapper">
            <div class="user-login">
                <?php echo '<img src="'.$_part_path.'_images/icon_user.png" />'; ?>
                    <?php echo $userNameOnSession; ?>
                <?php echo '<img src="'.$_part_path.'_images/divider_username.png" />'; ?>
                <?php
                if ( isset( $_SESSION['password'] ) ) {
                    
                    echo '<a href="' . $_part_path . 'session_destroy.php" class="link_user_login">Logout</a>';
                } else {
                    
                    echo '<a href="' . $_part_path . 'login.php" class="link-user-login">Login</a>';
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo '<a href="' . $_part_path . '">'; ?>
                <?php echo '<img class="img-responsive mob-logo" src="' . $_part_path . '_images/logo_clivelive.png" />'; ?>
                <?php echo '</a>'; ?>
            </div>
            <div style="display: none;" class="col-xs-8"><h1 class="medium-text-background">The place to go when looking for a place to go</h1></div>
        </div>
        <div class="row">
            <?php echo '<form class="mob-search-holder-home" action="' . $_part_path . 'search.php" style="display: none;">'; ?>
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
            <?php echo '</form>'; ?>
        </div>

        <div class="mob-search-results-btn">
            <button type="button" class="mob-search-btn-index btn btn-default btn-danger">SEARCH</button>
        </div>
    </div>
</div>
