<?php

include_once 'dataBaseConstants.php';

class DB_Connection {

    private static $db;
    private $_connection;

    private $_servername = SERVER_NAME;//"localhost";
    private $_username = USER_NAME;//"root";
    private $_password = PASSWORD;//"";
    private $_dbname = DB_NAME;//"faceAfekaUsers";
    private $_usersTable = "MyUsers";
    

    private function __construct() {
        // $this->_connection = mysqli_connect("localhost", "root", "", "faceAfekaUsers");
        echo("in ctor, befor connection\nuser: "); echo($this->_username . "\n");

        $this->_connection = @new mysqli($this->_servername, $this->_username, $this->_password, $this->_dbname);

        echo("in ctor, after connection\nerror:" . $this->_connection->connect_error);

        if($this->_connection->connect_error) {
            $this->createDataBase();
        }
        // $this->createUsersTable();
    }

    private function createDataBase() {
        $this->_connection = new mysqli($this->_servername, $this->_username, $this->_password);
        $sql = "CREATE DATABASE ".$this->_dbname;  //create database
        echo("in createDataBase");
        if ($this->_connection->query($sql) !== TRUE) {
            die("Connection failed: " . $this->_connection->connect_error);
        }
    }

    // private function createUsersTable() {
    //     $this->_connection = new mysqli($this->_servername, $this->_username, $this->_password, $this->_dbname);
    //     // Check connection
    //     if ($this->_connection->connect_error) {
    //         die("Connection failed: " . $this->_connection->connect_error);
    //     }

    //     // sql to create table for users
    //     $sql = "CREATE TABLE " . $this->_usersTable . " (
    //         `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    //         `fullname` VARCHAR(30) NOT NULL,
    //         `email` VARCHAR(50),
    //         `pass` VARCHAR(50) NOT NULL,
    //         `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    //         )";
            
    //     if ($this->_connection->query($sql) === TRUE) {
    //         // return TRUE;
    //         echo ("true");
    //     } else {
    //         // return FALSE;
    //         echo ("false");
    //     }
    // }

    public function __destruct() {
        $this->_connection->close();
    }

    public static function getConnection() {
        // echo("in static\ndb:". self::$db);
        if (self::$db == null) {
            self::$db = new DB_Connection();
        }
        echo("in static after\n:");
        return self::$db->_connection;
    }
}

?>