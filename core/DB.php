<?php
class DB {

    function connect($db)
    {
        try {
            $conn = new PDO("mysql:host={$db['host']};dbname=blog", $db['username'], $db['password']);

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $exception) {
            exit($exception->getMessage());
        }
    }
}

