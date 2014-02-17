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


public function add_tracking_code( $newsletter_id ) {
	if ( $this->should_tracker_be_output() ) {
		require_once( MailPoet_Piwik_Addon()->plugin_path . 'classes/class-mailpoet-piwik-addon-tracker.php' );

		$order = new WC_Order( $order_id );
		new WC_Piwik_Tracker( $order );
	}
}

private function should_tracker_be_output() {
	if ( current_user_can('manage_options') )
		return false;

	if ( ! $this->is_wp_piwik_plugin_set_for_tracking() )
		return false;

	if ( ! $this->is_wp_piwik_plugin_active() )
		return false;

	return true;
}

private function is_wp_piwik_plugin_set_for_tracking() {
	$wp_piwik_global_settings = get_option( 'wp-piwik_global-settings' );

	return ( isset( $wp_piwik_global_settings['add_tracking_code'] ) && $wp_piwik_global_settings['add_tracking_code'] );
}

private function is_wp_piwik_plugin_active() {
	return ( isset( $GLOBALS['wp_piwik'] ) );
}


?>