<?php
/**
 * @author Rajthemes.
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Globals
add_action( 'rzwmzoom_before_main_content', 'rzwmzoom_manager_output_content_start', 10 );
add_action( 'rzwmzoom_after_main_content', 'rzwmzoom_manager_output_content_end', 10 );

//Left Section Single Content
add_action( 'rzwmzoom_single_content_left', 'rzwmzoom_manager_featured_image', 10 );
add_action( 'rzwmzoom_single_content_left', 'rzwmzoom_manager_main_content', 20 );

//Right Section Single Content
add_action( 'rzwmzoom_single_content_right', 'rzwmzoom_manager_countdown_timer', 10 );
add_action( 'rzwmzoom_single_content_right', 'rzwmzoom_manager_meeting_details', 20 );
add_action( 'rzwmzoom_single_content_right', 'rzwmzoom_manager_meeting_join', 30 );
add_action( 'rzwmzoom_single_content_right', 'rzwmzoom_manager_meeting_end_author', 40 );

//single content
add_action( 'rzwmzoom_meeting_join_links', 'rzwmzoom_manager_meeting_join_link', 10 );

//Shortcode Hooks
add_action( 'rzwmzoom_meeting_before_shortcode', 'rzwmzoom_manager_shortcode_table', 10 );
add_action( 'rzwmzoom_meeting_shortcode_join_links', 'rzwmzoom_managershortcode_join_link', 10 );
add_action( 'rzwmzoom_meeting_shortcode_join_links_webinar', 'rzwmzoom_manager_shortcode_join_link_webinar', 10 );

//JBH Hooks
add_action( 'rzwmzoom_jbh_before_content', 'rzwmzoom_manager_before_jbh_html', 10 );
add_action( 'rzwmzoom_jbh_after_content', 'rzwmzoom_manager_after_jbh_html', 10 );