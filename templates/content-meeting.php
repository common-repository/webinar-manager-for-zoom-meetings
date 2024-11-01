<?php
/**
 * The template for displaying content of archive page meetings
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/content-meeting.php.
 *
 * @author Rajthemes
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="rjtm-rzwm-<?php the_ID(); ?>" class="rjtm-rzwm-<?php the_ID(); ?>">
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<?php the_excerpt(); ?>
</div>