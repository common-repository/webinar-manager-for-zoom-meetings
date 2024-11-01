<?php
/**
 * The template for displaying archive of meetings
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/archive-meetings.php.
 *
 * @author Rajthemes
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

    <div id="rjtm-rzwm-primary" class="rjtm-rzwm-primary container">

		<?php if ( have_posts() ) {
			// Start the Loop.
			while ( have_posts() ) {
				the_post();

				rzwm_get_template_part( 'content', 'meeting' );
			}
		} else {
			echo "<p>" . esc_html__( 'No Meetings found.', 'webinar-manager-for-zoom-meetings' ) . "</p>";
		}
		?>
    </div>

<?php
get_footer();