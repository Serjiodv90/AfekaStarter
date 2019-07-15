<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';


class PostsTable
{

    private $_postsTable = POSTS_TABLE;
    private $_usersTableName = USERS_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createPostsTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE $this->_postsTable (
            `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            `user_id` INT(6) UNSIGNED NOT NULL,
            `post_content` TEXT NOT NULL,
            `num_of_likes` INT(4) DEFAULT 0,
            `num_of_images` INT(1),
            `private` BOOLEAN DEFAULT FALSE,
            `publish_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`user_id`) REFERENCES $this->_usersTableName(`id`)  
            )";


        if ($this->_dbConnection->query($sql) === TRUE) {
            // return TRUE;
            echo ("true");
        } else {
            // return FALSE;
            echo ("false");
        }
    }

    // public function getUserByEmail($email) {
    //     $regVerifyQuery =  "SELECT * FROM $this->_postsTable WHERE `email` = '$email'";
    //     $result = $this->_dbConnection->query($regVerifyQuery);//mysqli_query($usersDbLink, $regVerifyQuery);
    //     return $result;
    // }


    public function inserPost($user_id, $content, $numOfImages, $isPrivate)
    {
        $returnMsg = "";

        $insertQuery = "INSERT INTO $this->_postsTable (`user_id`, `post_content`, `num_of_images`, `private`)
                         VALUES ('$user_id', '$content', '$numOfImages', '$isPrivate')";

        $query = $this->_dbConnection->query($insertQuery); // Insert query

        if ($query === TRUE) {
            $returnMsg = "ok";
        } else {
            $returnMsg = "DB Error";
            // echo "Error!! ";
        }

        return $returnMsg;
    }

    public function getAllpostsOfUserByDate($userId) 
    {
        $allPostsQueryByDate = "SELECT * from $this->_usersTableName ORDER BY `publish_date`";
        return $this->_dbConnection->query($allPostsQueryByDate);
    }

    
}
