<?php
include_once('/var/www/html/wp-config.php');
include_once('/var/www/html/wp-load.php');

define('SLF_REGISTRATION_INCLUDE_URL', plugin_dir_url(__FILE__).'includes/');
//global $bearer_token;
//add front end css
function slf_slider_trigger(){
	wp_enqueue_style('slf_caro_css_and_js', SLF_REGISTRATION_INCLUDE_URL."front-style.css"); 
	wp_enqueue_script('slf_caro_css_and_js');
}
add_action('wp_footer','slf_slider_trigger');





// function to login Shortcode
function slf_login_shortcode( $atts ) {

   //if looged in rediret to home page
 //   	 if ( is_user_logged_in() ) { 
	//     wp_redirect( get_option('home') );// redirect to home page
	// 	exit;  
	// }

	
    global $wpdb; 
	if(sanitize_text_field( $_GET['login'] ) != ''){
	 $login_fail_msg=sanitize_text_field( $_GET['login'] );
	}
	?>
	<div class="alar-login-form">
	<?php if($login_fail_msg=='failed'){?>
	<div class="error"  align="center"><?php _e('Username or password is incorrect','');?></div>
	<?php }?>
		<div class="alar-login-heading">
		<?php _e("Login Form",'');?>
		</div>
		<form method="post" action="<?php echo get_option('home');?>/wp-login.php" id="loginform" name="loginform" >
			<div class="ftxt">
			<label><?php _e('Login ID :','');?> </label>
			 <input type="text" tabindex="10" size="20" value="" class="input" id="user_login"  name="log" />
			</div>
			<div class="ftxt">
			<label><?php _e('Password :','');?> </label>
			  <input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" name="pwd" />
			</div>
			<div class="fbtn">
			<input type="submit" tabindex="100" value="Log In" class="button" id="wp-submit" name="wp-submit" />
			<input type="hidden" value="<?php echo get_option('home');?>" name="redirect_to">
			</div>
		</form>
	</div>
	<?php
}

//add login shortcoode
add_shortcode( 'simple-login-form', 'slf_login_shortcode' ,'writeMsg');


//redirect to front end ,when login is failed
//add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER']; 
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '/?login=failed' );  
      exit;
   }
}
	
?>

<?php
 if (isset($_POST['wp-submit']))
  {     
        include("config.php");
      //  session_start();
        $username=$_POST['log'];
        $password=$_POST['pwd'];
       // $_SESSION['login_user']=$username; 

//////////////////////////////

// global $wpdb; 
// $name = $_POST['log']; 
// $email = $_POST['log'];
// $table_name = $wpdb->prefix . "wpuser"; 
// $wpdb->insert( $table_name, array(
//         'name' => $name,
//         'email' => $email ) );

       
//////////////////////////////

require_once("autoload.php");

$t = new \theCodingCompany\Mastodon();

/**
 * Create a new App and get the client_id and client_secret
 */
//$token_info = $t->createApp("LastApp", "http://localhost/wp-login.php");


//Get the authorization url
$auth_url = $t->getAuthUrl();
/*
 * 1) Send the above URL '$auth_url' to the user. The need to authorize your App. 
 * 2) When they authorized your app, they will receive a token. The authorization token.
 * 3) Put the authorization token in the request below to exchange it for a bearer token.
 */

//Request the bearer token
$token_info = $t->getAccessToken("08164836757bcf69684723f9d71e3c85dfa3f0f0a8b089726b44d019050f4a4f");

/**
 * The above '$token_info' will now be an array with the info like below. (If successfull)
 * No these are not real, your right.
 * 
    {
        "client_id": "87885c2bf1a9d9845345345318d1eeeb1e48bb676aa747d3216adb96f07",
        "client_secret": "a1284899df5250bd345345f5fb971a5af5c520ca2c3e4ce10c203f81c6",
        "bearer": "77e0daa7f252941ae8343543653454f4de8ca7ae087caec4ba85a363d5e08de0d"
    }
 */

/**
 * Authenticate a user by username and password and receive the bearer token
 */
$bearer_token = $t->authUser($username,$password);
//echo $bearer_token;

//function writeMsg($atts) {
    //echo "$atts";
//}
//writeMsg($bearer_token);
if($bearer_token){

	global $wpdb;
	$name = $_POST['log'];
	$email = $_POST['log'];

	$table_name = $wpdb->prefix . "wpuser";
   
	include_once(ABSPATH . '/var/www/html/wp-config.php');
	$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$link = mysqli_select_db($connection, DB_NAME);
	if($link){
		echo "Success";
		////////////////

        $db_name = $wpdb->prefix . "users";
       // echo $table_name;
        $wpdb->insert( $db_name, array(
            'name' => $name,
            'email' => $email
        ) );

		///////////////
	}else{
		echo "Fail";
	}
    
}else{
	echo "Error";
}

//////////////////////////

if($bearer_token){
add_action( 'init', function () {
       
  $username = $_POST['log'];
  $password = $_POST['pwd'];
  $email_address = $_POST['log'];
    echo "if inside";

    

  if ( ! username_exists( $username ) ) 
  
        
    $user_id = wp_create_user( $username, $password, $email_address );
    $user = new WP_User( $user_id );
    $user->set_role( 'subscriber' );
    
    }
   );
}else{
	echo "Unauthenticate user";
}

/////////////////////////


//echo $bearer_token;
/**
 * Get the userinfo by authentication
 */

$user_info = $t->getUser($username,$password);

/**
 * Get user followers / following
 */
$followers = $t->authenticate($username,$password)
                ->getFollowers();

/**
 * Get user statusses
 */
$statusses = $t->authenticate($username,$password)
                ->getStatuses();


///////////////////////////////


        }

?>
