<?php
/**
 * @package    Feature
 */

/**
 * Shortcodes
 */
class Shortcodes {

	/**
	 * Feature form
	 */
	public static function feature_form() {
		if ( is_user_logged_in() ) {

			?>


			<div class="conatainer" style="position:fixed" >
				<h1 class="bg-white p-4 ideas_portal" >Ideas portal</h1>
				<div class=" main-conatiner" style="width:100%;height:100vh; background-color:#f3f7fb; margin-bottom:40px;" >
					<div class="col-3 bg-white filter-section p-5" id="left_side">
						<?php Filter::filter_section(); ?>
					</div>
					<div class="col-9 p-4 content-section " style="background-color:#f3f7fb" id= "content-area">
						<?php Filter::add_button(); ?>

						<?php
						if ( ! isset( $_GET['filter-feature-nonce'] ) || ! wp_verify_nonce( $_GET['filter-feature-nonce'], 'filter-feature' ) ) {
								Feature::feature_list();
						} elseif ( isset( $_GET['filter_feature'] ) ) {

								Form::filter_feature();
						}
						?>
						
					</div>
				</div>
			</div>
			<?php
		}
	}
}