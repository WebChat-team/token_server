<?php

    // includes ============================================= //
    include_once __DIR__ . "/../tables/tokens.php";

    // main ================================================= //
    function terminate_tokens_to_cookies(int $user_id){

        setcookie(
            "access_token",
            "",
            [
                "expires" => "Thu, 01 Jan 1970 00:00:00 GMT",
                "path" => "/",
                "httponly" => true,
                // "domain" => ".vision.com",
                "samesite" => "Lax"
            ]
        );
        setcookie(
            "refresh_token",
            "",
            [
                "expires" => "Thu, 01 Jan 1970 00:00:00 GMT",
                "path" => "/",
                "httponly" => true,
                // "domain" => ".vision.com",
                "samesite" => "Lax"
            ]
        );

        deleteTokenByUserId($user_id);

    }

?>