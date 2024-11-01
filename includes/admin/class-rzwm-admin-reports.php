<?php

/**
 * Class Reports
 *
 * @author  Rajthemes
 * @since   1.0.0
 */
class RZoomWebinarManagertLite_Reports {

	private static $instance;

	public function __construct() {
	}

	static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Zoom Rerports View
	 *
	 * @since   1.0.0
	 */
	public static function zoom_reports() {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'webinar-manager-for-zoom-meetings-js' );

		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'zoom_daily_report';

		//Get Template
		require_once RZWM_PLUGIN_DIR_PATH . 'includes/views/tpl-reports.php';
	}

	/**
	 * Generate daily report
	 *
	 * @return array|bool|mixed|null|object|string
	 */
	public function get_daily_report_html() {
		$return_result = false;
		$months        = array(
			1  => 'January',
			2  => 'February',
			3  => 'March',
			4  => 'April',
			5  => 'May',
			6  => 'June',
			7  => 'July',
			8  => 'August',
			9  => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December'
		);

		if ( isset( $_POST['zoom_check_month_year'] ) ) {
			$zoom_monthyear  = array_map( 'sanitize_text_field', $_POST['zoom_monthyear'] );
			if ( $zoom_monthyear == null || $zoom_monthyear == "" ) {
				$return_result = esc_html__( "Date field cannot be Empty !!", "webinar-manager-for-zoom-meetings" );
			} else {
				$exploded_data = explode( ' ', $zoom_monthyear );
				foreach ( $months as $key => $month ) {
					if ( $exploded_data[0] == $month ) {
						$month_int = $key;
					}
				}
				$year          = $exploded_data[1];
				$result        = zoom_conference()->getDailyReport( $month_int, $year );
				$return_result = json_decode( $result );
			}
		}

		return $return_result;
	}

	/**
	 * Generate Account Report
	 *
	 * @return array|mixed|null|object|string
	 */
	public function get_account_report_html() {
		$return_result = false;
		if ( isset( $_POST['zoom_account_from'] ) && isset( $_POST['zoom_account_to'] ) ) {
			$zoom_account_from = sanitize_text_field( $_POST['zoom_account_from'] );
			$zoom_account_to   = sanitize_text_field( $_POST['zoom_account_to'] );
			if ( $zoom_account_from == null || $zoom_account_to == null ) {
				$return_result = esc_html__( "The fields cannot be Empty !!", "webinar-manager-for-zoom-meetings" );
			} else {
				$result        = zoom_conference()->getAccountReport( $zoom_account_from, $zoom_account_to );
				$return_result = json_decode( $result );
			}
		}

		return $return_result;
	}
}

function rzwm_reports() {
	return RZoomWebinarManagertLite_Reports::getInstance();
}

rzwm_reports();