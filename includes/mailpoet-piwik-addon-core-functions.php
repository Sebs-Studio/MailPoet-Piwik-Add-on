<?php
/**
 * MailPoet Piwik Add-on Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet Piwik Add-on/Functions
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include core functions
include( 'mailpoet-piwik-addon-conditional-functions.php' );

// This function is added to the newsletter.
function add_tracking_code() {
	if ( should_tracker_be_output() ) {
		require_once( MailPoet_Piwik_Addon()->plugin_path . 'classes/class-mailpoet-piwik-addon-tracker.php' );

		$tracker = new MailPoet_Piwik_Addon_Tracker();
		$tracker->mailpoet_piwik_tracking();
	}
}

/**
 * This does a quick check before allowing the 
 * tracker to be added to the newsletters.
 */
function should_tracker_be_output() {
	if ( current_user_can('manage_options') )
		return false;

	if ( ! is_wp_piwik_plugin_set_for_tracking() )
		return false;

	if ( ! is_wp_piwik_plugin_active() )
		return false;

	return true;
}

/**
 * This checks if the settings for WP Piwik have been completed.
 */
function is_wp_piwik_plugin_set_for_tracking() {
	$wp_piwik_global_settings = get_option( 'wp-piwik_global-settings' );

	return ( isset( $wp_piwik_global_settings['add_tracking_code'] ) && $wp_piwik_global_settings['add_tracking_code'] );
}

/**
 * This checks if WP Piwik plugin is activated.
 */
function is_wp_piwik_plugin_active() {
	return ( isset( $GLOBALS['wp_piwik'] ) );
}

?>