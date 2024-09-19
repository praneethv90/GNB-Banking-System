<?php
require 'dbcon.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $clientNIC = $_POST["clientNIC"];
    $clientFname = $_POST["clientFname"];
    $clientLname = $_POST["clientLname"];
    $clientContact = $_POST["clientContact"];
    $clientAddress = $_POST["clientAddress"];
    $clientBday = $_POST["clientBday"];
    $clientEmail = $_POST["clientEmail"];
}
mysqli_begin_transaction($con);

// adding the client details to the clientinfo table uisng stored procedures.
if ($stmt1 = mysqli_prepare($con, "CALL editClient(?,?,?,?,?,?,?)")) {
    mysqli_stmt_bind_param($stmt1, "sssssss", $clientNIC, $clientFname, $clientLname,  $clientContact, $clientAddress, $clientBday, $clientEmail);

    if (mysqli_stmt_execute($stmt1)) {

        // Commit the transaction if all stored procedures are successful
        mysqli_commit($con);
    } else {
        mysqli_rollback($con);
        echo "Error executing first stored procedure: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt1);
} else {
    mysqli_rollback($con);
    echo "Error preparing first stored procedure: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
session_start();
$_SESSION['Adminmessage'] = 'Client Information Successfully Edited!';
header("Location: ../clientSummary.php?clientNIC=" . urlencode($clientNIC));
