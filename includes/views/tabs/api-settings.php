<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="rzwm-cover" style="display: none;"></div>
<div class="rzwm-row">
    <div class="rzwm-position-floater-left col-md-12" >
        <h3><?php esc_html_e( 'Please follow', 'webinar-manager-for-zoom-meetings' ) ?>
            <a target="_blank" href="<?php echo RZWM_PLUGIN_AUTHOR; ?>documentation/webinar-manager-for-zoom-meetings-documentation/"><?php esc_html_e( 'this guide', 'webinar-manager-for-zoom-meetings' ) ?> </a> <?php esc_html_e( 'to generate the below API values from your Zoom account', 'webinar-manager-for-zoom-meetings' ) ?>
        </h3>

        <form action="admin.php?page=zoom-webinar-settings" method="POST">
			<?php wp_nonce_field( '_zoom_settings_update_nonce_action', '_zoom_settings_nonce' ); ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th><label><?php esc_html_e( 'API Key', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="password" style="width: 400px;" name="zoom_api_key" id="zoom_api_key" value="<?php echo ! empty( $zoom_api_key ) ? esc_html( $zoom_api_key ) : ''; ?>">
                        <a href="javascript:void(0);" class="toggle-api">Show</a></td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'API Secret Key', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="password" style="width: 400px;" name="zoom_api_secret" id="zoom_api_secret" value="<?php echo ! empty( $zoom_api_secret ) ? esc_html( $zoom_api_secret ) : ''; ?>">
                        <a href="javascript:void(0);" class="toggle-secret">Show</a></td>
                </tr>
                <tr class="enabled-vanity-url">
                    <th><label><?php esc_html_e( 'Vanity URL', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="url" name="vanity_url" class="regular-text" value="<?php echo ( $zoom_vanity_url ) ? esc_html( $zoom_vanity_url ) : ''; ?>" placeholder="https://example.zoom.us">
                        <p class="description"><?php esc_html_e( 'If you are using Zoom Vanity URL then please insert it here else leave it empty.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                        <a href="https://support.zoom.us/hc/en-us/articles/215062646-Guidelines-for-Vanity-URL-Requests"><?php esc_html_e( 'Read more about Vanity
                                URLs', 'webinar-manager-for-zoom-meetings' ); ?></a>
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Hide Join Links for Non-Loggedin ?', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="hide_join_links_non_loggedin_users" <?php ! empty( $hide_join_link_nloggedusers ) ? checked( $hide_join_link_nloggedusers, 'on' ) : false; ?>>
                        <span class="description"><?php esc_html_e( 'Checking this option will hide join links from your shortcode for non-loggedin users.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Disable Embed password in Link ?', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="embed_password_join_link" <?php ! empty( $embed_password_join_link ) ? checked( $embed_password_join_link, 'on' ) : false; ?>>
                        <span class="description"><?php esc_html_e( 'Meeting password will not be included in the invite link to allow participants to join with just one click without having to enter the password.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                    </td>
                </tr>
                <tr class="enabled-join-links-after-mtg-end">
                    <th><label><?php esc_html_e( 'Show Past Join Link ?', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="meetingesc_html_end_join_link" <?php ! empty( $past_join_links ) ? checked( $past_join_links, 'on' ) : false; ?>>
                        <span class="description"><?php esc_html_e( 'This will show join meeting links on frontend even after meeting time is already past.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                    </td>
                </tr>
                <tr class="show-zoom-authors">
                    <th><label><?php esc_html_e( 'Show Zoom Author ?', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="meeting_show_zoom_author_original" <?php ! empty( $zoom_author_show ) ? checked( $zoom_author_show, 'on' ) : false; ?>>
                        <span class="description"><?php esc_html_e( 'Checking this show Zoom original Author in single meetings page which are created from', 'webinar-manager-for-zoom-meetings' ); ?>
                                <a href="<?php echo esc_url( admin_url( '/edit.php?post_type=zoom-meetings' ) ); ?>">Zoom Meetings</a></span>
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Meeting Started Text', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="text" style="width: 400px;" name="zoom_api_meeting_started_text" id="zoom_api_meeting_started_text" value="<?php echo ! empty( $zoom_started ) ? esc_html( $zoom_started ) : ''; ?>" placeholder="Leave empty for default text">
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Meeting going to start Text', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="text" style="width: 400px;" name="zoom_api_meeting_goingtostart_text" id="zoom_api_meeting_goingtostart_text" value="<?php echo ! empty( $zoom_going_to_start ) ? esc_html( $zoom_going_to_start ) : ''; ?>" placeholder="Leave empty for default text">
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Meeting Ended Text', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="text" style="width: 400px;" name="zoom_api_meetingesc_html_ended_text" id="zoom_api_meetingesc_html_ended_text" value="<?php echo ! empty( $zoomesc_html_ended ) ? esc_html( $zoomesc_html_ended ) : ''; ?>" placeholder="Leave empty for default text">
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'DateTime Format', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <div>
                            <input type="radio" value="LLLL" name="zoom_api_date_time_format" <?php echo ! empty( $locale_format ) ? checked( $locale_format, 'LLLL', false ) : 'checked'; ?> class="zoom_api_date_time_format"><?php esc_html_e( 'Wednesday, May 6, 2020 05:00 PM', 'webinar-manager-for-zoom-meetings' ); ?>
                        </div>
                        <div style="padding-top:10px;">
                            <input type="radio" value="lll" <?php echo ! empty( $locale_format ) ? checked( $locale_format, 'lll', false ) : ''; ?> name="zoom_api_date_time_format" class="zoom_api_date_time_format"> <?php esc_html_e( 'May 6, 2020 05:00 AM', 'webinar-manager-for-zoom-meetings' ); ?>
                        </div>
                        <div style="padding-top:10px;">
                            <input type="radio" value="llll" <?php echo ! empty( $locale_format ) ? checked( $locale_format, 'llll', false ) : ''; ?> name="zoom_api_date_time_format" class="zoom_api_date_time_format"> <?php esc_html_e( 'Wed, May 6, 2020 05:00 AM', 'webinar-manager-for-zoom-meetings' ); ?>
                        </div>
                        <div style="padding-top:10px;">
                            <input type="radio" value="L LT" <?php echo ! empty( $locale_format ) ? checked( $locale_format, 'L LT', false ) : ''; ?> name="zoom_api_date_time_format" class="zoom_api_date_time_format"> <?php esc_html_e( '05/06/2020 03:00 PM', 'webinar-manager-for-zoom-meetings' ); ?>
                        </div>
                        <div style="padding-top:10px;">
                            <input type="radio" value="l LT" <?php echo ! empty( $locale_format ) ? checked( $locale_format, 'l LT', false ) : ''; ?> name="zoom_api_date_time_format" class="zoom_api_date_time_format"><?php esc_html_e( '5/6/2020 03:00 PM', 'webinar-manager-for-zoom-meetings' ); ?>
                        </div>
                        <p class="description"><?php esc_html_e( 'Change date time formats according to your choice. Please edit this properly. Failure to correctly put value will result in failure to show date in frontend.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Use 24-hour format', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="zoom_api_twenty_fourhour_format" <?php echo ! empty( $twentyfour_format ) ? checked( $twentyfour_format, 'on' ) : false; ?> class="zoom_api_date_time_format">
                        <span class="description"><?php esc_html_e( 'Checking this option will show 24 hour time format in all event dates.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label><?php esc_html_e( 'Use full month label format ?', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                    <td>
                        <input type="checkbox" name="zoom_api_full_month_format" <?php echo ! empty( $full_month_format ) ? checked( $full_month_format, 'on' ) : false; ?> class="zoom_api_date_time_format">
                        <span class="description"><?php esc_html_e( 'Checking this option will show full month label for example: June, July, August etc.', 'webinar-manager-for-zoom-meetings' ); ?></span>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3 class="description" style="color:red;"><?php esc_html_e( 'After you enter your keys. Do save changes before doing "Check API Connection".', 'webinar-manager-for-zoom-meetings' ); ?></h3>
            <p class="submit">
                <input type="submit" name="save_zoom_settings" id="submit" class="button bg-gradient-blue text-white" value="<?php esc_html_e( 'Save Changes', 'webinar-manager-for-zoom-meetings' ); ?>">
                <a href="javascript:void(0);" class="button bg-gradient-blue text-white check-api-connection"><?php esc_html_e( 'Check API Connection', 'webinar-manager-for-zoom-meetings' ); ?></a>
            </p>
        </form>
    </div>
</div>
