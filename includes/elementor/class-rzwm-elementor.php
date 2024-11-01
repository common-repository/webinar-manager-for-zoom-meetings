<?php
namespace Rajthemes\WebinarManagerForZoomMeetings\Elementor;

use Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets\RZoomWebinarManagertLite_ElementorMeetingsList;
use Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets\RZoomWebinarManagertLite_Elementor_Meetings;
use Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets\RZoomWebinarManagertLite_ElementorMeetingsHost;
use Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets\RZoomWebinarManagertLite_Elementor_Embed;
use Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets\RZoomWebinarManagertLite_Elementor_RecordingsByHost;
use Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets\RZoomWebinarManagertLite_Elementor_RecordingsByMeetingID;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Invoke Elementor Dependency Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 * @author Rajthemes
 */
class RZoomWebinarManagertLite_Elementor {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access private
	 */
	private function add_actions() {
		// Register widget scripts.
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'widget_scripts' ] );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_categories' ] );
	}

	/**
	 * Widget Styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-elementor', RZWM_PLUGIN_URL . 'assets/admin/js/elementor.js', [ 'elementor-editor' ], RZWM_PLUGIN_VERSION, true );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Register Widget Category
	 *
	 * @param $elements_manager
	 */
	public function widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'rzwm-elements',
			[
				'title'  => 'Webinar Manager for Zoom Meetings',
				'icon'   => 'fa fa-plug',
				'active' => true
			]
		);
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access private
	 */
	private function includes() {
		require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/widgets/class-rzwm-elementor-meetings.php';
		require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/widgets/class-rzwm-elementor-meeting-list.php';
		require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/widgets/class-rzwm-elementor-meeting-host.php';
		require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/widgets/class-rzwm-elementor-meeting-embed.php';
		require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/widgets/class-rzwm-elementor-meeting-recordingsbyhost.php';
		require RZWM_PLUGIN_DIR_PATH . 'includes/elementor/widgets/class-rzwm-elementor-meeting-recordings-meeting.php';
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access private
	 */
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RZoomWebinarManagertLite_Elementor_Meetings() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RZoomWebinarManagertLite_ElementorMeetingsList() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RZoomWebinarManagertLite_ElementorMeetingsHost() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RZoomWebinarManagertLite_Elementor_Embed() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RZoomWebinarManagertLite_Elementor_RecordingsByHost() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new RZoomWebinarManagertLite_Elementor_RecordingsByMeetingID() );
	}
}

new RZoomWebinarManagertLite_Elementor();