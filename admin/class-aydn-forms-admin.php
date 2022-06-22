<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/lizatgit/test
 * @since      1.0.0
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/admin
 * @author     Shamber Shepherd <shambershepherd@gmail.com>
 */
class Aydn_Forms_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aydn-forms-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aydn-forms-admin.js', array( 'jquery' ), $this->version, false );		
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'jquery-ui-slider' );

	}

	/**
	 * Display the settings page content for the page we have created.
	 *
	 * @since    1.0.0
	 */
	public function display_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/aydn-forms-admin-display.php';

	}	

	/**
	 * Register the settings page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_settings_page() {

		// Create our settings page as a submenu page.
		add_menu_page(
			//'tools.php',                             // parent slug
			__( 'AYDN Admin', 'aydn-forms-admin' ),      // page title
			__( 'AYDN Admin', 'aydn-forms-admin' ),      // menu title
			'manage_options',                        // capability
			'aydn-forms-admin',                           // menu_slug
			array( $this, 'display_settings_page' )  // callable function
		);
	}

}
