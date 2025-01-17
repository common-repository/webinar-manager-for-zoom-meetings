<?php
/**
 * Timezones AJAX handler
 *
 * @since   1.0.0
 * @author  Rajthemes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RZoomWebinarManagertLite_Timezone {

	public function __construct() {
		add_action( 'wp_ajax_set_timezone', array( $this, 'set_timezone' ) );
		add_action( 'wp_ajax_nopriv_set_timezone', array( $this, 'set_timezone' ) );
	}

	/**
	 * See timezone and show links accordingly
	 *
	 * @throws Exception
	 * @author Rajthemes
	 * @since 1.0.0
	 */
	public function set_timezone() {
		$user_timezone = filter_input( INPUT_POST, 'user_timezone' );
		$start_date    = filter_input( INPUT_POST, 'start_date' );
		$type          = filter_input( INPUT_POST, 'type' );

		$user_meeting_time = rzwm_dateConverter( $start_date, $user_timezone, false );
		$current_user_time = rzwm_dateConverter( 'now -1 hour', $user_timezone, false );
		$show_defined_post = apply_filters( 'rzwm_show_join_links_specific_postID', array() );
		$past_join_links   = get_option( 'zoom_past_join_links' );
		$post_id           = absint( filter_input( INPUT_POST, 'post_id' ) );
		if ( $current_user_time <= $user_meeting_time || $past_join_links || in_array( $post_id, $show_defined_post ) ) {
			if ( $type === "page" ) {
				wp_send_json_success( $this->output_join_links_page( $post_id ) );
			} else {
				$join_uri    = filter_input( INPUT_POST, 'join_uri' );
				$browser_url = filter_input( INPUT_POST, 'browser_url' );
				wp_send_json_success( $this->output_join_links_shortcodes( $join_uri, $browser_url ) );
			}
		} else {
			wp_send_json_error( apply_filters( 'rzwmzoom_shortcode_link_not_valid_anymore', __( 'This meeting is no longer valid and cannot be joined !', 'webinar-manager-for-zoom-meetings' ) ) );
		}

		wp_die();
	}

	/**
	 * Show join links from here for pages
	 *
	 * @param $post_id
	 *
	 * @return false|string
	 * @author Rajthemes
	 *
	 */
	private function output_join_links_page( $post_id ) {
		unset( $GLOBALS['zoom'] );
		unset( $GLOBALS['vanity_enabled'] );
		$GLOBALS['zoom']           = get_post_meta( $post_id, '_meeting_zoom_details', true );
		$GLOBALS['zoom']->post_id  = $post_id;
		$GLOBALS['vanity_enabled'] = get_option( 'zoom_vanity_url' );

		ob_start(); //Init the output buffering
		$template = rzwm_get_template( 'fragments/join-links.php', false );
		include $template;
		$content = ob_get_clean(); //Get the buffer and erase it

		return $content;
	}

	/**
	 * Output join links for shortcode
	 *
	 * @param $join_uri
	 * @param $browser_url
	 *
	 * @return false|string
	 * @deprecated 3.3.1
	 */
	private function output_join_links_shortcodes( $join_uri, $browser_url ) {
		ob_start(); //Init the output buffering
		$template = rzwm_get_template( 'shortcode/join-links.php', false );
		include $template;
		$content = ob_get_clean(); //Get the buffer and erase it

		return $content;
	}

}

new RZoomWebinarManagertLite_Timezone();