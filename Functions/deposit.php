<?php

require 'dbcon.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account = $_POST["account"];
    $amount = $_POST["amount"];
    $type = $_POST["type"];
    $remark = $_POST["remark"];


    // Start a transaction to rollback the process if anyhting fails
    mysqli_begin_transaction($con);

    // adding the client details to the clientinfo table uisng stored procedures.
    if ($stmt1 = mysqli_prepare($con, "CALL depositToAccount(?,?,?,?)")) {
        mysqli_stmt_bind_param($stmt1, "ssss", $account, $remark, $amount, $type);

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

    // Close the database connection
    mysqli_close($con);
    session_start();
    $_SESSION['Adminmessage'] = 'Deposit Successful!';
    header("Location: ../creditDebit.php?account=" . urlencode($account));

    //header("Location: ../creditDebit.php");
    //header("Location: ../clientSummary.php?clientNIC=" . urlencode($clientNIC));



}
