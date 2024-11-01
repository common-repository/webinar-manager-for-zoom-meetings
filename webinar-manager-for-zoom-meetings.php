<?php
/**
 * Plugin Name: Webinar Manager for Zoom Meetings
 * Plugin URI:  
 * Description: Webinar Manager for Zoom Meetings plugin provides you with great functionality of managing Zoom meetings, Webinar scheduling options, and users directly from your WordPress dashboard.
 * Author:      RajThemes
 * Author URI:  https://rajthemes.com/
 * Version:     1.0.1
 * Text Domain: webinar-manager-for-zoom-meetings
 * Domain Path: /lang/
 **/

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

if ( ! defined( 'RZWM_PLUGIN_VERSION' ) ) {
	define( 'RZWM_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'RZWM_PLUGIN_AUTHOR' ) ) {
	define( 'RZWM_PLUGIN_AUTHOR', 'https://rajthemes.com/' );
}

if ( ! defined( 'RZWM_PLUGIN_URL' ) ) {
	define( 'RZWM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'RZWM_PLUGIN_DIR_PATH' ) ) {
	define( 'RZWM_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'RZWM_PLUGIN_BASENAME' ) ) {
	define( 'RZWM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'RZWM_PLUGIN_FILE' ) ) {
	define( 'RZWM_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'RZWM_PLUGIN_SLUG' ) ) {
	define( 'RZWM_PLUGIN_SLUG', 'webinar-manager-for-zoom-meetings' );
}

final class RZoomWebinarManagertLite {
	private static $instance = null;

	private function __construct() {
		$this->initialize_hooks();
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function initialize_hooks() {
		if ( ! class_exists( 'RZoomWebinarManagertLite_With_Zoom' ) ) {
			require_once RZWM_PLUGIN_DIR_PATH . 'includes/class-rzwm-init.php';
		}
		add_action( 'plugins_loaded', array( 'RZoomWebinarManagertLite_With_Zoom', 'instance' ), 99 );
		register_activation_hook( __FILE__, array( 'RZoomWebinarManagertLite_With_Zoom', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'RZoomWebinarManagertLite_With_Zoom', 'deactivate' ) );
	}
}
RZoomWebinarManagertLite::get_instance();