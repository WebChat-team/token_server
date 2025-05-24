<?php

    function parseDotenv(string $path, bool $isAllView = true) {

        try {

            $parts_path = preg_split("/\//", $path);
            if (!str_starts_with($parts_path[count($parts_path) - 1], ".env")) {
                throw new Exception("Расширение файла не является .env");
            }

            if (!is_file($path)) {
                throw new Exception("Файл не найден. Путь: ".$path);
            }

            $text_env_file = file_get_contents($path);
            $rows = explode("\n", $text_env_file);
            $new_envs = array();

            foreach ($rows as $row => $line) {
                if (preg_match("/(\s+)?[^=\s]+(\s+)?=(\s+)?[^=\s]+(\s+)?$/", $line)) {
                    $trimed_line = trim($line);
                    [$key, $value] = explode("=", $trimed_line);
                    $new_envs[trim($key, " \n\r\t\v\0")] = trim($value, " \n\r\t\v\0");
                }
            }

            if ($isAllView) {
                if (!isset($_ENV)) $_ENV = array();
                array_merge($_ENV, $new_envs);
            } else {
                return $new_envs;
            }

        } catch (Exception $error) {
            
        }

    }

?>