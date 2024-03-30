<?php

add_action('rest_api_init', 'register_rest_routes', 1);

function register_rest_routes()
{
    register_rest_route('api/v1', '/generate-ics', array(
        'methods' => 'GET',
        'callback' => 'generateICS',
        'permission_callback' => '__return_true',
    ));
}