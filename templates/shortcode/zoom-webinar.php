<?php
/**
 * The template for displaying shortcode
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode/zoom-shortcode.php.
 *
 * @author Rajthemes.
 * @since 1.0.0
 */

global $zoom_webinars;
?>

<div class="rjtm-rzwm-shortcode-op-wrapper">
	<?php
	$hide_join_link_nloggedusers = get_option( 'zoom_api_hide_shortcode_join_links' );
	?>
    <table class="rzwm-shortcode-meeting-table">
        <tr class="rzwm-shortcode-meeting-table--row1">
            <td><?php esc_html_e( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ); ?></td>
            <td><?php echo esc_html( $zoom_webinars->id ); ?></td>
        </tr>
        <tr class="rzwm-shortcode-meeting-table--row2">
            <td><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></td>
            <td><?php echo esc_html( $zoom_webinars->topic ); ?></td>
        </tr>
		<?php
		if ( ! empty( $zoom_webinars->type ) && $zoom_webinars->type === 9 ) {
			if ( ! empty( $zoom_webinars->occurrences ) ) {
				?>
                <tr class="rzwm-shortcode-meeting-table--row4">
                    <td><?php esc_html_e( 'Type', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    <td><?php esc_html_e( 'Recurring Meeting', 'webinar-manager-for-zoom-meetings' ); ?></td>
                </tr>
                <tr class="rzwm-shortcode-meeting-table--row4">
                    <td><?php esc_html_e( 'Ocurrences', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    <td><?php echo count( $zoom_webinars->occurrences ); ?></td>
                </tr>
                <tr class="rzwm-shortcode-meeting-table--row5">
                    <td><?php esc_html_e( 'Next Start Time', 'webinar-manager-for-zoom-meetings' ); ?></td>
                    <td>
						<?php
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

									esc_html_e( 'Meeting has ended !', 'webinar-manager-for-zoom-meetings' );
									break;
								}
							}
						}

						if ( $closest_occurence ) {
							echo rzwm_dateConverter( $closest_occurence, $zoom_webinars->timezone, 'F j, Y @ g:i a' );
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
		} else if ( ! empty( $zoom_webinars->type ) && $zoom_webinars->type === 6 ) {
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
                <td><?php echo rzwm_dateConverter( $zoom_webinars->start_time, $zoom_webinars->timezone, 'F j, Y @ g:i a' ); ?></td>
            </tr>
		<?php } ?>
        <tr class="rzwm-shortcode-meeting-table--row7">
            <td><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></td>
            <td><?php echo esc_html( $zoom_webinars->timezone ); ?></td>
        </tr>
		<?php if ( ! empty( $zoom_webinars->duration ) ) { ?>
            <tr class="rzwm-table-shortcode-duration">
                <td><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></td>
                <td><?php echo esc_html( $zoom_webinars->duration ); ?></td>
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
			 * Hook: rzwmzoom_meeting_shortcode_join_links_webinar
			 *
			 * @rzwmzoom_manager_shortcode_join_link_webinar - 10
			 *
			 */
			do_action( 'rzwmzoom_meeting_shortcode_join_links_webinar', $zoom_webinars );
		}
		?>
    </table>
</div>