<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';
include_once 'PostsTable.php';


class CommentsTable
{

    private $_postsTable = POSTS_TABLE;
    private $_usersTableName = USERS_TABLE;
    private $_commentsTable = COMMENTS_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createCommentsTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE $this->_commentsTable (
            `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            `post_id` INT(6) UNSIGNED NOT NULL,
            `user_id` INT(6) UNSIGNED NOT NULL,
            `comment_content` TEXT NOT NULL,
            `publish_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`post_id`) REFERENCES $this->_postsTable(`id`),
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



    public function inserComment($user_id, $post_id, $content)
    {
        $returnMsg = "";

        $content =  $this->_dbConnection->real_escape_string($content);
        

        $insertQuery = "INSERT INTO $this->_commentsTable (`post_id`, `user_id`, `comment_content`)
                         VALUES ('$post_id', '$user_id', '$content')";

        $query = $this->_dbConnection->query($insertQuery); // Insert query

        if ($query === TRUE) {
            $returnMsg = "ok";
        } else {
            $returnMsg = "DB Error";
            // echo "Error!! ";
        }
        
        return $returnMsg;
    }


    public function getAllCommentsOfPostByPostIdByDate($postId) 
    {
        $allCommentsQueryByDate = "SELECT * from $this->_commentsTable 
                                    WHERE `post_id` = '$postId' 
                                    ORDER BY `publish_date` ASC";

        $result = $this->_dbConnection->query($allCommentsQueryByDate);
        if($result)
            return $result;
        else 
            return 0;
    }

    
}