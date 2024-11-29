<?php

    include_once __DIR__."/../utils/getConnectionToDataBase.php";

    $connection = getConnectionToDataBase();

    function hasUserById(string $id) {
        global $connection;
        $findUserById = $connection->prepare("SELECT * FROM users WHERE id = ?");
        $findUserById->execute([$id]);
        $users = $findUserById->fetchAll();
        return count($users) !== 0;
    }
    
?>