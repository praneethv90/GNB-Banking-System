<?php
require 'dbcon.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accNo = $_POST['accountNo'];
    $type = $_POST['type'];
    $chequeNo = $_POST['chequeNo'];
    $amount = $_POST['amount'];
    $consignee = $_POST['consignee'];
    $consigneeID = $_POST['consigneeID'];


    mysqli_begin_transaction($con);

    if ($stmt1 = mysqli_prepare($con, "CALL withdrawCheques(?,?,?)")) {
        mysqli_stmt_bind_param($stmt1, "sss", $accNo, $amount, $chequeNo);

        if (mysqli_stmt_execute($stmt1)) {


            if ($stmt2 = mysqli_prepare($con, "CALL useCheque(?,?,?,?,?)")) {
                mysqli_stmt_bind_param($stmt2, "sssss", $chequeNo, $accNo,  $amount, $consignee, $consigneeID);

                if (mysqli_stmt_execute($stmt2)) {

                    mysqli_commit($con);
                } else {

                    mysqli_rollback($con);
                    echo "Error executing second stored procedure: " . mysqli_error($con);
                }

                mysqli_stmt_close($stmt2);
            } else {

                mysqli_rollback($con);
                echo "Error preparing second stored procedure: " . mysqli_error($con);
            }
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
    $_SESSION['Adminmessage'] = 'Cheque Withdrawal Successful!';
    header("Location: ../creditDebit.php?account=" . urlencode($accNo));
}
