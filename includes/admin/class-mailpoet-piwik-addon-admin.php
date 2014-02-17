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
			add_action( 'current_screen', array( $this, 'conditonal_includes' ) );
		}

		/**
		 * Include any classes we need within admin.
		 */
		public function includes() {
			// Functions
			include( 'mailpoet-piwik-addon-admin-functions.php' );
		}

		/**
		 * Include admin files conditionally
		 */
		public function conditonal_includes() {
			$screen = get_current_screen();

			switch ( $screen->id ) {
				case 'users' :
				case 'user' :
				case 'profile' :
				case 'user-edit' :

				break;
			}
		}

	} // end class.

} // end if class exists.

return new MailPoet_Piwik_Addon_Admin();

?>