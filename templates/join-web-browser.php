<?php
/**
 * The Template for joining meeting via browser
 *
 * This template can be overridden by copying it to yourtheme/webinar-manager-for-zoom-meetings/join-web-browser.php.
 *
 * @package    Webinar Manager for Zoom Meetings/Templates
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $zoom;

if ( rzwmzoom_manager_check_login() ) {
	if ( ! empty( $zoom['api']->state ) && $zoom['api']->state === "ended" ) {
		echo "<h3>" . esc_html__( 'This meeting has been ended by host.', 'webinar-manager-for-zoom-meetings' ) . "</h3>";
		die;
	}

	/**
	 * Trigger before the content
	 */
	do_action( 'rzwmzoom_jbh_before_content', $zoom );
	?>
    <div id="dpen-zoom-browser-meeting" class="dpen-zoom-browser-meeting-wrapper">
        <div id="dpen-zoom-browser-meeting--container">
			<?php
			$bypass_notice = apply_filters( 'rzwm_api_bypass_notice', false );
			if ( ! $bypass_notice ) {
				?>
                <div class="dpen-zoom-browser-meeting--info">
					<?php if ( ! is_ssl() ) { ?>
                        <p style="line-height: 1.5;">
                            <strong style="color:red;"><?php esc_html_e( '!!!ALERT!!!: ', 'webinar-manager-for-zoom-meetings' ); ?></strong><?php esc_html_e(
								'Browser did not detect a valid SSL certificate. Audio and Video for Zoom meeting will not work on a non HTTPS site, please install a valid SSL certificate to allow audio and video in your Meetings via browser.', 'webinar-manager-for-zoom-meetings' ); ?>
                        </p>
					<?php } ?>
                    <div class="dpen-zoom-browser-meeting--info__browser"></div>
                </div>
			<?php } ?>
            <form class="dpen-zoom-browser-meeting--meeting-form" id="dpen-zoom-browser-meeting-join-form" action="">
                <div class="form-group">
                    <input type="text" name="display_name" id="display_name" value="" placeholder="Your Name Here" class="form-control" required>
                </div>
				<?php if ( ! isset( $_GET['pak'] ) ) { ?>
                    <div class="form-group">
                        <input type="password" name="meeting_password" id="meeting_password" value="" placeholder="Meeting Password" class="form-control" required>
                    </div>
					<?php
				}

				$bypass_lang = apply_filters( 'rzwm_api_bypass_lang', false );
				if ( ! $bypass_lang ) {
					?>
                    <div class="form-group">
                        <select id="meeting_lang" name="meeting-lang" class="form-control">
                            <option value="en-US">English</option>
                            <option value="de-DE">German Deutsch</option>
                            <option value="es-ES">Spanish Español</option>
                            <option value="fr-FR">French Français</option>
                            <option value="jp-JP">Japanese 日本語</option>
                            <option value="pt-PT">Portuguese Portuguese</option>
                            <option value="ru-RU">Russian Русский</option>
                            <option value="zh-CN">Chinese 简体中文</option>
                            <option value="zh-TW">Chinese 繁体中文</option>
                            <option value="ko-KO">Korean 한국어</option>
                            <option value="vi-VN">Vietnamese Tiếng Việt</option>
                            <option value="it-IT">Italian italiano</option>
                        </select>
                    </div>
					<?php
				}
				?>

                <button type="submit" class="btn btn-primary" id="dpen-zoom-browser-meeting-join-mtg">
					<?php esc_html_e( 'Join', 'webinar-manager-for-zoom-meetings' ); ?>
                </button>
            </form>
        </div>
    </div>
	<?php
	/**
	 * Trigger before the content
	 */
	do_action( 'rzwmzoom_jbh_after_content' );
} else {
	echo "<h3>" . esc_html__( 'You do not have enough priviledge to access this page. Please login to continue or contact administrator.', 'webinar-manager-for-zoom-meetings' ) . "</h3>";
	die;
}