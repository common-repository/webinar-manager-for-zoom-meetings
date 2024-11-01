<?php
/**
 * The template for displaying meeting countdown timer
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/fragments/countdown-timer.php.
 *
 * @author Rajthemes.
 */

global $zoom;
if ( ! empty( $zoom['api']->start_time ) ) {
	?>
    <div class="rjtm-rzwm-sidebar-box">
        <div class="rjtm-rzwm-timer" id="rjtm-rzwm-timer" data-date="<?php echo esc_attr( $zoom['api']->start_time ); ?>" data-state="<?php echo ! empty( $zoom['api']->state ) ? $zoom['api']->state : false; ?>" data-tz="<?php echo esc_attr( $zoom['api']->timezone ); ?>">
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
    </div>
	<?php
}