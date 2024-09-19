<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accountNo = $_POST['accountNo'];
    $amount = $_POST['amount'];

    echo $accountNo;
    echo $amount;
        include 'dbcon.php';
        mysqli_begin_transaction($con);


        if ($stmt1 = mysqli_prepare($con, "CALL withdrawCashATM(?,?)")) {
            mysqli_stmt_bind_param($stmt1, "ss", $accountNo, $amount);

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

        header('Location: ../ATM.php');

        $_SESSION['ATMmessage'] = 'Transaction Successful. Please Collect your card and Cash';

        exit;
    } else {

        header('Location: ../ATM.php');

        $_SESSION['ATMmessage'] = 'Transaction Terminated';

        exit;
}