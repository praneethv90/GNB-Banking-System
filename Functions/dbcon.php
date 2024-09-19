<?php
$hostname = "localhost"; // Change to your database server hostname
$username = "root";      // Change to your database username
$password = "";          // Change to your database password
$database = "gnbdatabase"; // Change to your database name

// Create a connection to the database
$con = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
