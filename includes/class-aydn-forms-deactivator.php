<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/lizatgit/test
 * @since      1.0.0
 *
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Aydn_Forms
 * @subpackage Aydn_Forms/includes
 * @author     Shamber Shepherd <shambershepherd@gmail.com>
 */
class Aydn_Forms_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Get signup page id.
		$signup_form_id = get_option( 'aydn_signup_form_id' );

		// Check if the page id exists.
		if ( $signup_form_id ) {

			// Delete signup page.
			wp_delete_post( $signup_form_id, true );

			// Delete saved page id record in the database.
			delete_option( 'aydn_signup_form_id' );

		}

		// Get my-aydn page id.
		$my_aydn_page_id = get_option( 'my_aydn_page_id' );

		// Check if the page id exists.
		if ( $my_aydn_page_id ) {

			// Delete signup page.
			wp_delete_post( $my_aydn_page_id, true );

			// Delete saved page id record in the database.
			delete_option( 'my_aydn_page_id' );

		}
	}

}
