<?php

// Environment Management (Required)
include_once('functions/environment.php');

// Enqueue Scripts (Required)
include_once('functions/wordpress/enqueue_scripts.php');

// Functions (Required)
include_once('functions/wordpress/custom_post_types.php');
include_once('functions/wordpress/custom_taxonomies.php');
include_once('functions/wordpress/custom_sidebars.php');

// Functions (Utility)
include_once('functions/wordpress/utility.php');

// Add CPT icons and seperators to the admin menu
include_once('functions/wordpress/admin_menu.php');

// Handles contest entry
include_once('functions/site/contest-ajax.php');

// Handles getting the prep images for the time lapses
include_once('functions/site/prep-images-ajax.php');
