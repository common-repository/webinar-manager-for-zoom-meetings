<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
rzwmzoom_api_show_like_popup();
$get_host_id = isset( $_GET['host_id'] ) ? sanitize_text_field( $_GET['host_id'] ) : null;
?>
<div id="rzwm-cover" style="display: none;"></div>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="fas fa-podcast"></i>                 
                </span>
                <?php esc_html_e( "Webinars", "webinar-manager-for-zoom-meetings" ); ?>
            </h3>
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
			        	<div class="select_rzwm_user_listings_wrapp">
					        <div class="alignleft actions bulkactions">
					            <label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e( "Select bulk action", "webinar-manager-for-zoom-meetings" ); ?></label>
					            <select name="action" id="bulk-action-selector-top">
					                <option value="trash"><?php esc_html_e( "Move to Trash", "webinar-manager-for-zoom-meetings" ); ?></option>
					            </select>
					            <input type="submit" id="bulk_delete_meeting_listings" data-hostid="<?php echo esc_attr( $get_host_id ); ?>" class="button action" value="Apply">
					            <a href="<?php echo add_query_arg( array(
									'new'       => 'zoom-webinar-webinars-add'
								) ); ?>" class="button action" title="Add new meeting"><?php esc_html_e( 'Add New Webinar', 'webinar-manager-for-zoom-meetings' ); ?></a>
					        </div>
					        <div class="alignright">
					            <select onchange="location = this.value;" class="rzwm-hacking-select">
					                <option value="<?php echo add_query_arg( array(
										'page'      => 'zoom-webinar-webinars',
									) ); ?>">
										<?php esc_html_e( 'Select a User', 'webinar-manager-for-zoom-meetings' ); ?>
									</option>
									<?php foreach ( $users as $user ) { ?>
					                    <option value="<?php echo add_query_arg( array(
											'page'      => 'zoom-webinar-webinars',
											'host_id'   => $user->id
										) ); ?>" <?php echo $get_host_id == $user->id ? 'selected' : false; ?>>
											<?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?>
										</option>
									<?php } ?>
					            </select>
					        </div>
					        <div class="clear"></div>
					    </div>

					    <div class="rzwm_listing_table">
					        <table id="rzwm_meetings_list_table" class="display" width="100%">
					            <thead>
					            <tr>
					                <th class="rzwm-text-center"><input type="checkbox" id="checkall"/></th>
					                <th class="rzwm-text-left"><?php esc_html_e( 'Webinar ID', 'webinar-manager-for-zoom-meetings' ); ?></th>
					                <th class="rzwm-text-left"><?php esc_html_e( 'Shortcode', 'webinar-manager-for-zoom-meetings' ); ?></th>
					                <th class="rzwm-text-left"><?php esc_html_e( 'Topic', 'webinar-manager-for-zoom-meetings' ); ?></th>
					                <th class="rzwm-text-left"><?php esc_html_e( 'Status', 'webinar-manager-for-zoom-meetings' ); ?></th>
					                <th class="rzwm-text-left" class="rzwm-text-left"><?php esc_html_e( 'Start Time', 'webinar-manager-for-zoom-meetings' ); ?></th>
					                <th class="rzwm-text-left"><?php esc_html_e( 'Created On', 'webinar-manager-for-zoom-meetings' ); ?></th>
					            </tr>
					            </thead>
					            <tbody>
								<?php
								if ( ! empty( $webinars ) ) {
									foreach ( $webinars as $webinar ) {
										?>
					                    <tr>
					                        <td class="rzwm-text-center">
					                            <input type="checkbox" name="meeting_id_check[]" class="checkthis" value="<?php echo $webinar->id; ?>"/></td>
					                        <td><?php echo $webinar->id; ?></td>
					                        <td>
					                            <input class="text" id="meeting-shortcode-<?php echo esc_attr( $webinar->id ); ?>" type="text" readonly value='[rzwm_zoom_api_webinar webinar_id="<?php echo esc_attr( $webinar->id ); ?>" link_only="no"]' onclick="this.select(); document.execCommand('copy'); alert('Copied to clipboard');"/>
					                            <p class="description"><?php esc_html_e( 'Click to Copy Shortcode !', 'webinar-manager-for-zoom-meetings' ); ?></p>
					                        </td>
					                        <td>
					                            <a href="?new=zoom-webinar-webinars-add&page=zoom-webinar-webinars&edit=<?php echo esc_url( $webinar->id ); ?>&host_id=<?php echo esc_url( $webinar->host_id ); ?>"><?php echo esc_html( $webinar->topic ); ?></a>
												<?php
												$zoom_host_url             = 'https://zoom.us' . '/wc/' . $webinar->id . '/start';
												$zoom_host_url             = apply_filters( 'video_conferencing_zoom_join_url_host', $zoom_host_url );
												$start_meeting_via_browser = '<a class="start-meeting-btn reload-meeting-started-button" target="_blank" href="' . esc_url( $zoom_host_url ) . '" class="join-link">' . __( 'Start via Browser', 'webinar-manager-for-zoom-meetings' ) . '</a>';
												?>
					                            <div class="row-actionss">
					                                <span class="trash"><a style="color:red;" href="javascript:void(0);" data-meetingid="<?php echo esc_attr( $webinar->id ); ?>" data-hostid="<?php echo esc_attr( $webinar->host_id ); ?>" class="submitdelete delete-meeting"><?php esc_html_e( 'Trash', 'webinar-manager-for-zoom-meetings' ); ?></a> | </span>
					                                <span class="view"><a href="<?php echo ! empty( $webinar->start_url ) ? $webinar->start_url : $webinar->join_url; ?>" rel="permalink" target="_blank"><?php esc_html_e( 'Start via App', 'webinar-manager-for-zoom-meetings' ); ?></a></span>
					                                <span class="view"> | <?php echo esc_html( $start_meeting_via_browser ); ?></span>
					                            </div>
					                        </td>
					                        <td><?php
												if ( ! empty( $webinar->status ) ) {
													switch ( $webinar->status ) {
														case 0;
															echo '<img src="' . RZWM_PLUGIN_URL . 'assets/images/2.png" style="width:14px;" title="Not Started" alt="Not Started">';
															break;
														case 1;
															echo '<img src="' . RZWM_PLUGIN_URL . 'assets/images/3.png" style="width:14px;" title="Completed" alt="Completed">';
															break;
														case 2;
															echo '<img src="' . RZWM_PLUGIN_URL . 'assets/images/1.png" style="width:14px;" title="Currently Live" alt="Live">';
															break;
														default;
															break;
													}
												} else {
													echo "N/A";
												}
												?>
					                        </td>
					                        <td>
												<?php
												if ( $webinar->type === 5 ) {
													echo rzwm_dateConverter( $webinar->start_time, $webinar->timezone, 'F j, Y, g:i a ( e )' );
												} else if ( $webinar->type === 6 ) {
													esc_html_e( 'This is a recurring meeting with no fixed time.', 'webinar-manager-for-zoom-meetings' );
												} else if ( $webinar->type === 9 ) {
													esc_html_e( 'Recurring Webinar', 'webinar-manager-for-zoom-meetings' );
												} else {
													echo "N/A";
												}
												?>
					                        </td>
					                        <td><?php echo date( 'F j, Y, g:i a', strtotime( $webinar->created_at ) ); ?></td>
					                    </tr>
										<?php
									}
								} ?>
					            </tbody>
					        </table>
					    </div>
			        </div>
			    </div>
			</div>
        </div>
    </div>
</div>