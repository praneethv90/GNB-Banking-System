<?php
require 'dbcon.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accountNo = $_POST["accountNo"];
    $message = $_POST["message"];
    $reciever = 'Manager';

    echo $accountNo;
    echo $message;
}
mysqli_begin_transaction($con);


if ($stmt1 = mysqli_prepare($con, "CALL sendMessage(?,?,?)")) {
    mysqli_stmt_bind_param($stmt1, "sss", $accountNo, $reciever, $message);

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
$_SESSION['usermessage'] = 'Message sent Successfully!';
header("Location: ../usermessage.php?receiver=" . urlencode($accountNo));
