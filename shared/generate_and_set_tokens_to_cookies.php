<?php

    // includes ============================================= //
    include_once __DIR__ . "/../utils/jwt.php";
    include_once __DIR__ . "/../tables/tokens.php";
    include_once __DIR__ . "/../shared/write_log.php";

    // main ================================================= //
    function generate_and_set_tokens_to_cookies(int $user_id){

        $expiresInAccessToken = 60 * 60; // 1 hour
        $access_token = generateJWT(["user_id" => $user_id], ["expiresIn" => $expiresInAccessToken]);

        setcookie(
            "access_token",
            $access_token,
            [
                "expires" => time() + $expiresInAccessToken,
                "httponly" => true,
                "domain" => "158.160.168.65",
                "samesite" => "Lax"
            ]
        );


        
        $expiresInRefreshToken = 60 * 60 * 24 * 7; // 7 days
        $refresh_token = generateJWT(["user_id" => $user_id], ["expiresIn" => $expiresInRefreshToken]);

        setTokenBy($user_id, $refresh_token);

        setcookie(
            "refresh_token",
            $refresh_token,
            [
                "expires" => time() + $expiresInRefreshToken,
                "httponly" => true,
                "domain" => "158.160.168.65",
                "samesite" => "Lax"
            ]
        );

    }

?>