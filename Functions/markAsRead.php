<?php
require 'dbcon.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $messageID = $_POST["messageID"];


    echo $messageID;
}
mysqli_begin_transaction($con);


if ($stmt1 = mysqli_prepare($con, "CALL readMessage(?)")) {
    mysqli_stmt_bind_param($stmt1, "s", $messageID);

    if (mysqli_stmt_execute($stmt1)) {


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

mysqli_close($con);
session_start();
$_SESSION['Adminmessage'] = 'Message Read notification sent to Client!';
header("Location: ../messages.php");
