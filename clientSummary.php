<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<?php
if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
    echo '<div class="message">' . $_SESSION['message'] . '</div>';

    unset($_SESSION['message']);
}
if (isset($_GET["clientNIC"])) {
    $clientNIC = $_GET["clientNIC"];
} else {
    $clientNIC = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $clientNIC = $_POST["clientNIC"];
}

include 'Functions/dbcon.php';

$query = "CALL getEverythingByNIC(?)";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $clientNIC);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Error executing stored procedure: " . mysqli_error($con));
}
?>

<div class="container m-6 mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card_header">
                    <h4 class="View m-5">Client Details</h4>
                    <form method="post" class="form-inline m-2">
                        <div class="form-group">
                            <input type="text" class="form-control m-2" name="clientNIC" value="<?php echo $clientNIC; ?>" required>
                            <button type="submit" class="btn btn-primary form-control m-1">Search</button>
                            <button type="submit" class="btn btn-primary form-control m-1" formaction="editClient.php" formmethod="post">Edit</button>
                            <a href="admindashboard.php" class="btn btn-warning form-control m-1">Back to Dashboard</a>
                        </div>


                    </form>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Client First Name</th>
                                <th>Client Last Name</th>
                                <th>Client Contact Number</th>
                                <th>Client Address</th>
                                <th>Client NIC</th>
                                <th>Client Date of Birth</th>
                                <th>Client email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (isset($result) && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result); {
                            ?>
                                    <tr>
                                        <td><?php echo $row['clientFName'] ?></td>
                                        <td><?php echo $row['clentsLName'] ?></td>
                                        <td><?php echo $row['clientContact'] ?></td>
                                        <td><?php echo $row['cleintAddress'] ?></td>
                                        <td><?php echo $row['clientNIC'] ?></td>


                                        <td><?php echo $row['clientBday'] ?></td>
                                        <td><?php echo $row['clentEmail'] ?></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6'>No Record Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="card_header">
                    <h4 class="View m-5">Client Accounts</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Account Number</th>
                                <th>Account Type</th>
                                <th>Account Balance</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            mysqli_data_seek($result, 0);
                            if (isset($result) && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['accountNo'] ?></td>
                                        <td><?php echo $row['accountType'] ?></td>
                                        <td><?php echo $row['account_balance'] ?></td>

                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6'>No Record Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Components/footer.php' ?>