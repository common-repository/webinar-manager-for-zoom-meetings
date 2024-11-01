<?php
/**
 * The template for displaying meeting join and start links
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/fragments/join-links.php.
 *
 */

global $zoom;

if ( ! empty( $zoom ) ) {
	?>
    <div class="rjtm-rzwm-sidebar-box">
        <div class="join-links">
			<?php
			/**
			 * Hook: rzwmzoom_meeting_join_links
			 *
			 * @rzwmzoom_manager_meeting_join_link - 10
			 */
			do_action( 'rzwmzoom_meeting_join_links', $zoom );
			?>

			<?php if ( ! empty( $zoom->start_url ) && rzwm_check_author( $post_id ) ) { ?>
                <a target="_blank" href="<?php echo esc_url( $zoom->start_url ); ?>" rel="nofollow" class="btn btn-start-link"><?php esc_html_e( 'Start Meeting', 'webinar-manager-for-zoom-meetings' ); ?></a>
			<?php } ?>
        </div>
    </div>
	<?php
}