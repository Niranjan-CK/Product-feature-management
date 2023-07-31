<?php
/**
 * @package    Feature
 */

/**
 * Database
 */
class Database {

	/**
	 * Fetch data from project table
	 */
	public static function fetch_project_name() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}products" );

		return $results;
	}

	/**
	 * Take all tags
	 */
	public static function take_all_tags() {
		global $wpdb;
		$results = $wpdb->get_col( "SELECT DISTINCT project_tag FROM {$wpdb->prefix}feature" );
		return $results;
	}

	/**
	 * Take all user ids
	 */
	public static function take_user_id() {
		global $wpdb;
		$results = $wpdb->get_col( "SELECT DISTINCT user_id FROM {$wpdb->prefix}feature" );
		return $results;
	}

	/**
	 * Insert data into wp_feature table
	 */
	public static function create_table() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'feature';
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			project_id int(11) NOT NULL,
			title varchar(255) NOT NULL,
			description text NOT NULL,
			project_status varchar(255) NOT NULL,
			project_priority varchar(255) NOT NULL,
			project_tag varchar(255) NOT NULL,
			time datetime DEFAULT CURRENT_TIMESTAMP,
			update_time datetime DEFAULT CURRENT_TIMESTAMP,
			user_id int(11) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Insert data into wp_feature table
	 *
	 * @param string $table_name The table name.
	 * @param array  $data The data.
	 */
	public static function insert_data( $table_name, $data ) {
		global $wpdb;
		$wpdb->insert( $table_name, $data );
		return $wpdb->insert_id;
	}

	/**
	 * Get all Feature from wp_feature table
	 */
	public static function get_all_feature_data() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}feature ORDER BY `update_time` DESC" );

		return $results;
	}

	/**
	 * Fetch vote data from wp_vote table
	 */
	public static function fetch_vote_data() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}vote" );
		return $results;
	}

	/**
	 * Create vote table
	 */
	public static function create_vote_table() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'vote';
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $table_name (
			id int(11) NOT NULL AUTO_INCREMENT,
			feature_id int(11) NOT NULL,
			user_id varchar(255) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Get count of votes from wp_vote table
	 *
	 * @param int $id The id.
	 */
	public static function get_all_votes( $id ) {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}vote WHERE feature_id = %d", $id ) );
		return count( $results );
	}

	/**
	 * Check user already liked in this feature.
	 *
	 * @param int $feature_id The feature id.
	 */
	public static function already_liked_user( $feature_id ) {
		global $wpdb;

		$user_id = get_current_user_id();
		$result  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}vote WHERE feature_id = %d and user_id = %d", $feature_id, $user_id ) );
		if ( empty( $result ) ) {
			return true;
		} else {
			return false;

		}
	}

	/**
	 * Get on feature details
	 *
	 * @param int $feature_id feature id.
	 */
	public static function one_feature_details( $feature_id ) {
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}feature WHERE id = %d", $feature_id ) );
		return $results;
	}

	/**
	 * Check the responser is already liked or not.
	 *
	 * @param int $feature_id feature id.
	 * @param int $user_id responser id.
	 */
	public static function already_liked_response( $feature_id, $user_id ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}vote WHERE feature_id = %d and user_id = %s", $feature_id, $user_id ) );
		if ( empty( $result ) ) {
			return true;
		} else {
			return false;

		}
	}

	/**
	 * Take all feature images
	 *
	 * @param int $feature_id feature id.
	 */
	public static function take_images( $feature_id ) {
		global $wpdb;
		$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}upload_feature_image WHERE feature_id = %d", $feature_id ) );
		return $result;
	}

	/**
	 * Delete images from the table using image id and feature id
	 *
	 * @param int $feature_id id of feature.
	 */
	public static function delete_image_from_table( $feature_id ) {

		global $wpdb;

		$table_name = $wpdb->prefix . 'upload_feature_image';
		$data       = array(
			'feature_id' => $feature_id,
		);
		$result     = $wpdb->delete( $table_name, $data );
	}
}
