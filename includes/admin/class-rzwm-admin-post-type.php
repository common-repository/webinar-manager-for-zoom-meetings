<?php
/**
 * Meeting Post Type Controller
 *
 * @since      1.0.0
 * @author     Rajthemes
 */

class RZoomWebinarManagertLite_Admin_PostType {

	/**
	 * Post Type Flag
	 *
	 * @var string
	 */
	private $post_type = 'zoom-meetings';

	private $api_key;
	private $api_secret;

	/**
	 * RZoomWebinarManagertLite_Admin_PostType constructor.
	 */
	public function __construct() {
		$this->api_key    = get_option( 'zoom_api_key' );
		$this->api_secret = get_option( 'zoom_api_secret' );

		add_action( 'restrict_manage_posts', [ $this, 'filtering' ], 10 );
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'admin_menu', [ $this, 'hide_post_type' ] );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ), 10, 2 );
		add_filter( 'single_template', array( $this, 'single' ) );
		add_filter( 'archive_template', array( $this, 'archive' ) );
		add_action( 'before_delete_post', array( $this, 'delete' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_filter( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'add_columns' ), 20 );
		add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'render_data' ), 20, 2 );
	}

	/**
	 * Hide Post Type page
	 */
	public function hide_post_type() {
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] !== $this->post_type ) {
			return;
		}

		if ( ! rzwmzoom_manager_get_user_transients() ) {
			global $submenu;
			unset( $submenu['edit.php?post_type=zoom-meetings'][5] );
			unset( $submenu['edit.php?post_type=zoom-meetings'][10] );
			unset( $submenu['edit.php?post_type=zoom-meetings'][15] );
		}

	}

	/**
	 * Filters
	 *
	 * @param $post_type
	 */
	public function filtering( $post_type ) {
		if ( $this->post_type !== $post_type ) {
			return;
		}

		$taxnomy  = 'zoom-meeting';
		$taxonomy = get_taxonomy( $taxnomy );
		$selected = isset( $_REQUEST[ $taxnomy ] ) ? sanitize_text_field( $_REQUEST[ $taxnomy ] ) : '';
		wp_dropdown_categories( array(
			'show_option_all' => $taxonomy->labels->all_items,
			'taxonomy'        => $taxnomy,
			'name'            => $taxnomy,
			'orderby'         => 'name',
			'value_field'     => 'slug',
			'selected'        => $selected,
			'hierarchical'    => true,
		) );
	}

	/**
	 * Add New Start Link column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function add_columns( $columns ) {
		$columns['zoom_meeting_start']     = esc_html__( 'Start Meeting', 'webinar-manager-for-zoom-meetings' );
		$columns['zoom_meeting_startdate'] = esc_html__( 'Start Date', 'webinar-manager-for-zoom-meetings' );
		$columns['zoom_meeting_id']        = esc_html__( 'Meeting ID', 'webinar-manager-for-zoom-meetings' );
		$columns['zoom_end_meeting']       = esc_html__( 'Meeting State', 'webinar-manager-for-zoom-meetings' );
		unset( $columns['author'] );

		return $columns;
	}

	/**
	 * Render HTML
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function render_data( $column, $post_id ) {
		$meeting = get_post_meta( $post_id, '_meeting_zoom_details', true );
		switch ( $column ) {
			case 'zoom_meeting_start' :
				if ( ! empty( $meeting ) && ! empty( $meeting->start_url ) ) {
					echo '<a href="' . esc_url( $meeting->start_url ) . '" target="_blank">Start</a>';
				} else {
					esc_html_e( 'Meeting not created yet.', 'webinar-manager-for-zoom-meetings' );
				}
				break;
			case 'zoom_meeting_startdate' :
				if ( ! empty( $meeting ) && ! empty( $meeting->code ) && ! empty( $meeting->message ) ) {
					echo $meeting->message;
				} else if ( ! empty( $meeting ) && ! empty( $meeting->type ) && $meeting->type === 2 && ! empty( $meeting->start_time ) ) {
					echo rzwm_dateConverter( $meeting->start_time, $meeting->timezone, 'F j, Y, g:i a' );
				} else if ( ! empty( $meeting ) && ( $meeting->type === 3 || $meeting->type === 8 ) ) {
					esc_html_e( 'Recurring Meeting', 'webinar-manager-for-zoom-meetings' );
				} else {
					esc_html_e( 'Meeting not created yet.', 'webinar-manager-for-zoom-meetings' );
				}
				break;
			case 'zoom_meeting_id' :
				if ( ! empty( $meeting ) && ! empty( $meeting->code ) && ! empty( $meeting->message ) ) {
					echo $meeting->message;
				} else if ( ! empty( $meeting ) && ! empty( $meeting->id ) ) {
					echo $meeting->id;
				} else {
					esc_html_e( 'Meeting not created yet.', 'webinar-manager-for-zoom-meetings' );
				}
				break;
			case 'zoom_end_meeting' :
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
				break;
		}
	}

	/**
	 * Register Post Type
	 *
	 */
	public function register() {
		$this->register_post_type();
		$this->register_taxonomy();
	}

	/**
	 * Register Taxonomy
	 */
	public function register_taxonomy() {
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'          => _x( 'Category', 'Category', 'webinar-manager-for-zoom-meetings' ),
			'singular_name' => _x( 'Category', 'Category', 'webinar-manager-for-zoom-meetings' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
		);

		register_taxonomy( 'zoom-meeting', array( $this->post_type ), $args );
	}

	/**
	 * Register Post Type
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Zoom Meetings', 'Zoom Meetings', 'webinar-manager-for-zoom-meetings' ),
			'singular_name'      => _x( 'Zoom Meeting', 'Zoom Meeting', 'webinar-manager-for-zoom-meetings' ),
			'menu_name'          => _x( 'Webinar Manager for Zoom Meetings', 'Webinar Manager for Zoom Meetings', 'webinar-manager-for-zoom-meetings' ),
			'name_admin_bar'     => _x( 'Zoom Meeting', 'Zoom Meeting', 'webinar-manager-for-zoom-meetings' ),
			'add_new'            => esc_html__( 'Add New', 'webinar-manager-for-zoom-meetings' ),
			'add_new_item'       => esc_html__( 'Add New meeting', 'webinar-manager-for-zoom-meetings' ),
			'new_item'           => esc_html__( 'New meeting', 'webinar-manager-for-zoom-meetings' ),
			'edit_item'          => esc_html__( 'Edit meeting', 'webinar-manager-for-zoom-meetings' ),
			'view_item'          => esc_html__( 'View meetings', 'webinar-manager-for-zoom-meetings' ),
			'all_items'          => esc_html__( 'All meetings', 'webinar-manager-for-zoom-meetings' ),
			'search_items'       => esc_html__( 'Search meetings', 'webinar-manager-for-zoom-meetings' ),
			'parent_item_colon'  => esc_html__( 'Parent meetings:', 'webinar-manager-for-zoom-meetings' ),
			'not_found'          => esc_html__( 'No meetings found.', 'webinar-manager-for-zoom-meetings' ),
			'not_found_in_trash' => esc_html__( 'No meetings found in Trash.', 'webinar-manager-for-zoom-meetings' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'menu_icon'          => 'dashicons-video-alt',
			'capability_type'    => apply_filters( 'rzwm_cpt_capabilities_type', 'post' ),
			'capabilities'       => apply_filters( 'rzwm_cpt_capabilities', array() ),
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => apply_filters( 'rzwm_cpt_menu_position', 5 ),
			'map_meta_cap'       => apply_filters( 'rzwm_cpt_meta_cap', null ),
			'supports'           => array(
				'title',
				'editor',
				'author',
				'thumbnail',
			),
			'rewrite'            => array( 'slug' => apply_filters( 'rzwm_cpt_slug', $this->post_type ) ),
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Adds the meta box.
	 */
	public function add_metabox() {
		add_meta_box( 'zoom-meeting-meta', esc_html__( 'Zoom Details', 'webinar-manager-for-zoom-meetings' ), array(
			$this,
			'render_metabox'
		), $this->post_type, 'normal' );
		add_meta_box( 'zoom-meeting-meta-side', esc_html__( 'Meeting Details', 'webinar-manager-for-zoom-meetings' ), array(
			$this,
			'rendor_sidebox'
		), $this->post_type, 'side', 'high' );
		add_meta_box( 'zoom-meeting-debug-meta', esc_html__( 'Debug Log', 'webinar-manager-for-zoom-meetings' ), array(
			$this,
			'debug_metabox'
		), $this->post_type, 'normal' );
		if ( is_plugin_inactive( 'rzwm-woo-addon/rzwm-woo-addon.php' ) && is_plugin_inactive( 'rzwm-woocommerce-addon/rzwm-woocommerce-addon.php' ) ) {
			add_meta_box( 'zoom-meeting-woo-integration-info', esc_html__( 'WooCommerce Integration?', 'webinar-manager-for-zoom-meetings' ), array(
				$this,
				'render_woo_sidebox'
			), $this->post_type, 'side', 'normal' );
		}
	}

	public function render_woo_sidebox() {
		echo "<p>Enable this meeting to be purchased by your users ? </p><p>Check out <a href='" . admin_url( 'edit.php?post_type=zoom-meetings&page=zoom-webinar-addons' ) . "'>WooCommerce addon</a> for this plugin.</p>";
	}

	/**
	 * Renders the meta box.
	 */
	public function render_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( '_rzwm_meeting_save', '_rzwm_nonce' );

		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-select2-js' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-timepicker-js' );

		//Check if any transient by name is available
		$users = rzwmzoom_manager_get_user_transients();

		$meeting_fields = get_post_meta( $post->ID, '_meeting_fields', true );

		do_action( 'rzwm_before_fields_admin', $post );

		//Get Template
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/post-type/tpl-meeting-fields.php';
	}

	function rendor_sidebox( $post ) {
		$meeting_fields = get_post_meta( $post->ID, '_meeting_fields', true );
		// Add nonce for security and authentication.
		wp_nonce_field( '_rzwm_meeting_save', '_rzwm_nonce' );
		$meeting_details = get_post_meta( $post->ID, '_meeting_zoom_details', true );
		?>
        <div class="zoom-metabox-wrapper">
			<?php
			if ( ! empty( $meeting_details ) ) {
				if ( ! empty( $meeting_details->code ) && ! empty( $meeting_details->message ) ) {
					?>
                    <p><strong><?php esc_html_e( 'Meeting has not been created for this post yet. Publish your meeting or hit update to create a new one for this post!', 'webinar-manager-for-zoom-meetings' ); ?></strong></p>
					<?php
					echo '<p style="color:red;">Zoom Error:' . $meeting_details->message . '</p>';
				} else {
					$zoom_host_url = 'https://zoom.us' . '/wc/' . $meeting_details->id . '/start';
					$zoom_host_url = apply_filters( 'video_conferencing_zoom_join_url_host', $zoom_host_url );

					$join_url = ! empty( $meeting_details->encrypted_password ) ? rzwm_get_pwd_embedded_join_link( $meeting_details->join_url, $meeting_details->encrypted_password ) : $meeting_details->join_url;
					?>
                    <div class="zoom-metabox-content">
                        <p><a target="_blank" href="<?php echo esc_url( $meeting_details->start_url ); ?>" title="Start URL">Start Meeting</a></p>
                        <p><a target="_blank" href="<?php echo esc_url( $join_url ); ?>" title="Start URL">Join Meeting</a></p>
                        <p><a target="_blank" href="<?php echo esc_url( $zoom_host_url ); ?>" title="Start URL">Start via Browser</a></p>
                        <p><strong>Meeting ID:</strong> <?php echo $meeting_details->id; ?></p>
						<?php do_action( 'rzwm_meeting_details_admin', $meeting_details ); ?>
                    </div>
                    <hr>
					<?php
				}
			} else { ?>
                <p><strong><?php esc_html_e( 'Meeting has not been created for this post yet. Publish your meeting or hit update to create a new one for this post!', 'webinar-manager-for-zoom-meetings' ); ?></strong></p>
			<?php } ?>
            <div class="zoom-metabox-content">
                <p><?php esc_html_e( 'Requires Login?', 'webinar-manager-for-zoom-meetings' ); ?>
                    <input type="checkbox" name="option_logged_in" value="1" <?php ! empty( $meeting_fields['site_option_logged_in'] ) ? checked( '1', $meeting_fields['site_option_logged_in'] ) : false; ?> class="regular-text">
                </p>
                <p class="description"><?php esc_html_e( 'Only logged in users of this site will be able to join this meeting.', 'webinar-manager-for-zoom-meetings' ); ?></p>
                <p><?php esc_html_e( 'Hide Join via browser link ?', 'webinar-manager-for-zoom-meetings' ); ?>
                    <input type="checkbox" name="option_browser_join" value="1" <?php ! empty( $meeting_fields['site_option_browser_join'] ) ? checked( '1', $meeting_fields['site_option_browser_join'] ) : false; ?> class="regular-text">
                </p>
                <p class="description"><?php esc_html_e( 'This will disable join via browser link in frontend page.', 'webinar-manager-for-zoom-meetings' ); ?></p>
            </div>
        </div>
		<?php
	}

	/**
	 * Debug FUNCTION
	 *
	 * @param $post
	 */
	public function debug_metabox( $post ) {
		$meeting_fields  = get_post_meta( $post->ID, '_meeting_fields', true );
		$meeting_details = get_post_meta( $post->ID, '_meeting_zoom_details', true );
		?>
        <div class="zoom-metabox-wrapper">
            <div class="zoom-metabox-content">
                <p><?php esc_html_e( 'Enable Debug?', 'webinar-manager-for-zoom-meetings' ); ?>
                    <input type="checkbox" name="option_enable_debug_logs" value="1" <?php ! empty( $meeting_fields['site_option_enable_debug_log'] ) ? checked( '1', $meeting_fields['site_option_enable_debug_log'] ) : false; ?> class="regular-text">
                </p>
            </div>
        </div>
        <style>
            pre {
                position: relative;
                width: 100%;
                padding: 0;
                margin: 0;
                overflow: auto;
                overflow-y: hidden;
                font-size: 12px;
                line-height: 20px;
                background: #efefef;
                border: 1px solid #777;
            }
        </style>
		<?php
		if ( ! empty( $meeting_fields['site_option_enable_debug_log'] ) ) {
			if ( ! empty( $meeting_details->id ) ) {
				dump( json_decode( zoom_conference()->getMeetingInfo( $meeting_details->id ) ) );
			} else {
				dump( $meeting_details );
			}
		}
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @param int $post_id Post ID.
	 * @param WP_Post $post Post object.
	 */
	public function save_metabox( $post_id, $post ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['_rzwm_nonce'] ) ? $_POST['_rzwm_nonce'] : '';
		$nonce_action = '_rzwm_meeting_save';

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$pwd                = sanitize_text_field( filter_input( INPUT_POST, 'password' ) );
		$pwd                = ! empty( $pwd ) ? $pwd : $post_id;
		$create_meeting_arr = array(
			'userId'                    => sanitize_text_field( filter_input( INPUT_POST, 'userId' ) ),
			'start_date'                => sanitize_text_field( filter_input( INPUT_POST, 'start_date' ) ),
			'timezone'                  => sanitize_text_field( filter_input( INPUT_POST, 'timezone' ) ),
			'duration'                  => sanitize_text_field( filter_input( INPUT_POST, 'duration' ) ),
			'password'                  => $pwd,
			'join_before_host'          => filter_input( INPUT_POST, 'join_before_host' ),
			'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
			'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
			'option_mute_participants'  => filter_input( INPUT_POST, 'option_mute_participants' ),
			'option_auto_recording'     => filter_input( INPUT_POST, 'option_auto_recording' ),
			'alternative_host_ids'      => filter_input( INPUT_POST, 'alternative_host_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY )
		);

		$create_meeting_arr['site_option_logged_in']        = filter_input( INPUT_POST, 'option_logged_in' );
		$create_meeting_arr['site_option_browser_join']     = filter_input( INPUT_POST, 'option_browser_join' );
		$create_meeting_arr['site_option_enable_debug_log'] = filter_input( INPUT_POST, 'option_enable_debug_logs' );

		//Call before meeting is created.
		do_action( 'rzwm_admin_before_zoom_meeting_is_created', $create_meeting_arr );

		//Update Post Meta Values
		update_post_meta( $post_id, '_meeting_fields', $create_meeting_arr );
		update_post_meta( $post_id, '_meeting_field_start_date', $create_meeting_arr['start_date'] );

		try {
			//converted saved time from the timezone provided for meeting to UTC timezone so meetings can be better queried
			$savedDateTime     = new DateTime( $create_meeting_arr['start_date'], new DateTimeZone( $create_meeting_arr['timezone'] ) );
			$startDateTimezone = $savedDateTime->setTimezone( new DateTimeZone( 'UTC' ) );
			update_post_meta( $post_id, '_meeting_field_start_date_utc', $startDateTimezone->format( 'Y-m-d H:i:s' ) );
		} catch ( Exception $e ) {
			update_post_meta( $post_id, '_meeting_field_start_date_utc', $e->getMessage() );
		}

		//Create Zoom Meeting Now
		$meeting_id = get_post_meta( $post_id, '_meeting_zoom_meeting_id', true );
		if ( empty( $meeting_id ) ) {
			//Create new Zoom Meeting
			$this->create_zoom_meeting( $post );
		} else {
			//Update Zoom Meeting
			$this->update_zoom_meeting( $post, $meeting_id );
		}

		//Call this action after the Zoom Meeting completion created.
		do_action( 'rzwm_admin_after_zoom_meeting_is_created', $post_id, $post );
	}

	/**
	 * Create real time zoom meetings
	 *
	 * @param $post
	 *
	 */
	private function create_zoom_meeting( $post ) {
		$pwd       = sanitize_text_field( filter_input( INPUT_POST, 'password' ) );
		$pwd       = ! empty( $pwd ) ? $pwd : $post->ID;
		$mtg_param = array(
			'userId'                    => sanitize_text_field( filter_input( INPUT_POST, 'userId' ) ),
			'meetingTopic'              => $post->post_title,
			'start_date'                => sanitize_text_field( filter_input( INPUT_POST, 'start_date' ) ),
			'timezone'                  => sanitize_text_field( filter_input( INPUT_POST, 'timezone' ) ),
			'duration'                  => sanitize_text_field( filter_input( INPUT_POST, 'duration' ) ),
			'password'                  => $pwd,
			'join_before_host'          => filter_input( INPUT_POST, 'join_before_host' ),
			'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
			'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
			'option_mute_participants'  => filter_input( INPUT_POST, 'option_mute_participants' ),
			'option_auto_recording'     => filter_input( INPUT_POST, 'option_auto_recording' ),
			'alternative_host_ids'      => filter_input( INPUT_POST, 'alternative_host_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY )
		);

		$meeting_created = json_decode( zoom_conference()->createAMeeting( $mtg_param ) );
		if ( empty( $meeting_created->error ) ) {
			update_post_meta( $post->ID, '_meeting_zoom_details', $meeting_created );
			update_post_meta( $post->ID, '_meeting_zoom_join_url', $meeting_created->join_url );
			update_post_meta( $post->ID, '_meeting_zoom_start_url', $meeting_created->start_url );
			update_post_meta( $post->ID, '_meeting_zoom_meeting_id', $meeting_created->id );
		}
	}

	/**
	 * Update real time zoom meetings
	 *
	 * @param $post
	 * @param $meeting_id
	 *
	 */
	private function update_zoom_meeting( $post, $meeting_id ) {
		$pwd       = sanitize_text_field( filter_input( INPUT_POST, 'password' ) );
		$pwd       = ! empty( $pwd ) ? $pwd : $post->ID;
		$mtg_param = array(
			'meeting_id'                => $meeting_id,
			'topic'                     => $post->post_title,
			'start_date'                => filter_input( INPUT_POST, 'start_date' ),
			'timezone'                  => filter_input( INPUT_POST, 'timezone' ),
			'duration'                  => filter_input( INPUT_POST, 'duration' ),
			'password'                  => $pwd,
			'option_jbh'                => filter_input( INPUT_POST, 'join_before_host' ),
			'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
			'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
			'option_mute_participants'  => filter_input( INPUT_POST, 'option_mute_participants' ),
			'option_auto_recording'     => filter_input( INPUT_POST, 'option_auto_recording' ),
			'alternative_host_ids'      => filter_input( INPUT_POST, 'alternative_host_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY )
		);

		$meeting_updated = json_decode( zoom_conference()->updateMeetingInfo( $mtg_param ) );
		if ( empty( $meeting_updated->error ) ) {
			$meeting_info = json_decode( zoom_conference()->getMeetingInfo( $meeting_id ) );
			if ( ! empty( $meeting_info ) ) {
				update_post_meta( $post->ID, '_meeting_zoom_details', $meeting_info );
				update_post_meta( $post->ID, '_meeting_zoom_join_url', $meeting_info->join_url );
				update_post_meta( $post->ID, '_meeting_zoom_start_url', $meeting_info->start_url );
				update_post_meta( $post->ID, '_meeting_zoom_meeting_id', $meeting_info->id );
			}
		}
	}

	/**
	 * Single Page Template
	 *
	 * @param $template
	 *
	 * @return bool|string
	 */
	public function single( $template ) {
		global $post;

		if ( ! empty( $post ) && $post->post_type == $this->post_type ) {
			unset( $GLOBALS['zoom'] );

			$show_zoom_author_name = get_option( 'zoom_show_author' );

			$GLOBALS['zoom'] = get_post_meta( $post->ID, '_meeting_fields', true ); //For Backwards Compatibility ( Will be removed someday )
			$meeting_details = get_post_meta( $post->ID, '_meeting_zoom_details', true );

			if ( ! empty( $show_zoom_author_name ) ) {
				$zoom_user               = json_decode( zoom_conference()->getUserInfo( $meeting_details->host_id ) );
				$GLOBALS['zoom']['user'] = ! empty( $zoom_user ) ? $zoom_user : false;
			}

			if ( ! empty( $meeting_details ) ) {
				$GLOBALS['zoom']['api'] = get_post_meta( $post->ID, '_meeting_zoom_details', true );
			}

			$terms = get_the_terms( $post->ID, 'zoom-meeting' );
			if ( ! empty( $terms ) ) {
				$set_terms = array();
				foreach ( $terms as $term ) {
					$set_terms[] = $term->name;
				}
				$GLOBALS['zoom']['terms'] = $set_terms;
			}

			if ( isset( $_GET['type'] ) && $_GET['type'] === "meeting" && isset( $_GET['join'] ) ) {
				wp_enqueue_script( 'webinar-manager-for-zoom-meetings-react', RZWM_PLUGIN_URL . 'assets/vendor/zoom/react.production.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
				wp_enqueue_script( 'webinar-manager-for-zoom-meetings-react-dom', RZWM_PLUGIN_URL . 'assets/vendor/zoom/react-dom.production.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
				wp_enqueue_script( 'webinar-manager-for-zoom-meetings-redux', RZWM_PLUGIN_URL . 'assets/vendor/zoom/redux.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
				wp_enqueue_script( 'webinar-manager-for-zoom-meetings-thunk', RZWM_PLUGIN_URL . 'assets/vendor/zoom/redux-thunk.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
				wp_enqueue_script( 'webinar-manager-for-zoom-meetings-lodash', RZWM_PLUGIN_URL . 'assets/vendor/zoom/lodash.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
				wp_enqueue_script( 'zoom-meeting-source', RZWM_PLUGIN_URL . 'assets/vendor/zoom/zoomus-websdk.umd.min.js', array(
					'jquery',
					'webinar-manager-for-zoom-meetings-react',
					'webinar-manager-for-zoom-meetings-react-dom',
					'webinar-manager-for-zoom-meetings-redux',
					'webinar-manager-for-zoom-meetings-thunk',
					'webinar-manager-for-zoom-meetings-lodash'
				), RZWM_PLUGIN_VERSION, true );
				wp_enqueue_script( 'webinar-manager-for-zoom-meetings-browser', RZWM_PLUGIN_URL . 'assets/public/js/zoom-meeting.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
				wp_localize_script( 'webinar-manager-for-zoom-meetings-browser', 'rzwm_ajx', array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'rzwm_security'  => wp_create_nonce( "_nonce_rzwm_security" ),
					'redirect_page' => apply_filters( 'rzwm_api_redirect_join_browser', esc_url( get_permalink( $post->ID ) ) ),
					'meeting_id'    => sanitize_text_field( absint( rzwm_encrypt_decrypt( 'decrypt', $_GET['join'] ) ) ),
					'meeting_pwd'   => ! empty( $_GET['pak'] ) ? sanitize_text_field( rzwm_encrypt_decrypt( 'decrypt', $_GET['pak'] ) ) : false
				) );

				$template = rzwm_get_template( 'join-web-browser.php' );
			} else {
				//Render View
				$template = rzwm_get_template( 'single-meeting.php' );
			}
		}

		return $template;
	}

	/**
	 * Archive page template
	 *
	 * @param $template
	 *
	 * @return bool|string
	 * @return bool|string|void
	 */
	public function archive( $template ) {
		if ( ! is_post_type_archive( $this->post_type ) ) {
			return $template;
		}

		if ( isset( $_GET['type'] ) && $_GET['type'] === "meeting" && isset( $_GET['join'] ) ) {
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-react', RZWM_PLUGIN_URL . 'assets/vendor/zoom/react.production.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-react-dom', RZWM_PLUGIN_URL . 'assets/vendor/zoom/react-dom.production.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-redux', RZWM_PLUGIN_URL . 'assets/vendor/zoom/redux.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-thunk', RZWM_PLUGIN_URL . 'assets/vendor/zoom/redux-thunk.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-lodash', RZWM_PLUGIN_URL . 'assets/vendor/zoom/lodash.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
			wp_enqueue_script( 'zoom-meeting-source', RZWM_PLUGIN_URL . 'assets/vendor/zoom/zoomus-websdk.umd.min.js', array(
				'jquery',
				'webinar-manager-for-zoom-meetings-react',
				'webinar-manager-for-zoom-meetings-react-dom',
				'webinar-manager-for-zoom-meetings-redux',
				'webinar-manager-for-zoom-meetings-thunk',
				'webinar-manager-for-zoom-meetings-lodash'
			), RZWM_PLUGIN_VERSION, true );
			wp_enqueue_script( 'webinar-manager-for-zoom-meetings-browser', RZWM_PLUGIN_URL . 'assets/public/js/zoom-meeting.min.js', array( 'jquery' ), RZWM_PLUGIN_VERSION, true );
			wp_localize_script( 'webinar-manager-for-zoom-meetings-browser', 'rzwm_ajx', array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'rzwm_security'  => wp_create_nonce( "_nonce_rzwm_security" ),
				'redirect_page' => apply_filters( 'rzwm_api_redirect_join_browser', esc_url( home_url( '/' ) ) ),
				'meeting_id'    => absint( rzwm_encrypt_decrypt( 'decrypt', $_GET['join'] ) ),
				'meeting_pwd'   => ! empty( $_GET['pak'] ) ? sanitize_text_field( rzwm_encrypt_decrypt( 'decrypt', $_GET['pak'] ) ) : false
			) );

			$template = rzwm_get_template( 'join-web-browser.php' );
		} else {
			//Render View
			$template = rzwm_get_template( 'archive-meetings.php' );
		}

		return $template;
	}

	/**
	 * Delete the meeting
	 *
	 * @param $post_id
	 *
	 */
	public function delete( $post_id ) {
		if ( get_post_type( $post_id ) === $this->post_type ) {
			$meeting_id = get_post_meta( $post_id, '_meeting_zoom_meeting_id', true );
			if ( ! empty( $meeting_id ) ) {
				zoom_conference()->deleteAMeeting( $meeting_id );
			}
		}
	}

	public function admin_notices() {
		$screen = get_current_screen();

		//If not on the screen with ID 'edit-post' abort.
		if ( $screen->id === 'edit-zoom-meetings' || $screen->id === $this->post_type ) {
		} else {
			return;
		}
	}
}

new RZoomWebinarManagertLite_Admin_PostType();