<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
$users = rzwmzoom_manager_get_user_transients();
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fas fa-video"></i>                 
                </span>
                <?php esc_html_e( 'Sync your Live Zoom Meetings to your site', 'webinar-manager-for-zoom-meetings' ); ?>
            </h3>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-12">
                <div class="card shadow mb-12">
                    <div class="card-body">
                        <div class="rzwm-notification">
                            <p><strong><?php esc_html_e( "This allows you to sync your live meetings from your Zoom Account to this site directly. Synced meetings will be inside Zoom Meeting > All Meetings page.", "webinar-manager-for-zoom-meetings" ); ?></strong></p>
                            <p><?php esc_html_e( "Currently, you can only sync scheduled meetings. No recurring meetings can be synced yet or webinars. For now, you can only sync meetings from your Zoom Account.", "webinar-manager-for-zoom-meetings" ); ?></p>
                        </div>

                        <div class="rzwm-sync-admin-wrapper">
                            <form action="" method="POST">
                                <label><strong><?php esc_html_e( "Choose a Zoom User", "webinar-manager-for-zoom-meetings" ); ?></strong></label> : <select class="rzwm-sync-user-id rzwm-hacking-select">
                                    <option value=""><?php esc_html_e( 'Select a User', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                    <?php foreach ( $users as $user ) { ?>
                                        <option value="<?php echo $user->id; ?>"><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                                    <?php } ?>
                                </select>
                            </form>

                            <div class="rzwm-status-notification"></div>
                            <div class="results"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>