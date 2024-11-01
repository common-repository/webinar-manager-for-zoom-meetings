<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
//Check if any transient by name is available
$users        = rzwmzoom_manager_get_user_transients();
$meeting_info = json_decode( zoom_conference()->getMeetingInfo( sanitize_text_field( $_GET['edit'] ) ) );
if ( ! empty( $meeting_info ) ) {
	$option_jbh                = ! empty( $meeting_info->settings->join_before_host ) && $meeting_info->settings->join_before_host ? 'checked' : false;
	$option_host_video         = ! empty( $meeting_info->settings->host_video ) && $meeting_info->settings->host_video ? 'checked' : false;
	$option_participants_video = ! empty( $meeting_info->settings->participant_video ) && $meeting_info->settings->participant_video ? 'checked' : false;
	$option_mute_participants  = ! empty( $meeting_info->settings->mute_uponesc_html_entry ) && $meeting_info->settings->mute_uponesc_html_entry ? 'checked' : false;
	$optionesc_html_enforce_login      = ! empty( $meeting_info->settings->enforce_login ) && $meeting_info->settings->enforce_login ? 'checked' : false;
	$option_alternative_hosts  = $meeting_info->settings->alternative_hosts ? $meeting_info->settings->alternative_hosts : false;
	if ( ! empty( $option_alternative_hosts ) ) {
		$option_alternative_hosts = explode( ', ', $option_alternative_hosts );
	}
}
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fas fa-video"></i>                 
                </span>
                <?php esc_html_e( "Edit Meeting", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="admin.php?page=zoom-webinar-meetings&host_id=<?php echo esc_url( $meeting_info->host_id ); ?>" class="btn btn-block btn-lg btn-gradient-primary btn-success custom-btn"><?php esc_html_e( 'Back to list', 'webinar-manager-for-zoom-meetings' ); ?></a>
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

        <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
                <div class="card card-img-holder">
                    <div class="card-body">
                        <form action="admin.php?page=zoom-webinar-meetings&edit=<?php echo sanitize_text_field( $_GET['edit'] ); ?>&host_id=<?php echo sanitize_text_field( $_GET['host_id'] ); ?>" method="POST" class="rzwm-meetings-form">
                            <?php wp_nonce_field( '_zoom_update_meeting_nonce_action', '_zoom_update_meeting_nonce' ); ?>
                            <input type="hidden" name="meeting_id" value="<?php echo esc_attr( $meeting_info->id ); ?>">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="meetingTopic"><?php esc_html_e( 'Meeting Topic *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="meetingTopic" size="100" class="regular-text" required value="<?php echo ! empty( $meeting_info->topic ) ? $meeting_info->topic : null; ?>">
                                        <p class="description" id="meetingTopic-description"><?php esc_html_e( 'Meeting topic. (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="meetingAgenda"><?php esc_html_e( 'Meeting Agenda', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="agenda" class="regular-text" value="<?php echo ! empty( $meeting_info->agenda ) ? $meeting_info->agenda : null; ?>">
                                        <p class="description" id="meetingTopic-description"><?php esc_html_e( 'Meeting Description.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="userId"><?php esc_html_e( 'Meeting Host *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select name="userId" required class="rzwm-hacking-select">
                                            <option value=""><?php esc_html_e( 'Select a Host', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <?php foreach ( $users as $user ): ?>
                                                <option value="<?php echo esc_attr( $user->id ); ?>" <?php echo $meeting_info->host_id == $user->id ? 'selected' : null; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description" id="userId-description"><?php esc_html_e( 'This is host ID for the meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr <?php echo $meeting_info->type === 3 ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                                    <th scope="row"><label for="start_date"><?php esc_html_e( 'Start Date/Time *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <?php
                                        $start_time = ! empty( $meeting_info->start_time ) ? $meeting_info->start_time : false;
                                        $timezone   = ! empty( $meeting_info->timezone ) ? $meeting_info->timezone : "America/Los_Angeles";
                                        $tz         = new DateTimeZone( $timezone );
                                        $date       = new DateTime( $start_time );
                                        $date->setTimezone( $tz );
                                        ?>
                                        <input type="text" name="start_date" id="datetimepicker" data-existingdate="<?php echo $date->format( 'Y-m-d H:i:s' ); ?>" required class="regular-text">
                                        <p class="description" id="start_date-description"><?php esc_html_e( 'Starting Date and Time of the Meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="timezone"><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <?php $tzlists = rzwm_get_timezone_options(); ?>
                                        <select id="timezone" name="timezone" class="rzwm-hacking-select">
                                            <?php foreach ( $tzlists as $k => $tzlist ) { ?>
                                                <option value="<?php echo $k; ?>" <?php echo $meeting_info->timezone == $k ? 'selected' : null; ?>><?php echo $tzlist; ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="description" id="timezone-description"><?php esc_html_e( 'Meeting Timezone', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="duration"><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="number" name="duration" class="regular-text" value="<?php echo !empty($meeting_info->duration) && $meeting_info->duration ? $meeting_info->duration : 40; ?>">
                                        <p class="description" id="duration-description"><?php esc_html_e( 'Meeting duration (minutes). (optional)', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="password"><?php esc_html_e( 'Meeting Password', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="password" class="regular-text" maxlength="10" data-maxlength="9" value="<?php echo ! empty( $meeting_info->password ) ? $meeting_info->password : false; ?>">
                                        <p class="description" id="email-description"><?php esc_html_e( 'Password to join the meeting. Password may only contain the following characters: [a-z A-Z 0-9]. Max of 10 characters.( Leave blank for auto generate )', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="join_before_host"><?php esc_html_e( 'Join Before Host', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="join_before_host-description">
                                            <input type="checkbox" <?php echo esc_attr( $option_jbh ); ?> name="join_before_host" value="1" class="regular-text"><?php esc_html_e( 'Join meeting before host start the meeting. Only for scheduled or recurring meetings.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_host_video"><?php esc_html_e( 'Host join start', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_host_video-description">
                                            <input type="checkbox" <?php echo esc_attr( $option_host_video ); ?> name="option_host_video" value="1" class="regular-text"><?php esc_html_e( 'Start video when host join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="option_participants_video"><?php esc_html_e( 'Participants Video', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_participants_video-description">
                                            <input type="checkbox" <?php echo esc_attr( $option_participants_video ); ?> name="option_participants_video" value="1" class="regular-text"><?php esc_html_e( 'Start video when participants join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="option_mute_participants_uponesc_html_entry"><?php esc_html_e( 'Mute Participants upon entry', 'webinar-manager-for-zoom-meetings' ); ?></label>
                                    </th>
                                    <td>
                                        <p class="description" id="option_mute_participants_uponesc_html_entry">
                                            <input type="checkbox" <?php echo esc_attr( $option_mute_participants ); ?> value="1" name="option_mute_participants" class="regular-text"><?php esc_html_e( 'Mutes Participants when entering the meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="optionesc_html_enforce_login"><?php esc_html_e( 'Enforce Login', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="optionesc_html_enforce_login">
                                            <input type="checkbox" <?php echo esc_attr( $optionesc_html_enforce_login ); ?> name="optionesc_html_enforce_login" value="1" class="regular-text"><?php esc_html_e( 'Only signed-in users can join this meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_auto_recording"><?php esc_html_e( 'Auto Recording', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select id="option_auto_recording" name="option_auto_recording">
                                            <option value="none" <?php echo ! empty( $meeting_info->settings->auto_recording ) && $meeting_info->settings->auto_recording == "none" ? "selected" : false; ?>>
                                                <?php esc_html_e( 'No Recordings', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </option>
                                            <option value="local" <?php echo ! empty( $meeting_info->settings->auto_recording ) && $meeting_info->settings->auto_recording == "local" ? "selected" : false; ?>>
                                                <?php esc_html_e( 'Local', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </option>
                                            <option value="cloud" <?php echo ! empty( $meeting_info->settings->auto_recording ) && $meeting_info->settings->auto_recording == "cloud" ? "selected" : false; ?>>
                                                <?php esc_html_e( 'Cloud', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </option>
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
                                            <?php
                                            foreach ( $users as $user ):
                                                $user_found = false;
                                                if ( ! empty( $option_alternative_hosts ) && in_array( $user->email, $option_alternative_hosts ) ) {
                                                    $user_found = true;
                                                }
                                                ?>
                                                <option value="<?php echo esc_attr( $user->id ); ?>" <?php echo $user_found ? 'selected' : null; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description" id="settings_alternative_hosts"><?php esc_html_e( 'Paid Zoom Account is required for this !! Alternative hosts IDs. Multiple value separated by comma.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" name="update_meeting" class="button button-primary" value="Update Meeting"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>