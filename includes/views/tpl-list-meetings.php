<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
//Check if any transient by name is available
$users = rzwmzoom_manager_get_user_transients();

if ( isset( $_GET['host_id'] ) ) {
	$encoded_meetings = zoom_conference()->listMeetings( sanitize_text_field( $_GET['host_id'] ) );
	$decoded_meetings = json_decode( $encoded_meetings );
	$meetings         = $decoded_meetings->meetings;
	$meeting_states   = get_option( 'zoom_api_meeting_options' );
}
?>
<div id="rzwm-cover" style="display: none;"></div>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fas fa-video"></i>                 
                </span>
                <?php esc_html_e( "Meetings", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
        </div>

        <?php if ( ! empty( $error ) ) { ?>
        <div id="message" class="notice notice-error"><p><?php echo $error; ?></p></div>
        <?php } else {
        $get_host_id = isset( $_GET['host_id'] ) ? sanitize_text_field( $_GET['host_id'] ) : null;
        ?>
        <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
                <div class="card card-img-holder">
                    <div class="card-body">
                        <div class="select_rzwm_user_listings_wrapp">
                            <div class="alignleft actions bulkactions">
                                <label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e( "Select bulk action", "webinar-manager-for-zoom-meetings" ); ?></label>
                                <select name="action" id="bulk-action-selector-top">
                                    <option value="trash"><?php esc_html_e( "Move to Trash", "webinar-manager-for-zoom-meetings" ); ?></option>
                                </select>
                                <input type="submit" id="bulk_delete_meeting_listings" data-hostid="<?php echo $get_host_id; ?>" class="button action" value="Apply">
                                <a href="?page=zoom-webinar-add-meeting&host_id=<?php echo esc_url( $get_host_id ); ?>" class="button action" title="<?php esc_html_e( 'Add new meeting', 'webinar-manager-for-zoom-meetings' ); ?>"><?php esc_html_e( 'Add New Meeting', 'webinar-manager-for-zoom-meetings' ); ?></a>
                            </div>
                            <div class="alignright">
                                <select onchange="location = this.value;" class="rzwm-hacking-select">
                                    <option value="?page=zoom-webinar-meetings"><?php esc_html_e( 'Select a User', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                    <?php foreach ( $users as $user ) { ?>
                                        <option value="?page=zoom-webinar-meetings&host_id=<?php echo esc_attr( $user->id ); ?>" <?php echo $get_host_id == $user->id ? 'selected' : false; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="rzwm_listing_table">
                            <table id="rzwm_meetings_list_table" class="display" width="100%">
                                <thead>
                                <tr>
                                    <th class="rzwm-text-center"><input type="checkbox" id="checkall"/></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Shortcode', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Status', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left" class="rzwm-text-left"><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Meeting State', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Created On', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ( ! empty( $meetings ) ) {
                                    foreach ( $meetings as $meeting ) {
                                        ?>
                                        <tr>
                                            <td class="rzwm-text-center">
                                                <input type="checkbox" name="meeting_id_check[]" class="checkthis" value="<?php echo esc_attr( $meeting->id ); ?>"/></td>
                                            <td><?php echo $meeting->id; ?></td>
                                            <td>
                                                <input class="text" id="meeting-shortcode-<?php echo esc_attr( $meeting->id ); ?>" type="text" readonly value='[rzwm_zoom_api_link meeting_id="<?php echo esc_attr( $meeting->id ); ?>" link_only="no"]' onclick="this.select(); document.execCommand('copy'); alert('Copied to clipboard');"/>
                                                <p class="description"><?php esc_html_e( 'Click to Copy Shortcode !', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                            </td>
                                            <td>
                                                <a href="?page=zoom-webinar-add-meeting&edit=<?php echo esc_url( $meeting->id ); ?>&host_id=<?php echo esc_url( $meeting->host_id ); ?>"><?php echo esc_html( $meeting->topic ); ?></a>
                                                <?php
                                                $zoom_host_url             = 'https://zoom.us' . '/wc/' . $meeting->id . '/start';
                                                $zoom_host_url             = apply_filters( 'video_conferencing_zoom_join_url_host', $zoom_host_url );
                                                $start_meeting_via_browser = '<a class="start-meeting-btn reload-meeting-started-button" target="_blank" href="' . esc_url( $zoom_host_url ) . '" class="join-link">' . __( 'Start via Browser', 'webinar-manager-for-zoom-meetings' ) . '</a>';
                                                ?>
                                                <div class="row-actionss">
                                                    <span class="trash"><a style="color:red;" href="javascript:void(0);" data-meetingid="<?php echo esc_attr( $meeting->id ); ?>" data-hostid="<?php echo esc_attr( $meeting->host_id ); ?>" class="submitdelete delete-meeting"><?php esc_html_e( 'Trash', 'webinar-manager-for-zoom-meetings' ); ?></a> | </span>
                                                    <span class="view"><a href="<?php echo ! empty( $meeting->start_url ) ? $meeting->start_url : $meeting->join_url; ?>" rel="permalink" target="_blank"><?php esc_html_e( 'Start via App', 'webinar-manager-for-zoom-meetings' ); ?></a></span>
                                                    <span class="view"> | <?php echo esc_html( $start_meeting_via_browser ); ?></span>
                                                </div>
                                            </td>
                                            <td><?php
                                                if ( ! empty( $meeting->status ) ) {
                                                    switch ( $meeting->status ) {
                                                        case 0;
                                                            echo '<img src="' . RZWM_PLUGIN_URL . 'assets/images/2.png" style="width:14px;" title="Not Started" alt="Not Started">';
                                                            break;
                                                        case 1;
                                                            echo '<img src="' . RZWM_PLUGIN_URL . 'assets/images/3.png" style="width:14px;" title="Completed" alt="Completed">';
                                                            break;
                                                        case 2;
                                                            echo '<img src="' . RZWM_PLUGIN_URL . 'assets/images/1.png" style="width:14px;" title="Currently Live" alt="Live">';
                                                            break;
                                                        default;
                                                            break;
                                                    }
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ( $meeting->type === 2 ) {
                                                    echo rzwm_dateConverter( $meeting->start_time, $meeting->timezone, 'F j, Y, g:i a ( e )' );
                                                } else if ( $meeting->type === 3 ) {
                                                    esc_html_e( 'This is a recurring meeting with no fixed time.', 'webinar-manager-for-zoom-meetings' );
                                                } else if ( $meeting->type === 8 ) {
                                                    esc_html_e( 'Recurring Meeting', 'webinar-manager-for-zoom-meetings' );
                                                } else {
                                                    echo esc_html( "N/A" );
                                                }
                                                ?>
                                            </td>
                                            <td style="width: 120px;">
                                                <?php if ( ! isset( $meeting_states[ $meeting->id ]['state'] ) ) { ?>
                                                    <a href="javascript:void(0);" class="rzwm-meeting-state-change" data-type="shortcode" data-state="end" data-id="<?php echo esc_attr( $meeting->id ); ?>"><?php esc_html_e( 'Disable Join', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                                    <div class="rzwm-admin-info-tooltip">
                                                        <span class="dashicons dashicons-info"></span>
                                                        <span class="rzwm-admin-info-tooltip--text"><?php esc_html_e( 'Ending this will disable users to join this meeting. Applies to any shortcode output only.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                                                    </div>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0);" class="rzwm-meeting-state-change" data-type="shortcode" data-state="resume" data-id="<?php echo esc_attr( $meeting->id ); ?>"><?php esc_html_e( 'Enable Join', 'webinar-manager-for-zoom-meetings' ); ?></a>
                                                    <div class="rzwm-admin-info-tooltip">
                                                        <span class="dashicons dashicons-info "></span>
                                                        <span class="rzwm-admin-info-tooltip--text"><?php esc_html_e( 'Resuming this will enable users to join this meeting. Applies to any shortcode output only.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo date( 'F j, Y, g:i a', strtotime( $meeting->created_at ) ); ?></td>
                                        </tr>
                                        <?php
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>