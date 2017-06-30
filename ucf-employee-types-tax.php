<?php
/*
Plugin Name: UCF Employee Types Taxonomy
Version: 1.0.0
Author: UCF Web Communications
Description: Provides a "Employee Types" taxonomy.
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'UCF_EMPLOYEE_TYPES__FILE', __FILE__ );

include_once 'includes/ucf-employee-types-taxonomy.php';

if ( ! function_exists( 'ucf_employee_types_activation' ) ) {
	function ucf_employee_types_activation() {
		UCF_Employee_Types_Taxonomy::register();
		flush_rewrite_rules();
	}

	register_activation_hook( 'ucf_employee_types_activation', UCF_EMPLOYEE_TYPES__FILE );
}

if ( ! function_exists( 'ucf_employee_types_deactivation' ) ) {
	function ucf_employee_types_deactivation() {
		flush_rewrite_rules();
	}

	register_deactivation_hook( 'ucf_employee_types_deactivation', UCF_EMPLOYEE_TYPES__FILE );
}

// Run base actions inside of 'plugins_loaded' hook so we
// can check for the existence of other post_types and taxonomies
if ( ! function_exists( 'ucf_employee_types_init' ) ) {
	function ucf_employee_types_init() {
		// Register custom taxonomy
		add_action( 'init', array( 'UCF_Employee_Types_Taxonomy', 'register' ), 10, 0 );
	}

	add_action( 'plugins_loaded', 'ucf_employee_types_init' );
}
