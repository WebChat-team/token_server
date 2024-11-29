<?php

    // cors ================================================= //
    include_once __DIR__."/../utils/cors.php";  

    // includes ============================================= //
    include_once __DIR__."/../utils/jwt.php";
    include_once __DIR__."/../shared/generate_and_set_tokens_to_cookies.php";

    // main ================================================= //
    try {

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if (isset($data->refresh_token)) {

            if (verifyJWT(urldecode(string: $data->refresh_token))) {
                $user_data = parseJWT(urldecode($data->refresh_token));
                generate_and_set_tokens_to_cookies($user_data->user_id);
                http_response_code(200);
            } else {
                http_response_code(403);
            }

        } else {
            http_response_code(401);
        }

    } catch (Exception $error) {
        http_response_code(500);
        exit;
    }

?>