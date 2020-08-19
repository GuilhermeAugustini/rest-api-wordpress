<?php

function api_photos_get($request){

    $response = "teste";

    return rest_ensure_response($response);
}

function register_api_photos_get(){
    register_rest_route('api', '/photo',[
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'api_photos_get',
    ]);
}

add_action('rest_api_init', 'register_api_photos_get');

?>