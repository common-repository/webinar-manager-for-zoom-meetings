<?php

require RZWM_PLUGIN_DIR_PATH . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

/**
 * Class Connecting Zoom APi V2
 *
 * @since   1.0
 * @author  Rajthemes
 */
if ( ! class_exists( 'RZoomWebinarManagertLite_Api' ) ) {

	class RZoomWebinarManagertLite_Api {

		/**
		 * Zoom API KEY
		 *
		 * @var
		 */
		public $zoom_api_key;

		/**
		 * Zoom API Secret
		 *
		 * @var
		 */
		public $zoom_api_secret;

		/**
		 * Hold my instance
		 *
		 * @var
		 */
		protected static $_instance;

		/**
		 * API endpoint base
		 *
		 * @var string
		 */
		private $api_url = 'https://api.zoom.us/v2/';

		/**
		 * Create only one instance so that it may not Repeat
		 *
		 * @since 2.0.0
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * RZoomWebinarManagertLite_Api constructor.
		 *
		 * @param $zoom_api_key
		 * @param $zoom_api_secret
		 */
		public function __construct( $zoom_api_key = '', $zoom_api_secret = '' ) {
			$this->zoom_api_key    = $zoom_api_key;
			$this->zoom_api_secret = $zoom_api_secret;
		}

		/**
		 * Send request to API
		 *
		 * @param $calledFunction
		 * @param $data
		 * @param string $request
		 *
		 * @return array|bool|string|WP_Error
		 */
		protected function sendRequest( $calledFunction, $data, $request = "GET" ) {
			$request_url = $this->api_url . $calledFunction;
			$args        = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->generateJWTKey(),
					'Content-Type'  => 'application/json'
				)
			);

			if ( $request == "GET" ) {
				$args['body'] = ! empty( $data ) ? $data : array();
				$response     = wp_remote_get( $request_url, $args );
			} else if ( $request == "DELETE" ) {
				$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
				$args['method'] = "DELETE";
				$response       = wp_remote_request( $request_url, $args );
			} else if ( $request == "PATCH" ) {
				$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
				$args['method'] = "PATCH";
				$response       = wp_remote_request( $request_url, $args );
			} else {
				$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
				$args['method'] = "POST";
				$response       = wp_remote_post( $request_url, $args );
			}

			$response = wp_remote_retrieve_body( $response );
			/*dump($response);
			die;*/

			if ( ! $response ) {
				return false;
			}

			return $response;
		}

		//function to generate JWT
		private function generateJWTKey() {
			$key    = $this->zoom_api_key;
			$secret = $this->zoom_api_secret;

			$token = array(
				"iss" => $key,
				"exp" => time() + 3600 //60 seconds as suggested
			);

			return JWT::encode( $token, $secret );
		}

		/**
		 * Creates a User
		 *
		 * @param $postedData
		 *
		 * @return array|bool|string
		 */
		public function createAUser( $postedData = array() ) {
			$createAUserArray              = array();
			$createAUserArray['action']    = $postedData['action'];
			$createAUserArray['user_info'] = array(
				'email'      => $postedData['email'],
				'type'       => $postedData['type'],
				'first_name' => $postedData['first_name'],
				'last_name'  => $postedData['last_name']
			);
			$createAUserArray              = apply_filters( 'rzwm_createAUser', $createAUserArray );

			return $this->sendRequest( 'users', $createAUserArray, "POST" );
		}

		/**
		 * User Function to List
		 *
		 * @param $page
		 *
		 * @return array
		 */
		public function listUsers( $page = 1 ) {
			$listUsersArray                = array();
			$listUsersArray['page_size']   = 300;
			$listUsersArray['page_number'] = absint( $page );
			$listUsersArray                = apply_filters( 'rzwm_listUsers', $listUsersArray );

			return $this->sendRequest( 'users', $listUsersArray, "GET" );
		}

		/**
		 * Get A users info by user Id
		 *
		 * @param $user_id
		 *
		 * @return array|bool|string
		 */
		public function getUserInfo( $user_id ) {
			$getUserInfoArray = array();
			$getUserInfoArray = apply_filters( 'rzwm_getUserInfo', $getUserInfoArray );

			return $this->sendRequest( 'users/' . $user_id, $getUserInfoArray );
		}

		/**
		 * Delete a User
		 *
		 * @param $userid
		 *
		 * @return array|bool|string
		 */
		public function deleteAUser( $userid ) {
			$deleteAUserArray       = array();
			$deleteAUserArray['id'] = $userid;

			return $this->sendRequest( 'users/' . $userid, false, "DELETE" );
		}

		/**
		 * Get Meetings
		 *
		 * @param $host_id
		 *
		 * @return array
		 */
		public function listMeetings( $host_id ) {
			$listMeetingsArray              = array();
			$listMeetingsArray['page_size'] = 300;
			$listMeetingsArray              = apply_filters( 'rzwm_listMeetings', $listMeetingsArray );

			return $this->sendRequest( 'users/' . $host_id . '/meetings', $listMeetingsArray, "GET" );
		}

		/**
		 * Create A meeting API
		 *
		 * @param array $data
		 *
		 * @return array|bool|string|void|WP_Error
		 */
		public function createAMeeting( $data = array() ) {
			$post_time  = $data['start_date'];
			$start_time = gmdate( "Y-m-d\TH:i:s", strtotime( $post_time ) );

			$createAMeetingArray = array();

			if ( ! empty( $data['alternative_host_ids'] ) ) {
				if ( count( $data['alternative_host_ids'] ) > 1 ) {
					$alternative_host_ids = implode( ",", $data['alternative_host_ids'] );
				} else {
					$alternative_host_ids = $data['alternative_host_ids'][0];
				}
			}

			$createAMeetingArray['topic']      = $data['meetingTopic'];
			$createAMeetingArray['agenda']     = ! empty( $data['agenda'] ) ? $data['agenda'] : "";
			$createAMeetingArray['type']       = ! empty( $data['type'] ) ? $data['type'] : 2; //Scheduled
			$createAMeetingArray['start_time'] = $start_time;
			$createAMeetingArray['timezone']   = $data['timezone'];
			$createAMeetingArray['password']   = ! empty( $data['password'] ) ? $data['password'] : "";
			$createAMeetingArray['duration']   = ! empty( $data['duration'] ) ? $data['duration'] : 60;
			$createAMeetingArray['settings']   = array(
				'join_before_host'  => ! empty( $data['join_before_host'] ) ? true : false,
				'host_video'        => ! empty( $data['option_host_video'] ) ? true : false,
				'participant_video' => ! empty( $data['option_participants_video'] ) ? true : false,
				'mute_upon_entry'   => ! empty( $data['option_mute_participants'] ) ? true : false,
				'enforce_login'     => ! empty( $data['option_enforce_login'] ) ? true : false,
				'auto_recording'    => ! empty( $data['option_auto_recording'] ) ? $data['option_auto_recording'] : "none",
				'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
			);

			$createAMeetingArray = apply_filters( 'rzwm_createAmeeting', $createAMeetingArray );
			if ( ! empty( $createAMeetingArray ) ) {
				return $this->sendRequest( 'users/' . $data['userId'] . '/meetings', $createAMeetingArray, "POST" );
			} else {
				return;
			}
		}

		/**
		 * Updating Meeting Info
		 *
		 * @param array $update_data
		 *
		 * @return array|bool|string|void|WP_Error
		 */
		public function updateMeetingInfo( $update_data = array() ) {
			$post_time  = $update_data['start_date'];
			$start_time = gmdate( "Y-m-d\TH:i:s", strtotime( $post_time ) );

			$updateMeetingInfoArray = array();

			if ( ! empty( $update_data['alternative_host_ids'] ) ) {
				if ( count( $update_data['alternative_host_ids'] ) > 1 ) {
					$alternative_host_ids = implode( ",", $update_data['alternative_host_ids'] );
				} else {
					$alternative_host_ids = $update_data['alternative_host_ids'][0];
				}
			}

			$updateMeetingInfoArray['topic']      = $update_data['topic'];
			$updateMeetingInfoArray['agenda']     = ! empty( $update_data['agenda'] ) ? $update_data['agenda'] : "";
			$updateMeetingInfoArray['type']       = ! empty( $update_data['type'] ) ? $update_data['type'] : 2; //Scheduled
			$updateMeetingInfoArray['start_time'] = $start_time;
			$updateMeetingInfoArray['timezone']   = $update_data['timezone'];
			$updateMeetingInfoArray['password']   = ! empty( $update_data['password'] ) ? $update_data['password'] : "";
			$updateMeetingInfoArray['duration']   = ! empty( $update_data['duration'] ) ? $update_data['duration'] : 60;
			$updateMeetingInfoArray['settings']   = array(
				'join_before_host'  => ! empty( $update_data['option_jbh'] ) ? true : false,
				'host_video'        => ! empty( $update_data['option_host_video'] ) ? true : false,
				'participant_video' => ! empty( $update_data['option_participants_video'] ) ? true : false,
				'mute_upon_entry'   => ! empty( $update_data['option_mute_participants'] ) ? true : false,
				'enforce_login'     => ! empty( $update_data['option_enforce_login'] ) ? true : false,
				'auto_recording'    => ! empty( $update_data['option_auto_recording'] ) ? $update_data['option_auto_recording'] : "none",
				'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
			);

			$updateMeetingInfoArray = apply_filters( 'rzwm_updateMeetingInfo', $updateMeetingInfoArray );
			if ( ! empty( $updateMeetingInfoArray ) ) {
				return $this->sendRequest( 'meetings/' . $update_data['meeting_id'], $updateMeetingInfoArray, "PATCH" );
			} else {
				return;
			}
		}

		/**
		 * Get a Meeting Info
		 *
		 * @param  [INT] $id
		 * @param  [STRING] $host_id
		 *
		 * @return array
		 */
		public function getMeetingInfo( $id ) {
			$getMeetingInfoArray = array();
			$getMeetingInfoArray = apply_filters( 'rzwm_getMeetingInfo', $getMeetingInfoArray );

			return $this->sendRequest( 'meetings/' . $id, $getMeetingInfoArray, "GET" );
		}

		/**
		 * Delete A Meeting
		 *
		 * @param $meeting_id [int]
		 *
		 * @return array
		 */
		public function deleteAMeeting( $meeting_id ) {
			$deleteAMeetingArray = array();

			return $this->sendRequest( 'meetings/' . $meeting_id, $deleteAMeetingArray, "DELETE" );
		}

		/*Functions for management of reports*/
		/**
		 * Get daily account reports by month
		 *
		 * @param $month
		 * @param $year
		 *
		 * @return bool|mixed
		 */
		public function getDailyReport( $month, $year ) {
			$getDailyReportArray          = array();
			$getDailyReportArray['year']  = $year;
			$getDailyReportArray['month'] = $month;
			$getDailyReportArray          = apply_filters( 'rzwm_getDailyReport', $getDailyReportArray );

			return $this->sendRequest( 'report/daily', $getDailyReportArray, "GET" );
		}

		/**
		 * Get ACcount Reports
		 *
		 * @param $zoom_account_from
		 * @param $zoom_account_to
		 *
		 * @return array
		 */
		public function getAccountReport( $zoom_account_from, $zoom_account_to ) {
			$getAccountReportArray              = array();
			$getAccountReportArray['from']      = $zoom_account_from;
			$getAccountReportArray['to']        = $zoom_account_to;
			$getAccountReportArray['page_size'] = 300;
			$getAccountReportArray              = apply_filters( 'rzwm_getAccountReport', $getAccountReportArray );

			return $this->sendRequest( 'report/users', $getAccountReportArray, "GET" );
		}

		public function registerWebinarParticipants( $webinar_id, $first_name, $last_name, $email ) {
			$postData               = array();
			$postData['first_name'] = $first_name;
			$postData['last_name']  = $last_name;
			$postData['email']      = $email;

			return $this->sendRequest( 'webinars/' . $webinar_id . '/registrants', $postData, "POST" );
		}

		/**
		 * List webinars
		 *
		 * @param $userId
		 *
		 * @return bool|mixed
		 */
		public function listWebinar( $userId ) {
			$postData              = array();
			$postData['page_size'] = 300;

			return $this->sendRequest( 'users/' . $userId . '/webinars', $postData, "GET" );
		}

		/**
		 * Create Webinar
		 *
		 * @param $userID
		 * @param array $data
		 *
		 * @return array|bool|string|void|WP_Error
		 */
		public function createAWebinar( $userID, $data = array() ) {
			$postData = apply_filters( 'rzwm_createAwebinar', $data );

			return $this->sendRequest( 'users/' . $userID . '/webinars', $postData, "POST" );
		}

		/**
		 * Update Webinar
		 *
		 * @param $webinar_id
		 * @param array $data
		 *
		 * @return array|bool|string|void|WP_Error
		 */
		public function updateWebinar( $webinar_id, $data = array() ) {
			$postData = apply_filters( 'rzwm_updateWebinar', $data );

			return $this->sendRequest( 'webinars/' . $webinar_id, $postData, "PATCH" );
		}

		/**
		 * Get Webinar Info
		 *
		 * @param $id
		 *
		 * @return array|bool|string|WP_Error
		 */
		public function getWebinarInfo( $id ) {
			$getMeetingInfoArray = array();

			return $this->sendRequest( 'webinars/' . $id, $getMeetingInfoArray, "GET" );
		}

		/**
		 * List Webinar Participants
		 *
		 * @param $webinarId
		 *
		 * @return bool|mixed
		 */
		public function listWebinarParticipants( $webinarId ) {
			$postData              = array();
			$postData['page_size'] = 300;

			return $this->sendRequest( 'webinars/' . $webinarId . '/registrants', $postData, "GET" );
		}

		/**
		 * Get recording by meeting ID
		 *
		 * @param $meetingId
		 *
		 * @return bool|mixed
		 */
		public function recordingsByMeeting( $meetingId ) {
			return $this->sendRequest( 'meetings/' . $meetingId . '/recordings', false, "GET" );
		}

		/**
		 * Get all recordings by USER ID ( REQUIRES PRO USER )
		 *
		 * @param $host_id
		 * @param $data array
		 *
		 * @return bool|mixed
		 */
		public function listRecording( $host_id, $data = array() ) {
			$from     = date( 'Y-m-d', strtotime( '-1 year', time() ) );
			$to       = date( 'Y-m-d' );

			$data['from'] = ! empty( $data['from'] ) ? $data['from'] : $from;
			$data['to']   = ! empty( $data['to'] ) ? $data['to'] : $to;
			$data         = apply_filters( 'rzwm_listRecording', $data );

			return $this->sendRequest( 'users/' . $host_id . '/recordings', $data, "GET" );
		}
	}

	function zoom_conference() {
		return RZoomWebinarManagertLite_Api::instance();
	}

	zoom_conference();
}