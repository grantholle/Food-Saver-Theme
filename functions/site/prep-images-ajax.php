<?php

function get_prep_images () {

  $id = $_POST['id'];

  wp_send_json(get_field('food_preparation_pictures', $id));
}

add_filter('wp_ajax_get_prep_images', 'get_prep_images');
add_filter('wp_ajax_nopriv_get_prep_images', 'get_prep_images');