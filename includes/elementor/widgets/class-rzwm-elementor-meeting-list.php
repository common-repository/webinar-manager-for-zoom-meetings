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
 * @author Rajthemes
 */
class RZoomWebinarManagertLite_ElementorMeetingsList extends Widget_Base {

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
		return 'rzwm_meetings_list';
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
		return esc_html__( 'Zoom Meetings List', 'webinar-manager-for-zoom-meetings' );
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
				'label' => esc_html__( 'Content', 'webinar-manager-for-zoom-meetings' ),
			]
		);

		$this->add_control(
			'category',
			[
				'name'        => 'category',
				'label'       => esc_html__( 'Meetings Category', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_taxnomies(),
				'default'     => ''
			]
		);

		$this->add_control(
			'order',
			[
				'name'        => 'order',
				'label'       => esc_html__( 'Meetings Order By', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => [
					'ASC'  => 'Ascending',
					'DESC' => 'Descending'
				],
				'default'     => 'DESC'
			]
		);

		$this->add_control(
			'type',
			[
				'name'        => 'type',
				'label'       => esc_html__( 'Meetings Type', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => [
					''         => 'Show All',
					'upcoming' => 'Upcoming',
					'past'     => 'Past'
				],
				'default'     => ''
			]
		);

		$this->add_control(
			'count',
			[
				'name'        => 'count',
				'label'       => esc_html__( 'Count of meetings', 'webinar-manager-for-zoom-meetings' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => 3
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Get Taxonomies for Zoom meeting
	 *
	 * @return array
	 */
	private function get_taxnomies() {
		$args       = array(
			'taxonomy'   => 'zoom-meeting',
			'hide_empty' => false
		);
		$terms      = get_terms( $args );
		$result     = [];
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$result[ $term->term_id ] = $term->name;
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

		$count    = ! empty( $settings['count'] ) ? $settings['count'] : 5;
		$category = ! empty( $settings['category'] ) ? $settings['category'] : '';
		$type     = ! empty( $settings['type'] ) ? $settings['type'] : '';
		$order    = ! empty( $settings['order'] ) ? $settings['order'] : 'DESC';

		echo do_shortcode( '[rzwm_zoom_list_meetings per_page="' . esc_attr( $count ) . '" category="' . esc_attr( $category ) . '" order="' . esc_attr( $order ) . '" type="' . esc_attr( $type ) . '"]' );
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