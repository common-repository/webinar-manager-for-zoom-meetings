<?php
/**
 * Registering the Pages Here
 *
 * @since   1.0.0
 * @author  Rajthemes
 */
class RZoomWebinarManagertLite_Admin_Views {

	public static $message = '';
	public $settings;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'rzwmzoom_menus' ) );
	}

	/**
	 * Register Menus
	 *
	 * @since   1.0.0
	 */
	public function rzwmzoom_menus() {
		$dashboard = add_menu_page( esc_html__( 'Webinar Manager for Zoom Meetings', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Webinar Manager for Zoom Meetings', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'webinar-manager-for-zoom-meetings', array(
			'RZoomWebinarManagertLite_Admin_Views',
			'dashboard'
		), 'dashicons-video-alt', 25 );
		add_action( 'admin_print_styles-' . $dashboard, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

		/* Dashboard submenu */
		$dashboard_submenu = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Webinar Manager for Zoom Meetings', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Dashboard', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'webinar-manager-for-zoom-meetings', array(
			'RZoomWebinarManagertLite_Admin_Views',
			'dashboard'
		) );
		add_action( 'admin_print_styles-' . $dashboard_submenu, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

		if ( get_option( 'zoom_api_key' ) && get_option( 'zoom_api_secret' ) && rzwmzoom_manager_get_user_transients() ) {

			add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'All Meetings', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'All Meetings', 'webinar-manager-for-zoom-meetings' ), 'edit_posts', 'edit.php?post_type=zoom-meetings' );
			add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Add New', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Add New', 'webinar-manager-for-zoom-meetings' ), 'edit_posts', 'post-new.php?post_type=zoom-meetings' );
			add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Category', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Category', 'webinar-manager-for-zoom-meetings' ), 'edit_posts', 'edit-tags.php?taxonomy=zoom-meeting&post_type=zoom-meetings' );

			$dashboard1 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Live Webinars', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Live Webinars', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-webinars', array(
				'RZoomWebinarManagertLite_Admin_Webinars',
				'list_webinars'
			) );
			add_action( 'admin_print_styles-' . $dashboard1, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard2 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Live Meetings', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Live Meetings', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-meetings', array(
				'RZoomWebinarManagertLite_Admin_Meetings',
				'list_meetings'
			) );
			add_action( 'admin_print_styles-' . $dashboard2, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard3 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Add Live Meeting', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Add Live Meeting', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-add-meeting', array(
				'RZoomWebinarManagertLite_Admin_Meetings',
				'add_meeting'
			) );
			add_action( 'admin_print_styles-' . $dashboard3, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard4 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Zoom Users', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Zoom Users', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-list-users', array(
				'RZoomWebinarManagertLite_Admin_Users',
				'list_users'
			) );
			add_action( 'admin_print_styles-' . $dashboard4, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard5 = add_submenu_page( 'webinar-manager-for-zoom-meetings', 'Add Users', esc_html__( 'Add Users', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-add-users', array(
				'RZoomWebinarManagertLite_Admin_Users',
				'add_zoom_users'
			) );
			add_action( 'admin_print_styles-' . $dashboard5, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard6 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Reports', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Reports', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-reports', array(
				'RZoomWebinarManagertLite_Reports',
				'zoom_reports'
			) );
			add_action( 'admin_print_styles-' . $dashboard6, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard7 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Recordings', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Recordings', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-recordings', array(
				'RZoomWebinarManagertLite_Recordings',
				'rzwm_zoom_recordings'
			) );
			add_action( 'admin_print_styles-' . $dashboard7, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

			$dashboard8 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Import', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Import', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-sync', array(
				'RZoomWebinarManagertLite_Sync',
				'render'
			) );
			add_action( 'admin_print_styles-' . $dashboard8, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );

		}

		$dashboard10 = add_submenu_page( 'webinar-manager-for-zoom-meetings', esc_html__( 'Settings', 'webinar-manager-for-zoom-meetings' ), esc_html__( 'Settings', 'webinar-manager-for-zoom-meetings' ), 'manage_options', 'zoom-webinar-settings', array(
			$this,
			'rzwmzoom_api_zoom_settings'
		) );
		add_action( 'admin_print_styles-' . $dashboard10, array( 'RZoomWebinarManagertLite_Admin_Views', 'enque_admin_assets' ) );
	}

	public static function enque_admin_assets() {
		/* Enqueue styles */
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-timepicker', RZWM_PLUGIN_URL . 'assets/vendor/dtimepicker/jquery.datetimepicker.min.css', false, RZWM_PLUGIN_VERSION );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-select2', RZWM_PLUGIN_URL . 'assets/vendor/select2/css/select2.min.css', false, RZWM_PLUGIN_VERSION );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable', RZWM_PLUGIN_URL . 'assets/vendor/datatable/jquery.dataTables.min.css', false, RZWM_PLUGIN_VERSION );

		wp_register_script( 'webinar-manager-for-zoom-meetings-select2-js', RZWM_PLUGIN_URL . 'assets/vendor/select2/js/select2.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-timepicker-js', RZWM_PLUGIN_URL . 'assets/vendor/dtimepicker/jquery.datetimepicker.full.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-datable-js', RZWM_PLUGIN_URL . 'assets/vendor/datatable/jquery.dataTables.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );

		wp_enqueue_style( 'jquery-ui-datepicker-rzwm', RZWM_PLUGIN_URL . 'assets/admin/css/jquery-ui.css' );

		//Plugin Scripts
		wp_enqueue_style( 'bootstrap', RZWM_PLUGIN_URL . 'assets/bootstrap/css/bootstrap.min.css' );
		wp_enqueue_style( 'font-awesome', RZWM_PLUGIN_URL . 'assets/bootstrap/css/font-awesome.min.css' );
		wp_enqueue_script( 'popper-js', RZWM_PLUGIN_URL . 'assets/bootstrap/js/popper.min.js', array( 'jquery' ), true, true );
		wp_enqueue_script( 'bootstrap-js', RZWM_PLUGIN_URL . 'assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), true, true );

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings', RZWM_PLUGIN_URL . 'assets/admin/css/webinar-manager-for-zoom-meetings.min.css', false, RZWM_PLUGIN_VERSION );
		wp_register_script( 'webinar-manager-for-zoom-meetings-js', RZWM_PLUGIN_URL . 'assets/admin/js/scripts.min.js', array(
			'jquery',
			'webinar-manager-for-zoom-meetings-select2-js',
			'webinar-manager-for-zoom-meetings-timepicker-js',
			'webinar-manager-for-zoom-meetings-datable-js',
			'underscore'
		), RZWM_PLUGIN_VERSION, true );

		wp_localize_script( 'webinar-manager-for-zoom-meetings-js', 'rzwm_ajax', 
			array(
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
				'rzwm_security'   => wp_create_nonce( "_nonce_rzwm_security" ),
				'lang'            => array(
					'confirm_end' => esc_html__( "Are you sure you want to end this meeting ? Users won't be able to join this meeting shown from the shortcode.", "webinar-manager-for-zoom-meetings" )
				)
			) 
		);
	}

	public static function dashboard() {
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tpl-dashboard.php';
	}

	/**
	 * Zoom Settings View File
	 *
	 * @since   1.0.0
	 */
	public function rzwmzoom_api_zoom_settings() {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings' );

		rzwmzoom_api_show_like_popup();

		$tab        = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$active_tab = isset( $tab ) ? $tab : 'api-settings';
		?>
		<div class="main-panel">
		    <div class="content-wrapper">
		        <div class="page-header">
		            <h3 class="page-title">
		                <span class="page-title-icon bg-gradient-primary text-white mr-2">
		                <i class="fas fa-tools"></i>                 
		                </span>
		                <?php esc_html_e( 'Zoom Integration Settings', 'webinar-manager-for-zoom-meetings' ); ?>
		            </h3>
		        </div>
		        <div class="row">
		            <div class="col-lg-12 mb-12">
		                <div class="card shadow mb-12">
		                    <div class="card-header py-3">
		                        <h6 class="m-0 font-weight-bold text-primary"><?php esc_html_e( 'Settings', 'webinar-manager-for-zoom-meetings' ); ?></h6>
		                    </div>
		                    <div class="card-body">
		                    	<h2 class="nav-tab-wrapper settings-tabs">
					                <a href="<?php echo add_query_arg( array( 'tab' => 'api-settings' ) ); ?>" class="nav-tab <?php echo ( 'api-settings' === $active_tab ) ? esc_attr( 'nav-tab-active' ) : ''; ?>">
										<?php esc_html_e( 'API Settings', 'webinar-manager-for-zoom-meetings' ); ?>
					                </a>
					                <a style="background: #bf5252;color: #fff;" href="<?php echo add_query_arg( array( 'tab' => 'shortcode' ) ); ?>" class="nav-tab <?php echo ( 'shortcode' === $active_tab ) ? esc_attr( 'nav-tab-active' ) : ''; ?>">
										<?php esc_html_e( 'Shortcode', 'webinar-manager-for-zoom-meetings' ); ?>
					                </a>
									<?php do_action( 'rzwm_admin_tabs_heading', $active_tab ); ?>
					            </h2>
					            <?php
									do_action( 'rzwm_admin_tabs_content', $active_tab );

									if ( 'api-settings' === $active_tab ) {
										if ( isset( $_POST['save_zoom_settings'] ) ) {
											//Nonce
											check_admin_referer( '_zoom_settings_update_nonce_action', '_zoom_settings_nonce' );
											$zoom_api_key                       = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_key' ) );
											$zoom_api_secret                    = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_secret' ) );
											$vanity_url                         = esc_url_raw( filter_input( INPUT_POST, 'vanity_url' ) );
											$join_links                         = filter_input( INPUT_POST, 'meeting_end_join_link' );
											$zoom_author_show                   = filter_input( INPUT_POST, 'meeting_show_zoom_author_original' );
											$started_mtg                        = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_meeting_started_text' ) );
											$going_to_start                     = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_meeting_goingtostart_text' ) );
											$ended_mtg                          = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_meeting_ended_text' ) );
											$locale_format                      = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_date_time_format' ) );
											$twentyfour_format                  = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_twenty_fourhour_format' ) );
											$full_month_format                  = sanitize_text_field( filter_input( INPUT_POST, 'zoom_api_full_month_format' ) );
											$embed_pwd_in_join_link             = sanitize_text_field( filter_input( INPUT_POST, 'embed_password_join_link' ) );
											$hide_join_links_non_loggedin_users = sanitize_text_field( filter_input( INPUT_POST, 'hide_join_links_non_loggedin_users' ) );

											update_option( 'zoom_api_key', $zoom_api_key );
											update_option( 'zoom_api_secret', $zoom_api_secret );
											update_option( 'zoom_vanity_url', $vanity_url );
											update_option( 'zoom_past_join_links', $join_links );
											update_option( 'zoom_show_author', $zoom_author_show );
											update_option( 'zoom_started_meeting_text', $started_mtg );
											update_option( 'zoom_going_tostart_meeting_text', $going_to_start );
											update_option( 'zoom_ended_meeting_text', $ended_mtg );
											update_option( 'zoom_api_date_time_format', $locale_format );
											update_option( 'zoom_api_full_month_format', $full_month_format );
											update_option( 'zoom_api_twenty_fourhour_format', $twentyfour_format );
											update_option( 'zoom_api_embed_pwd_join_link', $embed_pwd_in_join_link );
											update_option( 'zoom_api_hide_shortcode_join_links', $hide_join_links_non_loggedin_users );

											//After user has been created delete this transient in order to fetch latest Data.
											rzwmzoom_manager_delete_user_cache();
											?>
						                    <div id="message" class="notice notice-success is-dismissible">
						                        <p><?php esc_html_e( 'Successfully Updated. Please refresh this page.', 'webinar-manager-for-zoom-meetings' ); ?></p>
						                        <button type="button" class="notice-dismiss">
						                            <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'webinar-manager-for-zoom-meetings' ); ?></span>
						                        </button>
						                    </div>
											<?php
										}

										//Defining Varaibles
										$zoom_api_key                = get_option( 'zoom_api_key' );
										$zoom_api_secret             = get_option( 'zoom_api_secret' );
										$zoom_vanity_url             = get_option( 'zoom_vanity_url' );
										$past_join_links             = get_option( 'zoom_past_join_links' );
										$zoom_author_show            = get_option( 'zoom_show_author' );
										$zoom_started                = get_option( 'zoom_started_meeting_text' );
										$zoom_going_to_start         = get_option( 'zoom_going_tostart_meeting_text' );
										$zoom_ended                  = get_option( 'zoom_ended_meeting_text' );
										$locale_format               = get_option( 'zoom_api_date_time_format' );
										$twentyfour_format           = get_option( 'zoom_api_twenty_fourhour_format' );
										$full_month_format           = get_option( 'zoom_api_full_month_format' );
										$embed_password_join_link    = get_option( 'zoom_api_embed_pwd_join_link' );
										$embed_password_join_link    = get_option( 'zoom_api_embed_pwd_join_link' );
										$hide_join_link_nloggedusers = get_option( 'zoom_api_hide_shortcode_join_links' );

										//Get Template
										require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tabs/api-settings.php';
									} else if ( 'shortcode' === $active_tab ) {
										require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tabs/shortcode.php';
									}
								?>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	<?php
	}

	static function get_message() {
		return self::$message;
	}

	static function set_message( $class, $message ) {
		self::$message = '<div class=' . $class . '><p>' . $message . '</p></div>';
	}
}

new RZoomWebinarManagertLite_Admin_Views();