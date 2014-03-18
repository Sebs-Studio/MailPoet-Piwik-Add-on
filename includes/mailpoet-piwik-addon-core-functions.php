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

/**
 * This checks if the settings for WP Piwik have been completed.
 */
function is_wp_piwik_plugin_set_for_tracking() {
	$wp_piwik_global_settings = get_option( 'wp-piwik_global-settings' );

	return ( isset( $wp_piwik_global_settings['add_tracking_code'] ) && $wp_piwik_global_settings['add_tracking_code'] == '1' );
}

/**
 * This checks if WP Piwik plugin is activated.
 */
function is_wp_piwik_plugin_active() {
	return ( isset( $GLOBALS['wp_piwik'] ) );
}

?>