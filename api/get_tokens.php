<?php

    // cors ================================================= //
    include_once __DIR__."/../utils/cors.php";  

    // includes ============================================= //
    include_once __DIR__."/../shared/generate_and_set_tokens_to_cookies.php";
    include_once __DIR__."/../tables/users.php";

    // main ================================================= //
    try {

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        if (isset($data->userId) and hasUserById($data->userId)) {
            generate_and_set_tokens_to_cookies($data->userId);
            http_response_code(200);
        } else {
            http_response_code(401);
        }

    } catch (Exception $error) {
        http_response_code(500);
        exit;
    }

?>