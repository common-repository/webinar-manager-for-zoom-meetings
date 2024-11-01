<?php
namespace Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Base_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class RZoomWebinarManagertLite_Elementor_Meetings extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'rzwm_zoom_meeting';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Zoom Meeting', 'webinar-manager-for-zoom-meetings' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'fas fa-video';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'rzwm-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Select a Zoom Meeting', 'webinar-manager-for-zoom-meetings' ),
			]
		);

		$this->add_control(
			'meeting_id',
			[
				'name'        => 'meeting_id',
				'label'       => esc_html__( 'Meeting', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $this->getMeetings(),
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Get Meetings
	 *
	 * @return array
	 */
	private function getMeetings() {
		$args       = array(
			'numberposts' => - 1,
			'post_type'   => 'zoom-meetings'
		);
		$result     = array();
		$meetings   = get_posts( $args );
		$result[''] = esc_html__( 'Select a Meeting', 'webinar-manager-for-zoom-meetings' );
		if ( ! empty( $meetings ) ) {
			foreach ( $meetings as $meeting ) {
				$meeting_details            = get_post_meta( $meeting->ID, '_meeting_zoom_meeting_id', true );
				$result[ $meeting_details ] = $meeting->post_title;
			}
		}

		wp_reset_postdata();

		return $result;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['meeting_id'] ) ) {
			echo do_shortcode( '[rzwm_zoom_api_link meeting_id="' . $settings['meeting_id'] . '"]' );
		}
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {

	}
}