<?php
/**
 * @package    Feature
 */

/**
 * Feature
 */
class Feature {

	/**
	 * Feature list
	 */
	public static function feature_list() {

		$all_features = Database::get_all_feature_data();
		if ( ! empty( $all_features ) ) {
			self::display_features( $all_features );
		} else {
			echo 'NO DATA';
		}
	}

	/**
	 * Display features
	 *
	 * @param array $all_features it have details of features.
	 */
	public static function display_features( $all_features ) {

		foreach ( $all_features as $feature ) {
			?>
				<div class="wrapper mt-5 mb-5 pb-3 row">

					<div class="vote col-2 p-4">
						<?php self::feature_vote( $feature->id ); ?>
						
					</div>

					<div class="col-8">
						<h4  class="feature-head" id="<?php echo esc_attr( $feature->id ); ?>" onclick="edit_feature(event,<?php echo esc_attr( $feature->id ); ?>)"><?php echo esc_html( $feature->title ); ?></h4>
						<p><?php echo esc_html( $feature->description ); ?></p>
						<!-- uploader details -->
						<div class=" uploader_details">
							<!-- Fetch user details -->
							<?php
								$user_id = $feature->user_id;
								$user    = get_user_by( 'id', $user_id );
							?>

							<!-- get user avatar -->
							<div class="  avatar-container">
								<?php echo get_avatar( $user_id, 25 ); ?>
							</div>

							<!-- get user name -->
							<div class="">
								<?php echo esc_html( $user->user_nicename ); ?>
							</div>

							<!--  feature uploaded  time -->
							<div class="">
								<?php $time_ago = self::get_time( $feature->time ); ?>

								<p><?php echo esc_html( $time_ago ); ?></p>
							</div>

							<div class=" larger-item project-box">
								<span class="text-box">
									<?php echo esc_html( ucwords( str_replace( '-', ' ', $feature->project_status ) ) ); ?>
								</span>
							</div>
							<div class=" project-box">
								<span class="text-box">
									<?php echo esc_html( ucwords( str_replace( '-', ' ', $feature->project_priority ) ) ); ?>
								</span>
							</div>
							
						</div>
					</div>
				</div>
			<?php
		}
	}

	/**
	 * Get time.
	 *
	 * @param string $datetime time.
	 * @return string
	 */
	public static function get_time( $datetime ) {
		// Get the site's timezone from WordPress settings.
		$timezone  = get_option( 'timezone_string' );
		$date_time = new DateTime( $datetime );

		$date = $date_time->format( 'd M Y' );
		// echo $datetime;
		// Create a DateTime object with the provided datetime and set the timezone.
		$datetime_obj = new DateTime( $datetime, new DateTimeZone( $timezone ) );
		// Calculate the time difference between the provided datetime and now.
		$now      = new DateTime( 'now', new DateTimeZone( $timezone ) );
		$interval = $datetime_obj->diff( $now );

		// Get the human-readable time difference.
		$human_time_diff = human_time_diff( $datetime_obj->getTimestamp(), $now->getTimestamp() );

		// Format the result as "X time ago".
		if ( $interval->y > 0 ) {
			return $date;
		} elseif ( $interval->m > 0 ) {
			return $date;
		} elseif ( $interval->d > 0 ) {

			return $date;
		} elseif ( $interval->h > 0 ) {
			return $human_time_diff . ' ago';
		} elseif ( $interval->i > 0 ) {
			return $human_time_diff . ' ago';
		} else {
			return 'just now';
		}
	}

	/**
	 * Feature vote.
	 *
	 * @param string $feature_id feature id.
	 */
	public static function feature_vote( $feature_id ) {
		?>
		<div class="vote-count ">
			<span id="<?php echo esc_attr( 'like' . $feature_id ); ?>">
				<?php
					$count = Database::get_all_votes( $feature_id );
					echo esc_html( $count );
				?>
			</span>
		</div>

		<?php
		self::vote_btn( $feature_id );
	}

	/**
	 * Its add vote button.
	 *
	 * @param int $feature_id is an feature id of product.
	 */
	public static function vote_btn( $feature_id ) {
		?>
		<div class="vote-grp">
			<?php if ( Database::already_liked_user( $feature_id ) ) : ?>
				<div class="btn-grp vote-btn-div" id="<?php echo esc_attr( 'bg' . $feature_id ); ?>" style="background-color:white; color:black;" >
					<button type="button" class="vote-btn btn-vote"  id = "<?php echo esc_attr( $feature_id ); ?>" onclick="vote(event,<?php echo esc_attr( $feature_id ); ?>)">
						Vote
					</button>
					<button type="button" class="vote-btn btn-vote arrow" id = "<?php echo esc_attr( $feature_id ); ?>" onclick="vote_on_behalf(event,<?php echo esc_attr( 'vote_' . $feature_id ); ?>)">
						<svg xmlns="http://www.w3.org/2000/svg" width="13" id="<?php echo esc_attr( 'svg' . $feature_id ); ?>" height="13" fill="black" class="bi bi-chevron-down" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
						</svg>
					</button>
				</div>
			<?php else : ?>
				<div class="btn-grp btn-primary" id="<?php echo esc_attr( 'bg' . $feature_id ); ?>"  >
					<button type="button" class="vote-btn unvote-btn"  id = "<?php echo esc_attr( $feature_id ); ?>" onclick="unvote(event,<?php echo esc_attr( $feature_id ); ?>)">
						VOTED
					</button>
					<button type="button" class="vote-btn unvote-btn arrow" id = "<?php echo esc_attr( $feature_id ); ?>" onclick="vote_on_behalf(event,<?php echo esc_attr( 'vote_' . $feature_id ); ?>)">
					<svg xmlns="http://www.w3.org/2000/svg" id="<?php echo esc_attr( 'svg' . $feature_id ); ?>" width="13" height="13" fill="#fff" class="bi bi-chevron-down" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
					</svg>
					</button>
				</div>
			<?php endif ?>
			<button type="button" class="vote-on-behalf btn text-dark " id="<?php echo esc_attr( 'vote_' . $feature_id ); ?>" style="display: none;" 	> Vote on behalf </button>
		   
			<!-- popup box -->
			<div class="popup" id="popupBox">
				<div class="popup-box">
					<div class="popup-content">
						<!-- Content for the pop-up box -->
						<div class="flex-container">
							<span name="add_reference" id="closePopupButton" class="  btn " >
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
									<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
								</svg>
							</span>
						</div>
						<p class="reference-head ">Reference</p>
							<div class=" popup-form ">
								<input type="text" id ="vote_reference_by_user"name="reference_name" class="form-control" placeholder="Text">
								<button  id="popup_btn" class="popup-btn btn btn-dark">Submit</button>
							</div>

					</div>
				</div>
			</div>
			<!-- end popup -->
		</div>
		<?php
	}

	/**
	 * Vote details of feature.
	 *
	 * @param int $feature_id id of feature.
	 */
	public static function feature_vote_in_edit_section( $feature_id ) {
		$count = Database::get_all_votes( $feature_id );
		?>
		<span id="<?php echo esc_attr( 'like' . $feature_id ); ?>"> <?php echo esc_html( $count ); ?></span> Votes
		<?php
	}

	/**
	 * List images.
	 *
	 * @param int $images images.
	 * @param int $feature_id as feature id.
	 */
	public static function display_images( $images, $feature_id ) {
		$target_dir = WP_CONTENT_URL . '/uploads/feature_images/';

		$json_img = wp_json_encode( $images );

		?>
		<div class="row">
			
			<?php foreach ( $images as $image ) : ?>
				<?php
				if ( is_object( $image ) ) {
					$image_path = $target_dir . $image->image;
					$image_id   = $image->id;
					$image_name = $image->image;
				} else {
					$image_path = $target_dir . $image['image'];
					$image_name = $image['image'];
					$image_id   = $image['id'];
				}
				?>
			<div class="image-preview col">
				<input type="hidden" name="image[]" value="<?php echo esc_html( $image_name ); ?>" id="" style="display:none" >
				<a href="<?php echo esc_url( $image_path ); ?>" style="text-decoration: none" download>
					<img src="<?php echo esc_url( $image_path ); ?>" alt="" style="width: 50px; height: 50px;">
				</a>
				<span onclick="deletefun(event, <?php echo esc_attr( strval( $image_id ) ); ?>, <?php echo esc_attr( strval( $feature_id ) ); ?>, <?php echo esc_attr( $json_img ); ?>)">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi delete-btn bi-x-circle" viewBox="0 0 16 16">
						<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
						<path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
					</svg>
				</span>
			</div>
				<?php
	endforeach;
			?>
	</div> 
		<?php
	}
}