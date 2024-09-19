<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form has been submitted
    $accountNo = $_POST["accountNo"]; // Get the accountNo from the button

}

include 'Functions/dbcon.php';

$query = "CALL getEverythingByAccNo(?)";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $accountNo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Error executing stored procedure: " . mysqli_error($con));
}
