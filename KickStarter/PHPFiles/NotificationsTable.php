<?php

include_once 'DB_Connection.php';
include_once 'dataBaseConstants.php';
include_once 'sessionManager.php';


class NotificationsTable
{

    private $_usersTable = USERS_TABLE;
    private $_notificationsTable = NOTIFICATIONS_TABLE;
    private $_dbConnection;



    public function __construct()
    {
        $this->_dbConnection = DB_Connection::getConnection();
    }

    public function createNotificationsTable()
    {

        if ($this->_dbConnection->connect_error) {
            die("Connection failed: " . $this->_connection->connect_error);
        }

        // sql to create table for users
        $sql = "CREATE TABLE $this->_notificationsTable (
            `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            `inviting_user_id` INT(6) UNSIGNED  NOT NULL, 
            `invited_user_id` INT(6) UNSIGNED NOT NULL,           
            `description` VARCHAR(255) NOT NULL, 
            `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(`inviting_user_id`) REFERENCES $this->_usersTable(`id`),
            FOREIGN KEY(`invited_user_id`) REFERENCES $this->_usersTable(`id`)    
            )";


        if ($this->_dbConnection->query($sql) === TRUE) {
            // return TRUE;
            echo ("true");
        } else {
            // return FALSE;
            echo ("false");
        }
    }

    public function removeNotificationById($notificationId){
        $deleteOldQuery = "DELETE FROM $this->_notificationsTable WHERE `id` = '$notificationId'";
        $result = $this->_dbConnection->query($deleteOldQuery);

    }

    public function getAllNotificationsForCurrentUser()
    {
        $currentUserId = getCurrentUserId();
        $likeQuery =  "SELECT * FROM $this->_notificationsTable WHERE `invited_user_id` = '$currentUserId'";
        $deleteOldQuery = "DELETE FROM $this->_notificationsTable WHERE `reg_date`< DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
        $result = $this->_dbConnection->query($likeQuery);

        if ($result->num_rows > 0) {
            $this->_dbConnection->query($deleteOldQuery);
            return $result;
        } else {
            return FALSE;
        }
    }

    public function addNotificationFromCurrentUserToFriendById($friendsId, $notificationDescription)
    {
        $currentUserId = getCurrentUserId();

        $insertQuery = "INSERT INTO $this->_notificationsTable (`inviting_user_id`, `invited_user_id`, `description`)
                        VALUES ('$currentUserId', '$friendsId', '$notificationDescription')";
        $query = $this->_dbConnection->query($insertQuery); // Insert query

        if($query)
            return TRUE;
        else 
            return FALSE;
    }
}
