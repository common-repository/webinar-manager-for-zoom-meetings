<?php
/**
 * The template for displaying shortcode join links
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode/join-links.php
 *
 * @author Rajthemes
 * @since  1.0.0
 */

global $webinars;

if ( ! empty( $webinars['join_uri'] ) ) {
	?>
    <tr>
        <td><?php esc_html_e( 'Join via Zoom App', 'webinar-manager-for-zoom-meetings' ); ?></td>
        <td>
            <a class="btn-join-link-shortcode" target="_blank" href="<?php echo esc_url( $webinars['join_uri'] ); ?>" title="Join via App"><?php esc_html_e( 'Join', 'webinar-manager-for-zoom-meetings' ); ?></a>
        </td>
    </tr>
<?php } ?>

<?php if ( ! empty( $webinars['browser_url'] ) ) { ?>
    <tr>
        <td><?php esc_html_e( 'Join via Web Browser', 'webinar-manager-for-zoom-meetings' ); ?></td>
        <td>
            <a class="btn-join-link-shortcode" target="_blank" href="<?php echo esc_url( $webinars['browser_url'] ); ?>" title="Join via Browser"><?php esc_html_e( 'Join', 'webinar-manager-for-zoom-meetings' ); ?></a>
        </td>
    </tr>
<?php } ?>