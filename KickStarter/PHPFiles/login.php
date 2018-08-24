<?php

//set the connection
$db_link = mysqli_connect("localhost", "root", "", "afeka-starter");
if (mysqli_connect_error())
    die("ERROR IN DB!!!");


// use different function in the php file
if (isset($_GET["function"]) and $_GET["function"] != "") {
    switch ($_GET["function"]) {
        case 'loginVerify' : loginVerify(); break;
    }
}



function loginVerify() {
    global $db_link;

    $emailFromUser = $_POST["email"];
    $passwordFromUser = $_POST["password"];
    $emailVerificationQuery = "SELECT * FROM `users` where `email` = '" . $emailFromUser . "' LIMIT 1";
    $result = mysqli_query($db_link, $emailVerificationQuery);

    if ($result != "") {
        
        $row = mysqli_fetch_array($result);
    
        if((strcmp($emailFromUser, $row["email"]) == 0) and (strcmp($passwordFromUser, $row["password"]) == 0)) {
            echo json_encode($row);
        }
        else {
            echo json_encode("wrong user");
        }
    }
    else {
        echo json_encode("wrong user");
    }
}

mysqli_close($db_link);


?>