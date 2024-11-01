<?php
/**
 * The Template for displaying list of recordings via Host ID
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/shortcode/zoom-recordings.php.
 *
 * @package    Webinar Manager for Zoom Meetings/Templates
 * @version    1.0.0
 */

global $rzwm_zoom_recordings;
?>
    <table id="rzwm-recordings-list-table" class="rzwm-recordings-list-table rzwm-user-meeting-list">
        <thead>
        <tr>
            <th><?php esc_html_e( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ); ?></th>
            <th><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></th>
            <th><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></th>
            <th><?php esc_html_e( 'Recorded', 'webinar-manager-for-zoom-meetings' ); ?></th>
            <th><?php esc_html_e( 'Size', 'webinar-manager-for-zoom-meetings' ); ?></th>
            <th><?php esc_html_e( 'Action', 'webinar-manager-for-zoom-meetings' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php
		foreach ( $rzwm_zoom_recordings->meetings as $recording ) {
			?>
            <tr>
                <td><?php echo esc_html( $recording->id ); ?></td>
                <td><?php echo esc_html( $recording->topic ); ?></td>
                <td><?php echo esc_html( $recording->duration ); ?></td>
                <td><?php echo date( 'F j, Y, g:i a', strtotime( $recording->start_time ) ); ?></td>
                <td><?php echo rzwm_filesize_converter( $recording->total_size ); ?></td>
                <td>
					<?php if ( ! empty( $recording->recording_files ) ) {
						foreach ( $recording->recording_files as $files ) {
							if ( $files->file_type === "MP4" ) {
								?>
                                <a href="<?php echo esc_url( $files->play_url ); ?>" target="_blank"><?php esc_html_e( 'Play', 'webinar-manager-for-zoom-meetings' ); ?></a>
								<?php if ( $rzwm_zoom_recordings->downloadable ) { ?>
                                    <a href="<?php echo esc_url( $files->download_url ); ?>" target="_blank"><?php esc_html_e( 'Download', 'webinar-manager-for-zoom-meetings' ); ?></a>
									<?php
								}
							}
						}
					} else {
						echo esc_html( "N/A" );
					} ?>
                </td>
            </tr>
			<?php
		}
		?>
        </tbody>
    </table>

<?php
if ( ! empty( $rzwm_zoom_recordings ) ) {
	rzwm_zoom_api_paginator( $rzwm_zoom_recordings, 'recordings' );
}
?>