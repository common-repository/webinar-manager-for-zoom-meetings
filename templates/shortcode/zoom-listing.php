<?php
/**
 * The Template for displaying all single meetings
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode/zoom-listing.php.
 *
 * @package    Webinar Manager for Zoom Meetings/Templates
 * @version    1.0.0
 */

$meeting_details = get_post_meta( get_the_id(), '_meeting_zoom_details', true );
?>
<div class="rzwm-list-zoom-meetings--item">
	<?php if ( has_post_thumbnail() ) { ?>
        <div class="rzwm-list-zoom-meetings--item__image">
			<?php the_post_thumbnail(); ?>
        </div><!--Image End-->
	<?php } ?>
    <div class="rzwm-list-zoom-meetings--item__details">
        <h3><?php the_title(); ?></h3>
        <div class="rzwm-list-zoom-meetings--item__details__meta">
            <div class="hosted-by meta">
                <strong><?php esc_html_e( 'Hosted By:', 'webinar-manager-for-zoom-meetings' ); ?></strong> <span><?php echo get_the_author(); ?></span>
            </div>
            <div class="start-date meta">
                <strong><?php esc_html_e( 'Start', 'webinar-manager-for-zoom-meetings' ); ?>:</strong>
                <span><?php echo rzwm_dateConverter( $meeting_details->start_time, $meeting_details->timezone, 'F j, Y @ g:i a' ); ?></span>
            </div>
            <div class="timezone meta">
                <strong><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?>:</strong>
                <span><?php echo esc_html( $meeting_details->timezone ); ?></span>
            </div>
        </div>
        <a href="<?php echo esc_url( get_the_permalink() ) ?>" class="btn"><?php esc_html_e( 'See More', 'webinar-manager-for-zoom-meetings' ); ?></a>
    </div><!--Details end-->
</div><!--List item end-->