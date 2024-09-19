<?php include 'Components/adminsession.php'; ?>
<?php include 'Components/header.php'; ?>
<?php include 'Components/navigation.php'; ?>

<div class="container">
    <?php
    if (isset($_SESSION['Adminmessage'])) {
        echo '<div class="alert alert-info">' . $_SESSION['Adminmessage'] . '</div>';
        unset($_SESSION['Adminmessage']);
    }
    ?>
    <div class="row">
        <div class="row align-items-end">
            <a href="messages.php" class="btn btn-danger m-3 col-auto mr-auto ">Back</a>
        </div>
        <div class="col-md-12">
            <h2>Unread Messages</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Message ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Message</th>
                        <th>Sent Date</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $accountNo = $_POST["accountNo"];
                        // This variable is used to determine whether the deposit is cash or cheque
                    }
                    $manager = 'manager';
                    include 'Functions/dbcon.php';
                    $query = "CALL allMessagesfromAccount(?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "s", $accountNo);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (!$result) {
                        die("Error executing stored procedure: " . mysqli_error($con));
                    }

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['messageID'] . "</td>";
                        echo "<td>" . $row['sender'] . "</td>";
                        echo "<td>" . $row['reciever'] . "</td>";
                        echo "<td>" . $row['message'] . "</td>";
                        echo "<td>" . $row['sentDate'] . "</td>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'Components/footer.php'; ?>