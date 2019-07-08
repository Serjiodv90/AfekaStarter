<?php

session_start();



//set the connection
$db_link = mysqli_connect("localhost", "root", "", "afeka-starter");
if (mysqli_connect_error()) {
    die("ERROR IN DB!!!");
}


// use different function in the php file
if (isset($_GET["function"]) and $_GET["function"] != "") {
    switch ($_GET["function"]) {
        case 'loginVerify': loginVerify(); break;
        case 'isLogged' : isLogged(); break;
        case 'logOut' : logOut(); break;
    }
}


function isLogged(){
    if((isset($_SESSION["logged"])) and $_SESSION["logged"] == "true") {
        $msgToFront = Array ("logged" => "true", "name" => $_SESSION["name"]);
        echo json_encode($msgToFront);
    }
    else {
        echo "User is not logged in";
    }
}

function logOut() {
    session_unset();
    session_destroy();
    echo json_encode("success");
    // header("refresh:0.5;url=index.php");
}


function loginVerify()
{
    global $db_link;

    $emailFromUser = $_POST["email"];
    $passwordFromUser = $_POST["password"];
    $passwordFromUser = sha1($passwordFromUser);
    $emailVerificationQuery = "SELECT * FROM `users` where `email` = '" . $emailFromUser . "' LIMIT 1";
    $result = mysqli_query($db_link, $emailVerificationQuery);

    if ($result != "") {
        $row = mysqli_fetch_array($result);
    
        if ((strcmp($emailFromUser, $row["email"]) == 0) and (strcmp($passwordFromUser, $row["password"]) == 0)) {
            echo json_encode($row);
            $_SESSION["logged"] = true;
            $_SESSION["name"] = $row["name"];
            $_SESSION["id"] = $row["id"];

        }
         else {
            echo json_encode("wrong user");
        }
    } else {
        echo json_encode("wrong user");
    }
}

mysqli_close($db_link);
