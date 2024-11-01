<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="zvc-row">
    <div class="zvc-position-floater-left">
        <section class="zoom-api-example-section">
            <h3><?php esc_html_e( 'Using Shortcode Example', 'webinar-manager-for-zoom-meetings' ); ?></h3>
            <p><?php esc_html_e( 'Below are few examples of how you can add shortcodes manually into your posts.', 'webinar-manager-for-zoom-meetings' ); ?></p>

            <div class="zoom-api-basic-usage" style="margin-top: 20px;border-top:1px solid #ccc;">
                <h3><?php esc_html_e( '1. Basic Usage', 'webinar-manager-for-zoom-meetings' ); ?>:</h3>
                <code>[rzwm_zoom_api_link meeting_id="123456789" link_only="no"]</code>
                <div class="zoom-api-basic-usage-description">
                    <label><?php esc_html_e( 'Description', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <p><?php esc_html_e( 'Show a list with meeting details for a specific meeting ID with join links.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <label><?php esc_html_e( 'Parameters', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <ul>
                        <li><strong>meeting_id</strong> <?php esc_html_e( ': Your meeting ID.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>link_only</strong><?php esc_html_e( ' : Yes or No - Adding yes will show join link only. Removing this parameter from shortcode will output description.', 'webinar-manager-for-zoom-meetings' ); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="zoom-api-basic-usage" style="margin-top: 20px;border-top:1px solid #ccc;">
                <h3><?php esc_html_e( '2. Listing Zoom Meetings', 'webinar-manager-for-zoom-meetings' ); ?>:</h3>
                <code>[rzwm_zoom_list_meetings per_page="5" category="test,test2,test3" order="ASC" type="upcoming"]</code>
                <div class="zoom-api-basic-usage-description">
                    <label><?php esc_html_e( 'Description', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <p><?php esc_html_e( 'Shows a list of meetings with start time, date and link to the meetings page. This is customizable by overriding from your
                        theme folder.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <label><?php esc_html_e( 'Parameters', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <ul>
                        <li><strong>per_page</strong><?php esc_html_e( ' : Posts per page.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>category</strong><?php esc_html_e( ' : Show linked categories', 'webinar-manager-for-zoom-meetings' ); ?>.</li>
                        <li><strong>order</strong><?php esc_html_e( ' : ASC or DESC based on post created time.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>type</strong> <?php esc_html_e( ': "upcoming" or "past" - To show only upcoming meeting based on start time (Update to meeting is
                            required for old post type meetings', 'webinar-manager-for-zoom-meetings' ); ?></li>
                    </ul>
                </div>
            </div>
            <div class="zoom-api-basic-usage" style="margin-top: 20px;border-top:1px solid #ccc;">
                <h3><?php esc_html_e( '3. List Host ID', 'webinar-manager-for-zoom-meetings' ); ?>:</h3>
                <code>[rzwm_zoom_list_host_meetings host="YOUR_HOST_ID"]</code>
                <div class="zoom-api-basic-usage-description">
                    <label><?php esc_html_e( 'Description', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <p><?php esc_html_e( 'Show a list with meeting table based on HOST ID in frontend.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <label><?php esc_html_e( 'Parameters', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <ul>
                        <li><strong>host</strong><?php esc_html_e( ' : Your HOST ID.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                    </ul>
                </div>
            </div>
            <div class="zoom-api-basic-usage" style="margin-top: 20px;border-top:1px solid #ccc;">
                <h3><?php esc_html_e( '4. Embed Zoom Meeting in your Browser', 'webinar-manager-for-zoom-meetings' ); ?>:</h3>
                <code>[rzwm_zoom_join_via_browser meeting_id="YOUR_MEETING_ID" login_required="no" help="yes" title="Test" height="500px"
                    disable_countdown="yes"]</code>
                <div class="zoom-api-basic-usage-description">
                    <label><?php esc_html_e( 'Description', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <p><?php esc_html_e( 'Embeds your meeting in an IFRAME for any page or post you insert this shortcode into.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <p style="color: red;"><?php esc_html_e( 'Although this embed feature is here. I do no garauntee this would work properly as this is not natively supported by Zoom itself. This is here only because of user requests. USE THIS AT OWN RISK !!', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <label><?php esc_html_e( 'Parameters', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <ul>
                        <li><strong>meeting_id</strong> <?php esc_html_e( ': Your MEETING ID.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>login_required</strong> <?php esc_html_e( ': "yes or no", Requires login to view or join.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>help</strong> <?php esc_html_e( ': "yes or no", Help text.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>title</strong> <?php esc_html_e( ': Title of your Embed Session', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>height</strong><?php esc_html_e( ' : Height of embedded video IFRAME.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>disable_countdown</strong><?php esc_html_e( ' : "yes or no", enable or disable countdown.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                    </ul>
                </div>
            </div>
            <div class="zoom-api-basic-usage" style="margin-top: 20px;border-top:1px solid #ccc;">
                <h3><?php esc_html_e( '5. Show webinars based on HOST ID in frontend.', 'webinar-manager-for-zoom-meetings' ); ?>:</h3>
                <code>[rzwm_zoom_list_host_webinars host="YOUR_HOST_ID"]</code>
                <div class="zoom-api-basic-usage-description">
                    <label><?php esc_html_e( 'Description', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <p><?php esc_html_e( 'Embeds your meeting in an IFRAME for any page or post you insert this shortcode into.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <label><?php esc_html_e( 'Parameters', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <ul>
                        <li><strong>host</strong> <?php esc_html_e( ': Your HOST ID. Grab it from wp-admin > Zoom Meetings > Users ( USER ID ).', 'webinar-manager-for-zoom-meetings' ); ?></li>
                    </ul>
                </div>
            </div>
            <div class="zoom-api-basic-usage" style="margin-top: 20px;border-top:1px solid #ccc;">
                <h3><?php esc_html_e( '6. Show webinar based meeting ID.', 'webinar-manager-for-zoom-meetings' ); ?>:</h3>
                <code>[rzwm_zoom_api_webinar webinar_id="YOUR_WEBINAR_ID" link_only="no"]</code>
                <div class="zoom-api-basic-usage-description">
                    <label><?php esc_html_e( 'Description', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <p><?php esc_html_e( 'Shows a Webinar detail based on a specific Webinar ID.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                    <label><?php esc_html_e( 'Parameters', 'webinar-manager-for-zoom-meetings' ); ?>:</label>
                    <ul>
                        <li><strong>webinar_id</strong> <?php esc_html_e( ': WEBINAR ID.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                        <li><strong>link_only</strong> <?php esc_html_e( ': yes or no.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</div>
