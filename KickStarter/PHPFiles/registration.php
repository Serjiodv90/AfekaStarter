<?php

session_start();
require_once 'UsersTable.php';

// $db_link = mysqli_connect("localhost", "root", "", "faceAfekaUsers");
// $db_link = DataBase::getConnection();

echo ("in registration\n");

// Fetching Values from POST Method.
$name = $_POST['name']; 
$email = $_POST['email'];
$password = $_POST['password']; 

$usersDb = new UsersTable();
$usersDb->insertUser($email, $name, $password);

// echo("\nin registration:\nuser name: " . $name . "\nPassword: " . $password . "\nemail: " . $email);

// // Check if e-mail address syntax is valid or not
// $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitizing email(Remove unexpected symbol like <,>,?,#,!, etc.)
// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     echo("Invalid email address");
// }
// else {
//     // $usersTableName = UsersDataBase::getUsersTableName();

//     $regVerifyQuery =  "SELECT * FROM MyUsers WHERE `email` = '$email'";
//     $result = $usersDbLink->query($regVerifyQuery);//mysqli_query($usersDbLink, $regVerifyQuery);
//     $numOfRows = $result->num_rows;//mysqli_num_rows($result);

//     echo("in else: \nnum of row: " . $result->num_rows);

//     if ($numOfRows == 0) {   // if the user doesn't registered already
         
//         $insertQuery = "INSERT INTO MyUsers (fullname, email, pass) VALUES ('$name', '$email', '$password')";
//         echo("\ninsertQuery: " . $insertQuery);
//         $query = $usersDbLink->query($insertQuery); // Insert query

//         echo("\ninsertion query: " . $usersDbLink->error . "\n");

//         if ($query === TRUE) {
//             echo json_encode('ok');
//             $_SESSION["logged"] = "true";
//             $_SESSION["name"] = $name;
//         } else {
//             echo "Error!! ";
//         }
//     } else {
//         echo "This email is already registered, Please try another email";
//     }
// }
// mysqli_close($db_link);



?>

