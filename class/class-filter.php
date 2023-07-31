<?php
/**
 * @package    Feature
 */

/**
 * Filters
 */
class Filter {

	/**
	 * Filter section
	 * All functions redirect to the ajax call
	 */
	public static function filter_section() {
		?>
		<p >  <?php esc_html_e( 'All ideas', 'aquila-features' ); ?>  </p>
		<form method="get">
			<?php
			wp_nonce_field( 'filter-feature', 'filter-feature-nonce' );

			self::filter_by_project();
			self::filter_by_date();
			?>
			<div class="date-filter " id="date_filter" style="display:none">
			
			<label for="from_date">
				<p> <?php esc_html_e( 'From date', 'aquila-features' ); ?>  </p>
				<input class="form-control " id="from_date" type="date" name="start_date" >
			</label>
				
			<label for="">
				<p> <?php esc_html_e( 'End Date', 'aquila-features' ); ?>  </p>
				<input class="form-control "  type="date" name="end_date" >
			</label>
			</div>
			
			
			<?php
			self::filter_by_status();
			self::filter_by_priority();
			self::filter_by_tags();
			self::filter_by_reporter();
			?>
			
			<button class="btn mt-2 filter-submit-btn btn-dark" id="filter_feature" name="filter_feature">
				<?php esc_html_e( 'Submit', 'aquila-features' ); ?>  
			</button>
					
		</form>
		<?php
	}

	/**
	 * Filter by project
	 */
	public static function filter_by_project() {
		$selected_project = '';
		if ( isset( $_GET['filter-feature-nonce'] ) && wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

			$selected_project = isset( $_GET['project_name'] ) ? sanitize_text_field( $_GET['project_name'] ) : '';

		}
		?>
		<p class="filter_head" > <?php esc_html_e( 'Filter by project', 'aquila-features' ); ?>  </p>
		<div class="filter_project">
		<?php
		$project_name = Database::fetch_project_name();
		?>
		<select class="form-select" name="project_name" id="project_name">
			<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
			<?php
			foreach ( $project_name as $name ) {
				?>
				
				<option value="<?php echo esc_html( $name->id ); ?>" <?php echo selected( $name->id, $selected_project, false ); ?>><?php echo esc_html( $name->product_name ); ?></option>
				<?php
			}
			?>
		</select>

		</div>
		<?php
	}

	/**
	 * Add button
	 */
	public static function add_button() {
		?>
		<div class="conatiner row">
			<div class="col-2 mt-2 fs-4 text-center"> <?php esc_html_e( 'All ideas', 'aquila-features' ); ?>  </div>
			<button class="btn col-2 btn-dark text-white" id="add_new"> <?php esc_html_e( 'Add New', 'aquila-features' ); ?>  </button>
		</div>
		
		<?php
	}

	/**
	 * Filter by date
	 */
	public static function filter_by_date() {
		$selected_date = '';
		if ( isset( $_GET['filter-feature-nonce'] ) && wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

			$selected_date = isset( $_GET['filter_project_date'] ) ? sanitize_text_field( $_GET['filter_project_date'] ) : '';
		}
		?>
			<p class="filter_head mt-3" > <?php esc_html_e( 'Filter by Date', 'aquila-features' ); ?>  </p>
			<select class="form-select " name="filter_project_date" id="filter_project_date">
				<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
				<option value="this-month" <?php echo selected( 'this-month', $selected_date, false ); ?> > <?php esc_html_e( 'This month', 'aquila-features' ); ?>  </option>
				<option value="last-month" <?php echo selected( 'last-month', $selected_date, false ); ?>> <?php esc_html_e( 'Last month', 'aquila-features' ); ?>  </option>
				<option value="this-qurter" <?php echo selected( 'this-qurter', $selected_date, false ); ?>> <?php esc_html_e( 'This qurter', 'aquila-features' ); ?>  </option>
				<option value="last-qurter"<?php echo selected( 'last-qurter', $selected_date, false ); ?>> <?php esc_html_e( 'Last qurter', 'aquila-features' ); ?>  </option>
				<option value="this-year" <?php echo selected( 'this-year', $selected_date, false ); ?>> <?php esc_html_e( 'This year', 'aquila-features' ); ?>  </option>
				<option value="last-year" <?php echo selected( 'last-year', $selected_date, false ); ?>> <?php esc_html_e( 'Last year', 'aquila-features' ); ?>  </option>
				<option value="custom" <?php echo selected( 'custom', $selected_date, false ); ?>> <?php esc_html_e( 'Custom', 'aquila-features' ); ?>  </option>

			</select>

		<?php
	}

	/**
	 * Filter by status
	 */
	public static function filter_by_status() {
		$selected_status = '';
		if ( isset( $_GET['filter-feature-nonce'] ) && wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

			$selected_status = isset( $_GET['filter_project_status'] ) ? sanitize_text_field( $_GET['filter_project_status'] ) : '';
		}
		?>
			<p class="filter_head mt-3" > <?php esc_html_e( 'Filter by Status', 'aquila-features' ); ?>  </p>
			<select class="form-select " name="filter_project_status" id="filter_project_status">
				<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Yet to consider' ) ); ?>" <?php echo selected( sanitize_title( 'Yet to consider' ), $selected_status, false ); ?>> <?php esc_html_e( 'Yet To Consider', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Future consideration' ) ); ?>" <?php echo selected( sanitize_title( 'Future Consideration' ), $selected_status, false ); ?>> <?php esc_html_e( 'Future Consideration', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'To Do' ) ); ?>" <?php echo selected( sanitize_title( 'To Do' ), $selected_status, false ); ?>> <?php esc_html_e( 'To Do', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Under Development' ) ); ?>" <?php echo selected( sanitize_title( 'Under Development' ), $selected_status, false ); ?>> <?php esc_html_e( 'Under Development', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Live' ) ); ?>" <?php echo selected( sanitize_title( 'Live' ), $selected_status, false ); ?>> <?php esc_html_e( 'Live', 'aquila-features' ); ?>  </option>
			</select>

		<?php
	}

	/**
	 * Filter by Priority
	 */
	public static function filter_by_priority() {
		$selected_priority = '';
		if ( isset( $_GET['filter-feature-nonce'] ) && wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

			$selected_priority = isset( $_GET['filter_project_priority'] ) ? sanitize_text_field( $_GET['filter_project_priority'] ) : '';
		}
		?>
			<p class="filter_head mt-3" > <?php esc_html_e( 'Filter by Priority', 'aquila-features' ); ?>  </p>
			<select class="form-select " name="filter_project_priority" id="filter_project_priority">
			<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Quick wins' ) ); ?>" <?php echo selected( sanitize_title( 'Quick Wins' ), $selected_priority, false ); ?>> <?php esc_html_e( 'Quick Wins', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'High potential' ) ); ?>" <?php echo selected( sanitize_title( 'High Potential' ), $selected_priority, false ); ?>> <?php esc_html_e( 'High Potential', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Good to have' ) ); ?>" <?php echo selected( sanitize_title( 'Good To Have' ), $selected_priority, false ); ?>> <?php esc_html_e( 'Good To Have', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Must have' ) ); ?>" <?php echo selected( sanitize_title( 'Must Have' ), $selected_priority, false ); ?>> <?php esc_html_e( 'Must Have', 'aquila-features' ); ?>  </option>
				<option value="<?php echo esc_html( sanitize_title( 'Out of scope' ) ); ?>" <?php echo selected( sanitize_title( 'Out Of Scope' ), $selected_priority, false ); ?>> <?php esc_html_e( 'Out Of Scope', 'aquila-features' ); ?>  </option>
			</select>
		<?php
	}

	/**
	 * Filter by tags.
	 */
	public static function filter_by_tags() {

		?>
		<p class="filter_head mt-3" > <?php esc_html_e( 'Filter by Tags', 'aquila-features' ); ?>  </p>
		<?php $tags = Database::take_all_tags(); ?>
		<select class="form-select" name="filter_project_tag" id="filter_project_tag">
			<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
			<?php
			$selected_tag = '';
			if ( isset( $_GET['filter-feature-nonce'] ) && wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

				$selected_tag = isset( $_GET['filter_project_tag'] ) ? sanitize_text_field( $_GET['filter_project_tag'] ) : '';

			}

			foreach ( $tags as $tag ) {
				?>
					<option value="<?php echo esc_html( $tag ); ?>" <?php echo selected( $tag, $selected_tag, false ); ?>><?php echo esc_html( $tag ); ?></option>
					<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Filter by reporter
	 */
	public static function filter_by_reporter() {

		?>
			<p class="filter_head mt-3" > <?php esc_html_e( 'Filter Reporter', 'aquila-features' ); ?>   </p>
			<?php $users = Database::take_user_id(); ?>
			<select class="form-select" name="filter_project_reporter" id="filter_project_reporter">
				<option value=""> <?php esc_html_e( 'View all', 'aquila-features' ); ?>  </option>
				<?php
				$selected_user = '';
				if ( isset( $_GET['filter-feature-nonce'] ) && wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {

					$selected_user = isset( $_GET['filter_project_reporter'] ) ? sanitize_text_field( $_GET['filter_project_reporter'] ) : '';

				}
				foreach ( $users as $user ) {
					$user_data = get_userdata( $user );
					$username  = $user_data->user_login;
					?>
						<option value="<?php echo esc_html( $user ); ?>" <?php echo selected( $user, $selected_user, false ); ?>> <?php echo esc_html( $username ); ?></option>
						<?php
				}
				?>
				</select>
				<?php
	}
}