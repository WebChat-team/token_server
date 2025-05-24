<?php

    include_once __DIR__."/../utils/getConnectionToDataBase.php";

    $connection = getConnectionToDataBase();

    function hasTokenByUserId(int $user_id) {
        global $connection;
        $findToken = $connection->prepare("SELECT * FROM tokens WHERE user_id = ?");
        $findToken->execute([$user_id]);
        $tokens = $findToken->fetchAll();
        return count($tokens) !== 0;
    }

    function setTokenBy(int $user_id, string $token) {
        global $connection;
        if (hasTokenByUserId($user_id)) {
            $updateToken = $connection->prepare("UPDATE tokens SET token = ? WHERE user_id = ?");
            $updateToken->execute([$token, $user_id]);
        } else {
            $setToken = $connection->prepare("INSERT INTO tokens (user_id, token) VALUES (?, ?)");
            $setToken->execute([$user_id, $token]);
        }
    }

    function deleteTokenByUserId(int $user_id) {
        global $connection;
        $deleteToken = $connection->prepare("DELETE FROM tokens WHERE user_id = ?");
        $deleteToken->execute([$user_id]);
    }

?>