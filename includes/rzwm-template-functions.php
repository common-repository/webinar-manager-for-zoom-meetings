<?php
/**
 * @author Rajthemes.
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function to check if a user is logged in or not
 * @author Rajthemes
 * @since 1.0.0
 */
function rzwmzoom_manager_check_login() {
	global $zoom;
	if ( ! empty( $zoom ) && ! empty( $zoom['site_option_logged_in'] ) ) {
		if ( is_user_logged_in() ) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

/**
 * Function to view featured image on the post
 * @author Rajthemes
 * @since 1.0.0
 */
function rzwmzoom_manager_featured_image() {
	rzwm_get_template( 'fragments/image.php', true );
}

/**
 * Function to view main content i.e title and main content
 * @author Rajthemes
 * @since 1.0.0
 */
function rzwmzoom_manager_main_content() {
	rzwm_get_template( 'fragments/content.php', true );
}

/**
 * Function to add in the counter
 * @author Rajthemes
 * @since 1.0.0
 */
function rzwmzoom_manager_countdown_timer() {
	rzwm_get_template( 'fragments/countdown-timer.php', true );
}

/**
 * Function to show meeting details
 * @author Rajthemes
 * @since 1.0.0
 */
function rzwmzoom_manager_meeting_details() {
	rzwm_get_template( 'fragments/meeting-details.php', true );
}

/**
 * Control State of the meeting by author from frontend
 */
function rzwmzoom_manager_meeting_end_author() {
	global $post;
	$meeting = get_post_meta( $post->ID, '_meeting_zoom_details', true );
	$author  = rzwm_check_author( $post->ID );
	if ( ! $author ) {
		return;
	}

	$data = array(
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
		'rzwm_security' => wp_create_nonce( "_nonce_rzwm_security" ),
		'lang'         => array(
			'confirm_end' => esc_html__( "Are you sure you want to end this meeting ? Users won't be able to join this meeting shown from the shortcode.", "webinar-manager-for-zoom-meetings" )
		)
	);
	wp_localize_script( 'webinar-manager-for-zoom-meetings', 'rzwm_state', $data );
	?>
    <div class="rjtm-rzwm-sidebar-state">
		<?php if ( empty( $meeting->state ) ) { ?>
            <a href="javascript:void(0);" class="rzwm-meeting-state-change" data-type="post_type" data-state="end" data-postid="<?php echo esc_attr( $post->ID ); ?>" data-id="<?php echo esc_attr( $meeting->id ); ?>"><?php esc_html_e( 'End Meeting ?', 'webinar-manager-for-zoom-meetings' ); ?></a>
		<?php } else { ?>
            <a href="javascript:void(0);" class="rzwm-meeting-state-change" data-type="post_type" data-state="resume" data-postid="<?php echo esc_attr( $post->ID ); ?>" data-id="<?php echo esc_attr( $meeting->id ); ?>"><?php esc_html_e( 'Enable Meeting Join ?', 'webinar-manager-for-zoom-meetings' ); ?></a>
		<?php } ?>
        <p><?php esc_html_e( 'You are seeing this because you are the author of this post.', 'webinar-manager-for-zoom-meetings' ); ?></p>
    </div>
	<?php
}

/**
 * Function to show meeting join links
 *
 * @author Rajthemes
 * @since 1.0.0
 */
function rzwmzoom_manager_meeting_join() {
	global $zoom;

	if ( empty( $zoom['api']->state ) && rzwmzoom_manager_check_login() ) {
		$data = array(
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'start_date' => $zoom['start_date'],
			'timezone'   => $zoom['timezone'],
			'post_id'    => get_the_ID(),
			'page'       => 'single-meeting'
		);
		wp_localize_script( 'webinar-manager-for-zoom-meetings', 'mtg_data', $data );
	} else {
		echo "<p>" . esc_html__( 'Please login to join this meeting.', 'webinar-manager-for-zoom-meetings' ) . "</p>";
	}
}

/**
 * Generate join links
 *
 * @param $zoom_meeting
 *
 * @since 1.0.0
 *
 * @author Rajthemes
 */
function rzwmzoom_manager_meeting_join_link( $zoom_meeting ) {
	$disable_app_join = apply_filters( 'rzwmzoom_join_meeting_via_app_disable', false );
	if ( ! empty( $zoom_meeting->join_url ) && ! $disable_app_join ) {
		$join_url = ! empty( $zoom_meeting->encrypted_password ) ? rzwm_get_pwd_embedded_join_link( $zoom_meeting->join_url, $zoom_meeting->encrypted_password ) : $zoom_meeting->join_url;
		?>
        <a target="_blank" href="<?php echo esc_url( $join_url ); ?>" class="btn btn-join-link btn-join-via-app"><?php echo apply_filters( 'rzwmzoom_join_meeting_via_app_text', esc_html__( 'Join Meeting via Zoom App', 'webinar-manager-for-zoom-meetings' ) ); ?></a>
		<?php
	}

	if ( wp_doing_ajax() ) {
		$post_id         = absint( filter_input( INPUT_POST, 'post_id' ) );
		$meeting_details = get_post_meta( $post_id, '_meeting_fields', true );
		if ( ! empty( $zoom_meeting->id ) && ! empty( $post_id ) && empty( $meeting_details['site_option_browser_join'] ) ) {
			if ( ! empty( $zoom_meeting->password ) ) {
				echo rzwm_get_browser_join_links( $post_id, $zoom_meeting->id, $zoom_meeting->password );
			} else {
				echo rzwm_get_browser_join_links( $post_id, $zoom_meeting->id );
			}
		}
	}
}

/**
 * Generate join links for webinar
 *
 * @param $zoom_webinars
 *
 * @throws Exception
 * @since 3.4.0
 *
 * @author Rajthemes
 */
function rzwmzoom_manager_shortcode_join_link_webinar( $zoom_webinars ) {
	if ( empty( $zoom_webinars ) ) {
		echo "<p>" . esc_html__( 'Webinar is not defined. Try updating this Webinar', 'webinar-manager-for-zoom-meetings' ) . "</p>";

		return;
	}

	$now               = new DateTime( 'now -1 hour', new DateTimeZone( $zoom_webinars->timezone ) );
	$closest_occurence = false;
	if ( ! empty( $zoom_webinars->type ) && $zoom_webinars->type === 9 && ! empty( $zoom_webinars->occurrences ) ) {
		foreach ( $zoom_webinars->occurrences as $occurrence ) {
			if ( $occurrence->status === "available" ) {
				$start_date = new DateTime( $occurrence->start_time, new DateTimeZone( $zoom_webinars->timezone ) );
				if ( $start_date >= $now ) {
					$closest_occurence = $occurrence->start_time;
					break;
				}
			}
		}
	} else if ( empty( $zoom_webinars->occurrences ) ) {
		$zoom_webinars->start_time = false;
	} else if ( ! empty( $zoom_webinars->type ) && $zoom_webinars->type === 6 ) {
		$zoom_webinars->start_time = false;
	}

	$start_time = ! empty( $closest_occurence ) ? $closest_occurence : $zoom_webinars->start_time;
	$start_time = new DateTime( $start_time, new DateTimeZone( $zoom_webinars->timezone ) );
	$start_time->setTimezone( new DateTimeZone( $zoom_webinars->timezone ) );
	if ( $now <= $start_time ) {
		unset( $GLOBALS['webinars'] );

		if ( ! empty( $zoom_webinars->password ) ) {
			$browser_join = rzwm_get_browser_join_shortcode( $zoom_webinars->id, $zoom_webinars->password, true );
		} else {
			$browser_join = rzwm_get_browser_join_shortcode( $zoom_webinars->id, false, true );
		}

		$join_url            = ! empty( $zoom_webinars->encrypted_password ) ? rzwm_get_pwd_embedded_join_link( $zoom_webinars->join_url, $zoom_webinars->encrypted_password ) : $zoom_webinars->join_url;
		$GLOBALS['webinars'] = array(
			'join_uri'    => apply_filters( 'rzwmzoom_join_webinar_via_app_shortcode', $join_url, $zoom_webinars ),
			'browser_url' => apply_filters( 'rzwmzoom_join_webinar_via_browser_disable', $browser_join )
		);
		rzwm_get_template( 'shortcode/webinar-join-links.php', true, false );
	}
}

/**
 * Generate join links
 *
 * @param $zoom_meetings
 *
 * @throws Exception
 * @since 1.0.0
 *
 * @author Rajthemes
 */
function rzwmzoom_manager_shortcode_join_link( $zoom_meetings ) {
	if ( empty( $zoom_meetings ) ) {
		echo "<p>" . esc_html__( 'Meeting is not defined. Try updating this meeting', 'webinar-manager-for-zoom-meetings' ) . "</p>";

		return;
	}

	$now               = new DateTime( 'now -1 hour', new DateTimeZone( $zoom_meetings->timezone ) );
	$closest_occurence = false;
	if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 && ! empty( $zoom_meetings->occurrences ) ) {
		foreach ( $zoom_meetings->occurrences as $occurrence ) {
			if ( $occurrence->status === "available" ) {
				$start_date = new DateTime( $occurrence->start_time, new DateTimeZone( $zoom_meetings->timezone ) );
				if ( $start_date >= $now ) {
					$closest_occurence = $occurrence->start_time;
					break;
				}
			}
		}
	} else if ( empty( $zoom_meetings->occurrences ) ) {
		$zoom_meetings->start_time = false;
	} else if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 3 ) {
		$zoom_meetings->start_time = false;
	}

	$start_time = ! empty( $closest_occurence ) ? $closest_occurence : $zoom_meetings->start_time;
	$start_time = new DateTime( $start_time, new DateTimeZone( $zoom_meetings->timezone ) );
	$start_time->setTimezone( new DateTimeZone( $zoom_meetings->timezone ) );
	if ( $now <= $start_time ) {
		unset( $GLOBALS['meetings'] );

		if ( ! empty( $zoom_meetings->password ) ) {
			$browser_join = rzwm_get_browser_join_shortcode( $zoom_meetings->id, $zoom_meetings->password, true );
		} else {
			$browser_join = rzwm_get_browser_join_shortcode( $zoom_meetings->id, false, true );
		}

		$join_url            = ! empty( $zoom_meetings->encrypted_password ) ? rzwm_get_pwd_embedded_join_link( $zoom_meetings->join_url, $zoom_meetings->encrypted_password ) : $zoom_meetings->join_url;
		$GLOBALS['meetings'] = array(
			'join_uri'    => apply_filters( 'rzwmzoom_join_meeting_via_app_shortcode', $join_url, $zoom_meetings ),
			'browser_url' => apply_filters( 'rzwmzoom_join_meeting_via_browser_disable', $browser_join )
		);
		rzwm_get_template( 'shortcode/join-links.php', true, false );
	}
}

if ( ! function_exists( 'rzwmzoom_manager_shortcode_table' ) ) {
	/**
	 *  * Render Zoom Meeting ShortCode table in frontend
	 *
	 * @param $zoom_meetings
	 *
	 * @throws Exception
	 * @since 1.0.0
	 *
	 * @author Rajthemes
	 */
	function rzwmzoom_manager_shortcode_table( $zoom_meetings ) {
		$hide_join_link_nloggedusers = get_option( 'zoom_api_hide_shortcode_join_links' );
		?>
        <table class="rzwm-shortcode-meeting-table">
            <tr class="rzwm-shortcode-meeting-table--row1">
                <td><?php esc_html_e( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ); ?></td>
                <td><?php echo esc_html( $zoom_meetings->id ); ?></td>
            </tr>
            <tr class="rzwm-shortcode-meeting-table--row2">
                <td><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></td>
                <td><?php echo esc_html( $zoom_meetings->topic ); ?></td>
            </tr>
            <tr class="rzwm-shortcode-meeting-table--row3">
                <td><?php esc_html_e( 'Meeting Status', 'webinar-manager-for-zoom-meetings' ); ?></td>
                <td>
					<?php
					if ( $zoom_meetings->status === "waiting" ) {
						esc_html_e( 'Waiting - Not started', 'webinar-manager-for-zoom-meetings' );
					} else if ( $zoom_meetings->status === "started" ) {
						esc_html_e( 'Meeting is in Progress', 'webinar-manager-for-zoom-meetings' );
					} else {
						echo $zoom_meetings->status;
					}
					?>
                    <p class="small-description"><?php esc_html_e( 'Refresh is needed to change status.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                </td>
            </tr>
			<?php
			if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 ) {
				if ( ! empty( $zoom_meetings->occurrences ) ) {
					?>
                    <tr class="rzwm-shortcode-meeting-table--row4">
                        <td><?php esc_html_e( 'Type', 'webinar-manager-for-zoom-meetings' ); ?></td>
                        <td><?php esc_html_e( 'Recurring Meeting', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    </tr>
                    <tr class="rzwm-shortcode-meeting-table--row4">
                        <td><?php esc_html_e( 'Ocurrences', 'webinar-manager-for-zoom-meetings' ); ?></td>
                        <td><?php echo count( $zoom_meetings->occurrences ); ?></td>
                    </tr>
                    <tr class="rzwm-shortcode-meeting-table--row5">
                        <td><?php esc_html_e( 'Next Start Time', 'webinar-manager-for-zoom-meetings' ); ?></td>
                        <td>
							<?php
							$now               = new DateTime( 'now -1 hour', new DateTimeZone( $zoom_meetings->timezone ) );
							$closest_occurence = false;
							if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 && ! empty( $zoom_meetings->occurrences ) ) {
								foreach ( $zoom_meetings->occurrences as $occurrence ) {
									if ( $occurrence->status === "available" ) {
										$start_date = new DateTime( $occurrence->start_time, new DateTimeZone( $zoom_meetings->timezone ) );
										if ( $start_date >= $now ) {
											$closest_occurence = $occurrence->start_time;
											break;
										}

										esc_html_e( 'Meeting has ended !', 'webinar-manager-for-zoom-meetings' );
										break;
									}
								}
							}

							if ( $closest_occurence ) {
								echo rzwm_dateConverter( $closest_occurence, $zoom_meetings->timezone, 'F j, Y @ g:i a' );
							} else {
								esc_html_e( 'Meeting has ended !', 'webinar-manager-for-zoom-meetings' );
							}
							?>
                        </td>
                    </tr>
					<?php
				} else {
					?>
                    <tr class="rzwm-shortcode-meeting-table--row6">
                        <td><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></td>
                        <td><?php esc_html_e( 'Meeting has ended !', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    </tr>
					<?php
				}
			} else if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 3 ) {
				?>
                <tr class="rzwm-shortcode-meeting-table--row6">
                    <td><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    <td><?php esc_html_e( 'This is a meeting with no Fixed Time.', 'webinar-manager-for-zoom-meetings' ); ?></td>
                </tr>
				<?php
			} else {
				?>
                <tr class="rzwm-shortcode-meeting-table--row6">
                    <td><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    <td><?php echo rzwm_dateConverter( $zoom_meetings->start_time, $zoom_meetings->timezone, 'F j, Y @ g:i a' ); ?></td>
                </tr>
			<?php } ?>
            <tr class="rzwm-shortcode-meeting-table--row7">
                <td><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></td>
                <td><?php echo $zoom_meetings->timezone; ?></td>
            </tr>
			<?php if ( ! empty( $zoom_meetings->duration ) ) { ?>
                <tr class="rzwm-table-shortcode-duration">
                    <td><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    <td><?php echo $zoom_meetings->duration; ?></td>
                </tr>
				<?php
			}

			if ( ! empty( $hide_join_link_nloggedusers ) ) {
				if ( is_user_logged_in() ) {
					$show_join_links = true;
				} else {
					$show_join_links = false;
				}
			} else {
				$show_join_links = true;
			}

			if ( $show_join_links ) {
				/**
				 * Hook: rzwmzoom_meeting_shortcode_join_links
				 *
				 * @rzwmzoom_manager_shortcode_join_link - 10
				 *
				 */
				do_action( 'rzwmzoom_meeting_shortcode_join_links', $zoom_meetings );
			}
			?>
        </table>
		<?php
	}
}

if ( ! function_exists( 'rzwmzoom_manager_output_content_start' ) ) {
	function rzwmzoom_manager_output_content_start() {
		rzwm_get_template( 'global/wrap-start.php', true );
	}
}

if ( ! function_exists( 'rzwmzoom_manager_output_content_end' ) ) {
	function rzwmzoom_manager_output_content_end() {
		rzwm_get_template( 'global/wrap-end.php', true );
	}
}

/**
 * Get a slug identifying the current theme.
 *
 * @return string
 * @since 3.0.2
 */
function rzwmzoom_manager_get_current_theme_slug() {
	return apply_filters( 'rzwmzoom_manager_theme_slug_for_templates', get_option( 'template' ) );
}

/**
 * Before join before host
 *
 * @param $zoom
 */
function rzwmzoom_manager_before_jbh_html( $zoom ) {
	?>
    <!DOCTYPE html><html>
    <head>
        <meta charset="UTF-8">
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="robots" content="noindex, nofollow">
        <title><?php echo ! empty( $zoom['api']->topic ) ? $zoom['api']->topic : 'Join Meeting'; ?></title>
        <?php rzwmzoom_manager_jbh_html_assets(); ?>
    </head><body class="join-via-browser-body">
	<?php
}

/**
 * AFter join before host
 */
function rzwmzoom_manager_after_jbh_html() {
	do_action( 'rzwm_join_via_browser_footer' );
	wp_footer();
	?>
    </body></html>
	<?php
}

function rzwmzoom_manager_jbh_html_assets() {
	wp_enqueue_style( 'bootstrap', RZWM_PLUGIN_URL . 'assets/vendor/zoom/bootstrap.css', false, RZWM_PLUGIN_VERSION );
	wp_enqueue_style( 'react-select', RZWM_PLUGIN_URL . 'assets/vendor/zoom/react-select.css', false, RZWM_PLUGIN_VERSION );
	wp_enqueue_style( 'webinar-manager-for-zoom-meetings-main', RZWM_PLUGIN_URL . 'assets/public/css/main.min.css', false, RZWM_PLUGIN_VERSION );
}