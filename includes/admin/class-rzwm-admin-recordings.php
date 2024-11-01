<?php

/**
 * Class Recordings
 *
 * @author  Rajthemes
 * @since   1.0.0
 */
class RZoomWebinarManagertLite_Recordings {

	private static $instance;

	public function __construct() {
	}

	static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Zoom Recordings View
	 *
	 */
	public static function rzwm_zoom_recordings() {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-select2-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-js' );

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		//Check if any transient by name is available
		if ( isset( $_GET['host_id'] ) ) {
			$recordings = json_decode( zoom_conference()->listRecording( $_GET['host_id'] ) );
		}

		if ( ! empty( $recordings ) && ! empty( $recordings->code ) ) {
			echo '<p>' . $recordings->message . '</p>';
		} else {
			//Get Template
			require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tpl-list-recordings.php';
		}
	}

	/**
	 * Get Host selection HTML block
	 *
	 * @param $host_id
	 */
	public function get_hosts( $host_id ) {
		$users = rzwmzoom_manager_get_user_transients();
		?>
        <div class="select_rzwm_user_listings_wrapp">
            <div class="alignright">
                <select onchange="location = this.value;" class="rzwm-hacking-select">
                    <option value="?page=zoom-webinar-meetings"><?php _e( 'Select a User', 'webinar-manager-for-zoom-meetings' ); ?></option>
					<?php
					foreach ( $users as $user ) {
						$host_recordings_link = add_query_arg( array(
							'page'      => 'zoom-webinar-recordings',
							'host_id'   => $user->id
						), admin_url( 'admin.php' ) );
						?>
                        <option value="<?php echo esc_url( $host_recordings_link ); ?>" <?php echo $host_id == $user->id ? 'selected' : false; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
					<?php } ?>
                </select>
            </div>
            <div class="clear"></div>
        </div>
		<?php
	}

}

function rzwm_recordings() {
	return RZoomWebinarManagertLite_Recordings::getInstance();
}

rzwm_recordings();