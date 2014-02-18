<?php
/**
 * MailPoet Piwik Add-on Admin Hooks
 *
 * Hooks for various functions used in the admin.
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet Piwik Add-on/Admin
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Actions
add_action( 'plugins_loaded', 'extend_newsletters' );

if ( ! is_wp_piwik_plugin_set_for_tracking() ) {
	add_action('admin_notices', 'display_notice_piwik_set_tracking' );
}

/**
 * This function is called to allow other 
 * actions and filters to work if only 
 * MailPoet has been detected.
 */
function extend_newsletters() {
	// Will run action and filter hooks if MailPoet is version 2.1.5
	if ( WYSIJA::get_version() >= '2.1.5' ) {
		if ( isset( $_REQUEST['page'] ) ) {

			switch( $_REQUEST['page'] ) {

				case 'wysija_campaigns':
					// This will display the Piwik fields after Google Analytics Campaign
					add_filter('wysija_extend_step3', 'extend_step3_piwik_tracking', 20 );

				break;

			} // end switch

		} // end if $_REQUEST['page']

	} // end if MailPoet version.
}

?>