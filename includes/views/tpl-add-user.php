<?php 
rzwmzoom_api_show_like_popup();
?>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fa fa-user"></i>                 
                </span>
                <?php esc_html_e( 'Add a User', 'webinar-manager-for-zoom-meetings' ); ?>
            </h3>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-12">
                <div class="card shadow mb-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><?php esc_html_e( 'Fill the details', 'webinar-manager-for-zoom-meetings' ); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="message">
                            <?php
                                $message = self::get_message();
                                if ( isset( $message ) && ! empty( $message ) ) {
                                    echo $message;
                                }
                            ?>
                        </div>
                        <div class="notice">
                            <p style="color:red;"><?php esc_html_e( 'What does this do ? Check out', 'webinar-manager-for-zoom-meetings' ); ?>
                                <a href="https://support.zoom.us/hc/en-us/articles/201363183-Managing-users"><?php esc_html_e( 'Zoom ', 'webinar-manager-for-zoom-meetings' ); ?> <?php esc_html_e( 'website', 'webinar-manager-for-zoom-meetings' ); ?></a>. <?php esc_html_e( 'Please note this may require a PRO account.', 'webinar-manager-for-zoom-meetings' ); ?>
                            </p>
                        </div>

                        <form action="?page=zoom-webinar-add-users" method="POST">
                            <?php wp_nonce_field( '_zoom_add_user_nonce_action', '_zoom_add_user_nonce' ); ?>
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="action"><?php esc_html_e( 'Action (Required).', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select name="action" id="action">
                                            <option value="create"><?php esc_html_e( 'Create', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option value="autoCreate"><?php esc_html_e( 'Auto Create', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option value="custCreate"><?php esc_html_e( 'Cust Create', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option value="ssoCreate"><?php esc_html_e( 'SSO Create', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                        </select>
                                        <div id="type-description">
                                            <p class="description"><?php esc_html_e( 'Type of User (Required)', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                            <p class="description">1. <strong>"<?php esc_html_e( 'Create', 'webinar-manager-for-zoom-meetings' ); ?>"</strong>
                                                - <?php esc_html_e( 'User will get an email sent from Zoom. There is a confirmation link in this email. User will then need to click this link to activate their account to the Zoom service. The user can set or change their password in Zoom.', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </p>

                                            <p class="description">2. <strong>"<?php esc_html_e( 'Auto Create', 'webinar-manager-for-zoom-meetings' ); ?>"</strong>
                                                - <?php esc_html_e( 'This action is provided for enterprise customer who has a managed domain. This feature is disabled by default because of the security risk involved in creating a user who does not belong to your domain without notifying the user.', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </p>

                                            <p class="description">3. <strong>"<?php esc_html_e( 'Cust Create', 'webinar-manager-for-zoom-meetings' ); ?>"</strong>
                                                - <?php esc_html_e( 'This action is provided for API partner only. User created in this way has no password and is not able to log into the Zoom web site or client.', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </p>

                                            <p class="description">4. <strong>"<?php esc_html_e( 'SSO Create', 'webinar-manager-for-zoom-meetings' ); ?>"</strong>
                                                - <?php esc_html_e( 'This action is provided for enabled “Pre-provisioning SSO User” option. User created in this way has no password. If it is not a basic user, will generate a Personal Vanity URL using user name (no domain) of the provisioning email. If user name or pmi is invalid or occupied, will use random number/random personal vanity URL.', 'webinar-manager-for-zoom-meetings' ); ?>
                                            </p></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="email"><?php esc_html_e( 'Email Address', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td><input name="email" type="email" required placeholder="john@doe.com" class="regular-text ltr">
                                        <p class="description" id="email-description"><?php esc_html_e( 'This address is used for zoom (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="first_name"><?php esc_html_e( 'First Name', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <input type="text" name="first_name" id="first_name" class="regular-text">
                                        <p class="description" id="first_name-description"><?php esc_html_e( 'First Name of the User (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="last_name"><?php esc_html_e( 'Last Name', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td><input type="text" name="last_name" id="last_name" class="regular-text">
                                        <p class="description" id="last_name-description"><?php esc_html_e( 'Last Name of the User (Required).', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="type"><?php esc_html_e( 'User Type (Required).', 'webinar-manager-for-zoom-meetings' ); ?></label></th>
                                    <td>
                                        <select name="type" id="type">
                                            <option value="1"><?php esc_html_e( 'Basic User', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                            <option value="2"><?php esc_html_e( 'Pro User', 'webinar-manager-for-zoom-meetings' ); ?></option>
                                        </select>
                                        <p class="description" id="type-description"><?php esc_html_e( 'Type of User (Required)', 'webinar-manager-for-zoom-meetings' ); ?></p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" name="add_zoom_user" class="button button-primary" value="<?php esc_html_e( 'Create User', 'webinar-manager-for-zoom-meetings' ); ?>"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>