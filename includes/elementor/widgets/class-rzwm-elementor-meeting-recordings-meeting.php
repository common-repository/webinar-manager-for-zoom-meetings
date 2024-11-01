<?php
namespace Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets;

use Elementor\Widget_Base;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 * @author Rajthemes
 */
class RZoomWebinarManagertLite_Elementor_RecordingsByMeetingID extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'rzwm_meetings_recordings_by_meetingid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Recordings by Meeting', 'webinar-manager-for-zoom-meetings' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @author Rajthemes
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
	 * @author Rajthemes
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
				'label' => esc_html__( 'Recording by Meeting', 'webinar-manager-for-zoom-meetings' ),
			]
		);

		$this->add_control(
			'meeting_id',
			[
				'label'       => esc_html__( 'Meeting ID', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '1234567890',
				'title'       => 'Your meeting ID'
			]
		);

		$this->add_control(
			'downloadable',
			[
				'name'        => 'downloadable',
				'label'       => esc_html__( 'Show Downloadable Link', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'multiple'    => false,
				'options'     => [
					'yes' => 'Yes',
					'no'  => 'No'
				],
				'default'     => 'no'
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$meeting_id   = ! empty( $settings['meeting_id'] ) ? $settings['meeting_id'] : false;
		$downloadable = ! empty( $settings['downloadable'] ) ? $settings['downloadable'] : 'no';
		if ( ! empty( $meeting_id ) ) {
			echo do_shortcode( '[rzwm_zoom_recordings_by_meeting meeting_id=' . esc_attr( $meeting_id ) . ' downloadable=' . esc_attr( $downloadable ) . ']' );
		} else {
			_e( 'No meeting ID is defined.', 'webinar-manager-for-zoom-meetings' );
		}
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @author Rajthemes
	 *
	 * @access protected
	 */
	protected function _content_template() {

	}
}