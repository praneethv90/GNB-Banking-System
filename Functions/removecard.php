<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accountNo = $_POST['accountNo'];
    $ATMID = $_POST['ATMID'];


    //checking the post variables
    // echo $accountNo;
    // echo '<br>';
    // echo $cardNo;
    // echo '<br>';
    // echo $PIN;
    // }
    include 'dbcon.php';
    mysqli_begin_transaction($con);


    if ($stmt1 = mysqli_prepare($con, "CALL deactivateATM(?)")) {
        mysqli_stmt_bind_param($stmt1, "s", $ATMID);

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

    header('Location: ../creditDebit.php');

    $_SESSION['Adminmessage'] = 'Card had been romoved from account Successfully';
    header("Location: ../creditDebit.php?account=" . urlencode($accountNo));
    exit;
} else {

    header('Location: ../creditDebit.php');

    $_SESSION['Adminmessage'] = 'Transaction Terminated';

    exit;
}
