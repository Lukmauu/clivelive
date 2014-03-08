<?php

//A30C5559@EBC6@49
//"//www.youtube.com/embed/Sge5sUNJkiY?feature=player_detailpage"

require '_includes/session.php';
if ( !isset( $_SESSION['email'] ) && !isset( $_SESSION['password'] ) ) {
    
    header("Location: login.php");exit;
}
require '_includes/clivelive-conn.php'; 
require '_includes/authentication.php';
require '_includes/func.php';

$auto = new authentication();

if ( !$auto->quickCheck( $_SESSION['user_name'], $_SESSION['password'] ) ) {
    
    header("Location: index.php");exit;
}

if ( $_SESSION['level'] === 'admin' ) {
    
    echoPre( 'WE ARE THE ADMIN, THIS NOT GOING TO WORK ( THIS IS JUST FOR TEST )', true ) ;
}

$MY_CLIVE_LIVE = false;

$jsMyCliveLive = !$MY_CLIVE_LIVE ? false : true;

$SESSION_ARRAY = $_SESSION;
$emailResult = '';
$neddle = '@';
$wholeEmail = ( isset( $SESSION_ARRAY['email'] ) ) ? $SESSION_ARRAY['email'] : '';
if (strlen($wholeEmail) > 2) {
    $emailResult = explode($neddle, $wholeEmail);
}

$fetch_row = fetchAssoc( "SELECT * FROM myclivelive WHERE wa_id='{$_SESSION['id']}'" );

if ( !$fetch_row ) {
    
    $MY_CLIVE_LIVE = false;
    $jsMyCliveLive = false;
} else {
    
    $fetch_row = desanitizePost( $fetch_row );
    
    $MY_CLIVE_LIVE = true;
    $jsMyCliveLive = true;
} 

/*if (count($fetch_row) > 1) {
    $UP_member_id           = $fetch_row[0];
    $UP_wa_id               = $fetch_row[1];
    $UP_first_name          = $fetch_row[2];
    $UP_last_name           = $fetch_row[3];
    $UP_user_name           = $fetch_row[4];
    $UP_business_phone      = $fetch_row[5];
    $UP_business_name       = $fetch_row[6];
    $UP_business_address    = $fetch_row[7];
    
    $UP_city                = $fetch_row[8];
    $UP_state               = $fetch_row[9];
   
    $UP_lat                 = $fetch_row[10];
    $UP_lng                 = $fetch_row[11];
    $UP_cityLat             = $fetch_row[12];
    $UP_cityLng             = $fetch_row[13];
    
    
    $UP_cuisine             = $fetch_row[14];
    $UP_atmosphere          = $fetch_row[15];
    $UP_parking             = $fetch_row[16];
    $UP_serving             = $fetch_row[17];
    $UP_h_h_average_price   = $fetch_row[18];
    $UP_reservation         = $fetch_row[19];
    $UP_neighborhood        = $fetch_row[20];
    
    $UP_b_b_title           = $fetch_row[21];
    $UP_b_b_text            = $fetch_row[22];
    
    $UP_main_image          = $fetch_row[23];
    
    $UP_main_email          = $fetch_row[24];
    $UP_second_email        = $fetch_row[25];
 
    $UP_social_facebook     = $fetch_row[26];
    $UP_social_googleplus   = $fetch_row[27];
    $UP_social_twitter      = $fetch_row[28];
    $UP_description         = $fetch_row[29];
    $MY_CLIVE_LIVE          = true;
    $jsMyCliveLive          = true;
} else {
    /* VARS */
   /* $UP_member_id = $UP_wa_id = $UP_first_name = $UP_last_name = $UP_business_phone
    = $UP_business_name = $UP_business_address = $UP_city = $UP_state = $UP_cuisine = $UP_atmosphere = $UP_parking
    = $UP_serving = $UP_h_h_average_price = $UP_reservation = $UP_neighborhood = $UP_b_b_title
    = $UP_b_b_text = $UP_main_image = $UP_main_email = $UP_second_email = $UP_social_facebook
    = $UP_social_twitter = $UP_social_googleplus = $UP_description = '';
    /* VARS */
//}


function getJsMyCliveLive () {
    global $jsMyCliveLive;
    echo json_encode( $jsMyCliveLive );
}

function getUserName () {
    global $SESSION_ARRAY;
    if( isset( $SESSION_ARRAY['user_name'] ) ) {
        
        echo $SESSION_ARRAY['user_name'];
    }
}

function getSessionArray () {
    global $SESSION_ARRAY;
    if( isset( $SESSION_ARRAY ) && is_array( $SESSION_ARRAY ) ) {
        
        echo json_encode( $SESSION_ARRAY );
    }
}

require '_includes/header-not-index.php';
?>
<!-- START .center -->
<div class="center cl-container">

        <a href="#" tabindex="-1" id="toTop"></a>
      <div id="profile-header">

        <!-- ERROR MSG -->    
            <div class="error-msg" style="display: none; width: 100%; border-bottom: dotted #535353 thin; margin-bottom: 0.6em;"></div>
        <!-- ERROR MSG --> 
        <h1 class="medium-text-background">Please fill all the required fields</h1>
        <small>based on this data, your MyClivelive will be build</small>
        <br><small class="class-small">*</small><small> required</small>


    </div>
<div class="row user-profile-btn" style="display: none;">
    <?php echo '<a href="" class="btn btn-default btn-warning" style="display: block; float: right; font-weight: bold;" id="btn-advance">ADVANCE SETTINGS</a>'; ?>
    <?php echo '<a href="' . $_part_href . $fetch_row['user_name'] . '" class="btn btn-default btn-warning" style="margin-right: 10px; display: block; float: right; font-weight: bold;">MY CLIVELIVE PAGE</a>'; ?>
</div>
    <div class="row" id="formContainer">

        <form action="profile.php" method="POST" id="profileForm" class="container" style="padding: 1.8em;">
            <input type="submit" value="SAVE DATA" class="finish-btn">
            <div class="btn-loading"
                style=" width: 100%;
                        margin: 0 auto;
                        text-align: center;
                        margin-top: 18px;
                        display: none;">
                <h4>Saving...</h4>
                <img style="display: inline; " src="_images/ajax-loader.gif" />
            </div>
            
            <div class="clearfix"></div>
            
            <div class="row lead">
                <label class="label col-xs-3">
                    Business Phone <small> * </small> 
                </label>
                <input type="text" name="business_phone" value="<?php echo $fetch_row['business_phone']; ?>">
            </div>

            <div class="row lead">
                <label class="label col-xs-3">
                    Business Name <small> * </small> 
                </label>
                <input type="text" name="business_name" value="<?php echo $fetch_row['business_name']; ?>">
            </div>

            <div class="row lead">
                <label class="label col-xs-3">
                    Business Address <small> * </small> 
                </label>
                <input type="text" placeholder="establishment street address" name="business_address" value="<?php echo $fetch_row['business_address']; ?>"> 
            </div>

            <div class="row lead" style="display: none;">
                <label class="label col-xs-3">
                    Business City <small> * </small> 
                </label>
                <input type="text" placeholder="establishment city" name="business_address_city" value="<?php echo $fetch_row['city']; ?>"> 
            </div>
            
            <div class="row lead" style="display: none;">
                <label class="label col-xs-3">
                    Business State <small> * </small> 
                </label>
                <!--<input type="text" placeholder="establishment street address" name="business_address" value="<?php //echo $UP_business_address_state; ?>"> -->
                <select name="state">
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA" selected="selected">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select>
                
            </div>
            
            
            <div class="row lead">
                <label class="label">
                    Description <small> * </small> 
                </label>
                 <textarea style="min-height: 150px;" class="col-xs-12 col-sm-6" placeholder="description that will be show on search results" cols="75" rows="7" name="description"><?php echo $fetch_row['description']; ?></textarea>
            </div>
            
            <div class="row lead">
                <label class="label col-xs-3">
                    Cuisine <small> * </small> 
                </label>
                <input type="text" placeholder="e.g. italian..." name="cuisine" value="<?php echo $fetch_row['cuisine']; ?>">
            </div>
            
            <div class="row lead">
                <label class="label col-xs-3">
                    Atmosphere <small> * </small> 
                </label>
                <input type="text" placeholder="e.g. casual, fun..." name="atmosphere" value="<?php echo $fetch_row['atmosphere']; ?>">
            </div>
            
            <div class="row lead">
                <label class="label col-xs-3">
                    Parking <small> * </small> 
                </label>
                <input type="text" placeholder="e.g. free, paid, street parking..." name="parking" value="<?php echo $fetch_row['parking']; ?>" >
            </div>
            
            <div class="row lead">
                <label class="label">
                    Serving <small> * </small> 
                </label>
                <input type="text" placeholder="e.g. lunch, dinner..." name="serving" value="<?php echo $fetch_row['serving']; ?>" >
            </div>

            <div class="row lead">
                <label class="label">
                    Happy Hours <small> * </small> 
                </label>
                <input type="text" placeholder="e.g. 15.00..." name="h_h_average_price" value="<?php echo $fetch_row['h_h_average_price']; ?>">
            </div>
            
            <div class="row lead">
                <label class="label">
                    Reservation <small> * </small> 
                </label>
                <input type="text" placeholder="e.g. needed, or not..." name="reservation" value="<?php echo $fetch_row['reservation']; ?>" >
            </div>
            
            <div class="row lead">
                <label class="label">
                    Neighborhood 
                </label>
                <input type="text" placeholder="e.g. needed, or not..." name="neighborhood" value="<?php echo $fetch_row['neighborhood']; ?>">
                <p class="optional">optional<p>
            </div>
            
            <div class="row lead" style="display: none;">
                <label class="label">
                    Bill-board Title
                </label>
                <input type="text" placeholder="e.g. special offers" name="b_b_title" value="<?php echo $fetch_row['b_b_title']; ?>">
                <p class="optional">optional<p>
            </div>
            
            <div class="row lead">
                <label class="label">
                    Bill-board Text 
                </label>
                <textarea class="col-xs-12 col-sm-6" placeholder="special offers" cols="75" rows="7" name="b_b_text"><?php echo $fetch_row['b_b_text']; ?></textarea>
                <p class="optional">optional<p>
            </div>

            <div class="row lead">
                <label class="label">
                    Facebook Id 
                </label>
                <input type="text" placeholder="url of your Facebook page" name="facebook" value="<?php echo $fetch_row['social_facebook']; ?>">
                <p class="optional">optional</p>
            </div>
            
            <div class="row lead">
                <label class="label">
                    Twitter Id 
                </label>
                <input type="text" placeholder="url of your Twitter page" name="twitter" value="<?php echo $fetch_row['social_twitter']; ?>">
                <p class="optional">optional</p>
            </div>
            
            <div class="row lead">
                <label class="label">
                    Google+ Id 
                </label>
                <input type="text" placeholder="url of your Google+ page" name="googleplus" value="<?php echo $fetch_row['social_googleplus']; ?>">
                <p class="optional">optional</p>
            </div>

            <input type="submit" value="SAVE DATA" class="finish-btn">
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
    <!-- style="display: none;" -->
    <div class="row" id="settings" style="padding: 1.5em;float: left; display: none;">
            
    <h1 class="page-header">Advance Settings</h1>
    <p class="lead border-bottom">Insert your video url</p>
    <form class="formContainer" 
                method="POST" 
                action="adv_settings.php" style="padding: 1.5em;float: left; border: dotted 2px #FF6600">
        <div 
            class="url-from-profile-msg" 
            style="display: none;  width: 100%; border-bottom: dotted #535353 thin; margin-bottom: 0.6em;">
            
        </div>
    <div style="display: none;" class="input-profile-form left lead">
        <label style="width: 200px;">User Name</label>
            <input type="hidden" name="user_name">
    </div>
    <div class="input-profile-form left lead">
        <label style="width: 200px;">URL</label>
            <input type="text" required name="url">
    </div>
        <input id="insert-url-from-profile" class="btn btn-default btn-warning" type="submit" value="INSERT URL">
    <img src="<?php echo $_part_path; ?>_images/btn-loader.GIF" style="display: none;" class="insert-url-profile-loader">
    <?php echo '</form>'; ?>
        <div class="clearfix"></div>
        <div class="lead left">
            <br />
            <ul style="list-style-type: circle;">
            <?php echo '<li><a href="_adv/changePassword.php?user='.$SESSION_ARRAY['user_name'].'">Change your password</a></li>';?>
            <?php echo '<li><a href="_adv/changeUsername.php?user='.$SESSION_ARRAY['user_name'].'">Change your user name</a></li>';?>
            </ul>
        </div>    
    </div>
    <div class="clearfix"></div>
</div>

<?php require '_includes/footer.php' ?>
<script type="text/javascript">
    //<![CDATA[
    var checkFirstTime = <?php getJsMyCliveLive(); ?>;
    var nowUser  = "<?php getUserName(); ?>";
    
    var e = function ( elem ) {
        
        if ( !elem ) {
            
            return false;
        }
        
        var _error_ = $( elem );
        
        return { 
            'msg' : function ( msg ) {
                
                if ( msg ) {
            
                    _error_
                            .html( msg )
                }

                if ( _error_.is( ':visible' ) ) {

                    _error_.hide();
                } else {

                    if ( msg ) {
                        
                        _error_.show( 'slow' );
                    }
                }
            },
            'target' : _error_
        }
    };
    
    var error = new e( $( '.error-msg' ) );
    var errorUrl = new e( $( '.url-from-profile-msg' ) );
    
    if ( checkFirstTime === true && typeof checkFirstTime === 'boolean' ) {
        
        $( '.user-profile-btn' ).css( { 
            'display' : 'block' 
        } );
    }
    
    function isValidUSPhoneFormat ( elementValue ) {

        var phoneNumberPattern = /^[(]{0,1}[0-9]{3}[)]{0,1}[-\s.]{0,1}[0-9]{3}[-\s.]{0,1}[0-9]{4}$/;  
        
        if ( !phoneNumberPattern.test( elementValue ) ) {
            
            var phoneNumberPattern = /^(\()?\d{3}(\))?(.|\s)?\d{3}(.|\s)\d{4}$/; 
            return phoneNumberPattern.test( elementValue );   
        }
        
        return phoneNumberPattern.test( elementValue );  
    }
    
    $( document )
        .on( 'submit', '#profileForm', sendProfile)
        .on( 'change',  'input[name="business_phone"]', function ( e ) {
            
            
            
        } )
        .on( 'click', '#insert-url-from-profile', function ( e ) {
            
            var buttom = $( this );
            var form = buttom.closest( 'form' );
            var sender = form.serializeObject();
            var action = form.attr( 'action' );
            
            e.preventDefault();

            sender.user_name = nowUser;
            sender.url = checkUrl( sender.url );
            
            $( '.insert-url-from-profile, .insert-url-profile-loader' ).toggle();
            
            errorUrl.target.hide();
            
            $( '.url-from-profile-msg' ).on( 'click', function ( e ) {
                
                $( this ).hide();
            } );
                
            if ( sender.url ) {

                form.find( 'input[name="url"]' ).val( sender.url );

            } else {

                errorUrl.msg( "<h3 class='lead-red'>URL was not accepted, please try again. \n\
                            <br />e.g. https://www.video.com/</h4>\n\
                            <a href='' class='close-msg btn btn-default'>CLOSE MSG</a>" );
                return false; 
            }
        
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
                            errorUrl.msg( "<h4 class='lead-red'>URL was not accepted, please try again.<br />e.g. https://www.video.com/</h4>\n\
                                        <a href='' class='close-msg btn btn-default'>CLOSE MSG</a>" );
                        }

                        if ( done.result ) {

                            errorUrl.msg( "<h4 class='lead-blue'>The URL was insert sucessufully.<h4>\n\
                                            <a href='' class='close-msg btn btn-default'>CLOSE MSG</a>" )
                            form.find( 'input[name="url"]' ).val( '' )
                            form.find( 'input[name="user_name"]' ).val( '' );
                        } else {

                            errorUrl.msg( "<h4 class='lead-red'>The URL was not insert, <br />\n\
                                        maybe the user does not exist or, <br />\n\
                                        URL was not accepted, please try again. <br />\n\
                                        e.g. http://www.video.com/</h4><a href='' class='close-msg btn btn-default'>CLOSE MSG</a>" );
                        }
                    } )
                    .fail( function ( fail ) {

                        errorUrl.msg( "<h4 class='lead-red'>The URL was not insert, <br />\n\
                                        maybe the user does not exist or, <br />\n\
                                        URL was not accepted, please try again. <br />\n\
                                        e.g. https://www.video.com/</h4><a href='' class='close-msg btn btn-default'>CLOSE MSG</a>" )
                    } );
        } )



//        function ( e ) { 
//            
//            e.preventDefault();
//            
//            $.get( 'bed.php' )
//                .done( function ( done ) {
//                    done = jQuery.parseJSON( done );
//                    
//                    
//                    
//                    var des = deserializeGoogleFullAddress( done.results[0].address_components, 'locality' );
//                    console.log( des );
//                    console.log( 'From done' );
//                } ) 
//                .fail( function ( fail ) {
//                    
//                    console.log( fail );
//                    console.log( 'From fail' );
//                } )
//        } )
        .on( 'click', '.close-msg', function ( e ) {
            
            e.preventDefault();
                $( this ).parent().hide( 'fast' );
        } )
        .on( 'click', '#btn-advance', function ( e ) {
            
            e.preventDefault();
                $( '#formContainer' ).slideToggle();
                    $( '#settings' ).slideToggle();
        } )
        .on( 'afterShow', '.error-msg', function ( e ) {
            
            doScrollTop();
            $( '.finish-btn, .btn-loading' ).toggle();
        } )
        .on( 'afterShow', '.url-from-profile-msg', function ( e ) {
        
            $( '.insert-url-from-profile, .insert-url-profile-loader' ).toggle();
        } );
    
    function sendProfile ( e ) {
        
        e.preventDefault();
       
        $( '.btn-loading, .finish-btn' ).toggle();
        error.target.hide();
        
        var sessionArray  = <?php echo getSessionArray(); ?>; 
        var $this = $( this );
        
        var _form_ = $this.serializeObject();
        var form = checkForm( _form_ );
        
        if ( form ) {
        
            form.ajax = true;
            form.user = sessionArray.user_name;
            form.email = sessionArray.email;
            form.first_name = sessionArray.first_name;
            form.last_name = sessionArray.last_name;
            form.id = sessionArray.id;
            
            form.business_address = addressWithoutHash( form.business_address );
            
            $.ajax( {

                url : 'http://maps.googleapis.com/maps/api/geocode/json?&sensor=false',
                data : {
                    'address' : form.business_address
                }
            } )
            .done( function ( done ) {

                var response = done.results[done.results.length - 1];

                    console.log(response);
                    form.lat = response.geometry.location.lat;
                    form.lng = response.geometry.location.lng;
                    form.business_address = response.formatted_address.replace( ', USA', '' );
                    console.log( form.business_address );
                    // CHECK IF IT IS WORKING
                
                var rest = deserializeGoogleFullAddress( done.results[0].address_components );
                
                form.business_address_city = rest.city;

                if ( done.status === "OK" ) {

                    var hasCityResult = hasCity( 
                        form,
                        rest.city.toLowerCase(),
                        rest.shortState.toUpperCase(),
                        true );

                    if( !hasCityResult ) {

                            /* inside google getCityGoogle insert new city on the object */
                            /* flag true will trigger finalSendProfile() intead of finalSearch() */
                            getCityGoogle( 
                                form, 
                                form.business_address_city, 
                                rest.shortState.toUpperCase(), true );
                        } else { 

                            finalSendProfile( hasCityResult );
                        }

                } else {
                    
                    error.msg( "<h4 class='lead-red'>Google geocode fail on this given address.</h4>\n\
                            <a href='' class='close-msg  btn-lg btn btn-default'>CLOSE MSG</a>" )
                    console.log( "Google geocode fail on this given address" );
                    console.log( fail );
                    return false;
                }
            } )
            .fail( function ( fail ) {

                error.msg( "<h4 class='lead-red'>Google geocode fail on this given address.</h4>\n\
                            <a href='' class='close-msg  btn-lg btn btn-default'>CLOSE MSG</a>" )
                console.log( "Google geocode fail on this given address" );
                console.log( fail );
                return false;
            } );
        } else {
            
            return false;
        }
    }
    
    function finalSendProfile ( form_ ) {
            
        /*  THIS FUNCTION IS READY   */
        
        $.post( 'profile_ajax.php', form_ )
            .done( function ( done, textStatus, jqXHR ) {
                
                console.log(done)
                try {
                    
                    done = jQuery.parseJSON( done );
                    
                } catch ( profile_ajax_response_did_not_parse ) {
                    
                    $( 'body' ).prepend( done );
                    error.msg( done + '<br/>' + profile_ajax_response_did_not_parse );
                }
                
                if( done.result && done.msg === 'first' ) {
                    
                    location.reload();
                } else if ( done.result ) {
                    
                    error.msg( done.msg );
                } else {
                    
                    error.msg( done.msg );
                }
            } )
            .fail( function ( fail ) {
                
                error.msg( "<h4 class='lead-red'>The request failed, please try again <br />" + fail + 
                        "</h4><a href='' class='close-msg  btn-lg btn btn-default'>CLOSE MSG</a>");
            } );
    } 
    
function checkForm ( form ) {
// \u2028\\u2029\
    var letter_numbers  = /^[((\r\n\|\n|\r)\&\.\;\,\'\"\@\!\?\%\$\_\-\+\=\%\$\:\#\\/(\)\*)0-9A-Za-z ]{1,}$/;
    var many_letters =    /^[((\r\n\|\n|\r)\&\.\;\,\'\"\@\!\?\%\$\_\-\+\=\%\$\:\#\\/(\)\*)0-9A-Za-z ]{1,250}$/;
    var only_letter     = /^[A-Za-z ]{3,30}$/;
    var ck_email        = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    var error           = [];
    var not_in_use1     = /^[0-9A-Za-z ]{1,}$/;
    var not_in_use2     = /^[a-zA-Z0-9:.,?!@][#$^]?$/;

    for ( var key in form ) {

        if ( key === 'last_time_login' || key === 'creation_time' ) {

            continue; 
        }
        if ( key === 'business_phone' ) {
            
            if ( isValidUSPhoneFormat( form[key] ) ) {
                
                if ( form[key].indexOf( '(' ) !== 0 && form[key].indexOf( ')' ) !== 3 ) {
                   
                    var phoneTest = formatPhone( form[key] );
                    
                    form[key] = phoneTest;
                } 
                
            } else {
                
                error[error.length] = 'Not a acceptible value in ' + key + '<br>' + form[key] + '. <br>'
                'Please only 10 numbers accept, please try again';
            }
            
        } else if ( key === 'facebook' || key === 'twitter' || key === 'googleplus' || key === 'url' ) {

            if ( form[key] !== null && form[key].length > 2 ) {

                var urlTest = checkUrl( form[key] );

                if ( !urlTest ) {

                    error[error.length] = 'Not a acceptible value in ' + key + '<br>' + form[key] + '. <br>\n\
                    Please confirm and type again try with protocol ( example => http://someWhere.com ) <br> \n\
                    or without protocol ( example => //someWhere.com ).';
                } else {

                    form[key] = urlTest;
                }
            }
        } else if ( key === 'description' || key === 'b_b_text' ) {
            
            if ( form[key].length < 51 ) {
                
                error[ error.length ] = 'Not a acceptible value in ' + key + '<br>' + form[key] +
                                        'Please minimun 50 chars ' + form[key].length;
            }
            
            if( form[key].length > 149 ) {
            
                error[ error.length ] = 'Not a acceptible value in ' + key + '<br>' + form[key] +
                                        'Please maximun 150 chars ' + form[key].length;
            }
            
            // check the min and max length first
            if ( !many_letters.test( form[key] ) ) {
                
                error[ error.length ] = 'Not a acceptible value in ' + key + '<br>' + form[key] +
                                        'Please between 50 and 150 chars, or strage chars inserted ' + form[key].length;
            }
        } else {

            if( !letter_numbers.test( form[key] ) && form[key].length > 2 ) {

                error[ error.length ] = 'Not a acceptible value in ' + key + '<br>' + form[key] + '   ' + form[key].length; 
            }
        }
    }

    if( error.length > 0 ) {

        reportError( error );
        return false;
    } 
    return form;
}

    function reportError( error_ ) {
    
        var msg = "<div class='lead-red'><h4>Please Enter Valide Data...</h4>";
        for ( var i = 0; i < error_.length; i++ ) {
            
            var numError = i + 1;
            msg += "<p style='font-size: 12px;font-weight: normal;'>" + "\n" + numError + ": " + error_[i] + "</p>";
        }
        
        msg += "</div><a href='' class='close-msg btn-lg btn btn-default'>CLOSE MSG</a>";
                
        error.msg( msg );
    }
    
    function phoneNumberValidate ( input_ ) {  
        
        var first_ = parseInt( input_[0] );
        if( typeof first_ === 'number' ) {
            
            return phoneNumberValidateHelper_( input_ );
        } else if ( first_ === '+' ) {
            
            return phoneNumberValidateHelper_( input_, true );
        } else {
            
            return false;
        }  
    }
    
    function phoneNumberValidateHelper_ ( value_, flag_ ) {
        
        
        var phone_ = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/; 
        if ( value_.match( phone_ ) ) {
            
            return formatPhone( value_, flag_ );    
        } else {
            
            return false;
        }
    }
    
    function formatPhone ( _value_ ) {
            
            var input_ = _value_;
            var input = input_.replace( /-/g, '' );
            
            var areaCode = input_.substring( 0, 3 );
            var exchange = input_.substring( 3, 6 );
            var tail = input_.substring( 6 );
            var return_ = '(' + areaCode + ')' + ' ' + exchange + '-' + tail;
            
            return return_;
        }
//]]>
</script>
