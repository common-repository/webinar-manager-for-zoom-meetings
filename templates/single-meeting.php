<?php
/**
 * The Template for displaying all single meetings
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/single-meetings.php.
 *
 * @package    Webinar Manager for Zoom Meetings/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

/**
 * rzwmzoom_before_main_content hook.
 *
 * @hooked rzwmzoom_manager_output_content_wrapper
 */
do_action( 'rzwmzoom_before_main_content' );

while ( have_posts() ) {
	the_post();

	rzwm_get_template_part( 'content', 'single-meeting' );
}

/**
 * rzwmzoom_after_main_content hook.
 *
 * @hooked rzwmzoom_manager_output_content_end
 */
do_action( 'rzwmzoom_after_main_content' );

get_footer();
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */