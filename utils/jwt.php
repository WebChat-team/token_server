<?php

    // requires ============================================ // 
    require_once __DIR__."/./parseDotenv.php";

    // helpers ============================================== //
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
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
                "alg" => "HS256"
            ]);
            $base64UrlHeader = base64url_encode($header);
    
            $payload = json_encode($data + [ "exp" => time() + (int) $options["expiresIn"] ]);
            $base64UrlPayload = base64url_encode($payload);
    
            global $envs;
            $signature = hash_hmac("sha256", $base64UrlHeader.".".$base64UrlPayload, $envs["JWT_SECRET"], true);
            $base64UrlSignature = base64url_encode($signature);
    
            return $base64UrlHeader.".".$base64UrlPayload.".".$base64UrlSignature;

        } catch (Exception $error) {
            return null;
        }

    }

    function verifyJWT(string $jwt) {
        
        try {

            $parts = explode(".", $jwt);
            if (count($parts) !== 3) { 
                throw new Exception("Invalid JWT structure");
            }

            [$headerBase64, $payloadBase64, $signatureBase64] = $parts;

            $header = json_decode(base64url_decode($headerBase64));
            $payload = json_decode(base64url_decode($payloadBase64));
            if (!isset($payload->exp) || $payload->exp < time()) {
                throw new Exception("Token expired");
            }
                        
            global $envs;
            $calculatedSignature = base64url_encode(hash_hmac("sha256", $headerBase64.".".$payloadBase64, $envs["JWT_SECRET"], true));

            return hash_equals($calculatedSignature, $signatureBase64);

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
            [$_, $base64UrlPayload, $_] = explode(".", $jwt);
            $payload = json_decode(base64url_decode($base64UrlPayload));

            return $payload;

        } catch (Exception $error) {
            return "null";
        }

    }

?>