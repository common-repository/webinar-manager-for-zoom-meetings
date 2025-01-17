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
 * @author Rajthemes
 */
class RZoomWebinarManagertLite_Elementor_Embed extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @author Rajthemes
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'rzwm_meetings_embed';
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
		return esc_html__( 'Embed Zoom Meeting', 'webinar-manager-for-zoom-meetings' );
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
				'label' => esc_html__( 'Embed Meeting', 'webinar-manager-for-zoom-meetings' ),
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
			'login_required',
			[
				'name'        => 'login_required',
				'label'       => esc_html__( 'Requires Login?', 'webinar-manager-for-zoom-meetings' ),
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

		$this->add_control(
			'help_text',
			[
				'name'        => 'help_text',
				'label'       => esc_html__( 'Show Help Text?', 'webinar-manager-for-zoom-meetings' ),
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

		$this->add_control(
			'title_text',
			[
				'label'       => esc_html__( 'Title', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Default title', 'webinar-manager-for-zoom-meetings' ),
				'placeholder' => esc_html__( 'Type your title here', 'webinar-manager-for-zoom-meetings' ),
			]
		);

		$this->add_control(
			'height',
			[
				'label'       => esc_html__( 'Embed Height', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'description' => esc_html__( 'Put height of the container.', 'webinar-manager-for-zoom-meetings' ),
				'placeholder' => '500',
				'default'     => 500
			]
		);

		$this->add_control(
			'disable_countdown',
			[
				'name'        => 'disable_countdown',
				'label'       => esc_html__( 'Disable Countdown Timer?', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'multiple'    => false,
				'options'     => [
					'yes' => 'Yes',
					'no'  => 'No'
				],
				'default'     => 'yes'
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

		$meeting_id        = ! empty( $settings['meeting_id'] ) ? $settings['meeting_id'] : false;
		$login_required    = ! empty( $settings['login_required'] ) ? $settings['login_required'] : 'no';
		$help_text         = ! empty( $settings['help_text'] ) ? $settings['help_text'] : 'no';
		$title_text        = ! empty( $settings['title_text'] ) ? $settings['title_text'] : false;
		$height            = ! empty( $settings['height'] ) ? $settings['height'] : 500;
		$disable_countdown = ! empty( $settings['disable_countdown'] ) ? $settings['disable_countdown'] : 'yes';
		if ( ! empty( $meeting_id ) ) {
			echo do_shortcode( '[rzwm_zoom_join_via_browser meeting_id="' . esc_attr( $meeting_id ) . '" login_required="' . esc_attr( $login_required ) . '" help="' . esc_attr( $help_text ) . '" title="' . esc_attr( $title_text ) . '" height="' . esc_attr( $height ) . 'px" disable_countdown="' . esc_attr( $disable_countdown ) . '"]' );
		} else {
			_e( 'No Meeting ID is defined.', 'webinar-manager-for-zoom-meetings' );
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