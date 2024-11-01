<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="far fa-clipboard"></i>                 
                </span>
                <?php esc_html_e( "Reports", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-12">
                <div class="card shadow mb-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?php esc_html_e( 'ATTENTION: Zoom Account & Setting Prerequisites for Reports Section', 'webinar-manager-for-zoom-meetings' ); ?></h6>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li><?php esc_html_e( 'Pro, Business, Enterprise, Education, or API Account. Check more', 'webinar-manager-for-zoom-meetings' ); ?>
                                <a target="_blank" href="https://support.zoom.us/hc/en-us/articles/201363173-Account-Types">here</a>.
                            </li>
                            <li><?php esc_html_e( 'Account owner or admin permissions to access Usage Reports for all users.', 'webinar-manager-for-zoom-meetings' ); ?></li>
                            <li><?php esc_html_e( 'Account Owner or a user is given the User activities reports.', 'webinar-manager-for-zoom-meetings' ); ?>
                                <a target="_blank" href="https://support.zoom.us/hc/en-us/articles/115001078646">role</a></li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="col-md-12 stretch-card grid-margin">
                <div class="card card-img-holder">
                    <div class="card-body">
                        <h2 class="nav-tab-wrapper settings-tabs">
                            <a href="?page=zoom-webinar-reports&tab=zoom_daily_report" class="nav-tab <?php echo $active_tab == 'zoom_daily_report' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( '1. Daily Report', 'webinar-manager-for-zoom-meetings' ); ?></a>
                            <a href="?page=zoom-webinar-reports&tab=zoom_acount_report" class="nav-tab <?php echo $active_tab == 'zoom_acount_report' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( '2. Account Report', 'webinar-manager-for-zoom-meetings' ); ?></a>
                        </h2>

                        <?php if ( $active_tab == 'zoom_daily_report' ): ?><?php $result = rzwm_reports()->get_daily_report_html();
                            if ( isset( $_POST['zoom_check_month_year'] ) ) {
                                if ( isset( $result->error ) ) {
                                    ?>
                                    <div id="message" class="notice notice-error">
                                        <p><?php echo $result->error->message; ?></p>
                                    </div>
                                    <?php
                                }

                                if ( isset( $result->message ) ) { ?>
                                    <div id="message" class="notice notice-error">
                                        <p><?php echo $result->message; ?></p>
                                    </div>
                                <?php }
                            } ?>
                            <div class="zoom_dateinput_field">
                                <form action="?page=zoom-webinar-reports" class="rzwm_daily_reports_check_form" method="POST">
                                    <label><?php esc_html_e( 'Enter the date to check:', 'webinar-manager-for-zoom-meetings' ); ?></label>
                                    <input name="zoom_month_year" id="reports_date"/> <input type="submit" name="zoom_check_month_year" value="Check">
                                </form>
                            </div>
                            <table class="wp-list-table widefat fixed striped posts">
                                <thead>
                                <tr>
                                    <th><span><?php esc_html_e( 'Date', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Meetings', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'New Users', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Participants', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Meeting Minutes', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                </tr>
                                </thead>
                                <tbody id="the-list">
                                <?php
                                if ( isset( $result->dates ) ) {
                                    $count = count( $result->dates );
                                    foreach ( $result->dates as $date ) { ?>
                                        <tr>
                                            <td><?php echo date( 'F j, Y', strtotime( $date->date ) ); ?></td>
                                            <td><?php echo ( $date->meetings > 0 ) ? '<strong style="color: #4300FF; font-size: 16px;">' . $date->meetings . '</strong>' : '-'; ?></td>
                                            <td><?php echo ( $date->new_users > 0 ) ? '<strong style="color:#00A1B5; font-size: 16px;">' . $date->new_users . '</strong>' : '-'; ?></td>
                                            <td><?php echo ( $date->participants > 0 ) ? '<strong style="color:#00AF00; font-size: 16px;">' . $date->participants . '</strong>' : '-'; ?></td>
                                            <td><?php echo ( $date->meeting_minutes > 0 ) ? '<strong style="color:red; font-size: 16px;">' . $date->meeting_minutes . '</strong>' : '-'; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="5"><?php esc_html_e( "Select a Date to Check..." ); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php elseif ( $active_tab == 'zoom_acount_report' ):
                            $result = rzwm_reports()->get_account_report_html();
                            if ( isset( $_POST['zoom_check_account_info'] ) ) {
                                if ( empty( $_POST['zoom_account_from'] ) || empty( $_POST['zoom_account_to'] ) ) { ?>
                                    <div id="message" class="notice notice-error">
                                        <?php if ( isset( $result->error ) ) { ?>
                                            <p><?php echo $result->error->message; ?></p>
                                        <?php } else { ?>
                                            <p><?php echo $result; ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } else {
                                    if ( isset( $result->message ) ) { ?>
                                        <div id="message" class="notice notice-error">
                                            <p><?php echo $result->message; ?></p>
                                        </div>
                                    <?php } ?>

                                    <div id="message" class="notice notice-success">
                                        <ul class="zoom_acount_lists">
                                            <li><?php echo isset( $result->from ) ? esc_html__( 'Searching From: ', 'webinar-manager-for-zoom-meetings' ) . $result->from . ' to ' : null; ?><?php echo isset( $result->to ) ? $result->to : null; ?></li>
                                            <li><?php echo isset( $result->total_records ) ? esc_html__( 'Total Records Found: ', 'webinar-manager-for-zoom-meetings' ) . $result->total_records : null; ?></li>
                                            <li><?php echo isset( $result->total_meetings ) ? esc_html__( 'Total Meetings Held: ', 'webinar-manager-for-zoom-meetings' ) . $result->total_meetings : null; ?></li>
                                            <li><?php echo isset( $result->total_participants ) ? esc_html__( 'Total Participants Involved: ', 'webinar-manager-for-zoom-meetings' ) . $result->total_participants : null; ?></li>
                                            <li><?php echo isset( $result->total_meeting_minutes ) ? esc_html__( 'Total Meeting Minutes Combined: ', 'webinar-manager-for-zoom-meetings' ) . $result->total_meeting_minutes : null; ?></li>
                                        </ul>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            <div class="zoom_dateinput_field">
                                <p><?php esc_html_e( 'Get account report for a specified period.', 'webinar-manager-for-zoom-meetings' ); ?>
                                    <a onclick="window.print();" href="javascript:void(0);"><?php esc_html_e( 'Print', 'webinar-manager-for-zoom-meetings' ); ?></a></p>
                                <form action="?page=zoom-webinar-reports&tab=zoom_acount_report" class="rzwm_accounts_reports_check_form" method="POST">
                                    <label><?php esc_html_e( 'From', 'webinar-manager-for-zoom-meetings' ); ?></label>
                                    <input name="zoom_account_from" class="zoom_account_datepicker"/>
                                    <label><?php esc_html_e( 'To', 'webinar-manager-for-zoom-meetings' ); ?></label>
                                    <input name="zoom_account_to" class="zoom_account_datepicker"/> <input type="submit" name="zoom_check_account_info" value="Check">
                                </form>
                            </div>
                            <table class="wp-list-table widefat fixed striped posts">
                                <thead>
                                <tr>
                                    <th><span><?php esc_html_e( 'By', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Meetings Held', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Total Participants', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Total Meeting Minutes', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                    <th><span><?php esc_html_e( 'Last Login Time', 'webinar-manager-for-zoom-meetings' ); ?></span></th>
                                </tr>
                                </thead>
                                <tbody id="the-list">
                                <?php
                                if ( isset( $result->users ) ) {
                                    $count = count( $result->users );
                                    if ( $count == 0 ) {
                                        echo '<tr colspan="5"><td>' . esc_html__( 'No Records Found..', 'webinar-manager-for-zoom-meetings' ) . '</td></tr>';
                                    } else {
                                        foreach ( $result->users as $user ) { ?>
                                            <tr>
                                                <td><?php echo esc_htnl( $user->email ); ?></td>
                                                <td><?php echo esc_htnl( $user->meetings ); ?></td>
                                                <td><?php echo esc_htnl( $user->participants ); ?></td>
                                                <td><?php echo esc_htnl( $user->meeting_minutes ); ?></td>
                                                <td><?php echo date( 'F j, Y g:i a', strtotime( $user->last_login_time ) ); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="5"><?php esc_html_e( "Enter a value to Check...", "webinar-manager-for-zoom-meetings" ); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>