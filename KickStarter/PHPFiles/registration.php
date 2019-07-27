<?php

session_start();
require_once 'UsersTable.php';
// require_once 'login.php';


// Fetching Values from POST Method.
$name = $_POST['name']; 
$email = $_POST['email'];
$password = $_POST['password']; 
$profileImage = "";

echo("files: " . $_FILES["profileImage"]["name"]);

if(isset($_FILES["profileImage"]) && $_FILES["profileImage"]["name"]){
    $profileImageName = $_FILES["profileImage"]["name"];
    echo("profile image: $profileImageName");
    $newProfileImageName = $name . "_" . $profileImageName;
    $profileImage =  "/pics/profileImages/" . $newProfileImageName;
    move_uploaded_file($_FILES["profileImage"]["tmp_name"], __DIR__ . '/..' .$profileImage);
}

$usersDb = new UsersTable();
$msg = $usersDb->insertUser($email, $name, $password, $profileImage);
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


function redirectToWallPage()
{
    header("Location: facebookScreen.php");
}



?>

