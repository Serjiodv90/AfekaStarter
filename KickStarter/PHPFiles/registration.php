<?php

session_start();
require_once 'UsersTable.php';


// Fetching Values from POST Method.
$name = $_POST['name']; 
$email = $_POST['email'];
$password = $_POST['password']; 

$usersDb = new UsersTable();
$usersDb->insertUser($email, $name, $password);





?>

