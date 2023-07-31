<?php
/**
 * @package    Feature
 */

/**
 * For ajax call
 */
class Ajax {

	/**
	 * Add new idea
	 */
	public static function back_to_home_ajax_handler() {

		// back to home.
		$home_url = home_url();
		$home_url = $home_url . '/feature';
		echo '<a href="' . esc_url( $home_url ) . '" class="nav-link back-to-home">' . esc_html( '&lt; Back to home' ) . '</a>';

		wp_die(); // Always remember to terminate the script.
	}

	/**
	 * Add new idea
	 */
	public static function add_new_idea_ajax_handler() {
		?>
		<div class="add_new_idea">
			<p class="add_new_idea_text"> <?php esc_html_e( 'Add new idea', 'aquila-features' ); ?> </p>
			<form  method="post" enctype="multipart/form-data" id="new_idea_form">
				<div class="row">
					<div class="col-6 ">


						<?php wp_nonce_field( 'new-idea-form', 'new-idea-form-nonce' ); ?>

						<!-- Project name -->
						<label for="project_name" class="form-label new_idea_form"> <?php esc_html_e( 'Project name', 'aquila-features' ); ?> </label>
						<?php
							$project_name = Database::fetch_project_name();

						?>
							<select class="form-select new_idea_form" name="project_name" id="project_name" required>
								<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
								<?php
								foreach ( $project_name as $name ) {
									?>
									<option value="<?php echo esc_html( $name->id ); ?>"><?php echo esc_html( $name->product_name ); ?></option>
									<?php
								}
								?>
							</select>

							<!-- title -->

							<label for="title" class="form-label new_idea_form"> <?php esc_html_e( 'Idea Title', 'aquila-features' ); ?>  </label>
							<input type="text" class="form-control new_idea_form" id="title" name="title" placeholder="Title" required>

							<!-- description -->

							<label for="description" class="form-label new_idea_form"> <?php esc_html_e( 'Idea Description', 'aquila-features' ); ?>  </label>
							<textarea class="form-control new_idea_form" id="description" name="description" rows="3" placeholder="Description" required></textarea>

							<!-- Attachments -->


							<p class="form-label new_idea_form"> <?php esc_html_e( 'Attachments', 'aquila-features' ); ?> </p>
							
							<div class="row">
								<label for="file" id="add_more" class="form-label new_idea_form" style="width:auto;">
									<div class="btn upload_btn"> <?php esc_html_e( 'Upload', 'aquila-features' ); ?> </div>
								</label>
							</div>
							

							

					</div>


					<div class="col-3">

						<!-- idea status -->
						<label for="project_status" class="form-label new_idea_form"> <?php esc_html_e( 'Status', 'aquila-features' ); ?> </label>
						<select class="form-select new_idea_form" name="project_status" id="project_status">
							<option value="<?php echo esc_html( sanitize_title( 'Yet to consider' ) ); ?>"> <?php esc_html_e( 'Yet To Consider', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'Future consideration' ) ); ?>"> <?php esc_html_e( 'Future Consideration', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'To Do' ) ); ?>"> <?php esc_html_e( 'To Do', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'Under Development' ) ); ?>"> <?php esc_html_e( 'Under Development', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'Live' ) ); ?>"> <?php esc_html_e( 'Live', 'aquila-features' ); ?> </option>
						</select>

						<!-- idea priority -->

						<label for="project_priority" class="form-label new_idea_form"> <?php esc_html_e( 'Priority', 'aquila-features' ); ?> </label>
						<select class="form-select new_idea_form" name="project_priority" id="project_priority">
							<option value="<?php echo esc_html( sanitize_title( 'Quick wins' ) ); ?>"> <?php esc_html_e( 'Quick Wins', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'High potential' ) ); ?>"> <?php esc_html_e( 'High Potential', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'Good to have' ) ); ?>"> <?php esc_html_e( 'Good To Have', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'Must have' ) ); ?>"> <?php esc_html_e( 'Must Have', 'aquila-features' ); ?> </option>
							<option value="<?php echo esc_html( sanitize_title( 'Out of scope' ) ); ?>"> <?php esc_html_e( 'Out Of Scope', 'aquila-features' ); ?> </option>
						</select>

						<!-- tags -->

						<label for="tags" class="form-label new_idea_form"> <?php esc_html_e( 'Tags', 'aquila-features' ); ?> </label>
						<textarea class="form-control new_idea_form" id="tags" name="tags" rows="3" placeholder="Tags" required></textarea>
					
					</div>
					<div class="wrapper text-center mt-2">
						<?php
							$home_url = home_url();
							$home_url = $home_url . '/feature';
						?>
						
							<button type="submit" class="btn text-white bg-dark " name="add_new_feature"> <?php esc_html_e( 'Post idea', 'aquila-features' ); ?> </button>
						
					</div>
				</div>
			</form>
		</div>
		<?php
		wp_die(); // Always remember to terminate the script.
	}

	/**
	 * Add Vote
	 */
	public static function feature_vote_ajax_handler() {
		check_ajax_referer( 'products-jquery', 'nonce' ); // Verify the nonce.
		if ( isset( $_POST['vote'] ) ) {
			$feature_id = isset( $_POST['vote'] ) ? sanitize_text_field( $_POST['vote'] ) : '';
			$user_id    = get_current_user_id();
			global $wpdb;
			$table_name = $wpdb->prefix . 'vote';
			$data       = array(
				'feature_id' => $feature_id,
				'user_id'    => $user_id,
			);

			if ( Database::already_liked_user( $feature_id ) ) {
				Database::insert_data( $table_name, $data );
			}

			// Get vote count.
			$vote_count = Database::get_all_votes( $feature_id );

			echo esc_html( $vote_count );

			exit();
		}
	}

	/**
	 * Unvote
	 */
	public static function feature_unvote_ajax_handler() {
		check_ajax_referer( 'products-jquery', 'nonce' ); // Verify the nonce.
		if ( isset( $_POST['vote'] ) ) {
			$feature_id = isset( $_POST['vote'] ) ? sanitize_text_field( $_POST['vote'] ) : '';
			$user_id    = get_current_user_id();
			global $wpdb;
			$table_name = $wpdb->prefix . 'vote';
			$data       = array(
				'feature_id' => $feature_id,
				'user_id'    => $user_id,
			);
			$wpdb->delete( $table_name, $data );
			// Get vote count.
			$vote_count = Database::get_all_votes( $feature_id );

			echo esc_html( $vote_count );

			exit();
		}
	}

	/**
	 * Edit feature
	 */
	public static function edit_feature_idea_ajax_handler() {
		check_ajax_referer( 'products-jquery', 'nonce' ); // Verify the nonce.
		$feature_id = isset( $_POST['feature'] ) ? sanitize_text_field( $_POST['feature'] ) : '';

		$details = Database::one_feature_details( $feature_id );
		?>
			<div class="add_new_idea">
				<p class="add_new_idea_text">  <?php esc_html_e( 'View/edit idea', 'aquila-features' ); ?>  </p>
				<form method="post" enctype="multipart/form-data" id="edit_idea_form" >
					<div class="row ">

						<div class="col-6">
							<?php wp_nonce_field( 'edit-idea-form', 'edit-idea-form-nonce' ); ?>
							<label for="project_name_edit" class="form-label new_idea_form"> <?php esc_html_e( 'Project name', 'aquila-features' ); ?> </label>
							<?php
							$project_name = Database::fetch_project_name();
							?>
							<select class="form-select new_idea_form" name="project_name_edit" id="project_name_edit" disabled>
								<option value=""><?php esc_html_e( 'View all', 'aquila-features' ); ?></option>
								<?php
								foreach ( $project_name as $name ) {
									if ( $name->id === $details[0]->project_id ) {
										?>

										<option value="<?php echo esc_html( $name->id ); ?>" selected><?php echo esc_html( $name->product_name ); ?></option>
										<?php
									}
								}
								?>
							</select>

							<!-- title -->

							<label for="title_edit" class="form-label new_idea_form"><?php esc_html_e( 'Idea Title', 'aquila-features' ); ?></label>
							<input type="text" class="form-control new_idea_form" id="title_edit" name="title_edit" placeholder="Title" value="<?php echo esc_html( $details[0]->title ); ?>" required>
							

							<!-- description -->

							<label for="description_edit" class="form-label new_idea_form"><?php esc_html_e( 'Idea Description', 'aquila-features' ); ?> </label>
							<textarea class="form-control new_idea_form" id="description_edit" name="description_edit" rows="3" placeholder="Description" value=""  required><?php echo esc_html( $details[0]->description ); ?></textarea>

							<!-- Attachments -->

							<p class="form-label new_idea_form"><?php esc_html_e( 'Attachments', 'aquila-features' ); ?> </p>

							<div class="row">
								<label for="file" id="add_more" class="form-label new_idea_form" style="width:auto;">
									<div class="btn upload_btn"><?php esc_html_e( 'Upload', 'aquila-features' ); ?> </div>
								</label>
							</div>

							<div id="images">
								<?php
									$images = Database::take_images( $feature_id );
								if ( ! empty( $images ) && is_array( $images ) ) {
									Feature::display_images( $images, $feature_id );
								} else {
									echo 'No images found.';
								}
								?>
							</div>

						</div>

						<div class="col-3">

							<div class="feature-post-details">
								<?php
									$current_user = wp_get_current_user();
									$username     = $current_user->user_login;
									// Create a DateTime object from the string.
									$date = new DateTime( $details[0]->time );

									// Format the date in the desired format "M-d-Y".
									$formatted_date = $date->format( 'd M Y' );

									$update_time = new DateTime( $details[0]->update_time );

									$updated_formatted_date = $update_time->format( 'd M Y' );

								?>
								<p class="feature_text"> <?php esc_html_e( 'Reported by', 'aquila-features' ); ?>   <?php echo esc_html( $username ); ?> </p>
								<p class="feature_text"> <?php esc_html_e( 'Reported on', 'aquila-features' ); ?>   <?php echo esc_html( $formatted_date ); ?></p>
								<p class="feature_text"> <?php esc_html_e( 'Last updated on', 'aquila-features' ); ?>   <?php echo esc_html( $updated_formatted_date ); ?></p>

								<div class="edit-sec-vote-grp ">
									<div class="">
										<?php Feature::feature_vote_in_edit_section( $feature_id ); ?>
									</div>
									<div class="">
										<?php
										Feature::vote_btn( $feature_id );
										?>
									</div>
								</div>



							</div>


							<!-- idea status -->
							
							<label for="project_status_edit" class="form-label new_idea_form">Status</label>
							<select class="form-select new_idea_form" name="project_status_edit" id="project_status_edit">
								
								<option value="<?php echo esc_html( $details[0]->project_status ); ?>" selected> <?php echo esc_html( ucwords( str_replace( '-', ' ', $details[0]->project_status ) ) ); ?>  </option>
								<option value="<?php echo esc_html( sanitize_title( 'Yet to consider' ) ); ?>"><?php esc_html_e( 'Yet To Consider', 'aquila-features' ); ?></option>
								<option value="<?php echo esc_html( sanitize_title( 'Future consideration' ) ); ?>"><?php esc_html_e( 'Future Consideration', 'aquila-features' ); ?></option>
								<option value="<?php echo esc_html( sanitize_title( 'To Do' ) ); ?>"><?php esc_html_e( 'To Do', 'aquila-features' ); ?></option>
								<option value="<?php echo esc_html( sanitize_title( 'Under Development' ) ); ?>"><?php esc_html_e( 'Under Development', 'aquila-features' ); ?></option>
								<option value="<?php echo esc_html( sanitize_title( 'Live' ) ); ?>"><?php esc_html_e( 'Live', 'aquila-features' ); ?></option>
							</select>


							<!-- idea priority -->

							<label for="project_priority_edit" class="form-label new_idea_form">Priority</label>
							<select class="form-select new_idea_form" name="project_priority_edit" id="project_priority_edit">
								<option value="<?php echo esc_html( $details[0]->project_priority ); ?>" selected> <?php echo esc_html( ucwords( str_replace( '-', ' ', $details[0]->project_priority ) ) ); ?>  </option>
								<option value="<?php echo esc_html( sanitize_title( 'Quick wins' ) ); ?>"> <?php esc_html_e( 'Quick Wins', 'aquila-features' ); ?> </option>
								<option value="<?php echo esc_html( sanitize_title( 'High potential' ) ); ?>"> <?php esc_html_e( 'High Potential', 'aquila-features' ); ?> </option>
								<option value="<?php echo esc_html( sanitize_title( 'Good to have' ) ); ?>"> <?php esc_html_e( 'Good To Have', 'aquila-features' ); ?> </option>
								<option value="<?php echo esc_html( sanitize_title( 'Must have' ) ); ?>"> <?php esc_html_e( 'Must Have', 'aquila-features' ); ?> </option>
								<option value="<?php echo esc_html( sanitize_title( 'Out of scope' ) ); ?>"> <?php esc_html_e( 'Out Of Scope', 'aquila-features' ); ?> </option>
							</select>

							<!-- tags -->

							<label for="tags_edit" class="form-label new_idea_form"><?php esc_html_e( 'Tags', 'aquila-features' ); ?></label>
							<textarea class="form-control new_idea_form" id="tags_edit" name="tags_edit" rows="3" placeholder="Tags" required><?php echo esc_html( $details[0]->project_tag ); ?></textarea>
						





						</div>

						<div class="wrapper text-center mt-4 col-9">
							<button class="btn text-white bg-dark " value="<?php echo esc_html( $feature_id ); ?>" name="edit_feature"> <?php esc_html_e( 'Save', 'aquila-features' ); ?> </button>
						</div>	
					</div>
				</form>
				
			</div>
		<?php

		exit();
	}

	/**
	 * Delete image
	 */
	public static function delete_image_ajax_handler() {
		check_ajax_referer( 'products-jquery', 'nonce' ); // Verify the nonce.
		$image_id   = isset( $_POST['image_id'] ) ? sanittize_text_field( $_POST['image_id'] ) : '';
		$feature_id = isset( $_POST['feature'] ) ? sanittize_text_field( $_POST['feature'] ) : '';
		$images     = isset( $_POST['images'] ) ? $_POST['images'] : false;

		foreach ( $images as $key => $image ) {
			if ( sanittize_text_field( $image['id'] ) === $image_id ) {
				// Remove the element from the $images array.
				unset( $images[ $key ] );
				break; // Exit the loop as we found and removed the desired image.
			}
		}

		// Now $images array will not contain the image with matching $image_id.

		if ( ! empty( $images ) && is_array( $images ) ) {
			Feature::display_images( $images, $feature_id );
		} else {
			echo 'No images found.';
		}
		exit;
	}
}