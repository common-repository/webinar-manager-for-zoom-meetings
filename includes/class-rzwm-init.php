<?php
/**
 * Ready Main Class
 *
 * @since 1.0.0
 * @author Rajthemes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die( "Not Allowed Here !" );
}

final class RZoomWebinarManagertLite_With_Zoom {

	private static $_instance = null;

	/**
	 * Create only one instance so that it may not Repeat
	 *
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor method for loading the components
	 *
	 * @since  1.0.0
	 * @author Rajthemes
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->init_api();

		add_action( "admin_print_styles-post-new.php", array( 'RZoomWebinarManagertLite_With_Zoom', 'admin_end_css' ) );
		add_action( "admin_print_styles-post.php", array( 'RZoomWebinarManagertLite_With_Zoom', 'admin_end_css' ) );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * INitialize the hooks
	 *
	 * @since    1.0.0
	 * @modified 1.0.0
	 * @author   Rajthemes
	 */
	protected function init_api() {
		//Load the Credentials
		zoom_conference()->zoom_api_key    = get_option( 'zoom_api_key' );
		zoom_conference()->zoom_api_secret = get_option( 'zoom_api_secret' );
	}

	/**
	 * Load Frontend Scriptsssssss
	 *
	 * @author  Rajthemes
	 */
	function enqueue_scripts() {
		$minified = SCRIPT_DEBUG ? '.js' : '.min.js';
		wp_register_style( 'webinar-manager-for-zoom-meetings', RZWM_PLUGIN_URL . 'assets/public/css/main.min.css', false, RZWM_PLUGIN_VERSION );
		//Enqueue MomentJS
		wp_register_script( 'webinar-manager-for-zoom-meetings-moment', RZWM_PLUGIN_URL . 'assets/vendor/moment/moment.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-moment-locales', RZWM_PLUGIN_URL . 'assets/vendor/moment/moment-with-locales.min.js', array(
			'jquery',
			'webinar-manager-for-zoom-meetings-moment'
		), RZWM_PLUGIN_VERSION, true );
		//Enqueue MomentJS Timezone
		wp_register_script( 'webinar-manager-for-zoom-meetings-moment-timezone', RZWM_PLUGIN_URL . 'assets/vendor/moment-timezone/moment-timezone-with-data-10-year-range.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings', RZWM_PLUGIN_URL . 'assets/public/js/scripts' . $minified, array(
			'jquery',
			'webinar-manager-for-zoom-meetings-moment'
		), RZWM_PLUGIN_VERSION, true );
		if ( is_singular( 'zoom-meetings' ) ) {
			wp_enqueue_style( 'webinar-manager-for-zoom-meetings' );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment' );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-locales' );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-timezone' );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings' );
			// Localize the script with new data

			$zoom_started        = get_option( 'zoom_started_meeting_text' );
			$zoom_going_to_start = get_option( 'zoom_going_tostart_meeting_text' );
			$zoom_ended          = get_option( 'zoom_ended_meeting_text' );
			$translation_array   = apply_filters( 'rzwm_meeting_event_text', array(
				'meeting_started'  => ! empty( $zoom_started ) ? $zoom_started : esc_html__( 'Meeting Has Started ! Click below join button to join meeting now !', 'webinar-manager-for-zoom-meetings' ),
				'meeting_starting' => ! empty( $zoom_going_to_start ) ? $zoom_going_to_start : esc_html__( 'Click join button below to join the meeting now !', 'webinar-manager-for-zoom-meetings' ),
				'meeting_ended'    => ! empty( $zoom_ended ) ? $zoom_ended : esc_html__( 'This meeting has been ended by the host.', 'webinar-manager-for-zoom-meetings' ),
				'date_format'      => get_option( 'zoom_api_date_time_format' )
			) );
			wp_localize_script( 'webinar-manager-for-zoom-meetings', 'rzwm_strings', $translation_array );
		}

	}

	/**
	 * Load the other class dependencies
	 *
	 * @since    1.0.0
	 * @modified 1.0.0
	 * @author   Rajthemes
	 */
	protected function load_dependencies() {
		//Include the Main Class
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/api/class-rzwm-zoom-api-v2.php';

		//Loading Includes
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/helpers.php';

		//AJAX CALLS SCRIPTS
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-ajax.php';

		//Admin Classes
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-post-type.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-users.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-meetings.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-webinars.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-reports.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-recordings.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-sync.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-settings.php';

		//Timezone
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/class-rzwm-timezone.php';

		//Templates
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/rzwm-template-hooks.php';
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/rzwm-template-functions.php';

		//Shortcodes
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/class-rzwm-shortcodes.php';

		if ( did_action( 'elementor/loaded' ) ) {
			require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/class-rzwm-elementor.php';
		}

		//Idea was to implement gutenberg also but in its current state ! ughh !
	}

	/**
	 * Enqueuing Scripts and Styles for Admin
	 *
	 * @since    1.0.0
	 * @modified 1.0.0
	 * @author   Rajthemes
	 */
	public static function admin_end_css() {
		global $post;
		if ( ! in_array( $post->post_type, array(
			'zoom-meetings'
		) ) ) {
			return;
		}

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-timepicker', RZWM_PLUGIN_URL . 'assets/vendor/dtimepicker/jquery.datetimepicker.min.css', false, RZWM_PLUGIN_VERSION );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-select2', RZWM_PLUGIN_URL . 'assets/vendor/select2/css/select2.min.css', false, RZWM_PLUGIN_VERSION );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable', RZWM_PLUGIN_URL . 'assets/vendor/datatable/jquery.dataTables.min.css', false, RZWM_PLUGIN_VERSION );

		wp_register_script( 'webinar-manager-for-zoom-meetings-select2-js', RZWM_PLUGIN_URL . 'assets/vendor/select2/js/select2.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-timepicker-js', RZWM_PLUGIN_URL . 'assets/vendor/dtimepicker/jquery.datetimepicker.full.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-datable-js', RZWM_PLUGIN_URL . 'assets/vendor/datatable/jquery.dataTables.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );

		wp_enqueue_style( 'jquery-ui-datepicker-rzwm', RZWM_PLUGIN_URL . 'assets/admin/css/jquery-ui.css' );

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings', RZWM_PLUGIN_URL . 'assets/admin/css/webinar-manager-for-zoom-meetings.min.css', false, RZWM_PLUGIN_VERSION );
		wp_register_script( 'webinar-manager-for-zoom-meetings-js', RZWM_PLUGIN_URL . 'assets/admin/js/scripts.min.js', array(
			'jquery',
			'webinar-manager-for-zoom-meetings-select2-js',
			'webinar-manager-for-zoom-meetings-timepicker-js',
			'webinar-manager-for-zoom-meetings-datable-js',
			'underscore'
		), RZWM_PLUGIN_VERSION, true );

		wp_localize_script( 'webinar-manager-for-zoom-meetings-js', 'rzwm_ajax', array(
			'ajaxurl'         => admin_url( 'admin-ajax.php' ),
			'rzwm_security'   => wp_create_nonce( "_nonce_rzwm_security" ),
			'lang'            => array(
				'confirm_end' => esc_html__( "Are you sure you want to end this meeting ? Users won't be able to join this meeting shown from the shortcode.", "webinar-manager-for-zoom-meetings" )
			)
		) );

	}

	/**
	 * Load Plugin Domain Text here
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 */
	public function load_plugin_textdomain() {
		$domain = 'webinar-manager-for-zoom-meetings';
		apply_filters( 'plugin_locale', get_locale(), $domain );
		load_plugin_textdomain( $domain, false, 'webinar-manager-for-zoom-meetings' );
	}

	/**
	 * Fire on Activation
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 */
	public static function activate() {
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/admin/class-rzwm-admin-post-type.php';
		$post_type = new RZoomWebinarManagertLite_Admin_PostType();
		$post_type->register();

		self::install();
		flush_rewrite_rules();
	}

	public static function install() {
		global $wp_version;
		$min_wp_version = 4.8;
		$exit_msg       = sprintf( esc_html__( '%s requires %s or newer.' ), "Webinar Manager for Zoom Meetings", $min_wp_version );
		if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
			exit( $exit_msg );
		}

		//Comparing Version
		if ( version_compare( PHP_VERSION, 5.6, "<" ) ) {
			$exit_msg = '<div class="error"><h3>' . esc_html__( 'Warning! It is not possible to activate this plugin as it requires above PHP 5.4 and on this server the PHP version installed is: ', 'webinar-manager-for-zoom-meetings' ) . '<b>' . PHP_VERSION . '</b></h3><p>' . esc_html__( 'For security reasons we <b>suggest</b> that you contact your hosting provider and ask to update your PHP to latest stable version.', 'webinar-manager-for-zoom-meetings' ) . '</p><p>' . esc_html__( 'If they refuse for whatever reason we suggest you to <b>change provider as soon as possible</b>.', 'webinar-manager-for-zoom-meetings' ) . '</p></div>';
			exit( $exit_msg );
		}
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}
}