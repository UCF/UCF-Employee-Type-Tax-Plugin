<?php
/**
 * Responsible for registering the taxonomy
 **/
if ( ! class_exists( 'UCF_Employee_Types_Taxonomy' ) ) {
	class UCF_Employee_Types_Taxonomy {
		/**
		 * Registers the Employee Type custom taxonomy
		 * @author RJ Bruneel
		 * @since 1.0.0
		 **/
		public static function register() {
			$labels = apply_filters(
				'ucf_employee_types_labels',
				array(
					'singular' => 'Employee Type',
					'plural'   => 'Employee Types',
					'slug'     => 'employee_types'
				)
			);

			$post_types = array( 'person' );;

			register_taxonomy( 'employee_types', $post_types, self::args( $labels ) );
		}

		/**
		 * Returns an array of labels for the custom taxonomy.
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $singular string | The singular form for the CPT labels.
		 * @param $plural string | The plural form for the CPT labels.
		 * @return Array
		 **/
		public static function labels( $singular, $plural ) {
			return array(
				'name'                       => _x( $plural, 'Taxonomy General Name', 'ucf_employee_types' ),
				'singular_name'              => _x( $singular, 'Taxonomy Singular Name', 'ucf_employee_types' ),
				'menu_name'                  => __( $plural, 'ucf_employee_types' ),
				'all_items'                  => __( 'All ' . $plural, 'ucf_employee_types' ),
				'parent_item'                => __( 'Parent ' . $singular, 'ucf_employee_types' ),
				'parent_item_colon'          => __( 'Parent :' . $singular, 'ucf_employee_types' ),
				'new_item_name'              => __( 'New ' . $singular . ' Name', 'ucf_employee_types' ),
				'add_new_item'               => __( 'Add New ' . $singular, 'ucf_employee_types' ),
				'edit_item'                  => __( 'Edit ' . $singular, 'ucf_employee_types' ),
				'update_item'                => __( 'Update ' . $singular, 'ucf_employee_types' ),
				'view_item'                  => __( 'View ' . $singular, 'ucf_employee_types' ),
				'separate_items_with_commas' => __( 'Separate ' . strtolower( $plural ) . ' with commas', 'ucf_employee_types' ),
				'add_or_remove_items'        => __( 'Add or remove ' . strtolower( $plural ), 'ucf_employee_types' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'ucf_employee_types' ),
				'popular_items'              => __( 'Popular ' . strtolower( $plural ), 'ucf_employee_types' ),
				'search_items'               => __( 'Search ' . $plural, 'ucf_employee_types' ),
				'not_found'                  => __( 'Not Found', 'ucf_employee_types' ),
				'no_terms'                   => __( 'No items', 'ucf_employee_types' ),
				'items_list'                 => __( $plural . ' list', 'ucf_employee_types' ),
				'items_list_navigation'      => __( $plural . ' list navigation', 'ucf_employee_types' ),
			);
		}

		public static function args( $labels ) {
			$singular = $labels['singular'];
			$plural   = $labels['plural'];
			$slug     = $labels['slug'];

			$args = array(
				'labels'                     => self::labels( $singular, $plural ),
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'rewrite'                    => array(
					'slug'         => $slug,
					'hierarchical' => true,
					'ep_mask'      => EP_PERMALINK | EP_PAGES
				)
			);

			$args = apply_filters( 'ucf_employee_types_args', $args );

			return $args;
		}
	}
}
