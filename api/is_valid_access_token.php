<?php

    // cors ================================================= //
    include_once __DIR__."/../utils/cors.php";  

    // includes ============================================= //
    include_once __DIR__."/../utils/jwt.php";

    // main ================================================= //
    try {

        $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (preg_match("/^Bearer (.+)$/", $auth_header, $matches_auth_header)) {

            $access_token = $matches_auth_header[1];

            if (verifyJWT(urldecode($access_token))) {
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