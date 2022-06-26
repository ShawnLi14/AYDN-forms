<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/ShawnLi14/AYDN-forms
 * @since      1.0.0
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/includes
 * @author     Shawn Li <shmorganl14@gmail.com>
 */
class Aydn_Forms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
       // create database tables
       Aydn_Forms_Activator::create_tables();

       // create pages
       $page_title = 'Signup';
       $page = get_page_by_title($page_title);
       if(!isset($page->title)){
			$signup_page_args = array(
				'post_title'   => __( $page_title, 'signup' ),
				'post_content' => '[aydn_signup_form]',
				'post_status'  => 'publish',
				'post_type'    => 'page'
			);
			// Insert the page and get its id.
			$signup_page_id = wp_insert_post( $signup_page_args );
			// Save page id to the database.
			add_option( 'aydn_signup_form_id', $signup_page_id ); 
		}

		$page_title = 'My AYDN';
        $page = get_page_by_title($page_title);
        if(!isset($page->title)){
			$my_aydn_page_args = array(
				'post_title'   => __( $page_title, 'my_aydn' ),
				'post_content' => '[my_aydn]',
				'post_status'  => 'publish',
				'post_type'    => 'page'
			);
			// Insert the page and get its id.
			$my_aydn_page_id = wp_insert_post( $my_aydn_page_args );
			// Save page id to the database.
			add_option( 'my_aydn_page_id', $my_aydn_page_id );     	      	
       }

	   // add an option for backup email address
	   add_option('aydn_backup_email', 'aydn.hours@gmail.com');

	}

	// Create database tables
	public static function create_tables(){

	  global $wpdb;
	  $charset_collate = $wpdb->get_charset_collate();
	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	  $volunteers_tablename = $wpdb->prefix."aydn_volunteers";
	  $sql = "CREATE TABLE IF NOT EXISTS $volunteers_tablename (
	  id mediumint(11) NOT NULL AUTO_INCREMENT,
	  name varchar(80) NOT NULL,
	  firstname varchar(50) NOT NULL,
	  lastname varchar(50) NOT NULL,
	  birthdate date NOT NULL,
	  email varchar(80) NOT NULL,
	  aydn_number varchar(50) NOT NULL,
	  resume varchar(65535),
	  parent_contact varchar(255),
	  status varchar(20) NOT NULL DEFAULT 'New',
	  date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 	  date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  PRIMARY KEY  (id)
	  ) $charset_collate;";  
	  dbDelta( $sql );

	  $hours_tablename = $wpdb->prefix."aydn_hours";
	  $sql = "CREATE TABLE IF NOT EXISTS $hours_tablename (
	  id mediumint(11) NOT NULL AUTO_INCREMENT,
	  volunteer_id mediumint(11) NOT NULL,
	  event_type varchar(50),
	  event_name varchar(250),
	  event_description varchar(65535),
	  event_date date,
	  start_time datetime,
	  end_time datetime,
	  hours varchar(20),
	  extra_hours varchar(20),
	  total_hours varchar(20),
	  others varchar(65535),
	  deny_reason varchar(65535),
	  status varchar(20) NOT NULL DEFAULT 'New',
	  date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 	  date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,	  
	  PRIMARY KEY  (id),
	  FOREIGN KEY (volunteer_id) REFERENCES $volunteers_tablename(id)
	  ) $charset_collate;";  
	  dbDelta( $sql );

	  $courses_tablename = $wpdb->prefix."aydn_courses";
	  $sql = "CREATE TABLE IF NOT EXISTS $courses_tablename (
	  id mediumint(11) NOT NULL AUTO_INCREMENT,
	  volunteer_id mediumint(11) NOT NULL,
	  title varchar(255) NOT NULL,
	  introduction varchar(65535),
	  syllabus varchar(65535),
	  start_date date,
	  start_time varchar(20),
	  length varchar(50),
	  time_zone varchar(50),
	  duration varchar(50),
	  capacity varchar(50),
	  photo_consent varchar(20),
	  note varchar(65535),
	  deny_reason varchar(65535),
	  status varchar(20) NOT NULL DEFAULT 'New',
	  date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 	  date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,	  
	  PRIMARY KEY  (id),
	  FOREIGN KEY (volunteer_id) REFERENCES $volunteers_tablename(id)
	  ) $charset_collate;";  
	  dbDelta( $sql );

	}
}
