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
                <i class="fas fa-podcast"></i>                 
                </span>
                <?php esc_html_e( "Edit Webinar", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="admin.php?page=zoom-webinar-webinars<?php echo isset( $_GET['host_id'] ) ? '&host_id=' . sanitize_text_field( $_GET['host_id'] ) : false; ?>" class="btn btn-block btn-lg btn-gradient-primary btn-success custom-btn"><?php esc_html_e( 'Back to selected host Webinars list', 'webinar-manager-for-zoom-meetings' ); ?></a>
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
            <div class="col-md-12 stretch-card grid-margin">
                <div class="card card-img-holder">
                    <div class="card-body">
                        <form action="" method="POST" class="rzwm-meetings-form">
                            <?php wp_nonce_field( '_zoom_add_meeting_nonce_action', '_zoom_add_meeting_nonce' ); ?>
                            <input type="hidden" name="webinar_id" value="<?php echo esc_attr( $meeting_info->id ); ?>">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="meetingTopic"><?php esc_html_e( 'Webinar Topic *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="meetingTopic" size="100" value="<?php echo ! empty( $meeting_info->topic ) ? esc_html( $meeting_info->topic ) : false; ?>" required class="regular-text">
                                        <p class="description" id="meetingTopic-description"><?php esc_html_e( 'Webinar topic. (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="meetingAgenda"><?php esc_html_e( 'Webinar Agenda', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="agenda" value="<?php echo ! empty( $meeting_info->agenda ) ? esc_html( $meeting_info->agenda ) : false; ?>" class="regular-text">
                                        <p class="description" id="meetingTopic-description"><?php esc_html_e( 'Webinar Description.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr <?php echo $meeting_info->type === 6 || $meeting_info->type === 9 ? 'style="display:none;"' : 'style="display:table-row;"'; ?>>
                                    <th scope="row"><label for="start_date"><?php esc_html_e( 'Start Date/Time *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <?php
                                        if ( ! empty( $meeting_info->start_time ) && ! empty( $meeting_info->timezone ) ) {
                                            $date = rzwm_dateConverter( $meeting_info->start_time, $meeting_info->timezone, 'Y-m-d H:i:s', false );
                                        } else {
                                            $date = false;
                                        }
                                        ?>
                                        <input type="text" name="start_date" id="datetimepicker" data-existingdate="<?php echo esc_attr( $date ); ?>" value="<?php echo esc_attr( $date ); ?>" required class="regular-text">
                                        <p class="description" id="start_date-description"><?php esc_html_e( 'Starting Date and Time of the Webinar (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="timezone"><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <?php $tzlists = rzwm_get_timezone_options(); ?>
                                        <select id="timezone" name="timezone" class="rzwm-hacking-select">
                                            <?php foreach ( $tzlists as $k => $tzlist ) { ?>
                                                <option value="<?php echo esc_attr( $k ); ?>" <?php echo $meeting_info->timezone == $k ? 'selected' : null; ?>><?php echo esc_html( $tzlist ); ?></option>
                                            <?php } ?>
                                        </select>
                                        <p class="description" id="timezone-description"><?php esc_html_e( 'Webinar Timezone', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="duration"><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="number" name="duration" class="regular-text" value="<?php echo ! empty( $meeting_info->duration ) && $meeting_info->duration ? $meeting_info->duration : 40; ?>">
                                        <p class="description" id="duration-description"><?php esc_html_e( 'Webinar duration (minutes). (optional)', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="password"><?php esc_html_e( 'Webinar Password', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="password" class="regular-text" maxlength="10" data-maxlength="9" value="<?php echo ! empty( $meeting_info->password ) ? $meeting_info->password : false; ?>">
                                        <p class="description" id="email-description"><?php esc_html_e( 'Password to join the meeting. Password may only contain the following characters: [a-z A-Z 0-9]. Max of 10 characters.( Leave blank for auto generate )', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_host_video"><?php esc_html_e( 'Host Video', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_host_video-description">
                                            <input type="checkbox" <?php echo ! empty( $meeting_info->settings->host_video ) ? 'checked' : false; ?> name="option_host_video" value="1" class="regular-text"><?php esc_html_e( 'Start video when host join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_panelist_video"><?php esc_html_e( 'Panelists Video', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_panelist_video-description">
                                            <input type="checkbox" <?php echo ! empty( $meeting_info->settings->panelists_video ) ? 'checked' : false; ?> name="option_panelist_video" value="1" class="regular-text"><?php esc_html_e( 'Start video when panelists join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_hd_video"><?php esc_html_e( 'HD Video', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <p class="description" id="option_hd_video-description">
                                            <input type="checkbox" <?php echo ! empty( $meeting_info->settings->hd_video ) ? 'checked' : false; ?> name="option_hd_video" value="1" class="regular-text"><?php esc_html_e( 'Defaults to HD video.', 'webinar-manager-for-zoom-meetings' ); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="option_auto_recording"><?php esc_html_e( 'Auto Recording', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select id="option_auto_recording" name="option_auto_recording">
                                            <option <?php echo ! empty( $meeting_info->settings->auto_recording ) ? 'selected' : false; ?> value="none"><?php esc_html_e( 'No Recordings', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </option>
                                            <option <?php echo ! empty( $meeting_info->settings->auto_recording ) ? 'selected' : false; ?> value="local"><?php esc_html_e( 'Local', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option <?php echo ! empty( $meeting_info->settings->auto_recording ) ? 'selected' : false; ?> value="cloud"><?php esc_html_e( 'Cloud', 'webinar-manager-for-zoom-meetings' ); ?></option>
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
                                            $option_alternative_hosts = $meeting_info->settings->alternative_hosts ? $meeting_info->settings->alternative_hosts : false;
                                            if ( ! empty( $option_alternative_hosts ) ) {
                                                $option_alternative_hosts = explode( ', ', $option_alternative_hosts );
                                            }
                                            foreach ( $users as $user ):
                                                $user_found = false;
                                                if ( ! empty( $option_alternative_hosts ) && in_array( $user->email, $option_alternative_hosts ) ) {
                                                    $user_found = true;
                                                }
                                                ?>
                                                <option value="<?php echo $user->id; ?>" <?php echo $user_found ? 'selected' : null; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <p class="description" id="settings_alternative_hosts"><?php esc_html_e( 'Paid Zoom Account is required for this !! Alternative hosts IDs. Multiple value separated by comma.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" name="create_meeting" class="button button-primary" value="<?php esc_html_e( 'Create Webinar', 'webinar-manager-for-zoom-meetings' ); ?>"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>