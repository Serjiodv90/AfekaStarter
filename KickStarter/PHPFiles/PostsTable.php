<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';


class PostsTable
{

    private $_postsTable = POSTS_TABLE;
    private $_usersTableName = USERS_TABLE;
    private $_friendsTable = FRIENDS_CONNECTION_TABLE;
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
            echo ("true");
        } else {
            echo ("false");
        }
    }

    public function togglePostPrivacy($postId)
    {
        $returnMsg = "";

        $selectRowQuery = "SELECT `private` 
                           FROM $this->_postsTable 
                           WHERE `id` = '$postId'";

        $currentPrivacyVal = 0;
        $result = $this->_dbConnection->query($selectRowQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_array();
            $currentPrivacyVal = $row["private"];
        }

        $nextPrivacyVal = ($currentPrivacyVal == 1) ? 0 : 1;
        $updatePrivacytQuery = "UPDATE $this->_postsTable SET `private` = '$nextPrivacyVal' WHERE `id` = '$postId'";
        $result = $this->_dbConnection->query($updatePrivacytQuery); // Update query

        if ($result === TRUE) {
            $returnMsg = "ok";
        } else {
            $returnMsg = "DB Error";
        }

        return $returnMsg;
    }

    public function addLikeToPostById($postId, $incDecVal)
    {
        $updateLikestQuery = "UPDATE $this->_postsTable SET `num_of_likes` = num_of_likes + $incDecVal WHERE `id` = '$postId'";
        $result = $this->_dbConnection->query($updateLikestQuery); // Update query

        if ($result === TRUE) {
            $returnMsg = "ok";
        } else {
            $returnMsg = "DB Error";
        }

        return $returnMsg;
    }

    public function inserPost($user_id, $content, $numOfImages, $isPrivate)
    {
        $returnMsg = "";
        $content = $this->_dbConnection->real_escape_string($content);

        $insertQuery = "INSERT INTO $this->_postsTable (`user_id`, `post_content`, `num_of_images`, `private`)
                         VALUES ('$user_id', '$content', '$numOfImages', '$isPrivate')";

        $query = $this->_dbConnection->query($insertQuery); // Insert query

        if ($query === TRUE) {
            $returnMsg = $this->_dbConnection->insert_id;
        } else {
            $returnMsg = "DB Error";
            // echo "Error!! ";
        }

        return $returnMsg;
    }

    public function getAllpostsOfUserByDate($userId)
    {
        // $allPostsQueryByDate = "SELECT * 
        //                         FROM $this->_postsTable 
        //                         WHERE `user_id` = $userId OR `private` = 0
        //                         ORDER BY `publish_date` DESC";


        $allPostsQueryByDate = "SELECT p.id ,p.user_id, p.post_content, p.num_of_likes, p.num_of_images, p.private, p.publish_date
                                FROM $this->_postsTable p
                                LEFT JOIN $this->_friendsTable f
                                    ON (p.user_id = f.friend_id AND f.user_id = $userId)
                                WHERE (f.user_id = $userId AND p.private = 0)
                                    OR (p.user_id = $userId) 
                                ORDER BY p.publish_date DESC";

        $result = $this->_dbConnection->query($allPostsQueryByDate);
        if ($result)
            return $result;
        else
            return 0;
    }
}
