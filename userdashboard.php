<?php include '<Components/usersession.php' ?>
<?php include 'Components/userheader.php' ?>
<?php include 'Components/usernavigation.php' ?>


<?php

if (isset($_GET["clientNIC"])) {
    $clientNIC = $_GET["clientNIC"];
} else {
    $clientNIC = "";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form has been submitted
    $clientNIC = $_POST["clientNIC"]; // Get the clientNIC from the form
} elseif (isset($_SESSION['loginID'])) {
    // Check if the session variable 'loginID' exists and assign it to $clientNIC
    $clientNIC = $_SESSION['loginID'];
}


include 'Functions/dbcon.php';

$query = "CALL 	geteverythingbyLoginID(?)";
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
                    <h4 class="View m-5">Your Details</h4>
                    <?php
                    if (isset($_SESSION['usermessage'])) {
                        echo '<div class="alert alert-info">' . $_SESSION['usermessage'] . '</div>';
                        unset($_SESSION['usermessage']);
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['Adminmessage'])) {
                        echo '<div class="alert alert-info">' . $_SESSION['Adminmessage'] . '</div>';
                        unset($_SESSION['Adminmessage']);
                    }
                    ?>

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
                                    $clNIC = $row['clientNIC'];
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
                    <form method="post" class="form-inline m-2" action="selfedit.php">
                        <div class="form-group">
                            <input type="hidden" class="form-control m-2" name="clientNIC" value="<?php echo $clNIC; ?>">

                            <button type="submit" class="btn btn-primary form-control m-1">Edit</button>
                        </div>



                </div>
                <div class="card_header">
                    <h4 class="View m-5">Your Accounts</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Account Number</th>
                                <th>Account Type</th>
                                <th>Account Balance</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            mysqli_data_seek($result, 0);
                            if (isset($result) && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $accountNo = $row['accountNo'];
                            ?>
                                    <tr>
                                        <td><?php echo $row['accountNo'] ?></td>
                                        <td><?php echo $row['accountType'] ?></td>
                                        <td><?php echo $row['account_balance'] ?></td>

                                        <td>
                                            </form>
                                            <form action="usertransactionsview.php" class="form-group m-2">
                                                <input type="hidden" name="accountNo" id="" value="<?php echo $accountNo; ?>">
                                                <button name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-dark form-control" type="submit">View Transactions</button>
                                            </form>
                                            </form>
                                            <form action="usertransfer.php" class="form-group m-2">
                                                <input type="hidden" name="accountNo" id="" value="<?php echo $accountNo; ?>">
                                                <button class="btn btn-dark form-control" type="submit">Transfer</button>
                                            </form>
                                            </form>
                                            <form action="usermessage.php" class="form-group m-2">
                                                <input type="hidden" name="accountNo" id="" value="<?php echo $accountNo; ?>">
                                                <button class="btn btn-dark form-control" type="submit">Messages</button>
                                            </form>
                                        </td>



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




<?php include 'Components/userfooter.php' ?>