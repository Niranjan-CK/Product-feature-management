<?php
/**
 * @package    Feature
 *
 * init file
 */

spl_autoload_register(
	function( $class_name ) {
		$class_name = strtolower( $class_name );

		if ( file_exists( dirname( __DIR__ ) . "/class/class-{$class_name}.php" ) || file_exists( dirname( __DIR__ ) . "/includes/class-{$class_name}.php" ) ) {
				require dirname( __DIR__ ) . "/class/class-{$class_name}.php";
		}
	}
);
