<?php
/**
 * @package    Feature
 */

/**
 * Activation hooks
 */
class Activation {

	/**
	 * Plugin activation
	 *
	 * @return void
	 */
	public static function activate() {
		$feature_page = array(
			'post_title'   => 'Feature',
			'post_content' => '[feature_form]',
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_name'    => 'feature',
			'post_slug'    => 'feature',
			'post_type'    => 'page',
		);
		wp_insert_post( $feature_page );
		flush_rewrite_rules();

		Database::create_table();
		Database::create_vote_table();
	}

	/**
	 * Plugin deactivation
	 *
	 * @return void
	 */
	public static function deactivate() {

		$page = get_page_by_path( 'feature' );
		wp_delete_post( $page->ID );
		flush_rewrite_rules();
	}

	/**
	 * Enqueue script
	 */
	public static function enqueue_script() {
		wp_enqueue_script( 'products-jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'products-script', PRODUCT_BASEFILE . 'assets/script.js', array( 'wp-i18n' ), '1.0.0', true );
		wp_enqueue_style( 'products-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '1.0.0' );
		wp_enqueue_style( 'products-style', PRODUCT_BASEFILE . 'assets/style.css', array(), '1.0.0' );

		wp_localize_script(
			'products-jquery',
			'wp_feature_select',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonces'   => array(
					'wt_ddfw_nonce' => wp_create_nonce( 'products-jquery' ),
				),

			)
		);
	}
}
