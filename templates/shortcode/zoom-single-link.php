<?php
/**
 * @author Rajthemes.
 */

global $zoom_meetings;
?>

<a href="<?php echo esc_url( $zoom_meetings->join_url ); ?>" title="Join Meeting"><?php esc_html_e( 'Join Meeting', 'webinar-manager-for-zoom-meetings' ); ?></a>