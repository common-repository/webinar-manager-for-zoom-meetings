<?php
/**
 * The template for displaying product content in the single-meeting.php template
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/single-meetings.php.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: rzwmzoom_before_single_meeting.
 */
do_action( 'rzwmzoom_before_single_meeting' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

/**
 *  Hook: rzwmzoom_before_content
 */
do_action( 'rzwmzoom_before_content' );
?>

    <div class="rjtm-rzwm-single-content-wrapper rjtm-rzwm-single-content-wrapper-<?php echo get_the_id(); ?>" id="rjtm-rzwm-single-content-wrapper-<?php echo get_the_id(); ?>">
        <div class="rjtm-rzwm-col-8">
			<?php
			/**
			 *  Hook: rzwmzoom_single_content_left
			 *
			 * @rzwmzoom_manager_featured_image - 10
			 * @rzwmzoom_manager_main_content - 20
			 */
			do_action( 'rzwmzoom_single_content_left' );
			?>
        </div>
        <div class="rjtm-rzwm-col-4">
            <div class="rjtm-rzwm-sidebar-wrapper">
				<?php
				/**
				 *  Hook: rzwmzoom_single_content_right
				 *
				 * @rzwmzoom_manager_countdown_timer - 10
				 * @rzwmzoom_manager_meeting_details - 20
				 * @rzwmzoom_manager_meeting_join - 30
				 *
				 */
				do_action( 'rzwmzoom_single_content_right' );
				?>
            </div>
        </div>
    </div>

<?php
/**
 *  Hook: rzwmzoom_after_content
 */
do_action( 'rzwmzoom_after_content' );

/**
 * Hook: rzwmzoom_manager_before_single_meeting.
 */
do_action( 'rzwmzoom_manager_after_single_meeting' );
?>