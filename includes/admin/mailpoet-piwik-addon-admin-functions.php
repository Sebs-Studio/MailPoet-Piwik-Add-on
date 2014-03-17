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
		'toplevel_page_wysija_campaigns'
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

	if( isset( $_REQUEST['wysija']['email']['params']['piwikenabled']) ) {
		$data['email']['params']['piwikcampaignname'] = $fields['piwikcampaignname']['default'] = $_REQUEST['wysija']['email']['params']['piwikcampaignname'];
		$data['email']['params']['piwikcampaignkeyword'] = $fields['piwikcampaignkeyword']['default'] = $_REQUEST['wysija']['email']['params']['piwikcampaignkeyword'];
	}

	return $fields;
}

/**
 * This tells MailPoet to apply 
 * Piwik tracking to the newsletter.
 * Applys the campaign and campaign keywords if any.
 */
function apply_piwik_tracking($email_url, $params) {
	if ( isset( $params['piwikenabled'] ) ) {
		if ( isset( $params['piwikcampaignname'] ) && isset( $email->params['piwikcampaignkeyword'] ) ) {
			$email_url = add_piwik_tracking_code( $email_url, trim( $email->params['piwikcampaignname'] ), trim( $email->params['piwikcampaignkeyword'] ) );
		}
		else if ( isset( $params['piwikcampaignname'] ) ) {
			$email_url = add_piwik_tracking_code( $email_url, trim( $email->params['piwikcampaignname'] ) );
		}
	}
}

/**
 * Embed Piwik tracking code into a link
 * @param string $link
 * @param string $campaign
 * @param string $keywords
 * @param string $media (email, web)
 * @return string
 */
function add_piwik_tracking_code( $link, $campaign, $keywords = '', $media = 'email' ) {
	$mailer = WYSIJA::get('helper', 'mailer'); // Access the mailer

	if( !$mailer->is_wysija_link($link) && $mailer->is_internal_link($link) ) {
		$hash_part_url = '';
		$argsp = array();

		if( strpos($link, '#') !== false ) {
			$hash_part_url = substr($link, strpos($link, '#'));
			$link = substr($link, 0, strpos($link, '#'));
		}

		if( !in_array( $argsp['utm_source'], $argsp ) ) {
			$argsp['utm_source']= 'wysija';
		}

		if( !in_array( $argsp['utm_medium'], $argsp ) ) {
			$argsp['utm_medium'] = !empty($media) ? trim($media) : 'email';
		}

		$argsp['pk_campaign'] = trim($campaign);

		// If keywords are also tracked, then prepare them.
		if( !empty( $keywords ) ) {
			$keywords = explode(',', $keywords); // Separates each keyword.
			$count_keywords = count($keywords); // Counts how many keywords are to be tracked.
			if( $count_keywords > 1 ) { // If there are more than one keyword then prepare the array for the url.
				foreach( $keywords as $keyword ) {
					$keywords[] = str_replace(' ', '%2C', $keyword . ' '); // Filters the commas at the end of each keyword.
				}
			}
			$argsp['pk_kwd'] = trim($keywords);
		}

		$link .= $mailer->get_started_character_of_param($link);
		$link .= http_build_query($argsp);
		$link .= $hash_part_url;
	}
	return $link;
}

/**
 * Display admin notice if tracking is not set in WP Piwik.
 */
function display_notice_piwik_set_tracking() {
	echo '<div id="message" class="error"><p>';
	echo sprintf( __('It appears that you have not set the tracking in WP Piwik. Please complete the <a href="%s">settings</a> in <strong>WP Piwik</strong> for tracking to work in <strong>MailPoet Newsletters</strong>.', MAILPOET_PIWIK_ADDON_TEXT_DOMAIN), admin_url('options-general.php?page=wp-piwik/wp-piwik.php') );
	echo '</p></div>';
}

?>