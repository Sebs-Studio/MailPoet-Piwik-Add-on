<?php
/**
 * MailPoet Piwik Add-on Admin.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet Piwik Add-on/Admin
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_Piwik_Addon_Admin' ) ) {

	class MailPoet_Piwik_Addon_Admin {

		/**
		 * Constructor
		 */
		public function __construct() {
			// Actions
			add_action( 'init', array( &$this, 'includes' ) );
		}

		/**
		 * Include any classes we need within admin.
		 */
		public function includes() {
			// Functions
			include( 'mailpoet-piwik-addon-admin-functions.php' );

			// Help
			if ( apply_filters( 'mailpoet_piwik_addon_enable_admin_help_tab', true ) ) {
				include( 'class-mailpoet-piwik-addon-admin-help.php' );
			}
		}

	} // end class.

} // end if class exists.

return new MailPoet_Piwik_Addon_Admin();

?>