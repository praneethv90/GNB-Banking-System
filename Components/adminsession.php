<?php
session_start();

// Check if 'username' session variable is not set
if (!isset($_SESSION['UserName'])) {
    header("Location: adminlogin.php");
    exit(); // Terminate the script
}
