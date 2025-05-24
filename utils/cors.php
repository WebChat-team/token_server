<?php

    $origin = isset($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : "";

    $allow_domains = [
        "http://id.vision.com",
        "http://api.vision.com"
    ];

    if (in_array($origin, $allow_domains)) {
        header("Access-Control-Allow-Origin: ".$origin);
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");
    } else {
        http_response_code(403);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('HTTP/1.1 200 OK');
        exit;
    }

?>