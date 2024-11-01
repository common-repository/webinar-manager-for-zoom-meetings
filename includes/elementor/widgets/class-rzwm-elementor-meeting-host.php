<?php
namespace Rajthemes\WebinarManagerForZoomMeetings\Elementor\Widgets;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 * @author Rajthemes
 */
class RZoomWebinarManagertLite_ElementorMeetingsHost extends Widget_Base {

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
		return 'rzwm_meetings_by_host';
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
		return esc_html__( 'Zoom Meetings via Host', 'webinar-manager-for-zoom-meetings' );
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
				'label' => esc_html__( 'Show Meeting by Zoom User', 'webinar-manager-for-zoom-meetings' ),
			]
		);

		$this->add_control(
			'host_id',
			[
				'name'        => 'host_id',
				'label'       => esc_html__( 'Select User', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => false,
				'options'     => $this->get_hosts(),
				'default'     => ''
			]
		);

		$this->add_control(
			'type',
			[
				'name'        => 'type',
				'label'       => esc_html__( 'Type', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'multiple'    => false,
				'options'     => [
					1 => 'Meeting',
					2 => 'Webinar'
				],
				'default'     => 1
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Get Taxonomies for Zoom meeting
	 *
	 * @return array
	 */
	private function get_hosts() {
		$users  = rzwmzoom_manager_get_user_transients();
		$result = array();
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$result[ $user->id ] = $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')';
			}
		}

		return $result;
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

		$host_id = ! empty( $settings['host_id'] ) ? $settings['host_id'] : false;
		$type    = ! empty( $settings['type'] ) ? $settings['type'] : 1;
		if ( ! empty( $host_id ) ) {
			if ( $type === 1 ) {
				echo do_shortcode( '[rzwm_zoom_list_host_meetings host=' . esc_attr( $host_id ) . ']' );
			} else {
				echo do_shortcode( '[rzwm_zoom_list_host_webinars host=' . esc_attr( $host_id ) . ']' );
			}
		} else {
			_e( 'No user selected.', 'webinar-manager-for-zoom-meetings' );
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