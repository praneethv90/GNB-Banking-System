<?php
include 'dbcon.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $accNo = $_POST['accountNo'];
    $startValue = $_POST['StartingNO'];
    $chkbookno = $_POST['chequebookNO'];
    $loopCount = $_POST['numberOption'];

    // Start a transaction
    $con->autocommit(false);

    // Variable to track success or failure
    $success = true;

    // Loop to insert rows
    for ($i = 0; $i < $loopCount; $i++) {
        $checkNumber = $startValue + $i;
        $sql = "Call addCheques($chkbookno ,$accNo,$checkNumber )";

        if ($con->query($sql) !== TRUE) {
            $success = false;
            break; // Break the loop if an error occurs
        }
    }

    // Check if the transaction was successful
    if ($success) {
        // Commit the transaction
        $con->commit();
        echo "Transaction committed successfully.<br>";
    } else {
        // Rollback the transaction
        $con->rollback();
        echo "Transaction rolled back due to an error.<br>";
    }

    // Close the database connection
    $con->close();

    $_SESSION['Adminmessage'] = 'Chequebook successfully added!';
    header("Location: ../creditDebit.php?account=" . urlencode($accNo));
}
