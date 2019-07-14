<?php

session_start();
require_once 'UsersTable.php';
require_once 'login.php';


// Fetching Values from POST Method.
$name = $_POST['name']; 
$email = $_POST['email'];
$password = $_POST['password']; 

$usersDb = new UsersTable();
$msg = $usersDb->insertUser($email, $name, $password);
$returnMsg = array();

if(strcmp($msg, "ok") != 0) 
{
    if(strcmp($msg, "User Exists") == 0)
    {
        $returnMsg["message"] = "This user is already registered";
        echo json_encode($returnMsg);        
    }
}
else
{
    redirectToWallPage();   //registration was successful so show wall page (main page)
}






?>

