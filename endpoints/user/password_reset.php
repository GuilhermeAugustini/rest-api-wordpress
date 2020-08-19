<?php

function api_password_reset($request){

    $login = $request['login'];
    $password = $request['password'];
    $key = $request['key'];
    $user = get_user_by('login', $login);

    if(empty($user)){
        $response = new WP_Error('error', 'Usuário não existe', ['status' => 401]);
        return rest_ensure_response($response);
    };

    $check_key = check_password_reset_key($key, $login);

    if(is_wp_error($check_key)){
        $response = new WP_Error('error', 'Token expirado', ['status' => 401]);
        return rest_ensure_response($response);
    };

    reset_password($user, $password);

    return rest_ensure_response("Senha alterada");
}

function register_api_password_reset(){
    register_rest_route('api', '/password/reset',[
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'api_password_reset',
    ]);
}

add_action('rest_api_init', 'register_api_password_reset');

?>