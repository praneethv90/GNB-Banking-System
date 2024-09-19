<?php

require 'dbcon.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $toAccount = $_POST["toAccount"];
    $amount = $_POST["amount"];
    $fromAccount = $_POST["account"];
    $remark = "trnsfr";



    // Start a transaction to rollback the process if anyhting fails
    mysqli_begin_transaction($con);

    // adding the client details to the clientinfo table uisng stored procedures.
    if ($stmt1 = mysqli_prepare($con, "CALL transfers(?,?,?)")) {
        mysqli_stmt_bind_param($stmt1, "sss", $amount, $fromAccount, $toAccount);

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
    $_SESSION['Adminmessage'] = 'Interaccount Fund Transfer Successful!';
    header("Location: ../creditDebit.php?account=" . urlencode($fromAccount));

    //header("Location: ../creditDebit.php");
    //header("Location: ../clientSummary.php?clientNIC=" . urlencode($clientNIC));



}
