<?php
/**
 * Users Controller
 *
 * @since   1.0.0
 * @author  Rajthemes
 */

class RZoomWebinarManagertLite_Admin_Users {

	public static $message = '';
	public $settings;

	/**
	 * List meetings page
	 *
	 * @since   1.0.0
	 */
	public static function list_users() {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );

		//Check if any transient by name is available
		if ( isset( $_GET['flush'] ) == true ) {
			rzwmzoom_manager_delete_user_cache();
			self::set_message( 'updated', esc_html__( "Flushed User Cache!", "webinar-manager-for-zoom-meetings" ) );
		}

		//Get Template
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tpl-list-users.php';
	}

	/**
	 * Add Zoom users view
	 *
	 * @since   1.0.0
	 */
	public static function add_zoom_users() {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );

		if ( isset( $_POST['add_zoom_user'] ) ) {
			check_admin_referer( '_zoom_add_user_nonce_action', '_zoom_add_user_nonce' );
			$postData = array(
				'action'     => filter_input( INPUT_POST, 'action' ),
				'email'      => sanitize_email( filter_input( INPUT_POST, 'email' ) ),
				'first_name' => sanitize_text_field( filter_input( INPUT_POST, 'first_name' ) ),
				'last_name'  => sanitize_text_field( filter_input( INPUT_POST, 'last_name' ) ),
				'type'       => filter_input( INPUT_POST, 'type' )
			);

			$created_user = zoom_conference()->createAUser( $postData );
			$result       = json_decode( $created_user );
			if ( ! empty( $result->code ) ) {
				self::set_message( 'error', $result->message );
			} else {
				self::set_message( 'updated', esc_html__( "Created a User. Please check email for confirmation. Added user will only appear in the list after approval.", "webinar-manager-for-zoom-meetings" ) );

				//After user has been created delete this transient in order to fetch latest Data.
				rzwmzoom_manager_delete_user_cache();
			}
		}

		require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tpl-add-user.php';
	}

	static function assign_host_id() {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );

		if ( isset( $_POST['saving_host_id'] ) ) {
			check_admin_referer( '_zoom_assign_hostid_nonce_action', '_zoom_assign_hostid_nonce' );

			$host_ids = filter_input( INPUT_POST, 'zoom_host_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			foreach ( $host_ids as $k => $host_id ) {
				update_user_meta( $k, 'user_zoom_hostid', $host_id );
			}

			self::set_message( 'updated', esc_html__( "Saved !", "webinar-manager-for-zoom-meetings" ) );
		}

		require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tpl-assign-host-id.php';
	}

	static function get_message() {
		return self::$message;
	}

	static function set_message( $class, $message ) {
		self::$message = '<div class=' . $class . '><p>' . $message . '</p></div>';
	}
}

new RZoomWebinarManagertLite_Admin_Users();