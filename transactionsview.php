<?php
include 'Components/adminsession.php';
include 'Components/header.php';
include 'Components/navigation.php';

$accountNo = '';

if (isset($_GET['accountNo'])) {
    $accountNo = $_GET['accountNo'];


    require 'Functions/dbcon.php';

    $query = "CALL transactionreportMostRecent(?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $accountNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error executing stored procedure: " . mysqli_error($con));
    }
}
?>

<div class="container mt-5">
    <h1>Transaction Report</h1>
    <table class="table table-light table-bordered">
        <thead>
            <tr>
                <th>Transaction Method</th>
                <th>Remarks</th>
                <th>Transaction Date</th>
                <th>Transaction Amount</th>
                <th>Transaction CRDB</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display the data in the table
            while ($row = mysqli_fetch_assoc($result)) {
                $backgroundColor = ($row['transactionCRDB'] == 'CR') ? 'lightblue' : (($row['transactionCRDB'] == 'DB') ? 'lightcoral' : '');

                echo "<tr style='background-color: $backgroundColor;'>";
                echo "<td>" . $row['transactionMethod'] . "</td>";
                echo "<td>" . $row['remarks'] . "</td>";
                echo "<td>" . $row['transactionDate'] . "</td>";
                echo "<td>" . $row['transactionAmount'] . "</td>";
                echo "<td>" . $row['transactionCRDB'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>


<?php include 'Components/footer.php'; ?>