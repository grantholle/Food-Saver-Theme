<?php

function enqueue_custom_styles() {

	// Add main stylesheet
	wp_register_style('style.css', (get_stylesheet_directory_uri() . '/style.css'), false, false, 'all');
	wp_enqueue_style('style.css');
}

function enqueue_custom_scripts() {

	// Add modernizr to the header
	wp_register_script('modernizr', (get_stylesheet_directory_uri() . '/assets/js/vendor/modernizr.js'), false, '2.8.3', false);
	wp_enqueue_script('modernizr');

	// Remove default jQuery and add Google hosted version to the footer
	// Are you building a site that requires IE8? Use: //ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js
	wp_deregister_script('jquery');
	wp_register_script('jquery', (get_stylesheet_directory_uri() . '/assets/js/vendor/jquery.js'), false, '2.1.3', true);
	wp_enqueue_script('jquery');

	// Load the site's main.js file but make sure jQuery is there first
	wp_register_script('site-main', (get_stylesheet_directory_uri() . '/assets/js/main.js'), array('jquery'), '1.0.0', true);
	wp_enqueue_script('site-main');

}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_styles' );
add_action( 'wp_enqueue_scripts', 'enqueue_custom_scripts' );