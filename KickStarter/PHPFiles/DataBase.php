<?php

class Database {

    private static $db;
    private $connection;
    
    private $host = "localhost";
    private $userName = "root";
    private $password = "";
    private $db_name = "afeka-starter";

    private function __construct() {
        $this->connection = mysqli_connect("localhost", "root", "", "afeka-starter");
    }

    function __destruct() {
        $this->connection->close();
    }

    public static function getConnection() {
        if (self::$db == null) {
            self::$db = new Database();
        }
        return self::$db->connection;
    }
}

?>