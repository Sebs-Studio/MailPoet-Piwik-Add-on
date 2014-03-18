<?php
/**
 * Installation related functions and actions.
 *
 * @author 		Sebs Studio
 * @category 	Admin
 * @package 	MailPoet Piwik Add-on/Admin/Classes
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_Piwik_Addon_Install' ) ) {

	/**
	 * MailPoet_Piwik_Addon_Install Class
	 */
	class MailPoet_Piwik_Addon_Install {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			register_activation_hook( MAILPOET_PIWIK_ADDON_FILE, array( &$this, 'install' ) );

			add_action( 'admin_init', array( &$this, 'check_version' ), 5 );
			add_action( 'in_plugin_update_message-'.plugin_basename( dirname( dirname( __FILE__ ) ) ), array( &$this, 'in_plugin_update_message' ) );
		}

		/**
		 * check_version function.
		 *
		 * @access public
		 * @return void
		 */
		public function check_version() {
			if ( ! defined( 'IFRAME_REQUEST' ) && ( get_option( 'mailpoet_piwik_addon_version' ) != MailPoet_Piwik_Addon()->version ) ) {
				$this->install();
			}
		}

		/**
		 * Install MailPoet Piwik Add-on
		 */
		public function install() {
			// Update version
			update_option( 'mailpoet_piwik_addon_version', MailPoet_Piwik_Addon()->version );
		}

		/**
		 * Show details of plugin changes on Installed Plugin Screen.
		 *
		 * @return void
		 */
		function in_plugin_update_message() {
			$response = wp_remote_get( MAILPOET_PIWIK_ADDON_README_FILE );

			if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {

				// Output Upgrade Notice
				$matches = null;
				$regexp = '~==\s*Upgrade Notice\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote( MAILPOET_PIWIK_ADDON_VERSION ) . '\s*=|$)~Uis';

				if ( preg_match( $regexp, $response['body'], $matches ) ) {
					$notices = (array) preg_split('~[\r\n]+~', trim( $matches[1] ) );

					echo '<div style="font-weight: normal; background: #cc99c2; color: #fff !important; border: 1px solid #b76ca9; padding: 9px; margin: 9px 0;">';

					foreach ( $notices as $index => $line ) {
						echo '<p style="margin: 0; font-size: 1.1em; color: #fff; text-shadow: 0 1px 1px #b574a8;">' . preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line ) . '</p>';
					}

					echo '</div>';
				}

				// Output Changelog
				$matches = null;
				$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*-(.*)=(.*)(=\s*' . preg_quote( MAILPOET_PIWIK_ADDON_VERSION ) . '\s*-(.*)=|$)~Uis';

				if ( preg_match( $regexp, $response['body'], $matches ) ) {
					$changelog = (array) preg_split('~[\r\n]+~', trim( $matches[2] ) );

					echo ' ' . __( 'What\'s new:', 'mailpoet_piwik_addon' ) . '<div style="font-weight: normal;">';

					$ul = false;

					foreach ( $changelog as $index => $line ) {
						if ( preg_match('~^\s*\*\s*~', $line ) ) {
							if ( ! $ul ) {
								echo '<ul style="list-style: disc inside; margin: 9px 0 9px 20px; overflow:hidden; zoom: 1;">';
								$ul = true;
							}
							$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
							echo '<li style="width: 50%; margin: 0; float: left; ' . ( $index % 2 == 0 ? 'clear: left;' : '' ) . '">' . $line . '</li>';
						}
						else {
							if ( $ul ) {
								echo '</ul>';
								$ul = false;
							}
							echo '<p style="margin: 9px 0;">' . htmlspecialchars( $line ) . '</p>';
						}
					}

					if ($ul) {
						echo '</ul>';
					}

					echo '</div>';
				}
			}
		}

	} // end if class.

} // end if class exists.

return new MailPoet_Piwik_Addon_Install();

?>