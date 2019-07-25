<?php

session_start();

function getCurrentUserId()
{
    return $_SESSION["id"];
}
?>