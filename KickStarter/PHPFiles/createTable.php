<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "faceAfekaUsers";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    function createNewTable() {
        // sql to create table
        $sql = "CREATE TABLE MyUsers (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            fullname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            pass VARCHAR(30) NOT NULL,
            reg_date TIMESTAMP
            )";
            
        if ($conn->query($sql) === TRUE) {
            // echo "Table MyUsers created successfully";
            echo json_encode("true");
        } else {
            // echo "Error creating table: " . $conn->error;
            echo json_encode("false");
        }
        
        $conn->close();
    }

?>