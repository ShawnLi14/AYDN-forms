<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/lizatgit/test
 * @since      1.0.0
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/public
 * @author     Shamber Shepherd <shambershepherd@gmail.com>
 */
class Aydn_Forms_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aydn_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aydn_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aydn-forms-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aydn_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aydn_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aydn-forms-public.js', array( 'jquery' ), $this->version, false );

	}

	public function process_signup_form() {

	 	global $wpdb;
	  	$charset_collate = $wpdb->get_charset_collate();
	  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	 	$volunteers_tablename = $wpdb->prefix."aydn_volunteers";
	 	$sql = "SELECT email from $volunteers_tablename where email='%s'";  
	  	$emailRows = $wpdb->get_results($wpdb->prepare($sql, $_POST["email"]));
	  	if(count($emailRows) == 0){
	  		$data = array(
	            'name' => $_POST['name'],
	            'firstname' => $_POST['firstname'],
	            'lastname' => $_POST['lastname'],
	            'birthdate' => $_POST['birthdate'],
	            'email' => $_POST['email'],
	            'aydn_number' => $_POST['aydn_number'],
	            'resume' => $_POST['resume'],
	            'parent_contact' => $_POST['parent_contact']
	        );
	        /*$format = array(
	            '%s',
	            '%s'
	        );*/
	        $success=$wpdb->insert( $volunteers_tablename, $data);
	        if($success){
	            echo 'data has been saved' ; 
	        }
	  	}
	  	else{
	  		print("Email already exists!<br>");
	  		$url = $_SERVER['HTTP_REFERER'];
	  		print("<a href='$url'>Click Here to Return to Form</a>");
	  	}
		return;
	}

	public function register_shortcodes() {
	  add_shortcode( 'aydn_signup_form', array( $this, 'display_signup_form') );
	}

	public function display_signup_form(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/signup.php';
	}

}
