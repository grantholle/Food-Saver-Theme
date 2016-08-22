<?php

function handle_contest_submission () {

  $input['input_1'] = $_POST['email'];

  $result = GFAPI::submit_form(1, $input);

  wp_send_json($result);
}

add_filter('wp_ajax_contest_submission', 'handle_contest_submission');
add_filter('wp_ajax_nopriv_contest_submission', 'handle_contest_submission');