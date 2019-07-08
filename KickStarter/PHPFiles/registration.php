<?php

session_start();
require_once('DataBase.php');

$db_link = mysqli_connect("localhost", "root", "", "afeka-starter");
// $db_link = DataBase::getConnection();

$name = $_POST['name']; // Fetching Values from URL.
$email = $_POST['email'];
$password = sha1($_POST['password']); // Password Encryption.

// Check if e-mail address syntax is valid or not
$email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitizing email(Remove unexpected symbol like <,>,?,#,!, etc.)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
}
 else {
    $regVerifyQuery =  "SELECT * FROM `users` WHERE `email` = '$email'";
    $result = mysqli_query($db_link, $regVerifyQuery);
    $numOfRows = mysqli_num_rows($result);

    if ($numOfRows == 0) {   // if the user doesn't registered already
         
        $insertQuery = "INSERT INTO `users` (`password`, `email`, `name`) VALUES ('$password', '$email', '$name')";
        $query = mysqli_query($db_link, $insertQuery); // Insert query

        if ($query) {
            echo json_encode('ok');
            $_SESSION["logged"] = "true";
            $_SESSION["name"] = $name;
        } else {
            echo "Error!! ";
        }
    } else {
        echo "This email is already registered, Please try another email";
    }
}
mysqli_close($db_link);



?>

