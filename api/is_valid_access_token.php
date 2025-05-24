<?php

    // cors ================================================= //
    include_once __DIR__."/../utils/cors.php";  

    // includes ============================================= //
    include_once __DIR__."/../utils/jwt.php";

    // main ================================================= //
    try {

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if (isset($data->access_token)) {

            if (verifyJWT($data->access_token)) {
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