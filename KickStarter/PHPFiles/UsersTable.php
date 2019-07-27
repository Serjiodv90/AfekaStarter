<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';


class UsersTable
{

    private $_usersTable = USERS_TABLE;
    private $_friendsTable = FRIENDS_CONNECTION_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createUsersTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE IF NOT EXISTS $this->_usersTable (
            `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            `fullname` VARCHAR(30) NOT NULL,
            `email` VARCHAR(50),
            `pass` VARCHAR(50) NOT NULL,
            `profile_image` VARCHAR(255) DEFAULT '', 
            `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";


        if ($this->_dbConnection->query($sql) === TRUE) {
            // return TRUE;
            echo ("true");
        } else {
            // return FALSE;
            echo ("false");
        }
    }

    public function getProfileImagePathByUserId($userId){
        $profileImageQuery = "SELECT `profile_image` FROM $this->_usersTable WHERE `id` = '$userId'";
        $result = $this->_dbConnection->query($profileImageQuery);
        if($row = $result->fetch_array())
            return $row["profile_image"];
        else
            return "";

    }

    public function getAllUserFriendsByUserId($userId) {
        $friendsQuery = "SELECT u.id, u.fullname 
                         FROM `myusers` u
                         LEFT JOIN `friends` f
                         ON f.user_id = $userId
                         WHERE u.id = f.friend_id";
        $result = $this->_dbConnection->query($friendsQuery);
        $namesArray = array();
        while ($row = $result->fetch_array()) {
            $namesArray[$row['id']] = $row['fullname'];
        }
        return $namesArray;
    }

    public function getUserByEmail($email)
    {
        $regVerifyQuery =  "SELECT * FROM $this->_usersTable WHERE `email` = '$email'";
        $result = $this->_dbConnection->query($regVerifyQuery); //mysqli_query($usersDbLink, $regVerifyQuery);
        return $result;
    }

    public function getAllUserName()
    {
        $searchForUser = "SELECT `id`, `fullname` FROM $this->_usersTable ";

        $result = $this->_dbConnection->query($searchForUser);
        $namesArray = array();
        while ($row = $result->fetch_array()) {
            $namesArray[$row['id']] = $row['fullname'];
        }
        return $namesArray;
    }

    public function getUserNameBySubstring($substring)
    {
        $searchForUser = "SELECT `id`, `fullname` 
                            FROM $this->_usersTable 
                            WHERE `fullname` LIKE '%$substring%';
                            -- OR `email` LIKE '%$substring%'";
        $result = $this->_dbConnection->query($searchForUser);
        $namesArray = array();
        while ($row = $result->fetch_array()) {
            $namesArray[$row['id']] = $row['fullname'];
        }
        return $namesArray;
    }

    public function getUserNameById($id)
    {
        $selectNameQuery = "SELECT `fullname` FROM $this->_usersTable WHERE `id` = '$id'";
        $result = $this->_dbConnection->query($selectNameQuery);
        if ($row = $result->fetch_array())
            return $row["fullname"];
        else
            return 0;
    }

    public function insertUser($email, $userName, $password, $profileImagePath)
    {
        $password = sha1($password); // Password Encryption.
        $returnMsg = "";

        // Check if e-mail address syntax is valid or not
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Sanitizing email(Remove unexpected symbol like <,>,?,#,!, etc.)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { } else {

            $result = $this->getUserByEmail($email);
            $numOfRows = $result->num_rows; //mysqli_num_rows($result);

            if ($numOfRows == 0) {   // if the user doesn't registered already

                $insertQuery = "INSERT INTO $this->_usersTable (fullname, email, pass, profile_image) 
                                VALUES ('$userName', '$email', '$password', '$profileImagePath')";
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
