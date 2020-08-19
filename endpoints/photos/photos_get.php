<?php

function api_photos_get($request){

    $_total =sanitize_text_field($request['_total']) ? : 6;
    $_page =sanitize_text_field($request['_page']) ? : 1;
    $user =sanitize_text_field($request['_user']) ? : 0;

    if(!is_numeric($user)) {
        $user = get_user_by('login' , $_user);
        if(!$user){
            $response = new WP_Error('error', 'Usuário não encontrado', ['status' => 404]);
            return rest_ensure_response($response);
        }
        $_user = $user->ID;
    };

    $args = [
        'post_type' => 'post',
        'author' => $_user,
        'posts_per_page' => $_total,
        'paged' => $_page,
    ];

    $query = new WP_Query($args);
    $posts = $query->posts;

    $photos = [];
    if($posts){
        foreach($posts as $post){
            $photos[] = photo_data($post);
        }
    };

    return rest_ensure_response($photos);
}

function register_api_photos_get(){
    register_rest_route('api', '/photo',[
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'api_photos_get',
    ]);
}

add_action('rest_api_init', 'register_api_photos_get');

?>