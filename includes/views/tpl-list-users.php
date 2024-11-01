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
                <i class="fa fa-user"></i>                 
                </span>
                <?php esc_html_e( "Users", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="?page=zoom-webinar-list-users&flush=true" class="btn btn-block btn-lg btn-gradient-primary btn-success custom-btn"><?php esc_html_e( 'Flush User Cache', 'webinar-manager-for-zoom-meetings' ); ?></a>
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
        <p><?php echo rzwmzoom_manager_pagination_next( $users ) . ' ' . rzwmzoom_manager_pagination_prev( $users ); ?></p>

        <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
                <div class="card card-img-holder">
                    <div class="card-body">
                        <div class="rzwm_listing_table">
                            <table id="rzwm_users_list_table" class="display" width="100%">
                                <thead>
                                <tr>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'SN', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'User ID', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Email', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Name', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Created On', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Last Login', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                    <th class="rzwm-text-left"><?php esc_html_e( 'Last Client', 'webinar-manager-for-zoom-meetings' ); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;
                                if ( ! empty( $users ) ) {
                                    foreach ( $users as $user ) {
                                        ?>
                                        <tr>
                                            <td><?php echo esc_html( $count++ ); ?></td>
                                            <td><?php echo esc_html( $user->id ); ?></td>
                                            <td><?php echo esc_html( $user->email ); ?></td>
                                            <td><?php echo esc_html( $user->first_name . ' ' . $user->last_name ); ?></td>
                                            <td><?php echo ! empty( $user->created_at ) ? date( 'F j, Y, g:i a', strtotime( $user->created_at ) ) : "N/A"; ?></td>
                                            <div id="rzwm_getting_user_info" style="display:none;">
                                                <div class="rzwm_getting_user_info_content"></div>
                                            </div>
                                            <td><?php echo ! empty( $user->last_login_time ) ? date( 'F j, Y, g:i a', strtotime( $user->last_login_time ) ) : "N/A"; ?></td>
                                            <td><?php echo ! empty( $user->last_client_version ) ? $user->last_client_version : "N/A"; ?></td>
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