<?php
/*
 * Plugin Name: MailPoet Piwik Add-on
 * Plugin URI: http://wordpress.org/plugins/mailpoet-piwik-add-on/
 * Description: Enables you to track analytics's with Piwik in your newsletters.
 * Version: 1.0.0
 * Author: Sebs Studio
 * Author URI: http://www.sebs-studio.com
 * Author Email: sebastien@sebs-studio.com
 * Requires at least: 3.7.1
 * Tested up to: 3.8.1
 *
 * Text Domain: mailpoet_piwik_addon
 * Domain Path: /languages/
 * Network: false
 *
 * Copyright: (c) 2014 Sebs Studio. (sebastien@sebs-studio.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package MailPoet_Piwik_Add_on
 * @author Sebs Studio
 * @category Core
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'MailPoet_Piwik_Addon' ) ) {

/**
 * Main MailPoet Piwik Addon Class
 *
 * @class MailPoet_Piwik_Addon
 * @version 1.0.0
 */
final class MailPoet_Piwik_Addon {

	/**
	 * Constants
	 */

	// Slug
	const slug = 'mailpoet_piwik_addon';

	// Text Domain
	const text_domain = 'mailpoet_piwik_addon';

	/**
	 * Global Variables
	 */

	/**
	 * The Plug-in name.
	 *
	 * @var string
	 */
	public $name = "MailPoet Piwik Add-on";

	/**
	 * The Plug-in version.
	 *
	 * @var string
	 */
	public $version = "1.0.0";

	/**
	 * The WordPress version the plugin requires minimum.
	 *
	 * @var string
	 */
	public $wp_version_min = "3.7.1";

	/**
	 * The MailPoet version the plugin requires minimum.
	 *
	 * @var string
	 */
	public $mp_version_min = "2.6.0.8";

	/**
	 * The single instance of the class
	 *
	 * @var 
	 */
	protected static $_instance = null;

	/**
	 * The Plug-in URL.
	 *
	 * @var string
	 */
	public $web_url = "http://wordpress.org/plugins/mailpoet-piwik-add-on/";

	/**
	 * The Plug-in documentation URL.
	 *
	 * @var string
	 */
	public $doc_url = "https://github.com/seb86/MailPoet-Piwik-Add-on/wiki/";

	/**
	 * GitHub Repo URL
	 *
	 * @var string
	 */
	public $github_repo_url = "https://github.com/seb86/MailPoet-Piwik-Add-on/";

	/**
	 * Main MailPoet Piwik Add-on Instance
	 *
	 * Ensures only one instance of  is loaded or can be loaded.
	 *
	 * @access public static
	 * @see MailPoet_Piwik_Addon()
	 * @return MailPoet Piwik Add-on - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		// Must be loaded before everything else.
		add_action( 'plugins_loaded', array( &$this, 'plugin_override' ) );

		// Hooks
		add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );
		add_action( 'init', array( &$this, 'init_mailpoet_piwik_addon' ), 0 );
	}

	/**
	 * Plugin row meta links
	 *
	 * @access public
	 * @param array $input already defined meta links
	 * @param string $file plugin file path and name being processed
	 * @return array $input
	 */
	public function plugin_row_meta( $input, $file ) {
		if ( plugin_basename( __FILE__ ) !== $file ) {
			return $input;
		}

		$links = array(
			'<a href="' . esc_url( $this->doc_url ) . '">' . __( 'Documentation', MAILPOET_PIWIK_ADDON_TEXT_DOMAIN ) . '</a>',
		);

		$input = array_merge( $input, $links );

		return $input;
	}

	/**
	 * Define Constants
	 *
	 * @access private
	 */
	private function define_constants() {
		define( 'MAILPOET_PIWIK_ADDON', $this->name ); // Plugin Name
		define( 'MAILPOET_PIWIK_ADDON_FILE', __FILE__ ); // Plugin file name
		define( 'MAILPOET_PIWIK_ADDON_VERSION', $this->version ); // Plugin version
		define( 'MAILPOET_PIWIK_ADDON_WP_VERSION_REQUIRE', $this->wp_version_min ); // WordPress requires to be...
		define( 'MAILPOET_PIWIK_ADDON_TEXT_DOMAIN', self::text_domain );

		define( 'MAILPOET_PIWIK_ADDON_README_FILE', 'http://plugins.svn.wordpress.org/mailpoet-piwik-addon/trunk/readme.txt' );

		define( 'GITHUB_REPO_URL' , $this->github_repo_url );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		define( 'MAILPOET_PIWIK_ADDON_SCRIPT_MODE', $suffix );

	}

	/**
	 * Loads requirements for the plugin before everything else.
	 *
	 * @access public
	 * @return MAILPOET_VERSION
	 */
	public function plugin_override() {
		if( class_exists( 'WYSIJA' ) ) {
			define( 'MAILPOET_VERSION', WYSIJA::get_version() );
		}
	}

	/**
	 * Checks that the WordPress setup meets the plugin requirements.
	 *
	 * @access private
	 * @global string $wp_version
	 * @return boolean
	 */
	private function check_requirements() {
		global $wp_version;

		require_once(ABSPATH.'/wp-admin/includes/plugin.php');

		if (!version_compare($wp_version, MAILPOET_PIWIK_ADDON_WP_VERSION_REQUIRE, '>=')) {
			add_action('admin_notices', array( &$this, 'display_req_notice' ) );
			return false;
		}

		if( function_exists( 'is_plugin_active' ) ) {
			if( !is_plugin_active( 'wp-piwik/wp-piwik.php' ) && !is_plugin_active( 'wysija-newsletters/index.php' ) ) {
				add_action('admin_notices', array( &$this, 'display_req_notice_mailpoet' ) );
				add_action('admin_notices', array( &$this, 'display_req_notice_piwik' ) );
				return false;
			}

			if( !is_plugin_active( 'wysija-newsletters/index.php' ) ) {
				add_action('admin_notices', array( &$this, 'display_req_notice_mailpoet' ) );
				return false;
			}
			else if( MAILPOET_VERSION < $this->mp_version_min ) {
				add_action('admin_notices', array( &$this, 'display_req_min_notice_mailpoet' ) );
				return false;
			}

			if( !is_plugin_active( 'wp-piwik/wp-piwik.php' ) ) {
				add_action('admin_notices', array( &$this, 'display_req_notice_piwik' ) );
				return false;
			}

			return true;
		}
	}

	/**
	 * Display the requirement notice for WordPress.
	 *
	 * @access static
	 */
	static function display_req_notice() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WordPress ' . MAILPOET_PIWIK_ADDON_WP_VERSION_REQUIRE . ' or higher. Please <a href="%s">upgrade</a> your WordPress setup.', MAILPOET_PIWIK_ADDON_TEXT_DOMAIN), MAILPOET_PIWIK_ADDON, admin_url( 'update-core.php' ) );
		echo '</p></div>';
	}

	/**
	 * Display the requirement notice for Piwik.
	 *
	 * @access static
	 */
	static function display_req_notice_piwik() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires WP-Piwik for this plugin to work. Please install and activate <strong><a href="%s">WP Piwik</a></strong> first.', MAILPOET_PIWIK_ADDON_TEXT_DOMAIN), MAILPOET_PIWIK_ADDON, admin_url('plugin-install.php?tab=search&type=term&s=WP+Piwik') );
		echo '</p></div>';
	}

	/**
	 * Display the requirement notice for MailPoet.
	 *
	 * @access static
	 */
	static function display_req_notice_mailpoet() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires MailPoet Newsletters for this plugin to work. Please install and activate <strong><a href="%s">MailPoet Newsletters</a></strong> first.', MAILPOET_PIWIK_ADDON_TEXT_DOMAIN), MAILPOET_PIWIK_ADDON, admin_url('plugin-install.php?tab=search&type=term&s=MailPoet+Newsletters+%28formerly+Wysija%29') );
		echo '</p></div>';
	}

	/**
	 * Display the version requirement notice for MailPoet.
	 *
	 * @access static
	 */
	static function display_req_min_notice_mailpoet() {
		echo '<div id="message" class="error"><p>';
		echo sprintf( __('Sorry, <strong>%s</strong> requires MailPoet Newsletters to be version %s or higher for the plugin to work. Please update MailPoet Newsletters.', MAILPOET_PIWIK_ADDON_TEXT_DOMAIN), MAILPOET_PIWIK_ADDON, $this->mp_version_min );
		echo '</p></div>';
	}

	/**
	 * Include required core files used through out the plugin.
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {
		include_once( 'includes/mailpoet-piwik-addon-core-functions.php' ); // Contains core functions for the plugin.

		if ( is_admin() ) {
			$this->admin_includes();
		}
	}

	/**
	 * Include required admin files.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		include_once( 'includes/admin/mailpoet-piwik-addon-admin-hooks.php' ); // Hooks used in the admin
		include_once( 'includes/admin/class-mailpoet-piwik-addon-install.php' ); // Install plugin
		include_once( 'includes/admin/class-mailpoet-piwik-addon-admin.php' ); // Admin section
	}

	/**
	 * Runs when the plugin is initialized.
	 *
	 * @access public
	 */
	public function init_mailpoet_piwik_addon() {
		// Define constants
		$this->define_constants();

		// Check plugin requirements
		$this->check_requirements();

		// Include required files
		$this->includes();

		// Set up localisation
		$this->load_plugin_textdomain();

		// Load JavaScript
		$this->register_scripts();
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any 
	 * following ones if the same translation is present.
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), self::text_domain );

		load_textdomain( self::text_domain, WP_LANG_DIR . "/" . self::slug . "/" . $locale . ".mo" );

		// Set Plugin Languages Directory
		// Plugin translations can be filed in the mailpoet-piwik-addon/languages/ directory
		// Wordpress translations can be filed in the wp-content/languages/ directory
		load_plugin_textdomain( self::text_domain, false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @access public
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Registers and enqueues javascripts for the 
	 * administration panel of the site.
	 *
	 * @access private
	 */
	private function register_scripts() {
		global $wp_locale;

		if ( is_admin() ) {

			$this->load_file( self::slug . '_admin_script', '/assets/js/admin/mailpoet-piwik-addon' . MAILPOET_PIWIK_ADDON_SCRIPT_MODE . '.js', true, array('jquery'), MailPoet_Piwik_Addon()->version );

		} // end if is_admin.

	} // end register_scripts

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 *
	 * @access private
	 */
	private function load_file( $name, $file_path, $is_script = false, $support = array(), $version = '' ) {
		$url = $this->plugin_url() . $file_path;
		$file = $this->plugin_path() . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, $support, $version );
				wp_enqueue_script( $name );
			}
			else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file

} // end class

} // end if class exists

/**
 * Returns the main instance of MailPoet_Piwik_Addon to prevent the need to use globals.
 *
 * @return 
 */
function MailPoet_Piwik_Addon() {
	return MailPoet_Piwik_Addon::instance();
}

// Global for backwards compatibility.
$GLOBALS['mailpoet_piwik_addon'] = MailPoet_Piwik_Addon();

?>