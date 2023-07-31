<?php
/**
 * @package    Feature
 */

/**
 * Plugin Name: Feature
 * Description: A plugin to add feature of products
 * Version: 1.0.0
 * Author: Niranjan C K
 * Text Domain: feature
 */
require __DIR__ . '/includes/init.php';
defined( 'ABSPATH' ) || die( 'not working' );

/**
 * Create constant value of base file.
 */
if ( ! defined( 'PRODUCT_BASEFILE' ) ) {
	define( 'PRODUCT_BASEFILE', plugin_dir_url( __FILE__ ) );
}

/**
 * Activation hooks
 */
register_activation_hook( __FILE__, array( 'Activation', 'activate' ) );

/**
 * Deactivation hooks
 */
register_deactivation_hook( __FILE__, array( 'Activation', 'deactivate' ) );

/**
 * Add admin dashboard
 */
add_action( 'admin_menu', array( 'Addashboard', 'add_dashboard' ) );

/**
 * Enqueue scripts
 */
add_action( 'admin_enqueue_scripts', array( 'Activation', 'enqueue_script' ) );
add_action( 'wp_enqueue_scripts', array( 'Activation', 'enqueue_script' ) );


/**
 * Add feature page
 */
add_action( 'init', array( 'Addashboard', 'add_product' ) );


/**
 * Add shortcode
 */
add_shortcode( 'feature_form', array( 'Shortcodes', 'feature_form' ) );


/**
 * Add ajax filters
 */


add_action( 'wp_ajax_back_to_home', array( 'ajax', 'back_to_home_ajax_handler' ) );
add_action( 'wp_ajax_add_new_idea', array( 'ajax', 'add_new_idea_ajax_handler' ) );
// Edit product using ajax.
add_action( 'wp_ajax_edit_feature_idea', array( 'ajax', 'edit_feature_idea_ajax_handler' ) );

// Add vote ajax.
add_action( 'wp_ajax_feature_vote', array( 'ajax', 'feature_vote_ajax_handler' ) );
add_action( 'wp_ajax_feature_unvote', array( 'ajax', 'feature_unvote_ajax_handler' ) );
add_action( 'wp_ajax_vote_by_rerference', array( 'Form', 'vote_by_rerference_ajax_handler' ) );

add_action( 'wp_ajax_delete_image', array( 'ajax', 'delete_image_ajax_handler' ) );



/**
 * Forms
 */
add_action( 'init', array( 'Form', 'add_new_idea_form' ) );
add_action( 'init', array( 'Form', 'edit_idea_form' ) );
