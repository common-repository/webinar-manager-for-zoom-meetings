<?php
/**
 * Shortcodes Controller
 *
 * @since   1.0.0
 * @author  Rajthemes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RZoomWebinarManagertLite_Shorcodes {

	/**
	 * Define post type
	 *
	 * @var string
	 */
	private $post_type = 'zoom-meetings';

	/**
	 * Meeting list
	 *
	 * @var string
	 */
	public static $meetings_list_number = '0';

	/**
	 * RZoomWebinarManagertLite_Shorcodes constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );
		
		// Meetings
		add_shortcode( 'rzwm_zoom_list_meetings', array( $this, 'show_meetings' ) );
		add_shortcode( 'rzwm_zoom_list_host_meetings', array( $this, 'show_host_meetings' ) );
		add_shortcode( 'rzwm_zoom_api_link', array( $this, 'render_main' ) );

		//Webinars
		add_shortcode( 'rzwm_zoom_api_webinar', array( $this, 'show_webinar' ) );
		add_shortcode( 'rzwm_zoom_list_host_webinars', array( $this, 'show_host_webinars' ) );

		//Embed Browser
		add_shortcode( 'rzwm_zoom_join_via_browser', array( $this, 'join_via_browser' ) );

		//Recordings
		add_shortcode( 'rzwm_zoom_recordings', array( $this, 'recordings' ) );
		add_shortcode( 'rzwm_zoom_recordings_by_meeting', array( $this, 'recordings_meeting_id' ) );
	}

	/**
	 * Enqueuing Scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings' );
		wp_register_script( 'webinar-manager-for-zoom-meetings-browser-js', RZWM_PLUGIN_URL . 'assets/public/js/join-browser.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
		wp_register_style( 'webinar-manager-for-zoom-meetings-datable', RZWM_PLUGIN_URL . 'assets/vendor/datatable/jquery.dataTables.min.css', false, RZWM_PLUGIN_VERSION );
		wp_register_style( 'webinar-manager-for-zoom-meetings-datable-responsive', RZWM_PLUGIN_URL . 'assets/vendor/datatable-responsive/responsive.dataTables.min.css', false, RZWM_PLUGIN_VERSION );
		wp_register_script( 'webinar-manager-for-zoom-meetings-datable-js', RZWM_PLUGIN_URL . 'assets/vendor/datatable/jquery.dataTables.min.js', [ 'jquery' ], RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-datable-dt-responsive-js', RZWM_PLUGIN_URL . 'assets/vendor/datatable-responsive/dataTables.responsive.min.js', [
			'jquery',
			'webinar-manager-for-zoom-meetings-datable-js'
		], RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-datable-responsive-js', RZWM_PLUGIN_URL . 'assets/vendor/datatable-responsive/responsive.dataTables.min.js', [
			'jquery',
			'webinar-manager-for-zoom-meetings-datable-js'
		], RZWM_PLUGIN_VERSION, true );
		wp_register_script( 'webinar-manager-for-zoom-meetings-shortcode-js', RZWM_PLUGIN_URL . 'assets/public/js/shortcode.js', [
			'jquery',
			'webinar-manager-for-zoom-meetings-datable-js'
		], RZWM_PLUGIN_VERSION, true );
	}

	/**
	 * Shows a list of Host Webinars
	 *
	 * @param $atts
	 *
	 * @return false|string|void
	 * @throws Exception
	 */
	public function show_host_webinars( $atts ) {
		$atts = shortcode_atts(
			[
				'host' => ''
			],
			$atts
		);

		if ( empty( $atts['host'] ) ) {
			return esc_html__( 'Host ID should be given when defining this shortcode.', 'webinar-manager-for-zoom-meetings' );
		}

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable' );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable-responsive' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-dt-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-shortcode-js' );

		$webinars         = get_option( '_rzwm_user_webinars_for_' . $atts['host'] );
		$cache_expiration = get_option( '_rzwm_user_webinars_for_' . $atts['host'] . '_expiration' );
		if ( empty( $webinars ) || $cache_expiration < time() ) {
			$encoded_meetings = zoom_conference()->listWebinar( $atts['host'] );
			$decoded_meetings = json_decode( $encoded_meetings );
			if ( isset( $decoded_meetings->webinars ) ) {
				$webinars = $decoded_meetings->webinars;
				update_option( '_rzwm_user_webinars_for_' . $atts['host'], $webinars );
				update_option( '_rzwm_user_webinars_for_' . $atts['host'] . '_expiration', time() + 60 * 5 );
			} else {
				if ( ! empty( $decoded_meetings ) && ! empty( $decoded_meetings->code ) ) {
					return '<strong>'.esc_html_e( 'Zoom API Error:', 'webinar-manager-for-zoom-meetings' ).'</strong>' . $decoded_meetings->message;
				} else {
					return esc_html__( 'Could not retrieve meetings, check Host ID', 'webinar-manager-for-zoom-meetings' );
				}
			}
		}

		ob_start();
		?>
        <table id="rzwm-show-webinars-list-table" class="rzwm-user-meeting-list">
            <thead>
            <tr>
                <th><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'webinar-manager-for-zoom-meetings' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			if ( ! empty( $webinars ) ) {
				foreach ( $webinars as $webinar ) {
					$pass = ! empty( $webinar->password ) ? $webinar->password : false;
					?>
                    <tr>
                        <td><?php echo esc_html( $webinar->topic ); ?></td>
                        <td><?php echo rzwm_dateConverter( $webinar->start_time, $webinar->timezone ); ?></td>
                        <td><?php echo $webinar->timezone; ?></td>
                        <td><a href="<?php echo esc_url( $webinar->join_url ); ?>"><?php esc_html_e( 'Join via App', 'webinar-manager-for-zoom-meetings' ); ?></a> /
                            <a href="<?php echo rzwm_get_browser_join_shortcode( $webinar->id, $pass, true ); ?>"><?php esc_html_e( 'Join via Browser', 'webinar-manager-for-zoom-meetings' ); ?></a>
                        </td>
                    </tr>
					<?php
				}
			}
			?>
            </tbody>
        </table>
		<?php
		return ob_get_clean();
	}

	/**
	 * Show Host Meetings list
	 *
	 * @param $atts
	 *
	 * @return false|string|void
	 * @throws Exception
	 */
	public function show_host_meetings( $atts ) {
		$atts = shortcode_atts(
			[
				'host' => ''
			],
			$atts
		);

		if ( empty( $atts['host'] ) ) {
			return esc_html__( 'Host ID should be given when defining this shortcode.', 'webinar-manager-for-zoom-meetings' );
		}

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable' );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable-responsive' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-dt-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-shortcode-js' );

		$meetings         = get_option( 'rzwm_user_meetings_for_' . $atts['host'] );
		$cache_expiration = get_option( 'rzwm_user_meetings_for_' . $atts['host'] . '_expiration' );
		if ( empty( $meetings ) || $cache_expiration < time() ) {
			$encoded_meetings = zoom_conference()->listMeetings( $atts['host'] );
			$decoded_meetings = json_decode( $encoded_meetings );
			if ( isset( $decoded_meetings->meetings ) ) {
				$meetings = $decoded_meetings->meetings;
				update_option( 'rzwm_user_meetings_for_' . $atts['host'], $meetings );
				update_option( 'rzwm_user_meetings_for_' . $atts['host'] . '_expiration', time() + 60 * 5 );
			} else {
				return esc_html__( 'Could not retrieve meetings, check Host ID', 'webinar-manager-for-zoom-meetings' );
			}
		}

		ob_start();
		?>
        <table id="rzwm-show-meetings-list-table" class="rzwm-user-meeting-list">
            <thead>
            <tr>
                <th><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Meeting Status', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'webinar-manager-for-zoom-meetings' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ( $meetings as $meeting ) {
				$zoom_host_url             = 'https://zoom.us' . '/wc/' . $meeting->id . '/start';
				$zoom_host_url             = apply_filters( 'video_conferencing_zoom_join_url_host', $zoom_host_url );
				$start_meeting_via_browser = '<a class="start-meeting-btn reload-meeting-started-button" target="_blank" href="' . esc_url( $zoom_host_url ) . '" class="join-link">' . esc_html__( 'Start via Browser', 'webinar-manager-for-zoom-meetings' ) . '</a>';

				$meeting_status = '';
				if ( ! empty( $meeting->status ) ) {
					switch ( $meeting->status ) {
						case 0;
							$meeting_status = '<img src="' . RZWM_PLUGIN_URL . 'assets/images/2.png" style="width:14px;" title="Not Started" alt="Not Started">';
							break;
						case 1;
							$meeting_status = '<img src="' . RZWM_PLUGIN_URL . 'assets/images/3.png" style="width:14px;" title="Completed" alt="Completed">';
							break;
						case 2;
							$meeting_status = '<img src="' . RZWM_PLUGIN_URL . 'assets/images/1.png" style="width:14px;" title="Currently Live" alt="Live">';
							break;
						default;
							break;
					}
				} else {
					$meeting_status = "N/A";
				}

				$start_url = ! empty( $meeting->start_url ) ? $meeting->start_url : $meeting->join_url;
				echo '<td>' . $meeting->topic . '</td>';
				echo '<td>' . $meeting_status . '</td>';
				echo '<td>' . rzwm_dateConverter( $meeting->start_time, $meeting->timezone, 'F j, Y, g:i a' ) . '</td>';
				echo '<td>' . $meeting->timezone . '</td>';
				echo '<td><div class="view">
<a href="' . $start_url . '" rel="permalink" target="_blank">' . esc_html__( 'Start via App', 'webinar-manager-for-zoom-meetings' ) . '</a><span class="sep"> /</span></div>
                                    <div class="view">' . $start_meeting_via_browser . '</div></td>';
				echo '</tr>';
			}
			?>
            </tbody>
        </table>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render output for shortcode
	 *
	 * @param      $atts
	 * @param null $content
	 *
	 * @return string
	 * @author Rajthemes
	 * @since  1.0.0
	 */
	function render_main( $atts, $content = null ) {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-locales' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-timezone' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings' );

		extract( shortcode_atts( array(
			'meeting_id' => 'javascript:void(0);',
			'link_only'  => 'no',
		), $atts ) );

		unset( $GLOBALS['vanity_uri'] );
		unset( $GLOBALS['zoom_meetings'] );

		ob_start();

		if ( empty( $meeting_id ) ) {
			echo '<h4 class="no-meeting-id"><strong style="color:red;">' . esc_html__( 'ERROR: ', 'webinar-manager-for-zoom-meetings' ) . '</strong>' . esc_html__( 'No meeting id set in the shortcode', 'webinar-manager-for-zoom-meetings' ) . '</h4>';

			return false;
		}

		$zoom_states = get_option( 'zoom_api_meeting_options' );
		if ( isset( $zoom_states[ $meeting_id ]['state'] ) && $zoom_states[ $meeting_id ]['state'] === "ended" ) {
			echo '<h3>' . esc_html__( 'This meeting has been ended by host.', 'webinar-manager-for-zoom-meetings ' ) . '</h3>';

			return;
		}

		$vanity_uri               = get_option( 'zoom_vanity_url' );
		$meeting                  = $this->fetch_meeting( $meeting_id );
		$GLOBALS['vanity_uri']    = $vanity_uri;
		$GLOBALS['zoom_meetings'] = $meeting;
		if ( ! empty( $meeting ) && ! empty( $meeting->code ) ) {
			?>
            <p class="rjtmerror rjtmmtg-not-found"><?php echo $meeting->message; ?></p>
			<?php
		} else {
			if ( ! empty( $link_only ) && $link_only === "yes" ) {
				$this->generate_link_only();
			} else {
				if ( $meeting ) {
					//Get Template
					rzwm_get_template( 'shortcode/zoom-shortcode.php', true, false );
				} else {
					printf( __( 'Please try again ! Some error occured while trying to fetch meeting with id:  %d', 'webinar-manager-for-zoom-meetings' ), $meeting_id );
				}
			}
		}

		return ob_get_clean();
	}

	/**
	 * Show webinar details
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return bool|false|string|void
	 * @author Rajthemes Bajracharya
	 *
	 * @since 3.4.0
	 */
	public function show_webinar( $atts, $content = null ) {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-locales' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-timezone' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings' );

		extract( shortcode_atts( array(
			'webinar_id' => 'javascript:void(0);',
			'link_only'  => 'no',
		), $atts ) );

		unset( $GLOBALS['vanity_uri'] );
		unset( $GLOBALS['zoom_webinars'] );

		ob_start();
		if ( empty( $webinar_id ) ) {
			echo '<h4 class="no-meeting-id"><strong style="color:red;">' . esc_html__( 'ERROR: ', 'webinar-manager-for-zoom-meetings' ) . '</strong>' . esc_html__( 'No webinar id set in the shortcode', 'webinar-manager-for-zoom-meetings' ) . '</h4>';

			return false;
		}

		$vanity_uri               = get_option( 'zoom_vanity_url' );
		$webinar                  = $this->fetch_webinar( $webinar_id );
		$GLOBALS['vanity_uri']    = $vanity_uri;
		$GLOBALS['zoom_webinars'] = $webinar;
		if ( ! empty( $webinar ) && ! empty( $webinar->code ) ) {
			?>
            <p class="rjtmerror rjtmmtg-not-found"><?php echo $webinar->message; ?></p>
			<?php
		} else {
			if ( ! empty( $link_only ) && $link_only === "yes" ) {
				$this->generate_link_only();
			} else {
				if ( $webinar ) {
					//Get Template
					rzwm_get_template( 'shortcode/zoom-webinar.php', true, false );
				} else {
					printf( __( 'Please try again ! Some error occured while trying to fetch webinar with id:  %d', 'webinar-manager-for-zoom-meetings' ), $webinar_id );
				}
			}
		}

		return ob_get_clean();
	}

	/**
	 * Show All Meetings or Upcomings or Past
	 *
	 * @param $atts
	 *
	 * @return string
	 * @throws Exception
	 */
	public function show_meetings( $atts ) {
		self::$meetings_list_number ++;
		$atts = shortcode_atts(
			array(
				'per_page' => 5,
				'category' => '',
				'order'    => 'DESC',
				'type'     => ''
			),
			$atts, 'rzwm_zoom_list_meetings'
		);
		if ( is_front_page() ) {
			$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}

		$query_args = array(
			'post_type'      => $this->post_type,
			'posts_per_page' => $atts['per_page'],
			'post_status'    => 'publish',
			'paged'          => $paged,
			'orderby'        => 'meta_value',
			'meta_key'       => '_meeting_field_start_date_utc',
			'order'          => $atts['order']
		);

		if ( ! empty( $atts['type'] ) ) {
			$type                     = ( $atts['type'] === "upcoming" ) ? '>=' : '<=';
			$query_args['meta_query'] = array(
				array(
					'key'     => '_meeting_field_start_date_utc',
					'value'   => rzwm_dateConverter( 'now', 'UTC', 'Y-m-d H:i:s', false ),
					'compare' => $type,
					'type'    => 'DATETIME'
				),
			);
		}

		if ( ! empty( $atts['category'] ) ) {
			$category                = array_map( 'trim', explode( ',', $atts['category'] ) );
			$query_args['tax_query'] = [
				[
					'taxonomy' => 'zoom-meeting',
					'field'    => 'slug',
					'terms'    => $category,
					'operator' => 'IN'
				]
			];
		}

		$query         = apply_filters( 'rzwm_meeting_list_query_args', $query_args );
		$zoom_meetings = new \WP_Query( $query );
		$content       = '';

		unset( $GLOBALS['zoom_meetings'] );
		$GLOBALS['zoom_meetings'] = $zoom_meetings;
		ob_start();
		if ( $zoom_meetings->have_posts() ):
			rzwm_get_template( 'shortcode-listing.php', true );
		else:
			_e( "No meetings found.", "webinar-manager-for-zoom-meetings" );
		endif;
		$content .= ob_get_clean();

		return $content;
	}

	/**
	 * Join via browser shortcode
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return mixed|string|void
	 * @deprecated 3.3.1
	 *
	 */
	public function join_via_browser( $atts, $content = null ) {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-moment-timezone' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-browser-js' );

		// Allow addon devs to perform action before window rendering
		do_action( 'rzwm_before_shortcode_content' );

		extract( shortcode_atts( array(
			'meeting_id'        => 'javascript:void(0);',
			'title'             => '',
			'id'                => 'zoom_video_uri',
			'login_required'    => "no",
			'help'              => "yes",
			'height'            => "500px",
			'disable_countdown' => 'yes'
		), $atts ) );

		ob_start();
		if ( empty( $meeting_id ) ) {
			echo '<h4 class="no-meeting-id"><strong style="color:red;">' . esc_html__( 'ERROR: ', 'webinar-manager-for-zoom-meetings' ) . '</strong>' . esc_html__( 'No meeting id set in the shortcode', 'webinar-manager-for-zoom-meetings' ) . '</h4>';

			return;
		}

		if ( ! empty( $login_required ) && $login_required === "yes" && ! is_user_logged_in() ) {
			echo '<h3>' . esc_html__( 'Restricted access, please login to continue.', 'webinar-manager-for-zoom-meetings' ) . '</h3>';

			return;
		}

		$vanity_uri  = get_option( 'zoom_vanity_url' );
		$meeting     = $this->fetch_meeting( $meeting_id );
		$zoom_states = get_option( 'zoom_api_meeting_options' );

		if ( empty( $zoom_vanity_url ) ) {
			$mobile_zoom_url = 'https://zoom.us/j/' . $meeting_id;
		} else {
			$mobile_zoom_url = trailingslashit( $zoom_vanity_url . '/j' ) . $meeting_id;
		}

		if ( ! empty( $meeting ) && ! empty( $meeting->code ) ) {
			echo $meeting->message;
		} else {
			if ( ! empty( $meeting ) ) {
				$meeting_time = date( 'Y-m-d h:i a', strtotime( $meeting->start_time ) );
				try {
					$meeting_timezone_time = rzwm_dateConverter( 'now', $meeting->timezone );
					$meeting_time_check    = rzwm_dateConverter( $meeting_time, $meeting->timezone );

					if ( ! empty( $title ) ) {
						?>
                        <h1><?php esc_html_e( $title ); ?></h1>
						<?php
					}

					if ( ! empty( $help ) && $help === "yes" ) {
						$app_store_link = rzwm_get_browser_agent_type();
						if ( ! isset( $zoom_states[ $meeting_id ]['state'] ) ) {
							?>
                            <div class="zoom-app-notice">
                                <p><?php echo esc_html__( 'Note: If you are having trouble joining the meeting below, enter Meeting ID: ', 'webinar-manager-for-zoom-meetings' ) . '<strong>' . esc_html( $meeting_id ) . '</strong> ' . esc_html__( 'and join via Zoom App.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                <div class="zoom-links">
                                    <ul>
                                        <li>
                                            <a href="<?php echo esc_url( $mobile_zoom_url ); ?>" class="join-link retry-url"><?php esc_html_e( 'Join via Zoom App', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url( $app_store_link ); ?>" class="download-link"><?php esc_html_e( 'Download App from Store', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                        </li>
                                        <li>
                                            <a href="https://zoom.us/client/latest/zoom.apk" class="download-link"><?php esc_html_e( 'Download from Zoom', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
						<?php }
					}

					if ( isset( $zoom_states[ $meeting_id ]['state'] ) && $zoom_states[ $meeting_id ]['state'] === "ended" ) {
						echo '<h3>' . esc_html__( 'This meeting has been ended by host.', 'webinar-manager-for-zoom-meetings ' ) . '</h3>';
					} elseif ( $meeting_time_check > $meeting_timezone_time && ! empty( $disable_countdown ) && $disable_countdown === "no" ) {
						?>
                        <div class="rjtm-rzwm-timer zoom-join-via-browser-countdown" id="rjtm-rzwm-timer" data-date="<?php echo $meeting_time; ?>" data-tz="<?php echo esc_attr( $meeting->timezone ); ?>">
                            <div class="rjtm-rzwm-timer-cell">
                                <div class="rjtm-rzwm-timer-cell-number">
                                    <div id="rjtm-rzwm-timer-days"></div>
                                </div>
                                <div class="rjtm-rzwm-timer-cell-string"><?php esc_html_e( 'days', 'webinar-manager-for-zoom-meetings' ); ?></div>
                            </div>
                            <div class="rjtm-rzwm-timer-cell">
                                <div class="rjtm-rzwm-timer-cell-number">
                                    <div id="rjtm-rzwm-timer-hours"></div>
                                </div>
                                <div class="rjtm-rzwm-timer-cell-string"><?php esc_html_e( 'hours', 'webinar-manager-for-zoom-meetings' ); ?></div>
                            </div>
                            <div class="rjtm-rzwm-timer-cell">
                                <div class="rjtm-rzwm-timer-cell-number">
                                    <div id="rjtm-rzwm-timer-minutes"></div>
                                </div>
                                <div class="rjtm-rzwm-timer-cell-string"><?php esc_html_e( 'minutes', 'webinar-manager-for-zoom-meetings' ); ?></div>
                            </div>
                            <div class="rjtm-rzwm-timer-cell">
                                <div class="rjtm-rzwm-timer-cell-number">
                                    <div id="rjtm-rzwm-timer-seconds"></div>
                                </div>
                                <div class="rjtm-rzwm-timer-cell-string"><?php esc_html_e( 'seconds', 'webinar-manager-for-zoom-meetings' ); ?></div>
                            </div>
                        </div>
					<?php } else { ?>
                        <div class="zoom-window-wrap">
							<?php if ( ! is_ssl() ) { ?>
                                <h4 class="ssl-alert">
                                    <strong style="color:red;"><?php esc_html_e( 'ALERT: ', 'webinar-manager-for-zoom-meetings' ); ?></strong><?php esc_html_e( 'Audio and Video for Zoom meeting will not work on a non HTTPS site, please install a valid SSL certificate on your site to allow participants use audio and video during Zoom meeting: ', 'webinar-manager-for-zoom-meetings' ); ?>
                                </h4>
								<?php
							}

							$styling           = ! empty( $height ) ? "height: " . $height : "height: 500px;";
							$iframe_link       = get_post_type_archive_link( 'zoom-meetings' );
							$iframe_query_args = add_query_arg( array(
								'join' => rzwm_encrypt_decrypt( 'encrypt', $meeting_id ),
								'type' => 'meeting'
							), $iframe_link );
							?>
                            <div id="<?php echo ! empty( $id ) ? esc_html( $id ) : 'video-conferncing-embed-iframe'; ?>" class="zoom-iframe-container">
                                <iframe scrolling="no" style="width:100%; <?php echo $styling; ?>" sandbox="allow-forms allow-scripts allow-same-origin allow-popups" allowfullscreen="allowfullscreen" allow="encrypted-media; autoplay; microphone; camera" src="<?php echo esc_url( $iframe_query_args ); ?>" frameborder="0"></iframe>
                            </div>
                        </div>
						<?php
					}
				} catch ( Exception $e ) {
					error_log( $e->getMessage() );
				}
			}
		}


		$content .= ob_get_clean();

		// Allow addon devs to perform filter before window rendering
		$content = apply_filters( 'rzwm_after_shortcode_content', $content );

		return $content;
	}

	/**
	 * Pagination
	 *
	 * @param $query
	 */
	public static function pagination( $query ) {
		$big = 999999999999999;
		if ( is_front_page() ) {
			$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}
		echo paginate_links( array(
			'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'  => '?paged=%#%',
			'current' => max( 1, $paged ),
			'total'   => $query->max_num_pages
		) );
	}

	/**
	 * Output only singel link
	 *
	 * @since  3.0.4
	 * @author Rajthemes
	 */
	public function generate_link_only() {
		//Get Template
		rzwm_get_template( 'shortcode/zoom-single-link.php', true, false );
	}

	/**
	 * Get Meeting INFO
	 *
	 * @param $meeting_id
	 *
	 * @return bool|mixed|null
	 */
	private function fetch_meeting( $meeting_id ) {
		$meeting = json_decode( zoom_conference()->getMeetingInfo( $meeting_id ) );
		if ( ! empty( $meeting->error ) ) {
			return false;
		}

		return $meeting;
	}

	/**
	 * Get a webinar detail
	 *
	 * @param $webinar_id
	 *
	 * @return bool|mixed|null
	 */
	private function fetch_webinar( $webinar_id ) {
		$webinar = json_decode( zoom_conference()->getWebinarInfo( $webinar_id ) );

		return $webinar;
	}

	/**
	 * Recordings API Shortcode
	 *
	 * @param $atts
	 *
	 * @return bool|false|string
	 */
	public function recordings( $atts ) {
		$atts = shortcode_atts(
			array(
				'host_id'      => '',
				'per_page'     => 300,
				'downloadable' => 'no'
			),
			$atts, 'rzwm_zoom_recordings'
		);

		if ( empty( $atts['host_id'] ) ) {
			echo '<h3 class="no-host-id-defined"><strong style="color:red;">' . esc_html__( 'Invalid HOST ID. Please define a host ID to show recordings based on host.', 'webinar-manager-for-zoom-meetings' ) . '</h3>';

			return false;
		}

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable' );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable-responsive' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-dt-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-shortcode-js' );

		$postParams = array(
			'page_size' => 300 //$atts['per_page'] disbled for now
		);

		//Pagination
		if ( isset( $_GET['pg'] ) && isset( $_GET['type'] ) && $_GET['type'] === "recordings" ) {
			$postParams['next_page_token'] = sanitize_text_field( $_GET['pg'] );
			$recordings                    = json_decode( zoom_conference()->listRecording( $atts['host_id'], $postParams ) );
		} else {
			$recordings = json_decode( zoom_conference()->listRecording( $atts['host_id'], $postParams ) );
		}

		unset( $GLOBALS['rzwm_zoom_recordings'] );
		ob_start();
		if ( ! empty( $recordings ) ) {
			if ( ! empty( $recordings->code ) && ! empty( $recordings->message ) ) {
				echo $recordings->message;
			} else {
				if ( ! empty( $recordings->meetings ) ) {
					$GLOBALS['rzwm_zoom_recordings']               = $recordings;
					$GLOBALS['rzwm_zoom_recordings']->downloadable = ( ! empty( $atts['downloadable'] ) && $atts['downloadable'] === "yes" ) ? true : false;
					rzwm_get_template( 'shortcode/zoom-recordings.php', true );
				} else {
					_e( "No recordings found.", "webinar-manager-for-zoom-meetings" );
				}
			}
		} else {
			_e( "No recordings found.", "webinar-manager-for-zoom-meetings" );
		}

		return ob_get_clean();
	}

	/**
	 * Show recordings based on Meeting ID
	 *
	 * @param $atts
	 *
	 * @return bool|false|string
	 */
	public function recordings_meeting_id( $atts ) {
		$atts = shortcode_atts(
			array(
				'meeting_id'   => '',
				'downloadable' => 'no'
			),
			$atts, 'rzwm_zoom_recordings'
		);

		if ( empty( $atts['meeting_id'] ) ) {
			echo '<h3 class="no-meeting-id-defined"><strong style="color:red;">' . esc_html__( 'Invalid Meeting ID.', 'webinar-manager-for-zoom-meetings' ) . '</h3>';

			return false;
		}

		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable' );
		wp_enqueue_style( 'webinar-manager-for-zoom-meetings-datable-responsive' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-datable-dt-responsive-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-shortcode-js' );

		$recordings = json_decode( zoom_conference()->recordingsByMeeting( $atts['meeting_id'] ) );
		unset( $GLOBALS['rzwm_zoom_recordings'] );
		ob_start();
		if ( ! empty( $recordings ) ) {
			if ( ! empty( $recordings->code ) && ! empty( $recordings->message ) ) {
				echo $recordings->message;
			} else {
				if ( ! empty( $recordings->recording_files ) ) {
					$GLOBALS['rzwm_zoom_recordings']               = $recordings;
					$GLOBALS['rzwm_zoom_recordings']->downloadable = ( ! empty( $atts['downloadable'] ) && $atts['downloadable'] === "yes" ) ? true : false;
					rzwm_get_template( 'shortcode/zoom-recordings-by-meeting.php', true );
				} else {
					esc_html_e( "No recordings found.", "webinar-manager-for-zoom-meetings" );
				}
			}
		} else {
			esc_html_e( "No recordings found.", "webinar-manager-for-zoom-meetings" );
		}

		return ob_get_clean();
	}
}

new RZoomWebinarManagertLite_Shorcodes();