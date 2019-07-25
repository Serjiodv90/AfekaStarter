<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';


class LikesTable
{

    private $_likesTable = LIKES_CONNECTION_TABLE;
    private $_postsTable = POSTS_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createLikesTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE $this->_likesTable (
            `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            `user_id` INT(6) UNSIGNED  NOT NULL, 
            `post_id` INT(6) UNSIGNED NOT NULL,            
            `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`post_id`) REFERENCES $this->_postsTable(`id`)  
            )";


        if ($this->_dbConnection->query($sql) === TRUE) {
            // return TRUE;
            echo ("true");
        } else {
            // return FALSE;
            echo ("false");
        }
    }

    public function checkCurrentUserLikePostByPostId($postId)
    {
        $currentUserId = $_SESSION["id"];
        $likeQuery =  "SELECT * FROM $this->_likesTable WHERE `user_id` = '$currentUserId' AND `post_id` = '$postId'";
        $result = $this->_dbConnection->query($likeQuery);
        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function toggleLikeForCurrentUserToPostByPostId($postId)
    {
        $currentUserId = $_SESSION["id"];
        $returnMsg = "";
        $query = null;

        //check wether user liked this post, if so , delete that row, if not add
        if ($this->checkCurrentUserLikePostByPostId($postId) === FALSE) {
            $insertQuery = "INSERT INTO $this->_likesTable (`user_id`, `post_id`) VALUES ('$currentUserId', '$postId')";
            $query = $this->_dbConnection->query($insertQuery); // Insert query
            $returnMsg = "true";
        } else {
            $deleteLikeQuery = "DELETE FROM $this->_likesTable WHERE `post_id` = '$postId' AND `user_id` = '$currentUserId'";
            $query = $this->_dbConnection->query($deleteLikeQuery); // Delete query
            $returnMsg = "false";
        }

        if ($query !== TRUE) {
            $returnMsg = "DB Error";
        }

        return $returnMsg;
    }
}
