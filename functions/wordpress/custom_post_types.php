<?php

	// http://codex.wordpress.org/Function_Reference/register_post_type

function register_custom_post_types() {



	// Duplicate this for each CPT.

	$labels = array(
		'name' => _x('Time Lapse', 'post type general name'),
		'singular_name' => _x('Time Lapse', 'post type singular name'),
		'add_new' => _x('Add New', 'Time Lapse'),
		'add_new_item' => __('Add New Time Lapse'),
		'edit_item' => __('Edit Time Lapse'),
		'new_item' => __('New Time Lapse'),
		'view_item' => __('View Time Lapse'),
		'search_items' => __('Search Time Lapse'),
		'not_found' =>  __('No Time Lapse Items found'),
		'not_found_in_trash' => __('No Time Lapse Items found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Time Lapse'

	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'time-lapse'),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => 21,
		'supports' => array('title', 'page-attributes')
	);

	register_post_type('time-lapse', $args);

}
add_action( 'init', 'register_custom_post_types' );