<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';


class FriendsTable
{

    private $_usersTable = USERS_TABLE;
    private $_friendsTable = FRIENDS_CONNECTION_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createFriendsTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE $this->_friendsTable (
            `user_id` INT(6) UNSIGNED  PRIMARY KEY, 
            `friend_id` INT(6) UNSIGNED NOT NULL,            
            `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`friend_id`) REFERENCES $this->_usersTable(`id`)  

            )";


        if ($this->_dbConnection->query($sql) === TRUE) {
            // return TRUE;
            echo ("true");
        } else {
            // return FALSE;
            echo ("false");
        }
    }

    public function checkFriendshipExists($friendId)
    {
        $currentUserId = $_SESSION["id"];
        $friendshipQuery =  "SELECT * FROM $this->_friendsTable WHERE `user_id` = '$currentUserId' AND `friend_id` = '$friendId'";
        $result = $this->_dbConnection->query($friendshipQuery);
        if ($result->num_rows > 0)
            return TRUE;
        else
            return FALSE;
    }

    public function insertFriendOfCurrentUser($friendId)
    {
        $currentUserId = $_SESSION["id"];
        $returnMsg = "";
        //connecth both users from both sides
        if ($this->checkFriendshipExists($friendId) === FALSE) {
            $insertQuery = "INSERT INTO $this->_friendsTable (`user_id`, `friend_id`) VALUES ('$currentUserId', '$friendId');";
            $insertQuery .= "INSERT INTO $this->_friendsTable (`user_id`, `friend_id`) VALUES ('$friendId', '$currentUserId');";
            $query = $this->_dbConnection->multi_query($insertQuery); // Insert query
            

            if ($query === TRUE) {
                $returnMsg = "ok";
            } else {
                $returnMsg = "DB Error";
            }

            return $returnMsg;
        }
        else
            return "friendship exists";
    }

    // public function getUserByEmail($email)
    // {
    //     $regVerifyQuery =  "SELECT * FROM $this->_usersTable WHERE `email` = '$email'";
    //     $result = $this->_dbConnection->query($regVerifyQuery); //mysqli_query($usersDbLink, $regVerifyQuery);
    //     return $result;
    // }

    // public function getUserNameBySubstring($substring)
    // {
    //     $searchForUser = "SELECT `id`, `fullname` 
    //                         FROM $this->_usersTable 
    //                         WHERE `fullname` LIKE '%$substring%';
    //                         -- OR `email` LIKE '%$substring%'";
    //     $result = $this->_dbConnection->query($searchForUser);
    //     $namesArray = array();
    //     while($row = $result->fetch_array())
    //     {
    //         $namesArray[$row['id']] = $row['fullname'];
    //     }
    //     return $namesArray;

    // }

    // public function getUserNameById($id)
    // {
    //     $selectNameQuery = "SELECT `fullname` FROM $this->_usersTable WHERE `id` = '$id'";
    //     $result = $this->_dbConnection->query($selectNameQuery);
    //     if ($row = $result->fetch_array())
    //         return $row["fullname"];
    //     else
    //         return 0;
    // }

    public function insertUser($email, $userName, $password)
    {
        $password = sha1($password); // Password Encryption.
        $returnMsg = "";

        // Check if e-mail address syntax is valid or not
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitizing email(Remove unexpected symbol like <,>,?,#,!, etc.)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { } else {

            $result = $this->getUserByEmail($email);
            $numOfRows = $result->num_rows; //mysqli_num_rows($result);

            if ($numOfRows == 0) {   // if the user doesn't registered already

                $insertQuery = "INSERT INTO $this->_usersTable (fullname, email, pass) VALUES ('$userName', '$email', '$password')";
                $query = $this->_dbConnection->query($insertQuery); // Insert query

                if ($query === TRUE) {
                    $returnMsg = "ok";
                    // echo ('ok');
                    $_SESSION["logged"] = "true";
                    $_SESSION["name"] = $userName;
                    $_SESSION["email"] = $email;
                    $_SESSION["id"] = $this->_dbConnection->insert_id;  //get last inserted id
                } else {
                    $returnMsg = "DB Error";
                    // echo "Error!! ";
                }
            } else {
                $returnMsg = "User exists";
                // echo "This email is already registered, Please try another email";
            }
        }
        return $returnMsg;
    }
}