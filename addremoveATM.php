<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $accountNo = $_POST['accountNo'];

    include 'Functions/dbcon.php';
    $query = "CALL findATMfromaccount(?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $accountNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error executing stored procedure: " . mysqli_error($con));
    }
} else {
    echo "No Client NIC Provided";
}

?>
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h4>ATM card Information</h4>
            </div>
            <div class="card-body">
                <?php if (isset($result) && mysqli_num_rows($result) > 0) { ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Card No</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row = mysqli_fetch_assoc($result);
                            $ATMID = $row['ATMID'];
                            $cardNo = $row['cardNo'];
                            ?>
                            <tr>
                                <td><?php echo $cardNo; ?></td>
                                <td>
                                    <form action="Functions/removecard.php" method="post" onsubmit="return validateremove()">
                                        <input type="hidden" name="accountNo" value="<?php echo $accountNo; ?>">
                                        <input type="hidden" name="ATMID" value="<?php echo $ATMID; ?>">
                                        <button type="submit" name="deactivate" class="btn btn-danger">Deactivate</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No ATM card information found.</p>
                    <form action="Functions\addcard.php" method="post" onsubmit="return validateForm()">
                        <input type="hidden" name="accountNo" value="<?php echo $accountNo; ?>">
                        <div class="form-group">
                            <input type="text" class="form-control" id="ATMNO" name="ATMNO" pattern="[0-9]{16}" title="ATM must be 16 digits" placeholder="Enter your New 16 digit ATM No" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="atmPin" name="ATMpin" pattern="[0-9]{4}" title="ATM PIN must be 4 digits" placeholder="Enter your 4 digit ATM PIN" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirmAtmPin" name="confirmATMpin" pattern="[0-9]{4}" title="ATM PIN must be 4 digits" placeholder="Confirm your 4 digit ATM PIN" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Card</button>
                        <a href="admindashboard.php" class="btn btn-warning form-control m-2">Back to Dashboard</a>
                    </form>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

















<?php include 'Components/footer.php' ?>

<script>
    function validateForm() {
        var ATMpin = document.getElementById("atmPin").value;
        var confirmATMpin = document.getElementById("confirmAtmPin").value;

        if (ATMpin !== confirmATMpin) {
            alert("ATM PIN and confirmation PIN do not match. Please try again.");
            return false;
        }

        var ATMNO = document.getElementById("ATMNO").value;
        var accountNo = "<?php echo $accountNo; ?>";

        var confirmationMessage = "Are you sure you want to add " + ATMNO + " to account no " + accountNo + "?";

        if (confirm(confirmationMessage)) {
            return true;
        } else {
            return false;
        }
    }

    function validateremove() {

        var ATMNO = "<?php echo $cardNo; ?>";
        var accountNo = "<?php echo $accountNo; ?>";

        var confirmationMessage = "Are you sure you want to Remove " + ATMNO + " to account no " + accountNo + "?";

        if (confirm(confirmationMessage)) {
            return true;
        } else {
            return false;
        }
    }
</script>