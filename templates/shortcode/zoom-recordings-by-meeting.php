<?php
/**
 * The Template for displaying list of recordings via meeting ID
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode/zoom-recordings-by-meeting.php.
 *
 * @package    Webinar Manager for Zoom Meetings/Templates
 * @version     1.0.0
 */

global $rzwm_zoom_recordings;
?>
<div class="rzwm-recordings-meeting-id-description">
    <ul>
        <li><strong><?php esc_html_e( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ); ?>:</strong> <?php echo esc_html( $rzwm_zoom_recordings->id ); ?></li>
        <li><strong><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?>:</strong> <?php echo esc_html( $rzwm_zoom_recordings->topic ); ?></li>
        <li><strong><?php esc_html_e( 'Total Size', 'webinar-manager-for-zoom-meetings' ); ?>:</strong> <?php echo rzwm_filesize_converter( $rzwm_zoom_recordings->total_size ); ?></li>
    </ul>
</div>
<table id="rzwm-recordings-list-table" class="rzwm-recordings-list-table rzwm-user-meeting-list">
    <thead>
    <tr>
        <th><?php esc_html_e( 'Start Date', 'webinar-manager-for-zoom-meetings' ); ?></th>
        <th><?php esc_html_e( 'End Date', 'webinar-manager-for-zoom-meetings' ); ?></th>
        <th><?php esc_html_e( 'Size', 'webinar-manager-for-zoom-meetings' ); ?></th>
        <th><?php esc_html_e( 'Action', 'webinar-manager-for-zoom-meetings' ); ?></th>
    </tr>
    </thead>
    <tbody>
	<?php
	foreach ( $rzwm_zoom_recordings->recording_files as $recording ) {
		if ( $recording->file_type !== "MP4" ) {
			break;
		}
		?>
        <tr>
            <td><?php echo rzwm_dateConverter( $recording->recording_start, $rzwm_zoom_recordings->timezone, 'F j, Y, g:i a' ); ?></td>
            <td><?php echo rzwm_dateConverter( $recording->recordingesc_html_end, $rzwm_zoom_recordings->timezone, 'F j, Y, g:i a' ); ?></td>
            <td><?php echo rzwm_filesize_converter( $recording->file_size ); ?></td>
            <td>
                <a href="<?php echo esc_url( $recording->play_url ); ?>" target="_blank"><?php esc_html_e( 'Play', 'webinar-manager-for-zoom-meetings' ); ?></a>
				<?php if ( $rzwm_zoom_recordings->downloadable ) { ?>
                    <a href="<?php echo esc_url( $recording->download_url ); ?>" target="_blank"><?php esc_html_e( 'Download', 'webinar-manager-for-zoom-meetings' ); ?></a>
				<?php } ?>
            </td>
        </tr>
		<?php
	}
	?>
    </tbody>
</table>