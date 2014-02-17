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

// Will run action and filter hooks if MailPoet is version 2.1.5
if ( WYSIJA::get_version() >= '2.1.5' ) {
	if ( isset( $_REQUEST['page'] ) ) {

		switch( $_REQUEST['page'] ) {

			case 'wysija_campaigns':
				$helper_extend_campaigns = WYSIJA::get('extend_campaigns', 'helper', false, WYSIJANLP);
				add_filter('wysija_extend_step3', array( $helper_extend_campaigns, 'extend_step3_piwik_tracking' ) );

			break;

		} // end switch

	} // end if $_REQUEST['page']

} // end if MailPoet version.
?>