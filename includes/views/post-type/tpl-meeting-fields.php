<?php
/**
 * @author     Rajthemes.
 * @created_on 11/19/19
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<table class="form-table">
    <tbody>
	<?php
	global $post;
	$meeting_details = get_post_meta( $post->ID, '_meeting_zoom_details', true );
	if ( $post->post_status == 'publish' && is_object( $meeting_details ) && isset( $meeting_details->id ) ) {
		?>
        <tr>
            <th scope="row"><label for="meeting-shortcode">Shortcode</label></th>
            <td>
                <input class="text regular-text" id="meeting-shortcode" type="text" readonly value='[rzwm_zoom_api_link meeting_id="<?php echo $meeting_details->id; ?>" link_only="no"]' onclick="this.select(); document.execCommand('copy'); alert('Copied to clipboard');"/>
                <p class="description">
					<?php esc_html_e( 'If you need to show this meeting on another page or post please use this shortcode', 'webinar-manager-for-zoom-meetings' ); ?>
                </p>
            </td>

        </tr>
		<?php
	}

	$show_host = apply_filters( 'rzwm_admin_show_host_selection', true );
	if ( $show_host ) {
		if ( ! empty( $meeting_details ) && ! empty( $meeting_details->id ) && $post->post_status === 'publish' ) { ?>
            <tr class="zoom-host-id-selection-admin">
                <th scope="row"><label for="userId"><?php esc_html_e( 'Meeting Host *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                <td>
					<?php
					if ( ! empty( $users ) ) {
						foreach ( $users as $user ) {
							if ( $meeting_details->host_id === $user->id ) {
								echo '<input type="hidden" name="userId" value="' . $user->id . '">';
								echo esc_html( $user->first_name ) . ' ( ' . esc_html( $user->email ) . ' )';
								break;
							}
						}
					} else {
						printf( __( 'Did not find any hosts here ? Please %scheck here%s to verify your API keys are working correctly.', 'webinar-manager-for-zoom-meetings' ), '<a href="' . admin_url( 'edit.php?post_type=zoom-meetings&page=zoom-webinar-settings' ) . '">', '</a>' );
					} ?>
                    <p class="description" id="userId-description"><?php esc_html_e( 'This is host ID for the meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                </td>
            </tr>
			<?php
		} else {
			?>
            <tr class="zoom-host-id-selection-admin">
                <th scope="row"><label for="userId"><?php esc_html_e( 'Meeting Host *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                <td>
					<?php if ( ! empty( $users ) ) { ?>
                        <select name="userId" required class="rzwm-hacking-select">
                            <option value=""><?php esc_html_e( 'Select a Host', 'webinar-manager-for-zoom-meetings' ); ?></option>
							<?php foreach ( $users as $user ) { ?>
                                <option value="<?php echo $user->id; ?>" <?php ! empty( $meeting_fields['userId'] ) ? selected( esc_attr( $meeting_fields['userId'] ), $user->id ) : false; ?> ><?php echo esc_html( $user->first_name ) . ' ( ' . esc_html( $user->email ) . ' )'; ?></option>
							<?php } ?>
                        </select>
					<?php } else {
						printf( __( 'Did not find any hosts here ? Please %scheck here%s to verify your API keys are working correctly.', 'webinar-manager-for-zoom-meetings' ), '<a href="' . admin_url( 'edit.php?post_type=zoom-meetings&page=zoom-webinar-settings' ) . '">', '</a>' );
					} ?>
                    <p class="description" id="userId-description"><?php esc_html_e( 'This is host ID for the meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                </td>
            </tr>
		<?php }
	}
	?>
    <tr>
        <th scope="row"><label for="start_date"><?php esc_html_e( 'Start Date/Time *', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
            <input type="text" name="start_date" id="datetimepicker" data-existingdate="<?php echo ! empty( $meeting_fields['start_date'] ) ? esc_attr( $meeting_fields['start_date'] ) : false; ?>" required class="regular-text" value="<?php echo ! empty( $meeting_fields['start_date'] ) ? esc_attr( $meeting_fields['start_date'] ) : false; ?>">
            <p class="description" id="start_date-description"><?php esc_html_e( 'Starting Date and Time of the Meeting (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
        </td>
    </tr>

	<?php do_action( 'rzwm_admin_before_additional_fields' ); ?>

    <tr>
        <th scope="row"><label for="timezone"><?php esc_html_e( 'Timezone', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
			<?php
			$tzlists     = rzwm_get_timezone_options();
			$wp_timezone = rzwm_get_timezone_offset_wp();
			?>
            <select id="timezone" name="timezone" class="rzwm-hacking-select">
				<?php foreach ( $tzlists as $k => $tzlist ) { ?>
                    <option value="<?php echo $k; ?>" <?php ( ! empty( $meeting_fields['timezone'] ) ? selected( $k, $meeting_fields['timezone'] ) : ( ! empty( $wp_timezone ) && ! empty( $tzlists[ $wp_timezone ] ) && $tzlists[ $wp_timezone ] !== false ) ) ? selected( $k, $wp_timezone ) : false; ?>><?php echo esc_html( $tzlist ); ?></option>
				<?php } ?>
            </select>
            <p class="description" id="timezone-description"><?php esc_html_e( 'Meeting Timezone', 'webinar-manager-for-zoom-meetings' ); ?></p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="duration"><?php esc_html_e( 'Duration', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
            <input type="number" name="duration" class="regular-text" value="<?php echo ! empty( $meeting_fields['duration'] ) ? esc_html( $meeting_fields['duration'] ) : '40'; ?>">
            <p class="description" id="duration-description"><?php esc_html_e( 'Meeting duration (minutes). (optional)', 'webinar-manager-for-zoom-meetings' ); ?></p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="password"><?php esc_html_e( 'Password', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td class="rzwm-meetings-form">
            <input type="text" name="password" maxlength="10" data-maxlength="10" class="regular-text" value="<?php echo ! empty( $meeting_details->password ) ? esc_attr( $meeting_details->password ) : false; ?>">
            <p class="description" id="email-description"><?php esc_html_e( 'Password to join the meeting. Password may only contain the following characters: [a-z A-Z 0-9]. Max of 10 characters.( Leave blank for auto generate )', 'webinar-manager-for-zoom-meetings' ); ?></p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="join_before_host"><?php esc_html_e( 'Join Before Host', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
            <p class="description" id="join_before_host-description">
                <input type="checkbox" name="join_before_host" value="1" <?php ! empty( $meeting_fields['join_before_host'] ) ? checked( '1', $meeting_fields['join_before_host'] ) : false; ?> class="regular-text"><?php esc_html_e( 'Join meeting before host start the meeting. Only for scheduled or recurring meetings.', 'webinar-manager-for-zoom-meetings' ); ?>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="option_host_video"><?php esc_html_e( 'Host join start', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
            <p class="description" id="option_host_video-description">
                <input type="checkbox" name="option_host_video" value="1" <?php ! empty( $meeting_fields['option_host_video'] ) ? checked( '1', $meeting_fields['option_host_video'] ) : false; ?> class="regular-text"><?php esc_html_e( 'Start video when host join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="option_participants_video"><?php esc_html_e( 'Participants Video', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
            <p class="description" id="option_participants_video-description">
                <input type="checkbox" name="option_participants_video" <?php ! empty( $meeting_fields['option_participants_video'] ) ? checked( '1', $meeting_fields['option_participants_video'] ) : false; ?> value="1" class="regular-text"><?php esc_html_e( 'Start video when participants join meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="option_mute_participants_uponesc_html_entry"><?php esc_html_e( 'Mute Participants upon entry', 'webinar-manager-for-zoom-meetings' ); ?></label>
        </th>
        <td>
            <p class="description" id="option_mute_participants_uponesc_html_entry">
                <input type="checkbox" name="option_mute_participants" value="1" <?php ! empty( $meeting_fields['option_mute_participants'] ) ? checked( '1', $meeting_fields['option_mute_participants'] ) : false; ?> class="regular-text"><?php esc_html_e( 'Mutes Participants when entering the meeting.', 'webinar-manager-for-zoom-meetings' ); ?>
            </p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="option_auto_recording"><?php esc_html_e( 'Auto Recording', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
        <td>
            <select id="option_auto_recording" name="option_auto_recording">
                <option value="none" <?php ! empty( $meeting_fields['option_auto_recording'] ) ? selected( 'none', $meeting_fields['option_auto_recording'] ) : false; ?>>
					<?php esc_html_e( 'No Recordings', 'webinar-manager-for-zoom-meetings' ); ?>
                </option>
                <option value="local" <?php ! empty( $meeting_fields['option_auto_recording'] ) ? selected( 'local', $meeting_fields['option_auto_recording'] ) : false; ?>>
					<?php esc_html_e( 'Local', 'webinar-manager-for-zoom-meetings' ); ?>
                </option>
                <option value="cloud" <?php ! empty( $meeting_fields['option_auto_recording'] ) ? selected( 'cloud', $meeting_fields['option_auto_recording'] ) : false; ?>>
					<?php esc_html_e( 'Cloud', 'webinar-manager-for-zoom-meetings' ); ?>
                </option>
            </select>
            <p class="description" id="option_auto_recording_description"><?php esc_html_e( 'Set what type of auto recording feature you want to add. Default is none.', 'webinar-manager-for-zoom-meetings' ); ?></p>
        </td>
    </tr>
	<?php
	$show_host = apply_filters( 'rzwm_admin_show_alternative_host_selection', true );
	if ( $show_host ) {
		?>
        <tr>
            <th scope="row"><label for="settings_alternative_hosts"><?php esc_html_e( 'Alternative Hosts', 'webinar-manager-for-zoom-meetings' ); ?></label>
            </th>
            <td>
				<?php if ( ! empty( $users ) ) { ?>
                    <select name="alternative_host_ids[]" multiple class="rzwm-hacking-select">
                        <option value=""><?php esc_html_e( 'Select a Host', 'webinar-manager-for-zoom-meetings' ); ?></option>
						<?php foreach ( $users as $user ): ?>
                            <option value="<?php echo $user->id; ?>" <?php echo ! empty( $meeting_fields['alternative_host_ids'] ) && in_array( $user->id, $meeting_fields['alternative_host_ids'] ) ? 'selected' : false; ?>><?php echo esc_html( $user->first_name ) . ' ( ' . esc_html( $user->email ) . ' )'; ?></option>
						<?php endforeach; ?>
                    </select>
				<?php } else {
					printf( __( 'Did not find any hosts here ? Please %scheck here%s to verify your API keys are working correctly.', 'webinar-manager-for-zoom-meetings' ), '<a href="' . admin_url( 'edit.php?post_type=zoom-meetings&page=zoom-webinar-settings' ) . '">', '</a>' );
				} ?>
                <p class="description" id="settings_alternative_hosts"><?php esc_html_e( 'Paid Zoom Account is required for this !! Alternative hosts IDs. Multiple value separated by comma.', 'webinar-manager-for-zoom-meetings' ); ?></p>
            </td>
        </tr>
	<?php } ?>
    </tbody>
</table>