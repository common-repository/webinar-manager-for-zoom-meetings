<?php
/**
 * The template for displaying shortcode
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode/zoom-shortcode.php.
 *
 * @author Rajthemes.
 * @since 1.0.0
 */

global $zoom_meetings;
?>

<div class="rjtm-rzwm-shortcode-op-wrapper">
	<?php
	/**
	 * Hook: rzwmzoom_meeting_before_shortcode
     * @rzwmzoom_manager_shortcode_table
	 */
	do_action( 'rzwmzoom_meeting_before_shortcode', $zoom_meetings );
	?>
</div>