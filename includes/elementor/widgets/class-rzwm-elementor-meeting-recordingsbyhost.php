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
class RZoomWebinarManagertLite_Elementor_RecordingsByHost extends Widget_Base {

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
		return 'rzwm_recordings_by_host';
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
		return esc_html__( 'Zoom Recordings by Host', 'webinar-manager-for-zoom-meetings' );
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
				'label' => esc_html__( 'Show Recordings via Host ID', 'webinar-manager-for-zoom-meetings' ),
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

		$host_id      = ! empty( $settings['host_id'] ) ? $settings['host_id'] : false;
		$downloadable = ! empty( $settings['downloadable'] ) ? $settings['downloadable'] : 'no';
		if ( ! empty( $host_id ) ) {
			echo do_shortcode( '[rzwm_zoom_recordings host_id=' . esc_attr( $host_id ) . ' downloadable=' . esc_attr( $downloadable ) . ']' );
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