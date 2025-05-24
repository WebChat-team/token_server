<?php

include_once __DIR__."/./parseDotenv.php";

function getConnectionToDataBase() {

    try {

        $envs = parseDotenv(__DIR__."/../.env.local", false);

        $host = $envs["DATABASE_HOST"];
        $port = $envs["DATABASE_PORT"];
        $name = $envs["DATABASE_NAME"];
        $username = $envs["DATABASE_USERNAME"];
        $password = $envs["DATABASE_PASSWORD"];

        return new PDO(
            "mysql:host=".$host.":".$port.";dbname=".$name,
            $username,
            $password
        );

    } catch (Exception $error) {

        print_r(
            "Неудачное подключение к базе данных \n".
            "Ошибка: ".$error->getMessage()
        );
        return null;

    }

}

?>