<?php

include_once 'dataBaseConstants.php';

class DB_Connection {

    private static $db;
    private $_connection;

    private $_servername = SERVER_NAME;//"localhost";
    private $_username = USER_NAME;//"root";
    private $_password = PASSWORD;//"";
    private $_dbname = DB_NAME;//"faceAfekaUsers";
    

    private function __construct() {
        $this->_connection = @new mysqli($this->_servername, $this->_username, $this->_password, $this->_dbname);
        if($this->_connection->connect_error) {
            $this->createDataBase();
        }
    }

    private function createDataBase() {
        $this->_connection = new mysqli($this->_servername, $this->_username, $this->_password);
        $sql = "CREATE DATABASE ".$this->_dbname;  //create database
        if ($this->_connection->query($sql) !== TRUE) {
            die("Connection failed: " . $this->_connection->connect_error);
        }
    }


    public function __destruct() {
        $this->_connection->close();
    }

    public static function getConnection() {
        // echo("in static\ndb:". self::$db);
        if (self::$db == null) {
            self::$db = new DB_Connection();
        }
        return self::$db->_connection;
    }
}

?>