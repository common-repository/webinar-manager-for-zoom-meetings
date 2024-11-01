<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
$host_id = isset( $_GET['host_id'] ) ? sanitize_text_field( $_GET['host_id'] ) : null;
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fas fa-video"></i>                 
                </span>
                <?php esc_html_e( "Recordings", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
        </div>
        <?php
        rzwmzoom_manager_show_api_notice();
        rzwm_recordings()->get_hosts( $host_id );
        ?>
        <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
                <div class="card card-img-holder">
                    <div class="card-body">
                        <div class="rzwm_listing_table">
                            <table id="rzwm_meetings_list_table" class="display" width="100%">
                                <thead>
                                <tr>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Recorded', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Size', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Action', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ( ! empty( $recordings ) && ! empty( $recordings->meetings ) ) {
                                    foreach ( $recordings->meetings as $recording ) {
                                        ?>
                                        <tr>
                                            <td><?php echo $recording->id; ?></td>
                                            <td><?php echo $recording->topic; ?></td>
                                            <td><?php echo $recording->duration; ?></td>
                                            <td><?php echo date( 'F j, Y, g:i a', strtotime( $recording->start_time ) ); ?></td>
                                            <td><?php echo rzwm_filesize_converter( $recording->total_size ); ?></td>
                                            <td>
                                                <?php if ( ! empty( $recording->recording_files ) ) { ?>
                                                    <a href="#TB_inline?width=600&height=550&inlineId=recording-<?php echo $recording->id; ?>" class="thickbox">View
                                                        Recordings</a>
                                                    <div id="recording-<?php echo esc_attr( $recording->id ); ?>" style="display:none;">
                                                        <?php foreach ( $recording->recording_files as $files ) { ?>
                                                            <ul class="rzwm-inside-table-wrapper rzwm-inside-table-wrapper-<?php echo esc_attr( $files->id ); ?>">
                                                                <li><strong><?php esc_html_e( 'File Type', 'webinar-manager-for-zoom-meetings' ); ?>
                                                                        :</strong> <?php echo esc_html( $files->file_type ); ?></li>
                                                                <li><strong><?php esc_html_e( 'File Size', 'webinar-manager-for-zoom-meetings' ); ?>
                                                                        :</strong> <?php echo rzwm_filesize_converter( $files->file_size ); ?></li>
                                                                <li><strong><?php esc_html_e( 'Play', 'webinar-manager-for-zoom-meetings' ); ?>:</strong>
                                                                    <a href="<?php echo esc_url( $files->play_url ); ?>" target="_blank"><?php esc_html_e( 'Play', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                                                </li>
                                                                <li><strong><?php esc_html_e( 'Download', 'webinar-manager-for-zoom-meetings' ); ?>:</strong>
                                                                    <a href="<?php echo esc_url( $files->download_url ); ?>" target="_blank"><?php esc_html_e( 'Download', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                                                </li>
                                                                <li><strong><?php esc_html_e( 'Recording Type', 'webinar-manager-for-zoom-meetings' ); ?>
                                                                        :</strong> <?php echo esc_html( $files->recording_type ); ?></li>
                                                            </ul>
                                                        <?php } ?>
                                                    </div>
                                                <?php } else {
                                                    echo esc_html( "N/A" );
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>