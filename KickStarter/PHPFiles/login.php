<?php

session_start();
include_once 'dataBaseConstants.php';
include_once 'UsersTable.php';



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
        case 'redirectToWallPage':
            redirectToWallPage();
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
    if (isset($_SESSION["logged"]) and session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }

    $returnMsg = array();
    $returnMsg['status'] = "success";
    $returnMsg['page'] = '/index.php';
    echo json_encode($returnMsg);
}


function loginVerify()
{
    $emailFromUser = $_POST["email"];
    $usersTableConn = new UsersTable();
    $result = $usersTableConn->getUserByEmail($emailFromUser);


    $passwordFromUser = $_POST["password"];
    $passwordFromUser = sha1($passwordFromUser);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array(); //mysqli_fetch_array($result);

        if ((strcmp($emailFromUser, $row["email"]) == 0) and (strcmp($passwordFromUser, $row["pass"]) == 0)) {
            // echo json_encode(array("name" => $row["fullname"]));
            $_SESSION["logged"] = true;
            $_SESSION["name"] = $row["fullname"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["id"] = $row["id"];
            
            ob_clean();
            header("Location: facebookScreen.php");
        } else {
            echo json_encode("wrong user");
        }
    } else {
        echo json_encode("wrong user");
    }
}

function redirectToWallPage()
{
    header("Location: facebookScreen.php");
}
