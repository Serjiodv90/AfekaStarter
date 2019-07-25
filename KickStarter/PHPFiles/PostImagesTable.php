<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';


class PostImagesTable
{

    private $_usersTable = USERS_TABLE;
    private $_postsTable = POSTS_TABLE;
    private $_imagesTable = POST_IMAGES_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createImagesTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE $this->_imagesTable (
            `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            `user_id` INT(6) UNSIGNED  NOT NULL, 
            `post_id` INT(6) UNSIGNED NOT NULL,   
            `image_name` VARCHAR(255) NOT NULL,         
            `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`user_id`) REFERENCES $this->_usersTable(`id`),
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

    public function getImagesForPostByPostId($postId)
    {

        $imagesQuery =  "SELECT `image_name` FROM $this->_imagesTable WHERE `post_id` = '$postId'";
        $result = $this->_dbConnection->query($imagesQuery);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return FALSE;
        }
    }

    public function saveImageForPost($postId, $userId, $imageName)
    {
        $returnMsg = "";

        $insertQuery = "INSERT INTO $this->_imagesTable (`user_id`, `post_id`, `image_name`) VALUES ('$userId', '$postId', '$imageName')";
        $query = $this->_dbConnection->query($insertQuery); // Insert query

        if ($query !== TRUE) {
            $returnMsg = "DB Error";
        }
        else {
            $returnMsg = "true";
        }

        return $returnMsg;
    }
}
