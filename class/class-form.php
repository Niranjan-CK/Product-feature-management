<?php
/**
 * @package    Feature
 */

/**
 * Form section
 */
class Form {

	/**
	 * Add new idea form
	 */
	public static function add_new_idea_form() {
		if ( ! isset( $_POST['new-idea-form-nonce'] ) || ! wp_verify_nonce( $_POST['new-idea-form-nonce'], 'new-idea-form' ) ) {
			return;
		}
		if ( isset( $_POST['add_new_feature'] ) ) {
			$project_name     = $_POST['project_name'];
			$title            = $_POST['title'];
			$description      = $_POST['description'];
			$project_status   = $_POST['project_status'];
			$project_priority = $_POST['project_priority'];
			$project_tag      = $_POST['tags'];
			$user_id          = get_current_user_id();

				$data = array(
					'project_id'       => $project_name,
					'title'            => $title,
					'description'      => $description,
					'project_status'   => $project_status,
					'project_priority' => $project_priority,
					'project_tag'      => $project_tag,
					'user_id'          => $user_id,
				);

				global $wpdb;
				// Check wp_feature table is exist or not.
				$table_name = $wpdb->prefix . 'feature';
				// Vote table name.
				$vote_table_name = $wpdb->prefix . 'vote';

					// Insert data.
					$id        = Database::insert_data( $table_name, $data );
					$vote_data = array(
						'feature_id' => $id,
						'user_id'    => $user_id,
					);
					Database::insert_data( $vote_table_name, $vote_data );

					// Image upload.

					$file_count = isset( $_FILES['file']['name'] ) ? count( $_FILES['file']['name'] ) : -1;

					for ( $i = 0; $i < $file_count; $i++ ) {
						$image         = $_FILES['file']['name'][ $i ];
						$uploaded_file = self::upload_image_to_folder( $_FILES['file'] );

						if ( $uploaded_file ) {
							$table_name = $wpdb->prefix . 'upload_feature_image';
							if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) !== $table_name ) {
								$sql = "CREATE TABLE $table_name (
								id int(11) NOT NULL AUTO_INCREMENT,
								feature_id varchar(255) NOT NULL,
								image varchar(255) NOT NULL,
								PRIMARY KEY  (id)
								);";
								require_once ABSPATH . 'wp-admin/includes/upgrade.php';
								dbDelta( $sql );

								foreach ( $uploaded_file as $file ) {
									$wpdb->insert(
										$table_name,
										array(
											'feature_id' => $id,
											'image'      => $file,
										)
									);
								}
							} else {
								foreach ( $uploaded_file as $file ) {
									$wpdb->insert(
										$table_name,
										array(
											'feature_id' => $id,
											'image'      => $file,
										)
									);
								}
							}
						}
					}

					// Image upload end.

		}
		wp_safe_redirect( home_url() . '/feature' );
		exit;
	}

	/**
	 * Upload image to folder
	 *
	 * @param array $files Array of files.
	 */
	public static function upload_image_to_folder( $files ) {
		$upload_dir = wp_upload_dir(); // Get the upload directory details.

		// Specify the target directory within the upload directory.
		$target_dir = $upload_dir['basedir'] . '/feature_images/';

		// Create the target directory if it doesn't exist.
		if ( ! file_exists( $target_dir ) ) {
			wp_mkdir_p( $target_dir );
		}

		$uploaded_files = array(); // Array to store the paths of uploaded files.

		// Process each file in the $_FILES array.
		foreach ( $files['name'] as $index => $name ) {
			// Generate a unique filename for the uploaded file.
			$file_name = wp_unique_filename( $target_dir, $name );

			// Move the uploaded file to the target directory.
			if ( move_uploaded_file( $files['tmp_name'][ $index ], $target_dir . $file_name ) ) {
				// File moved successfully.
				$uploaded_files[] = $file_name; // Store the path of the uploaded file.
			}
		}

		return $uploaded_files;
	}

	/**
	 * Add references
	 */
	public static function vote_by_rerference_ajax_handler() {

		check_ajax_referer( 'products-jquery', 'nonce' ); // Verify the nonce.

			$feature    = $_POST['feature_id'];
			$parts      = explode( '_', $feature );
			$feature_id = $parts[1];
			$user_id    = $_POST['vote_reference'];

		if ( '' !== $user_id ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'vote';
			$data       = array(
				'feature_id' => $feature_id,
				'user_id'    => $user_id,
			);
			if ( Database::already_liked_response( $feature_id, $user_id ) ) {
				$wpdb->insert( $table_name, $data, array( '%d', '%s' ) );
				$vote_count = Database::get_all_votes( $feature_id );
				echo esc_html( $vote_count );
			} else {
				echo "<script>alert('Already Voted')</script>";
			}
		} else {
			echo "<script>alert('enter user id')</script>";
		}

			exit();
	}

	/**
	 * Update feature
	 */
	public static function edit_idea_form() {
		if ( ! isset( $_POST['edit-idea-form-nonce'] ) || ! wp_verify_nonce( $_POST['edit-idea-form-nonce'], 'edit-idea-form' ) ) {
			return;
		}

		if ( isset( $_POST['edit_feature'] ) ) {

			$feature_id       = $_POST['edit_feature'];
			$title            = $_POST['title_edit'];
			$description      = $_POST['description_edit'];
			$project_status   = $_POST['project_status_edit'];
			$project_priority = $_POST['project_priority_edit'];
			$project_tag      = $_POST['tags_edit'];
			$data             = array(
				'title'            => $title,
				'description'      => $description,
				'project_status'   => $project_status,
				'project_priority' => $project_priority,
				'project_tag'      => $project_tag,
				'update_time'      => current_time( 'mysql' ),
			);
			Database::delete_image_from_table( $feature_id );
			global $wpdb;
			$table_name = $wpdb->prefix . 'feature';
			$wpdb->update( $table_name, $data, array( 'id' => $feature_id ) );
			$file_count = isset( $_FILES['file']['name'] ) ? count( $_FILES['file']['name'] ) : -1;
			for ( $i = 0; $i < $file_count; $i++ ) {
				$image         = $_FILES['file']['name'][ $i ];
				$uploaded_file = self::upload_image_to_folder( $_FILES['file'] );
				if ( $uploaded_file ) {
					$table_name = $wpdb->prefix . 'upload_feature_image';
					foreach ( $uploaded_file as $file ) {
						$wpdb->insert(
							$table_name,
							array(
								'feature_id' => $feature_id,
								'image'      => $file,
							)
						);
					}
				}
			}
		}

		$uploaded_images = isset( $_POST['image'] ) ? $_POST['image'] : false;

		if ( $uploaded_images ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'upload_feature_image';
			foreach ( $uploaded_images as $image ) {
				$wpdb->insert(
					$table_name,
					array(
						'feature_id' => $feature_id,
						'image'      => $image,
					)
				);
			}
		}
	}

	/**
	 * Filter feature
	 */
	public static function filter_feature() {

		if ( ! isset( $_GET['filter-feature-nonce'] ) || ! wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

			return;
		}

		if ( isset( $_GET['filter_feature'] ) ) {

			$project_name = isset( $_GET['project_name'] ) ? $_GET['project_name'] : false;
			$status       = isset( $_GET['filter_project_status'] ) ? $_GET['filter_project_status'] : false;
			$priority     = isset( $_GET['filter_project_priority'] ) ? $_GET['filter_project_priority'] : false;
			$tag          = isset( $_GET['filter_project_tag'] ) ? $_GET['filter_project_tag'] : false;
			$reporter     = isset( $_GET['filter_project_reporter'] ) ? $_GET['filter_project_reporter'] : false;
			$date         = isset( $_GET['filter_project_date'] ) ? $_GET['filter_project_date'] : false;
			$start_date   = null;

			if ( $date ) {
				list($start_date, $end_date) = self::date_filter( $date );
			}

				global $wpdb;
				$where = ' WHERE 1=1'; // Start with a base WHERE condition.

			if ( $project_name ) {
				$where .= $wpdb->prepare( ' AND project_id = %d', $project_name );
			}

			if ( $status ) {
				$where .= $wpdb->prepare( ' AND project_status = %s', $status );
			}

			if ( $tag ) {
				$where .= $wpdb->prepare( ' AND project_tag = %s', $tag );
			}

			if ( $priority ) {
				$where .= $wpdb->prepare( ' AND project_priority = %s', $priority );
			}

			if ( $reporter ) {
				$where .= $wpdb->prepare( ' AND user_id = %d', $reporter );
			}

			if ( $start_date && $end_date ) {
				$where .= $wpdb->prepare( ' AND time >= %s AND time <= %s', $start_date, $end_date );
			}

				$query = "SELECT * FROM {$wpdb->prefix}feature $where ORDER BY `update_time` DESC ";

				$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscoresv

				Feature::display_features( $results );

		}
	}

	/**
	 * Date Filter.
	 *
	 * @param int $selected_value its an date.
	 */
	public static function date_filter( $selected_value ) {
		$current_date = gmdate( 'Y-m-d' );

		switch ( $selected_value ) {
			case 'this-month':     // Set the start date to the first day of the current month.
									$start_date = gmdate( 'Y-m-01', strtotime( $current_date ) );
									// Set the end date to the last day of the current month.
									$end_date = gmdate( 'Y-m-t', strtotime( $current_date ) );
				break;

			case 'last-month':     // Get the timestamp of the first day of the current month.
									$first_day_of_current_month = strtotime( gmdate( 'Y-m-01', strtotime( $current_date ) ) );
									// Get the timestamp of the last day of the previous month.
									$last_day_of_last_month = strtotime( '-1 day', $first_day_of_current_month );

									// Set the start date to the first day of the last month.
									$start_date = gmdate( 'Y-m-01', $last_day_of_last_month );
									// Set the end date to the last day of the last month.
									$end_date = gmdate( 'Y-m-t', $last_day_of_last_month );
				break;

			case 'this-qurter':     // Get current month.
									$current_month          = gmdate( 'n', strtotime( $current_date ) );
									$start_month_of_quarter = ( ceil( $current_month / 3 ) - 1 ) * 3 + 1;
									$start_date             = gmdate( 'Y-m-01', strtotime( gmdate( 'Y' ) . '-' . $start_month_of_quarter . '-01' ) );

									// Calculate the end date of the current quarter.
									$end_month_of_quarter = $start_month_of_quarter + 2;
									$end_date             = gmdate( 'Y-m-t', strtotime( gmdate( 'Y' ) . '-' . $end_month_of_quarter . '-01' ) );
				break;
			case 'last-qurter':  // Calculate the start date of the last quarter.
									$current_month               = gmdate( 'n', strtotime( $current_date ) );
									$start_month_of_last_quarter = ( ceil( $current_month / 3 ) - 2 ) * 3 + 1;
									$start_date                  = gmdate( 'Y-m-01', strtotime( gmdate( 'Y' ) . '-' . $start_month_of_last_quarter . '-01' ) );

									// Calculate the end date of the last quarter.
									$end_month_of_last_quarter = $start_month_of_last_quarter + 2;
									$end_date                  = gmdate( 'Y-m-t', strtotime( gmdate( 'Y' ) . '-' . $end_month_of_last_quarter . '-01' ) );
				break;
			case 'this-year':   // Get current year.
									$current_year = gmdate( 'Y' );

									// Set the start date to the first day of the current year.
									$start_date = $current_year . '-01-01';

									// Set the end date to the last day of the current year.
									$end_date = $current_year . '-12-31';

				break;
			case 'last-year':   // Get last year.
									$last_year = gmdate( 'Y' ) - 1;

									// Set the start date to the first day of the current year.
									$start_date = $last_year . '-01-01';

									// Set the end date to the last day of the current year.
									$end_date = $last_year . '-12-31';

				break;
			case 'custom':
				if ( isset( $_GET['filter-feature-nonce'] ) || wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {
					$start_date = $_GET['start_date'];
					$end_date   = $_GET['end_date'];
				}

				break;

		}
		return array( $start_date, $end_date );
	}
}
