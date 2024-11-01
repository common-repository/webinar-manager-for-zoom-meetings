<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
//Check if any transient by name is available
$users = rzwmzoom_manager_get_user_transients();
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fas fa-video"></i>                 
                </span>
                <?php esc_html_e( 'Add a Meeting', 'webinar-manager-for-zoom-meetings' ); ?>
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php if ( isset( $_GET['host_id'] ) ) {
                                $Host_ID = sanitize_text_field( $_GET['host_id'] );
                            } else {
                                $Host_ID = false;
                            }
                        ?>
                        <a href="admin.php?page=zoom-webinar-meetings<?php echo esc_url( $Host_ID ); ?>" class="btn btn-block btn-lg btn-gradient-primary btn-success custom-btn"><?php esc_html_e( 'Back to selected host Meetings list', 'webinar-manager-for-zoom-meetings' ); ?></a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="message">
            <?php
                $message = self::get_message();
                if ( isset( $message ) && ! empty( $message ) ) {
                    echo $message;
                }
            ?>
        </div>
        <?php rzwmzoom_manager_show_api_notice(); ?>

        <div class="row">
            <div class="col-lg-12 mb-12">
                <div class="card shadow mb-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?php esc_html_e( 'Fill the details', 'webinar-manager-for-zoom-meetings' ); ?></h6>
                    </div>
                    <div class="card-body">
                        <?php if ( isset( $_GET['host_id'] ) ) {
                                $Host_ID = sanitize_text_field( $_GET['host_id'] );
                            } else {
                                $Host_ID = false;
                            }
                        ?>
                        <form action="admin.php?page=zoom-webinar-add-meeting<?php echo esc_url( $Host_ID ); ?>" method="POST" class="rzwm-meetings-form">
                            <?php wp_nonce_field( '_zoom_add_meeting_nonce_action', '_zoom_add_meeting_nonce' ); ?>
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="meetingTopic"><?php esc_html_e( 'Meeting Topic *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="meetingTopic" size="100" required class="regular-text">
                                        <p class="description" id="meetingTopic-description"><?php esc_html_e( 'Meeting topic. (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="meetingAgenda"><?php esc_html_e( 'Meeting Agenda', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="agenda" class="regular-text">
                                        <p class="description" id="meetingTopic-description"><?php esc_html_e( 'Meeting Description.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="userId"><?php esc_html_e( 'Meeting Host *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select name="userId" required class="rzwm-hacking-select">
                                            <option value=""><?php esc_html_e( 'Select a Host', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <?php foreach ( $users as $user ): ?>
                                                <option value="<?php echo $user->id; ?>" <?php echo isset( $_GET['host_id'] ) && $_GET['host_id'] == $user->id ? 'selected' : null; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description" id="userId-description"><?php esc_html_e( 'This is host ID for the meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="start_date"><?php esc_html_e( 'Start Date/Time *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="start_date" id="datetimepicker" required class="regular-text">
                                        <p class="description" id="start_date-description"><?php esc_html_e( 'Starting Date and Time of the Meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="timezone"><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <?php $tzlists = rzwm_get_timezone_options(); ?>
                                        <select id="timezone" name="timezone" class="rzwm-hacking-select">
                                            <?php foreach ( $tzlists as $k => $tzlist ) { ?>
                                                <option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $tzlist ); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="description" id="timezone-description"><?php esc_html_e( 'Meeting Timezone', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="duration"><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="number" name="duration" class="regular-text">
                                        <p class="description" id="duration-description"><?php esc_html_e( 'Meeting duration (minutes). (optional)', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="password"><?php esc_html_e( 'Meeting Password', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="password" class="regular-text" maxlength="10" data-maxlength="9">
                                        <p class="description" id="email-description"><?php esc_html_e( 'Password to join the meeting. Password may only contain the following characters: [a-z A-Z 0-9]. Max of 10 characters.( Leave blank for auto generate )', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="join_before_host"><?php esc_html_e( 'Join Before Host', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="join_before_host-description">
                                            <input type="checkbox" name="join_before_host" value="1" class="regular-text"><?php esc_html_e( 'Join meeting before host start the meeting. Only for scheduled or recurring meetings.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_host_video"><?php esc_html_e( 'Host join start', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_host_video-description">
                                            <input type="checkbox" name="option_host_video" value="1" class="regular-text"><?php esc_html_e( 'Start video when host join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="option_participants_video"><?php esc_html_e( 'Participants Video', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_participants_video-description">
                                            <input type="checkbox" name="option_participants_video" value="1" class="regular-text"><?php esc_html_e( 'Start video when participants join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="option_mute_participants_uponesc_html_entry"><?php esc_html_e( 'Mute Participants upon entry', 'webinar-manager-for-zoom-meetings' ); ?></label>
                                    </th>
                                    <td>
                                        <p class="description" id="option_mute_participants_uponesc_html_entry">
                                            <input type="checkbox" name="option_mute_participants" value="1" class="regular-text"><?php esc_html_e( 'Mutes Participants when entering the meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="optionesc_html_enforce_login"><?php esc_html_e( 'Enforce Login', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="optionesc_html_enforce_login-description">
                                            <input type="checkbox" name="optionesc_html_enforce_login" value="1" class="regular-text"><?php esc_html_e( 'Only signed-in users can join this meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_auto_recording"><?php esc_html_e( 'Auto Recording', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select id="option_auto_recording" name="option_auto_recording">
                                            <option value="none"><?php esc_html_e( 'No Recordings', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option value="local"><?php esc_html_e( 'Local', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option value="cloud"><?php esc_html_e( 'Cloud', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                        </select>
                                        <p class="description" id="option_auto_recording_description"><?php esc_html_e( 'Set what type of auto recording feature you want to add. Default is none.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="settings_alternative_hosts"><?php esc_html_e( 'Alternative Hosts', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select name="alternative_host_ids[]" multiple class="rzwm-hacking-select">
                                            <option value=""><?php esc_html_e( 'Select a Host', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <?php foreach ( $users as $user ): ?>
                                                <option value="<?php echo esc_attr( $user->id ); ?>"><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description" id="settings_alternative_hosts"><?php esc_html_e( 'Alternative hosts IDs. Multiple value separated by comma.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" name="create_meeting" class="button button-primary" value="<?php esc_html_e( 'Create Meeting', 'webinar-manager-for-zoom-meetings' ); ?>"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>