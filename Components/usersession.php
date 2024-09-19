<?php
session_start();

if (!isset($_SESSION['UserNickName'])) {
    header("Location: userlogin.php");
    exit(); // Terminate the script
}
