<?php

//---------------------------------
// Required
//---------------------------------

// Clean up WP Header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

// Add Wordpress Feature Support
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

// For ACF options page
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page();
}

// Get a cleaner template name for pages - use as ID or class in your template containers
// Called on <body> in header.php
function get_template_name() {
	if (is_page()) {
		global $post;
		return str_replace('.php', '', get_post_meta($post->ID, '_wp_page_template', true));
	}
	return '';
}

// Add Wysiwyg styles to the Wordpress editor.
function wysiwyg_editor_styles() {
	add_editor_style( 'style-wysiwyg.css' );
}
add_action( 'admin_init', 'wysiwyg_editor_styles' );

// This function gets the path to a partial based on keeping the /partials/ standard
// Allows for use of dot notation, i.e. get_partial_path(sub-dir.component) = theme/partials/sub-dir/component.php
function get_partial_path($name, $extension = 'php', $partials_dir = '/partials/') {
	$name = str_replace('.', '/', $name);

	// Quality checks for paths n stuff
	if ($extension[0] === '.')
		$extension = substr($extension, 1);

	if ($partials[0] !== '/')
		$partials = '/' . $partials;

	if ($partials[strlen($partials) - 1] !== '/')
		$partials = $partials . '/';

	return get_template_directory() . $partials_dir . $name . '.' . $extension;
}

function get_svg($name) {
	return file_get_contents(get_template_directory() . '/assets/images/' . $name . '.svg');
}

function get_image_path($name) {
	return get_bloginfo('template_directory') . '/assets/images/' . $name;
}

//---------------------------------
// Gets the ID of the current live video
//---------------------------------
function get_current_live_id($camera) {
	global $wpdb;

	$query = "SELECT wp_posts.ID FROM wp_posts INNER JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id) INNER JOIN wp_postmeta AS mt1 ON (wp_posts.ID = mt1.post_id) INNER JOIN wp_postmeta AS mt2 ON (wp_posts.ID = mt2.post_id) INNER JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id ) WHERE 1=1 AND (wp_postmeta.meta_key = 'start_date' AND ((mt1.meta_key = 'live' AND CAST(mt1.meta_value AS CHAR) NOT IN ('')) AND (mt2.meta_key = 'end_date' AND CAST(mt2.meta_value AS CHAR) IN ('')) AND (mt3.meta_key = 'camera' AND CAST(mt3.meta_value AS CHAR) = '$camera'))) AND wp_posts.post_type = 'time-lapse' AND ((wp_posts.post_status = 'publish')) GROUP BY wp_posts.ID ORDER BY CAST(wp_postmeta.meta_value AS DATE) ASC LIMIT 0, 1";

	return $wpdb->get_var($query);
}

//---------------------------------
// Optional
//---------------------------------

// Hide WP Admin Bar
//add_filter( 'show_admin_bar', '__return_false' );

// Add custom photo sizes - remember that this creates extra files for every upload
if (function_exists('add_image_size')) {
	add_image_size('time-lapse', 780, 438, true);
}

// Add responsive container to embed videos
// function forty_responsive_video( $html ) {
// 	//add http protocol
//     $html = str_replace('<iframe src="//', '<iframe src="http://', $html);
//     return '<div class="flex-video">' . $html . '</div>';
// }
// add_filter( 'embed_oembed_html', 'forty_responsive_video', 10, 3 );
// add_filter( 'video_embed_html', 'forty_responsive_video' );


// Returns timthumbified URL
// function forty_timthumbify($src){
// 	global $blog_id;
// 	if(is_multisite()){
// 		$blog_upload_dir = get_site_url(1).'/wp-content/blogs.dir/'.$blog_id;
// 		$src = get_site_url(1).'/functions/timthumb/timthumb.php?src='.$blog_upload_dir.strstr($src, '/files/');
// 	}else
// 		$src = get_stylesheet_directory_uri() . '/functions/timthumb/timthumb.php?src=' . $src;

// 	return $src;
// }
