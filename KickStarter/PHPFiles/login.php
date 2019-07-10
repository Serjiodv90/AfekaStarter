<?php

session_start();
include_once 'dataBaseConstants.php';
include_once 'UsersTable.php';



//set the connection
$db_link = mysqli_connect("localhost", "root", "", "faceAfekaUsers");
if (mysqli_connect_error()) {
    die("ERROR IN DB!!!");
}


// use different function in the php file
if (isset($_GET["function"]) and $_GET["function"] != "") {
    switch ($_GET["function"]) {
        case 'loginVerify':
            loginVerify();
            break;
        case 'isLogged':
            isLogged();
            break;
        case 'logOut':
            logOut();
            break;
    }
}


function isLogged()
{
    if ((isset($_SESSION["logged"])) and $_SESSION["logged"] == "true") {
        $msgToFront = array("logged" => "true", "name" => $_SESSION["name"]);
        echo json_encode($msgToFront);
    } else {
        echo "User is not logged in";
    }
}

function logOut()
{
    session_unset();
    session_destroy();
    echo json_encode("success");
    // header("refresh:0.5;url=index.php");
}


function loginVerify()
{
    echo json_encode("in loginVerify\n");
    $emailFromUser = $_POST["email"];
    $usersTableConn = new UsersTable();
    $result = $usersTableConn->getUserByEmail($emailFromUser);


    $passwordFromUser = $_POST["password"];
    $passwordFromUser = sha1($passwordFromUser);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array(); //mysqli_fetch_array($result);

        if ((strcmp($emailFromUser, $row["email"]) == 0) and (strcmp($passwordFromUser, $row["pass"]) == 0)) {
            echo json_encode($row);
            $_SESSION["logged"] = true;
            $_SESSION["name"] = $row["fullname"];
            $_SESSION["id"] = $row["id"];
        } else {
            echo json_encode("wrong user");
        }
    } else {
        echo json_encode("wrong user");
    }
}

// mysqli_close($db_link);
