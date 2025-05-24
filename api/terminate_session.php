<?php

    // cors ================================================= //
    include_once __DIR__."/../utils/cors.php";  

    // includes ============================================= //
    include_once __DIR__."/../shared/reset_tokens_to_cookies.php";
    include_once __DIR__."/../tables/users.php";
    include_once __DIR__."/../utils/jwt.php";

    // main ================================================= //
    try {

        $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (preg_match("/^Bearer (.+)$/", $auth_header, $matches_auth_header)) {

            $token = urldecode($matches_auth_header[1]);

            if (verifyJWT($token)) {
                $user_data = parseJWT($token);
                if (hasUserById($user_data->user_id)) {
                    terminate_tokens_to_cookies($user_data->user_id);
                    http_response_code(200);
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(401);
            }

        }

    } catch (Exception $error) {
        http_response_code(500);
        exit;
    }

?>