<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<?php include 'Functions/accountDetailsbyAccNo.php' ?>
<?php
if (isset($_SESSION['Adminmessage'])) {
    echo '<div class="alert alert-info">' . $_SESSION['Adminmessage'] . '</div>';
    unset($_SESSION['Adminmessage']);
}
?>
<div class="container m-6 mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card_header">
                    <h4 class="View m-5">
                    </h4>
                    <?php
                    if (isset($_GET['account'])) {
                        // If 'accountNo' exists in the URL, automatically run the search
                        $accountNo = htmlspecialchars($_GET['account']);

                        include 'Functions/dbcon.php';

                        $query = "CALL getEverythingByAccNo(?)";
                        $stmt = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($stmt, "s", $accountNo);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (!$result) {
                            die("Error executing stored procedure: " . mysqli_error($con));
                        }
                    } else {
                        // If 'accountNo' is not in the URL, manual search form will be displayed
                        echo '<form method="post" class="form-inline m-2">';
                        echo '<div class="form-group">';
                        echo '<input type="text" class="form-control m-2" name="accountNo" placeholder="Enter Account Number">';
                        echo '<button type="submit" class="btn btn-primary form-control m-1">Search</button>';
                        echo '<a href="admindashboard.php" class="btn btn-warning form-control m-1">Back to Dashboard</a>';
                        echo '</div>';
                        echo '</form>';
                    }
                    ?>
                </div>

                <div class="card-body table-responsive">

                    <?php
                    if (isset($row['accountStatus']) && $row['accountStatus'] == 'Inactive') {
                        echo "The account was closed on " . $row['closedDate'];
                    } else {

                    ?>
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
                    <?php
                    }
                    ?>
                </div>
                <div class="card_header">

                    <h4 class="View m-5"> Account No: <?php
                                                        if (isset($row['accountNo'])) {
                                                            echo $row['accountNo'];
                                                        } else {
                                                            echo "No account selected. Please check the account number or check the Account Status with the Account holder NIC";
                                                        }
                                                        ?></h4>
                </div>
                <div class="card-body">
                    <?php if (!isset($row['accountNo'])) : ?>
                        <p>No account selected</p>
                    <?php else : ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Account Number</th>
                                    <th>Account Type</th>
                                    <th>Account Balance</th>
                                    <th>Actions</th>
                                    <th>Account Status</th>

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

                                            <td>
                                                <?php if ($row['accountType'] == 'saving') { ?>
                                                    <!-- Buttons for saving accounts -->

                                                    <form action="cashWithdraw.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="cash">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-danger btn-sm">Withdraw</button>
                                                    </form>

                                                    <form action="deposit.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cash">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-primary btn-sm">Cash Deposit</button>
                                                    </form>
                                                    <form action="deposit.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-success btn-sm">Cheque Deposit</button>
                                                    </form>

                                                    <form action="transfer.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-info btn-sm">Transfer</button>
                                                    </form>
                                                    <form action="addremoveATM.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-warning btn-sm">ATM</button>
                                                    </form>
                                                    <form action="adminReplyMessage.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-secondary btn-sm">Send Message</button>
                                                    </form>
                                                    <form action="oldMseesages.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-dark btn-sm">Old messages</button>
                                                    </form>
                                                <?php } elseif ($row['accountType'] == 'current') { ?>
                                                    <!-- Buttons for current accounts -->
                                                    <form action="checqueWithdraw.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-warning btn-sm">Withdraw</button>
                                                    </form>

                                                    <form action="deposit.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-success btn-sm">Deposit</button>
                                                    </form>
                                                    <form action="addchequebook.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="ChequeD">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-primary btn-sm">Add Chequebook</button>
                                                    </form>
                                                    <form action="adminReplyMessage.php" method="post" class="d-inline mx-2">
                                                        <input type="hidden" name="cash" value="Cheque">
                                                        <button type="submit" name="accountNo" value="<?= $row['accountNo'] ?>" class="btn btn-secondary btn-sm">Send Message</button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row['accountStatus'] ?></td>

                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No Record Found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <form action="creditDebit.php" class="form-group m-2">
                            <button class="btn btn-dark form-control" type="submit">Reset</button>
                        </form>
                        <form action="transactionsview.php" class="form-group m-2">
                            <input type="hidden" name="accountNo" id="" value="<?php echo $accountNo; ?>">
                            <button class="btn btn-dark form-control" type="submit">View Transactions</button>
                        </form>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Components/footer.php' ?>