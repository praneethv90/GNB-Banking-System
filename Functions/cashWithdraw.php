<?php
require 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accNO = $_POST['account'];
    $amount = $_POST['amount'];



    // Start a transaction to rollback the process if anyhting fails
    mysqli_begin_transaction($con);

    // adding the client details to the clientinfo table uisng stored procedures.
    if ($stmt1 = mysqli_prepare($con, "CALL withdrawCash(?,?)")) {
        mysqli_stmt_bind_param($stmt1, "ss", $accNO, $amount);

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
    $_SESSION['Adminmessage'] = 'Cash Withdrawal Successful!';
    header("Location: ../creditDebit.php?account=" . urlencode($accNO));
}
