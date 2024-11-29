<?php

    require_once __DIR__."/./parseDotenv.php";

    // helpers ============================================== //
    function base64url_encode($data) {
        return str_replace(["+", "_", "="], ["-", "/", ""], base64_encode($data));
    }
    function base64url_decode($data) {
        return base64_decode(str_replace(["+", "_", "="], ["-", "/", ""], $data));
    }

    // constants ============================================ //
    $envs = parseDotenv(__DIR__."/../.env.local", false);
    
    // main ================================================= //
    function generateJWT(array $data, array $options = [ "expiresIn" => 60 * 60 * 24 ]) {

        try {

            if (!is_array($data)) {
                throw new Exception("Данные для генерации JWT токена должны быть в формате JSON");
            }

            $header = json_encode([
                "type" => "JWT",
                "alg" => "HS256",
                "exp" => time() + (int) $options["expiresIn"]
            ]);
            $base64UrlHeader = base64url_encode($header);
    
            $payload = json_encode($data);
            $base64UrlPayload = base64url_encode($payload);
    
            global $envs;
            $signature = hash_hmac("sha256", $base64UrlHeader.".".$base64UrlPayload, $envs["JWT_SECRET"], true);
            $base64UrlSignature = base64url_encode($signature);
    
            return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;

        } catch (Exception $error) {
            print_r(
                "Неудачное создание JWT токена \n".
                "Ошибка: ".$error->getMessage()
            );
            return null;
        }

    }

    function verifyJWT(string $jwt) {

        try {

            $parts_jwt = explode(".", $jwt);
            if (count($parts_jwt) !== 3) {
                throw new Exception("Отсутсвуют определённые части токена");
            }

            [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts_jwt;

            $header = json_decode(base64url_decode($base64UrlHeader));
            $isExpired = $header->exp < time();

            global $envs;
            $calculatedSignature = hash_hmac("sha256", $base64UrlHeader.".".$base64UrlPayload, $envs["JWT_SECRET"], true);
            $calculatedBase64UrlSignature = base64url_encode($calculatedSignature);

            return (
                base64url_decode($base64UrlHeader) and
                base64url_decode($base64UrlPayload) and
                base64url_decode($base64UrlSignature) and
                !$isExpired and
                hash_equals($calculatedBase64UrlSignature, $base64UrlSignature)
            );

        } catch (Exception $error) {
            return false;
        }

    }

    function parseJWT(string $jwt) {

        try {

            if (!verifyJWT($jwt)) {
                throw new Exception(
                    "Токен не является валидным \n".
                    "Токен: ".$jwt."\n"
                );
            }

            global $envs;
            $base64UrlPayload = explode(".", $jwt)[1];
            $payload = json_decode(base64url_decode($base64UrlPayload));

            return $payload;

        } catch (Exception $error) {
            print_r(
                "Неудачный парсинг JWT токена \n".
                "Ошибка: ".$error->getMessage()
            );
            return "null";
        }

    }

?>