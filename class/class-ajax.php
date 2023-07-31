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
			<p class="add_new_idea_text">Add new idea</p>
			<form  method="post" enctype="multipart/form-data" id="new_idea_form">
				<div class="row">
					<div class="col-6 ">


						<?php wp_nonce_field( 'new-idea-form', 'new-idea-form-nonce' ); ?>

						<!-- Project name -->
						<label for="project_name" class="form-label new_idea_form">Project name</label>
						<?php
							$project_name = Database::fetch_project_name();

						?>
							<select class="form-select new_idea_form" name="project_name" id="project_name" required>
								<option value="">View all</option>
								<?php
								foreach ( $project_name as $name ) {
									?>
									<option value="<?php echo esc_html( $name->id ); ?>"><?php echo esc_html( $name->product_name ); ?></option>
									<?php
								}
								?>
							</select>

							<!-- title -->

							<label for="title" class="form-label new_idea_form">Idea Title</label>
							<input type="text" class="form-control new_idea_form" id="title" name="title" placeholder="Title" required>

							<!-- description -->

							<label for="description" class="form-label new_idea_form">Idea Description</label>
							<textarea class="form-control new_idea_form" id="description" name="description" rows="3" placeholder="Description" required></textarea>

							<!-- Attachments -->


							<p class="form-label new_idea_form">Attachments</p>
							
							<div class="row">
								<label for="file" id="add_more" class="form-label new_idea_form" style="width:auto;">
									<div class="btn upload_btn">Upload</div>
								</label>
							</div>
							

							

					</div>


					<div class="col-3">

						<!-- idea status -->
						<label for="project_status" class="form-label new_idea_form">Status</label>
						<select class="form-select new_idea_form" name="project_status" id="project_status">
							<option value="<?php echo esc_html( sanitize_title( 'Yet to consider' ) ); ?>">Yet To Consider</option>
							<option value="<?php echo esc_html( sanitize_title( 'Future consideration' ) ); ?>">Future Consideration</option>
							<option value="<?php echo esc_html( sanitize_title( 'To Do' ) ); ?>">To Do</option>
							<option value="<?php echo esc_html( sanitize_title( 'Under Development' ) ); ?>">Under Development</option>
							<option value="<?php echo esc_html( sanitize_title( 'Live' ) ); ?>">Live</option>
						</select>

						<!-- idea priority -->

						<label for="project_priority" class="form-label new_idea_form">Priority</label>
						<select class="form-select new_idea_form" name="project_priority" id="project_priority">
							<option value="<?php echo esc_html( sanitize_title( 'Quick wins' ) ); ?>">Quick Wins</option>
							<option value="<?php echo esc_html( sanitize_title( 'High potential' ) ); ?>">High Potential</option>
							<option value="<?php echo esc_html( sanitize_title( 'Good to have' ) ); ?>">Good To Have</option>
							<option value="<?php echo esc_html( sanitize_title( 'Must have' ) ); ?>">Must Have</option>
							<option value="<?php echo esc_html( sanitize_title( 'Out of scope' ) ); ?>">Out Of Scope</option>
						</select>

						<!-- tags -->

						<label for="tags" class="form-label new_idea_form">Tags</label>
						<textarea class="form-control new_idea_form" id="tags" name="tags" rows="3" placeholder="Tags" required></textarea>
					
					</div>
					<div class="wrapper text-center mt-2">
						<?php
							$home_url = home_url();
							$home_url = $home_url . '/feature';
						?>
						
							<button type="submit" class="btn text-white bg-dark " name="add_new_feature">Post idea</button>
						
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
			$feature_id = $_POST['vote'];
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
			$feature_id = $_POST['vote'];
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
		$feature_id = $_POST['feature'];

		$details = Database::one_feature_details( $feature_id );
		?>
			<div class="add_new_idea">
				<p class="add_new_idea_text"> View/edit idea </p>
				<form method="post" enctype="multipart/form-data" id="edit_idea_form" >
					<div class="row ">

						<div class="col-6">
							<?php wp_nonce_field( 'edit-idea-form', 'edit-idea-form-nonce' ); ?>
							<label for="project_name_edit" class="form-label new_idea_form">Project name</label>
							<?php
							$project_name = Database::fetch_project_name();
							?>
							<select class="form-select new_idea_form" name="project_name_edit" id="project_name_edit" disabled>
								<option value="">View all</option>
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

							<label for="title_edit" class="form-label new_idea_form">Idea Title</label>
							<input type="text" class="form-control new_idea_form" id="title_edit" name="title_edit" placeholder="Title" value="<?php echo esc_html( $details[0]->title ); ?>" required>
							

							<!-- description -->

							<label for="description_edit" class="form-label new_idea_form">Idea Description</label>
							<textarea class="form-control new_idea_form" id="description_edit" name="description_edit" rows="3" placeholder="Description" value=""  required><?php echo esc_html( $details[0]->description ); ?></textarea>

							<!-- Attachments -->

							<p class="form-label new_idea_form">Attachments</p>

							<div class="row">
								<label for="file" id="add_more" class="form-label new_idea_form" style="width:auto;">
									<div class="btn upload_btn">Upload</div>
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
								<p class="feature_text">Reported by <?php echo esc_html( $username ); ?> </p>
								<p class="feature_text">Reported on <?php echo esc_html( $formatted_date ); ?></p>
								<p class="feature_text">Last updated on <?php echo esc_html( $updated_formatted_date ); ?></p>

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
								<option value="<?php echo esc_html( sanitize_title( 'Yet to consider' ) ); ?>">Yet to consider</option>
								<option value="<?php echo esc_html( sanitize_title( 'Future consideration' ) ); ?>">Future consideration</option>
								<option value="<?php echo esc_html( sanitize_title( 'To Do' ) ); ?>">To Do</option>
								<option value="<?php echo esc_html( sanitize_title( 'Under Development' ) ); ?>">Under Development</option>
								<option value="<?php echo esc_html( sanitize_title( 'Live' ) ); ?>">Live</option>
							</select>


							<!-- idea priority -->

							<label for="project_priority_edit" class="form-label new_idea_form">Priority</label>
							<select class="form-select new_idea_form" name="project_priority_edit" id="project_priority_edit">
								<option value="<?php echo esc_html( $details[0]->project_priority ); ?>" selected> <?php echo esc_html( ucwords( str_replace( '-', ' ', $details[0]->project_priority ) ) ); ?>  </option>
								<option value="<?php echo esc_html( sanitize_title( 'Quick wins' ) ); ?>">Quick Wins</option>
								<option value="<?php echo esc_html( sanitize_title( 'High potential' ) ); ?>">High Potential</option>
								<option value="<?php echo esc_html( sanitize_title( 'Good to have' ) ); ?>">Good To Have</option>
								<option value="<?php echo esc_html( sanitize_title( 'Must have' ) ); ?>">Must Have</option>
								<option value="<?php echo esc_html( sanitize_title( 'Out of scope' ) ); ?>">Out Of Scope</option>
							</select>

							<!-- tags -->

							<label for="tags_edit" class="form-label new_idea_form">Tags</label>
							<textarea class="form-control new_idea_form" id="tags_edit" name="tags_edit" rows="3" placeholder="Tags" required><?php echo esc_html( $details[0]->project_tag ); ?></textarea>
						





						</div>

						<div class="wrapper text-center mt-4 col-9">
							<button class="btn text-white bg-dark " value="<?php echo esc_html( $feature_id ); ?>" name="edit_feature">Save</button>
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
		$image_id   = $_POST['image_id'];
		$feature_id = $_POST['feature'];
		$images     = $_POST['images'];

		foreach ( $images as $key => $image ) {
			if ( $image['id'] === $image_id ) {
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