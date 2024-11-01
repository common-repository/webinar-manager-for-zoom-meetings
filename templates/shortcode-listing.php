<?php
/**
 * The Template for displaying all single meetings
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode-listing.php.
 *
 * @package    Webinar Manager for Zoom Meetings/Templates
 * @version    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $zoom_meetings;

if ( ! is_object( $zoom_meetings ) && ! ( $zoom_meetings instanceof \WP_Query ) ) {
	return;
}
?>
<div class="rzwm-list-zoom-meetings">
    <div class="rzwm-list-zoom-meetings--items">
        <?php
		while ( $zoom_meetings->have_posts() ) {
			$zoom_meetings->the_post();

			rzwm_get_template_part( 'shortcode/zoom', 'listing' );
		}

		wp_reset_postdata();
		?>
    </div>
    <div class="rzwm-list-zoom-meetings--pagination">
		<?php RZoomWebinarManagertLite_Shorcodes::pagination( $zoom_meetings ); ?>
    </div>
</div>