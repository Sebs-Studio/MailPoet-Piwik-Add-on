<?php
/**
 * MailPoet Piwik Add-on Admin Functions
 *
 * @author 		Sebs Studio
 * @category 	Core
 * @package 	MailPoet Piwik Add-on/Admin/Functions
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get all MailPoet Piwik Add-on screen ids
 *
 * @return array
 */
function mailpoet_piwik_addon_get_screen_ids() {
	$mailpoet_piwik_addon_screen_id = strtolower( str_replace ( ' ', '-', __( 'MailPoet Piwik Add-on', 'mailpoet_piwik_addon' ) ) );

	return apply_filters( 'mailpoet_piwik_addon_screen_ids', array(
		'toplevel_page_' . $mailpoet_piwik_addon_screen_id,
	) );
}

?>