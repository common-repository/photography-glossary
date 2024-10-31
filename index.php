<?php
/* 
 * Plugin Name:   Photography Glossary
 * Version:       1.2.1
 * Plugin URI:    http://www.dimbal.com/
 * Description:   Add this plugin to display a random Photography term from the Dimbal Photo Glossary.  Plugin comes with a widget that can be included in your sidebar or footer.
 * Author:        Ben Hall
 * Author URI:    http://www.benhallbenhall.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit();	// sanity check

//error_reporting(E_ALL);
//ini_set('display_errors','On');

//Let's grab the data from the text file and display it
$dmbl_pg_file = file_get_contents('jsonResults.txt', true);
$dmbl_pg_items = json_decode($dmbl_pg_file);
$dmbl_pg_items = (array)$dmbl_pg_items;
$dmbl_pg_letter = $dmbl_pg_items[array_rand($dmbl_pg_items)];
$dmbl_pg_letter = (array)$dmbl_pg_letter;
$dmbl_pg_item = $dmbl_pg_letter[array_rand($dmbl_pg_letter)];

$dmbl_pg_time_diff = $dmbl_pg_end_time - $dmbl_pg_start_time;

register_activation_hook(__FILE__, 'dmbl_pg_activate');

//Function that is run when the plugin is activated
function dmbl_pg_activate(){
	
}

//Grab a random item and display it
function dmbl_pg_display_item(){
	global $dmbl_pg_item;
	echo '<div>';
	echo '<p><span style="font-weight:bold;">'.$dmbl_pg_item->title.'</span> : '.$dmbl_pg_item->content.'</p>';
	echo '</div>';	
}



//Widget class for determining when to display the Glossary Term
class DMBL_PG_Term extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
	 		'dmbl_pg_term', // Base ID
			'Dimbal Photo Glossary', // Name
			array( 'description' => __( 'Add this widget to display a random Photography term from the Dimbal Photo Glossary.', 'text_domain' ), ) // Args
		);
	}

 	public function form( $instance ) {
		// outputs the options form on admin
		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
		}
		else {
			$title = __( 'Photography Glossary' );
		}
?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

<?php 
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
		
		//Title information
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( $instance['title'] );
			echo $args['after_title'];
		}
		
		//Display the actual word to printout
		dmbl_pg_display_item();
	}

}

//Now register the widgets into the system
function dimbal_pg_register_widgets() {
	register_widget( 'DMBL_PG_Term' );
}
add_action( 'widgets_init', 'dimbal_pg_register_widgets' );


//ADMIN MENU PAGE
add_action( 'admin_menu', 'dimbal_pg_plugin_menu' );
function dimbal_pg_plugin_menu() {
	add_options_page( 'Dimbal Photography Glossary Settings', 'Dimbal Photography Glossary', 'manage_options', 'dimbal-pg-plugin-options', 'dimbal_pg_plugin_options' );
}
function dimbal_pg_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	?>
	<div style="float:right; margin: 10px 10px 0 0"><a href="http://www.dimbal.com"><img src="http://www.dimbal.com/images/logo_300.png" alt="Dimbal Software" /></a></div>
	<h1>The Dimbal Photography Glossary</h1>
	<p style="font-style:italic; font-size:larger;">Easily add pre-made Photography Definitions to your website in 3 simple steps.</p>
	<hr />
	<div style="display:table; width:100%;">
		<div style="display:table-cell; width:auto; vertical-align:top;">
			<!-- LEFT SIDE CONTENT -->
			<h4>Usage Instructions</h4>
			<p>This plugin comes ready to use right out of the box.  Below are some tips to help you include the photo glossary in your posts.</p>
			<p>1. On the Widget Settings page within Wordpress, find the Dimbal Photo Glossary widget and drag it to the sidebar location of your choosing.</p>
			<p><img src="http://www.dimbal.com/images/dimbal-photo-glossary-widget.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>2. Customize the title as you see appropriate.  Click the save button once finished.</p>
			<p><img src="http://www.dimbal.com/images/dimbal-photo-glossary-customize.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>3. That's it.  A random photography term and definition will now show in your Wordpress site.</p>
			<p><img src="http://www.dimbal.com/images/dimbal-photo-glossary-definition.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<br /><br />
		</div>
		<div style="display:table-cell; width:300px; padding-left:20px; vertical-align:top;">
			<!-- RIGHT SIDE CONTENT -->
			<h4>Did you like this Plugin?  Please help it grow.</h4>
			<div style="text-align:center;"><a href="http://wordpress.org/support/view/plugin-reviews/photography-glossary">Rate this Plugin on Wordpress</a></div>
			<br />
			<div style="text-align:center;">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="5GMXFKZ79EJFA">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
			<hr />
			<h4>Follow us for Free Giveaways and more...</h4>
			<div id="fb-root"></div>
			<script type="text/javascript">
			  // Additional JS functions here
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId      : '539348092746687', // App ID
			      //channelUrl : '//<?=(URL_ROOT)?>channel.html', // Channel File
			      status     : true, // check login status
			      cookie     : true, // enable cookies to allow the server to access the session
			      xfbml      : true,  // parse XFBML
			      frictionlessRequests: true,  //Enable Frictionless requests
			    });
			  };

			  // Load the SDK Asynchronously
			  (function(d){
			     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			     if (d.getElementById(id)) {return;}
			     js = d.createElement('script'); js.id = id; js.async = true;
			     js.src = "//connect.facebook.net/en_US/all.js";
			     ref.parentNode.insertBefore(js, ref);
			   }(document));
			</script>
			<div style="text-align:center;"><div class="fb-like" data-href="https://www.facebook.com/dimbalsoftware" data-send="false" data-layout="standard" data-show-faces="false" data-width="200"></div></div>
			<hr />
			<h4>Questions?  Support?  Record a Bug?</h4>
			<p>Need help with this plugin? Visit...</p>
			<p><a href="http://www.dimbal.com/support">http://www.dimbal.com/support</a></p>
			<hr />
			<h4>Other great Dimbal Products</h4>
			<div class="dbmWidgetWrapper" dbmZone="18"></div>
			<div class="dbmWidgetWrapper" dbmZone="19"></div>
			<div class="dbmWidgetWrapper" dbmZone="20"></div>
			<a href="http://www.dimbal.com">Powered by the Dimbal Banner Manager</a>
		</div>
	</div>
	<?
	wp_enqueue_script('dbmScript','http://www.dimbal.com/dbm/banner/dbm.js', false);
}