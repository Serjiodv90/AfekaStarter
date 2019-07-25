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
            `id` INT(6) UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT(6) UNSIGNED  NOT NULL, 
            `friend_id` INT(6) UNSIGNED NOT NULL,            
            `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`user_id`) REFERENCES $this->_usersTable(`id`),
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
}
