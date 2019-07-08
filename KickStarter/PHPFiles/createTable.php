<?php

session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "faceAfekaUsers";
    $usersTable = "MyUsers";

    // use different function in the php file
// if (isset($_GET["function"]) and $_GET["function"] != "") {
//     switch ($_GET["function"]) {
//         case 'createUsersTable': createUsersTable(); break;
//         case 'createDatabase' : createDatabase(); break;
//     }
// }

    // //create connection to MySql, and connect to it
    // function createDatabase() {
        
    //     // echo "in createDatabase";

    //     $conn = new mysqli($servername, $username, $password);
    //     $sql = "CREATE DATABASE ".$dbname;  //create database
        
    //     if ($conn->query($sql) !== TRUE) {
    //         die("Connection failed: " . $conn->connect_error);
    //     }
    // }

    // //create table for users
    // function createUsersTable() {

    //     // echo "in createUsersTable";

    //     $conn = new mysqli($servername, $username, $password);
    //     if($conn->select_db($dbname) === FALSE)
    //         createDatabase();

    //     // echo "trying to connect to db";

    //     // Create connection
    //     $conn = new mysqli($servername, $username, $password, $dbname);
    //     // Check connection
    //     if ($conn->connect_error) {
    //         die("Connection failed: " . $conn->connect_error);
    //     } 

    //     // echo "create table myUsers";

    //     // sql to create table
    //     $sql = "CREATE TABLE `MyUsers` (
    //         `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    //         `fullname` VARCHAR(30) NOT NULL,
    //         `email` VARCHAR(50),
    //         `pass` VARCHAR(30) NOT NULL,
    //         `reg_date` TIMESTAMP
    //         )";
            
    //     $msgToFront = Array ("val");
    //     if ($conn->query($sql) === TRUE) {
    //         // echo "Table MyUsers created successfully";
    //     $msgToFront["val"] = "true";
    //         // echo json_encode($msgToFront);
    //         echo "true";
    //     } else {
    //         // echo "Error creating table: " . $conn->error;
    //         $msgToFront["val"] = "false";
    //         // echo json_encode($msgToFront);
    //         echo "false";
    //     }
        
    //     $conn->close();
    
    // }

    

    //verify that tha DB exists
    $conn = new mysqli($servername, $username, $password);
    if($conn->select_db($dbname) === FALSE) {
        $conn = new mysqli($servername, $username, $password);
        $sql = "CREATE DATABASE ".$dbname;  //create database
        
        if ($conn->query($sql) !== TRUE) {
            die("Connection failed: " . $conn->connect_error);
        }
    }

    // echo "trying to connect to db";

    // Create connection to DB
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    // echo "create table myUsers";

    // sql to create table for users
    $sql = "CREATE TABLE " . $usersTable . " (
        `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        `fullname` VARCHAR(30) NOT NULL,
        `email` VARCHAR(50),
        `pass` VARCHAR(30) NOT NULL,
        `reg_date` TIMESTAMP
        )";
        
    $msgToFront = Array ("val");
    if ($conn->query($sql) === TRUE) {
        // echo "Table MyUsers created successfully";
    $msgToFront["val"] = "true";
        // echo json_encode($msgToFront);
        echo "true";
    } else {
        // echo "Error creating table: " . $conn->error;
        $msgToFront["val"] = "false";
        // echo json_encode($msgToFront);
        echo "false";
    }
    
    $conn->close();
    



?>