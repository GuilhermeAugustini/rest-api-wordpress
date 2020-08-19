<?php

function api_photo_post($request){

    $user = wp_get_current_user();
    $user_id = $user->ID;

    if($user_id === 0){
        $response = new WP_Error('error', 'Usuário não possui permissão', ['status' => 401]);
        return rest_ensure_response($response);
    }

    $nome = saniite_text_field($request['nome']);
    $pai = saniite_text_field($request['pai']);
    $filho = saniite_text_field($request['filho']);
    $files = $request->get_file_params();

    if(empty($nome) || empty($pai) || empty($filho) || empty($files)){
        $response = new WP_Error('error', 'Dados incompletos', ['status' => 422]);
        return rest_ensure_response($response);
    }

    $response = [
        'post_author' => $user_id,
        'post-type' => 'post',
        'post_status' => 'publish',
        'post-title' => $nome,
        'post_content' => $nome,
        'meta_input' => [
            'pai' => $peso,
            'filho' => $idade,
            'acessos' => 0,
        ],
    ];

    $post_id = wp_insert_post($response);

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $photo_id = media_handle_upload( 'img', $post_id);
    update_post_meta($post_id , 'img', '$photo_id'); 

    return rest_ensure_response($response);
}

function register_api_photo_post(){
    register_rest_route('api', '/photo',[
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'api_photo_post',
    ]);
}

add_action('rest_api_init', 'register_api_photo_post');

?>