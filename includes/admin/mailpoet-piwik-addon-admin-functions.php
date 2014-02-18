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

/**
 * Extends step 3 by adding the option to track via Piwik.
 * @param type $fields
 * @return type
 */
function extend_step3_piwik_tracking($fields){
	$config = WYSIJA::get('config','model');

	// When this checkbox is ticked the piwik fields below show up.
	$fields['piwikenabled'] = array(
		'type' => 'checkbox',
		'isparams' => 'params',
		'class' => '',
		'label' => __('Enable to track with Piwik', WYSIJA),
		'desc' => '',
	);

	/*$fields['piwikbaseurl'] = array(
		'type' => 'input',
		'isparams' => 'params',
		'class' => 'required',
		'label' => __('Piwik Base URL', WYSIJA),
		'desc' => __('Enter your Piwik base url. For example, "http://yourdomain.com/piwik/". No idea what I\'m talking about? [link]Get help.[/link]', WYSIJA),
		'link' => '<a href="http://peepbo.de/board/viewtopic.php?f=5&t=10" target="_blank">'
	);*/

	// Not sure if this field will be needed if the user puts in the site ID number below.
	/*$fields['piwikauthtoken'] = array(
		'type' => 'input',
		'isparams' => 'params',
		'class' => 'required',
		'label' => __('Piwik Auth Token', WYSIJA),
		'desc' => __('Enter your personal Piwik authentification token. You can get the token on the API page inside your Piwik interface. It looks like "1234a5cd6789e0a12345b678cd9012ef"', WYSIJA),
	);*/

	$fields['piwiksiteid'] = array(
		'type' => 'input',
		'isparams' => 'params',
		'class' => 'required',
		'label' => __('Piwik Site ID', WYSIJA),
		'desc' => __('Enter the ID number of the site you are tracking', WYSIJA),
	);

	$fields['piwikcampaignname'] = array(
		'type' => 'input',
		'isparams' => 'params',
		'class' => 'required',
		'label' => __('Piwik Campaign Name', WYSIJA),
		'desc' => __('Enter the name of the campaign you are tracking', WYSIJA),
	);

	$fields['piwikcampaignkeyword'] = array(
		'type' => 'input',
		'isparams' => 'params',
		'class' => 'optional',
		'label' => __('Piwik Campaign Keyword', WYSIJA),
		'desc' => __('(optional) Used to track the keyword, or sub-category', WYSIJA),
	);

	// Need to work on this part more.
	if( isset( $_REQUEST['wysija']['email']['params']['piwikenabled']) ) {
		$data['email']['params']['piwiktrackingcode'] = $fields['piwiktrackingcode']['default'] = $_REQUEST['wysija']['email']['params']['piwiktrackingcode'];
	}

	return $fields;
}

/**
 * Display admin notice if tracking is not set in WP Piwik.
 */
function display_notice_piwik_set_tracking() {
	echo '<div id="message" class="error"><p>';
	echo sprintf( __('It appears that you have not set the tracking in WP Piwik. Please complete the <a href="%s">settings</a> in <strong>WP Piwik</strong> for tracking to work in <strong>MailPoet Newsletters</strong>.', 'mailpoet_piwik_addon'), admin_url('options-general.php?page=wp-piwik/wp-piwik.php') );
	echo '</p></div>';
}

?>