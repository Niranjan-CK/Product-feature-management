<?php
/**
 * @package    Feature
 */

/**
 * For add admin dashboard
 */
class Addashboard {

	/**
	 * Add admin dashboard
	 *
	 * @return void
	 */
	public static function add_dashboard() {
		add_menu_page(
			'Feature',
			'Feature',
			'manage_options',
			'feature',
			array( 'Addashboard', 'feature_page' ),
			'dashicons-admin-generic',
			6
		);
	}

	/**
	 * Add feature page
	 *
	 * @return void
	 */
	public static function feature_page() {
		?>
		<h1><?php esc_html_e( 'Add Products', 'aquila-features' ); ?></h1>

		<form method="post">
			<?php wp_nonce_field( 'add_product', 'add_product_nonce' ); ?>
			<table>
				<tr>
					<td> <?php esc_html_e( 'Product Name', 'aquila-features' ); ?> </td>
					<td><input type="text" name="product_name" id="product_name"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="add_product" id="add_product" value="Add Product"></td>
				</tr>
			</table>
		</form>
		<?php
	}

	/**
	 * Function for add product
	 */
	public static function add_product() {
		if ( isset( $_POST['add_product'] ) ) {
			if ( ! isset( $_POST['add_product_nonce'] ) || ! wp_verify_nonce( $_POST['add_product_nonce'], 'add_product' ) ) {
				wp_die( 'Security check' );
			}
			$product_name = isset( $_POST['product_name'] ) ? sanitize_text_field( $_POST['product_name'] ) : false;
			$product_slug = sanitize_title( $product_name );
			global $wpdb;
			$data            = array(
				'product_name' => $product_name,
				'product_slug' => $product_slug,
			);
			$table_name      = $wpdb->prefix . 'products';
			$charset_collate = $wpdb->get_charset_collate();
			$query           = $wpdb->query( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );

			if ( 1 !== $query ) {
				$sql = "CREATE TABLE $table_name (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    product_name varchar(255) NOT NULL,
                    product_slug varchar(255) NOT NULL,
                    PRIMARY KEY  (id)
                ) $charset_collate;";
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta( $sql );
				$wpdb->insert( $table_name, $data );
				echo "<script>alert( wp.i18n.__( 'Product added successfully' ) );</script>";

			} else {
				$wpdb->insert( $table_name, $data );
				echo "<script>alert( wp.i18n.__( 'Product added successfully' ) );</script>";
			}
		}
	}
}
