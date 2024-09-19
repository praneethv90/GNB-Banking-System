<?php
require 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $clientFname = $_POST["clientFname"];
    $clientLname = $_POST["clientLname"];
    $clientNIC = $_POST["clientNIC"];
    $clientContact = $_POST["clientContact"];
    $clientAddress = $_POST["clientAddress"];
    $clientBday = $_POST["clientBday"];
    $clientEmail = $_POST["clientEmail"];
    $accountType  = $_POST["accountType"];

    // Start a transaction to rollback the process if anyhting fails
    mysqli_begin_transaction($con);

    // adding the client details to the clientinfo table uisng stored procedures.
    if ($stmt1 = mysqli_prepare($con, "CALL insertClient(?,?,?,?,?,?,?)")) {
        mysqli_stmt_bind_param($stmt1, "sssssss", $clientFname, $clientLname, $clientNIC, $clientContact, $clientAddress, $clientBday, $clientEmail);

        if (mysqli_stmt_execute($stmt1)) {

            //adding the account type to the account info table using stored procedure
            // triggers are set in the DB to automatically bind the latest client and the latest account when a new account is added to the accounts table.
            if ($stmt2 = mysqli_prepare($con, "CALL CreateAccount(?)")) {
                mysqli_stmt_bind_param($stmt2, "s", $accountType);

                if (mysqli_stmt_execute($stmt2)) {
                } else {
                    mysqli_rollback($con);
                    echo "Error executing second stored procedure: " . mysqli_error($con);
                }

                mysqli_stmt_close($stmt2);
            } else {
                mysqli_rollback($con);
                echo "Error preparing second stored procedure: " . mysqli_error($con);
            }

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
    $_SESSION['Adminmessage'] = 'Account Successfully Created!';
    header("Location: ../clientSummary.php?clientNIC=" . urlencode($clientNIC));
}
