<?php
session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "faceAfekaUsers";
    $usersTable = "MyUsers";
    
    //verify that tha DB exists
    $conn = @new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error) {
        $conn = new mysqli($servername, $username, $password);
        $sql = "CREATE DATABASE ".$dbname;  //create database
        
        if ($conn->query($sql) !== TRUE) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    // Create connection to DB
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    // sql to create table for users
    $sql = "CREATE TABLE " . $usersTable . " (
        `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        `fullname` VARCHAR(30) NOT NULL,
        `email` VARCHAR(50),
        `pass` VARCHAR(30) NOT NULL,
        `reg_date` TIMESTAMP
        )";
        
    if ($conn->query($sql) === TRUE) {
        echo "true";
    } else {
        echo "false";
    }
    
    $conn->close();
    
?>