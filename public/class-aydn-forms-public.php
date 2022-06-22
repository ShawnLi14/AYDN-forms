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
		wp_enqueue_style( 'bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
		wp_enqueue_style( 'flatpickr', '//cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');

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
		wp_enqueue_script( 'bootstrap', "//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js", false );
		wp_enqueue_script( 'flatpickr', "//cdn.jsdelivr.net/npm/flatpickr", false);
		wp_enqueue_script( 'inputmask', "//cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8-beta.17/jquery.inputmask.min.js", false);

	}

	public function register_shortcodes() {
	  add_shortcode( 'aydn_signup_form', array( $this, 'display_signup_form') );
	  add_shortcode( 'my_aydn', array( $this, 'display_my_aydn'));
	}

	public function display_signup_form(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/signup.php';
	}

	public function display_my_aydn(){
		// check if user is logged in
		if ( !is_user_logged_in() ) {
		   // show no access page
			$signup_form_id = get_option( 'aydn_signup_form_id' );
			$signup_from_url = get_page_link( $signup_form_id );
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/no_access.php';
		} else {
		   // your code for logged out user 
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/my_aydn.php';
		}	
	}

	public function process_signup_form() {

	 	global $wpdb;
	  	$charset_collate = $wpdb->get_charset_collate();
	  	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	 	$volunteers_tablename = $wpdb->prefix."aydn_volunteers";
	 	$courses_tablename = $wpdb->prefix."aydn_courses";
	 	$sql = "SELECT email from $volunteers_tablename where email='%s'";  
	  	$emailRows = $wpdb->get_results($wpdb->prepare($sql, $_POST["email"]));
		$status = "New";
		if(substr($_POST['aydn_number'], 1, 1) != 0 || strlen($_POST['aydn_number']) != 5){
			$status = "Invalid";
		}
	  	if(count($emailRows) == 0){
	  		$volunteers_data = array(
	            'name' => $_POST['name'],
	            'firstname' => $_POST['firstname'],
	            'lastname' => $_POST['lastname'],
	            'birthdate' => $_POST['birthdate'],
	            'email' => $_POST['email'],
	            'aydn_number' => $_POST['aydn_number'],
	            'resume' => $_POST['resume'],
	            'parent_contact' => $_POST['parent_contact'],
				'status' => $status
	        );

	        $volunteers_success=$wpdb->insert( $volunteers_tablename, $volunteers_data);
	        $lastid = $wpdb->insert_id;
	        $courses_data = array(
	            'title' => $_POST['title'],
	            'introduction' => $_POST['introduction'],
	            'syllabus' => $_POST['syllabus'],
	            'start_date' => $_POST['start_date'],
	            'start_time' => $_POST['start_time'],
	            'length' => $_POST['length'],
	            'time_zone' => $_POST['time_zone'],
	            'duration' => $_POST['duration'],
	            'capacity' => $_POST['capacity'],
	            'photo_consent' => $_POST['photo_consent'],
	            'note' => $_POST['note'],
	            'volunteer_id' => $lastid
	        );

	        $courses_success=$wpdb->insert( $courses_tablename, $courses_data);
	        if($volunteers_success && $courses_success){
	            echo 'data has been saved' ; 
	        }
	  	}
	  	else{
	  		print("Email already exists!<br>");
	  		$url = $_SERVER['HTTP_REFERER'];
	  		print("<a href='$url'>If you are an existing AYDN volunteer, please log in. Otherwise click Here to Return to Form</a>");
	  	}
		return;
	}

}
