<?php

session_start();
// include 'DB_Connection.php';
include_once 'UsersTable.php';


$usersDb = new UsersTable();
$usersDb->createUsersTable();

   


?>