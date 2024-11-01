<?php
/**
 * The template for displaying meeting details of zoom
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/fragments/meeting-details.php.
 *
 * @author Rajthemes.
 */

global $zoom;
?>
<div class="rjtm-rzwm-sidebar-box">
    <div class="rjtm-rzwm-sidebar-tile">
        <h3><?php esc_html_e( 'Details', 'webinar-manager-for-zoom-meetings' ); ?></h3>
    </div>
    <div class="rjtm-rzwm-sidebar-content">
        <div class="rjtm-rzwm-sidebar-content-list">
            <span><strong><?php esc_html_e( 'Hosted By', 'webinar-manager-for-zoom-meetings' ); ?>:</strong></span>
            <span><?php echo ! empty( $zoom['user'] ) && ! empty( $zoom['user']->first_name ) ? $zoom['user']->first_name . ' ' . $zoom['user']->last_name : get_the_author(); ?></span>
        </div>
		<?php if ( ! empty( $zoom['api']->start_time ) ) { ?>
            <div class="rjtm-rzwm-sidebar-content-list">
                <span><strong><?php esc_html_e( 'Start', 'webinar-manager-for-zoom-meetings' ); ?>:</strong></span>
                <span class="sidebar-start-time"><?php echo rzwm_dateConverter( $zoom['api']->start_time, $zoom['api']->timezone, 'F j, Y @ g:i a' ); ?></span>
            </div>
		<?php } ?>
		<?php if ( ! empty( $zoom['terms'] ) ) { ?>
            <div class="rjtm-rzwm-sidebar-content-list">
                <span><strong><?php esc_html_e( 'Category', 'webinar-manager-for-zoom-meetings' ); ?>:</strong></span>
                <span class="sidebar-category"><?php echo implode( ', ', $zoom['terms'] ); ?></span>
            </div>
		<?php } ?>
		<?php if ( ! empty( $zoom['api']->duration ) ) { ?>
            <div class="rjtm-rzwm-sidebar-content-list">
                <span><strong><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?>:</strong></span>
                <span><?php echo esc_html( $zoom['api']->duration ); ?></span>
            </div>
		<?php } ?> <?php if ( ! empty( $zoom['api']->timezone ) ) { ?>
            <div class="rjtm-rzwm-sidebar-content-list">
                <span><strong><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?>:</strong></span>
                <span><?php echo esc_html( $zoom['api']->timezone ); ?></span>
            </div>
		<?php } ?>
        <p class="rjtm-rzwm-display-or-hide-localtimezone-notice"><?php printf( __( '%sNote%s: Countdown time is shown based on your local timezone.', 'webinar-manager-for-zoom-meetings' ), '<strong>', '</strong>' ); ?></p>
    </div>
</div>