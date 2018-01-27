<?php
function rnz_pal_post_type() {

	$labels = array(
		'name'                => _x( 'Alternative Links', 'Post Type General Name', 'Alternative Link' ),
		'singular_name'       => _x( 'Alternative Link', 'Post Type Singular Name', 'Alternative Link' ),
		'menu_name'           => __( 'Alternative Links', 'Alternative Link' ),
		'parent_item_colon'   => __( 'Parent Alternative Link', 'Alternative Link' ),
		'all_items'           => __( 'All Alternative Links', 'Alternative Link' ),
		'view_item'           => __( 'View Alternative Link', 'Alternative Link' ),
		'add_new_item'        => __( 'Add New Alternative Link', 'Alternative Link' ),
		'add_new'             => __( 'Add New', 'Alternative Link' ),
		'edit_item'           => __( 'Edit Alternative Link', 'Alternative Link' ),
		'update_item'         => __( 'Update Alternative Link', 'Alternative Link' ),
		'search_items'        => __( 'Search Alternative Link', 'Alternative Link' ),
		'not_found'           => __( 'Not Found', 'Alternative Link' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'Alternative Link' ),
	);
	
	
	$args = array(
		'label'               => __( 'Auto Alternative Links', 'Alternative Link' ),
		'description'         => __( 'Auto Alternative Link', 'Alternative Link' ),
		'labels'              => $labels,
		'supports'            => array('title'),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'       	  => 'dashicons-admin-links',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',

	);
	
	register_post_type( 'rnz_pal', $args );

}

add_action( 'init', 'rnz_pal_post_type', 0 );
?>