<?php
/**
 * Adds a help tab.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet Piwik Add-on/Admin
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_Piwik_Addon_Admin_Help' ) ) {

/**
 * MailPoet_Piwik_Addon_Admin_Help Class
 */
class MailPoet_Piwik_Addon_Admin_Help {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'current_screen', array( &$this, 'add_tabs' ), 10 );
	}

	/**
	 * Add help tabs
	 */
	public function add_tabs() {
		$screen = get_current_screen();

		if ( ! in_array( $screen->id, mailpoet_piwik_addon_get_screen_ids() ) )
			return;

		if( $_REQUEST['action'] == 'editDetails' ){

			$screen->add_help_tab( array(
				'id'	=> 'mailpoet_piwik_addon_bugs_tab',
				'title'	=> __( 'Piwik Tracking Addon', 'mailpoet_piwik_addon' ),
				'content'	=>

					'<p>' . sprintf( __( 'If you find a bug within <strong>%s</strong> you can create a ticket via <a href="%s">Github issues</a>. Ensure you read the <a href="%s">contribution guide</a> prior to submitting your report. Be as descriptive as possible.', 'mailpoet_piwik_addon' ), MailPoet_Piwik_Addon()->name, GITHUB_REPO_URL . 'issues?state=open', GITHUB_REPO_URL . 'blob/master/CONTRIBUTING.md' ) . '</p>'

			) );

		}

	}

} // end class.

} // end if class exists.

return new MailPoet_Piwik_Addon_Admin_Help();

?>