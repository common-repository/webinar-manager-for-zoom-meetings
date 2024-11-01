<?php
defined( 'ABSPATH' ) or die();
require_once( RZWM_PLUGIN_DIR_PATH . 'includes/helpers/helper.php' );

//Check if any transient by name is available
$users = rzwmzoom_manager_get_user_transients();

if ( isset( $_GET['host_id'] ) ) {
	$encoded_meetings = zoom_conference()->listMeetings( sanitize_text_field( $_GET['host_id'] ) );
	$decoded_meetings = json_decode( $encoded_meetings );
	$meetings         = $decoded_meetings->meetings;

	$encoded_webinars = zoom_conference()->listWebinar( sanitize_text_field( $_GET['host_id'] ) );
	$decoded_webinars = json_decode( $encoded_webinars );
	if ( ! empty( $decoded_webinars->code ) ) {
		echo $message = '<div class=error><p>' . $decoded_webinars->message . '</p></div>';
	} else {
		$webinars = $decoded_webinars->webinars;
	}
}

rzwmzoom_api_show_like_popup();
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
	          	<i class="fa fa-home"></i>                 
	        	</span>
	        	<?php esc_html_e( 'Dashboard', 'webinar-manager-for-zoom-meetings' ); ?>
	      	</h3>
	      	<?php $get_host_id = isset( $_GET['host_id'] ) ? sanitize_text_field( $_GET['host_id'] ) : null; ?>
	      	<nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <select onchange="location = this.value;" class="rzwm-hacking-select">
                            <option value="?page=webinar-manager-for-zoom-meetings"><?php esc_html_e( 'Select a User', 'webinar-manager-for-zoom-meetings' ); ?></option>
                            <?php foreach ( $users as $user ) { ?>
                                <option value="?page=webinar-manager-for-zoom-meetings&host_id=<?php echo esc_url( $user->id ); ?>" <?php echo $get_host_id == $user->id ? 'selected' : false; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
                            <?php } ?>
                        </select>
                    </li>
                </ul>
            </nav>
	    </div>

	    <div class="row dashboard_card">
	      	<div class="col-md-4 stretch-card grid-margin">
	        	<div class="card bg-gradient-blue card-img-holder text-white">
	          		<div class="card-body">
			            <img src="<?php echo RZWM_PLUGIN_URL; ?>assets/images/circle.svg" class="card-img-absolute" alt="circle-image"/>                  
			            <div class="row">
			              	<div class="col-md-9">
				                <h4 class="text-white"><?php echo esc_html( RZWMLiteHelperClass::get_current_user_data( get_current_user_id(), 'fullname') ); ?></h4>
			              	</div>
				            <div class="col-md-1 gravtar_wprsmp">
				                <?php echo wp_kses_post( get_avatar( RZWMLiteHelperClass::get_current_user_data( get_current_user_id(), 'user_email'), 70) ); ?>
				            </div>
			            </div>
			        </div>
	        	</div>
	      	</div>
	      	<div class="col-md-2 stretch-card grid-margin">
		        <div class="card card-img-holder">
			        <div class="card-body text-center">
					    <div class="card_inner_icon">
					        <i class="fas fa-video bg-gradient-blue text-white"></i>
					    </div>
					    <h3><?php esc_html_e( 'All Meetings', 'webinar-manager-for-zoom-meetings' ); ?></h3>
					    <p class="counter_text"><?php  $count_pages = wp_count_posts( $post_type = 'zoom-meetings' ); echo $count_pages->publish; ?></p>
			        </div>
		        </div>
		    </div>
		    <div class="col-md-2 stretch-card grid-margin">
		        <div class="card card-img-holder">
			        <div class="card-body text-center">
					    <div class="card_inner_icon">
					        <i class="fas fa-globe bg-gradient-new-primary text-white"></i>
					    </div>
					    <h3><?php esc_html_e( 'Zoom Live Meetings', 'webinar-manager-for-zoom-meetings' ); ?></h3>
					    <p class="counter_text"><?php if ( isset( $meetings ) ) { echo sizeof( $meetings ); } else {
					    	echo '0';
					    } ?></p>
			        </div>
		        </div>
		    </div>
		    <div class="col-md-2 stretch-card grid-margin">
		        <div class="card card-img-holder">
			        <div class="card-body text-center">
					    <div class="card_inner_icon">
					        <i class="fas fa-podcast bg-gradient-danger text-white"></i>
					    </div>
					    <h3><?php esc_html_e( 'Zoom Live Webinar', 'webinar-manager-for-zoom-meetings' ); ?></h3>
					    <p class="counter_text"><?php if ( isset( $webinars ) ) { echo sizeof( $webinars ); } else {
					    	echo '0';
					    } ?></p>
			        </div>
		        </div>
		    </div>
	      	<div class="col-md-2 stretch-card grid-margin">
		        <div class="card card-img-holder">
			        <div class="card-body text-center">
					    <div class="card_inner_icon">
					        <i class="fa fa-user bg-gradient-new-success"></i>
					    </div>
					    <h3><?php esc_html_e( 'Total Zoom Users', 'webinar-manager-for-zoom-meetings' ); ?></h3>
					    <p class="counter_text"><?php echo sizeof( $users ); ?></p>
			        </div>
		        </div>
		    </div>
		    <div class="col-md-12 stretch-card grid-margin">
		        <div class="card card-img-holder">
			        <div class="card-body">
					    <h3 class="recent_card_heading"><strong><?php esc_html_e( 'Recent Meetings', 'webinar-manager-for-zoom-meetings' ); ?></strong></h3>
					    <div class="table-responsive">
					    	<table class="table table-hover table-fixed">
							  <!--Table head-->
							  <thead>
							    <tr>
							      <th><?php esc_html_e( '#', 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <th><?php esc_html_e( 'Title', 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <th><?php esc_html_e( 'Category', 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <th><?php esc_html_e( 'Date', 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <th><?php esc_html_e( 'Start Meeting', 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <th><?php esc_html_e( 'Start Date', 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <th><?php esc_html_e( 'Meeting State', 'webinar-manager-for-zoom-meetings' ); ?></th>
							    </tr>
							  </thead>
							  <!--Table head-->

							  <!--Table body-->
							  <tbody>
							  	<?php 
							  		$args = array(  
								        'post_type'      => 'zoom-meetings',
								        'post_status'    => 'publish',
								        'posts_per_page' => 5, 
								        'order'          => 'ASC', 
								    );

								    $loop = new WP_Query( $args ); 
								    $sno = 1;
								    while ( $loop->have_posts() ) : $loop->the_post(); 
								    	$meeting = get_post_meta( get_the_ID(), '_meeting_zoom_details', true );
							  	?>
							    <tr>
							      <th scope="row"><?php esc_html_e( $sno, 'webinar-manager-for-zoom-meetings' ); ?></th>
							      <td><?php echo get_the_title(); ?></td>
							      <td>Horwitz</td>
							      <td><?php echo get_the_date(); ?></td>
							      <td>
							      	<?php if ( ! empty( $meeting ) && ! empty( $meeting->start_url ) ) {
											echo '<a href="' . esc_url( $meeting->start_url ) . '" target="_blank">Start</a>';
										} else {
											esc_html_e( 'Meeting not created yet.', 'webinar-manager-for-zoom-meetings' );
										}
									?>
							      </td>
							      <td>
							      	<?php
							      		if ( ! empty( $meeting ) && ! empty( $meeting->code ) && ! empty( $meeting->message ) ) {
											echo $meeting->message;
										} else if ( ! empty( $meeting ) && ! empty( $meeting->type ) && $meeting->type === 2 && ! empty( $meeting->start_time ) ) {
											echo rzwm_dateConverter( $meeting->start_time, $meeting->timezone, 'F j, Y, g:i a' );
										} else if ( ! empty( $meeting ) && ( $meeting->type === 3 || $meeting->type === 8 ) ) {
											esc_html_e( 'Recurring Meeting', 'webinar-manager-for-zoom-meetings' );
										} else {
											esc_html_e( 'Meeting not created yet.', 'webinar-manager-for-zoom-meetings' );
										}
									?>
							      </td>
							      <td>
							      	<?php
							      		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );
										if ( ! empty( $meeting ) ) {
											if ( ! empty( $meeting->code ) && ! empty( $meeting->message ) ) {
												echo $meeting->message;
											} else if ( empty( $meeting->state ) ) { ?>
						                        <a href="javascript:void(0);" class="rzwm-meeting-state-change" data-type="post_type" data-state="end" data-postid="<?php echo esc_attr( $post_id ); ?>" data-id="<?php echo esc_attr( $meeting->id ); ?>"><?php esc_html_e( 'Disable Join', 'webinar-manager-for-zoom-meetings' ); ?></a>
						                        <p class="description"><?php esc_html_e( 'Restrict users to join this meeting before the start time or after the meeting is completed.', 'webinar-manager-for-zoom-meetings' ); ?></p>
											<?php } else { ?>
						                        <a href="javascript:void(0);" class="rzwm-meeting-state-change" data-type="post_type" data-state="resume" data-postid="<?php echo esc_attr( $post_id ); ?>" data-id="<?php echo esc_attr( $meeting->id ); ?>"><?php esc_html_e( 'Enable Join', 'webinar-manager-for-zoom-meetings' ); ?></a>
						                        <p class="description"><?php esc_html_e( 'Resuming this will enable users to join this meeting.', 'webinar-manager-for-zoom-meetings' ); ?></p>
											<?php }
										} else {
											esc_html_e( 'Meeting not created yet.', 'webinar-manager-for-zoom-meetings' );
										}
									?>
							      </td>
							    </tr>
							    <?php 
							    	$sno++; endwhile;
    								wp_reset_postdata(); 
							    ?>
							  </tbody>
							  <!--Table body-->

							</table>
							<!--Table-->
					    </div>
			        </div>
		        </div>
		    </div>
		    
		</div>
	</div>
</div>