<?php
session_start();


include_once 'UsersTable.php';


$usersDb = new UsersTable();
$usersDb->createUsersTable();

?>