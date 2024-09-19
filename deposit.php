<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<?php include 'Functions/accountDetailsbyAccNo.php' ?>


<?php
$cash = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cash = $_POST["cash"];
    // This variable is used to determine whether the deposit is cash or cheque
}
?>
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <div class="card">

                <?php
                if (isset($result) && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    $fname = $row['clientFName'];
                    $lname = $row['clentsLName'];
                }
                ?>
                <div class="card-header text-center">

                    <h4>Account No: <?php echo $accountNo; ?></h4>
                    <h4>Account Name: <?php echo $fname . ' ' . $lname; ?></h4>
                    <p>Please enter the amount, the method, and the remarks.</p>
                    <p>If it is a cheque deposit, please enter the cheque Number as the remark</p>
                </div>
                <div class="card-body">
                    <form action="Functions/deposit.php" method="post" onsubmit="return validateDeposit()">
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Amount" class="form-control" name="amount">
                            <input type="hidden" value="<?php echo $accountNo; ?>" class="form-control" name="account">
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-group mb-3">
                                <input type="text" value="<?php echo $cash; ?>" class="form-control" readonly name="type">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Remark" class="form-control" name="remark">
                        </div>

                        <div class="text-center">
                        </div>
                        <div id="warningMessage" class="text-danger justify-content-center align-items-center">
                        </div>
                        <button class="btn btn-info">Deposit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Components/footer.php' ?>

<!-- validating the input amount -->
<script>
    function validateDeposit() {
        var amountInput = document.querySelector('input[name="amount"]');
        var type = document.querySelector('input[name="type"]').value;
        var remark = document.querySelector('input[name="remark"]').value;
        var warningMessage = document.getElementById('warningMessage');

        var amountStr = amountInput.value.trim();

        // amount should not be empty or not a valid number
        if (amountStr === '' || isNaN(parseFloat(amountStr))) {
            warningMessage.innerHTML = "Please enter a valid numeric amount.";
            return false;
        }

        // the amount should not be 0 or negative
        var amount = parseFloat(amountStr);
        if (amount <= 0) {
            warningMessage.innerHTML = "Amount must be greater than zero.";
            return false;
        }
        // promting to enter the cheque number when a cheque is deposited
        if (type === 'cheque' || remark === '') {
            warningMessage.innerHTML = "Please Enter the Check Number as the Remark"
            return false;
        }
        return true;
    }

    document.querySelector('form').onsubmit = validateDeposit;
</script>