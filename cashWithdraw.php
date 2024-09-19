<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<?php include 'Functions/accountDetailsbyAccNo.php' ?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <div class="card">

                <?php
                if (isset($result) && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    $fname = $row['clientFName'];
                    $lname = $row['clentsLName'];
                    $balance = $row['account_balance'];
                }
                ?>
                <div class="card-header text-center">
                    <h4>Account No: <?php echo $accountNo; ?></h4>
                    <h4>Account Name: <?php echo $fname . ' ' . $lname; ?></h4>
                    <h4>Balance: <?php echo $balance; ?></h4>
                </div>
                <div class="card-body">
                    <form action="Functions/cashWithdraw.php" method="post" onsubmit="return validateWithdrawal()">
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Amount" class="form-control" name="amount">
                            <input type="hidden" value="<?php echo $accountNo; ?>" class="form-control" name="account">
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-group mb-3">
                                <input type="text" value="withdrawal" class="form-control" readonly name="type">
                            </div>
                            <div id="warningMessage" class="text-danger justify-content-center align-items-center">
                            </div>
                            <div class="text-center">
                                <button class="btn btn-info">Withdraw</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Components/footer.php' ?>

<script>
    function validateWithdrawal() {
        var amountInput = document.querySelector('input[name="amount"]');
        var warningMessage = document.getElementById('warningMessage');

        // Get the user-entered withdrawal amount as a string
        var amountStr = amountInput.value.trim();

        // Check if the input is empty, not a number, or equal to zero
        if (amountStr === '' || isNaN(amountStr) || parseFloat(amountStr) === 0) {
            warningMessage.innerHTML = "Please enter a valid non-zero numeric amount.";
            return false;
        }

        var balance = parseFloat("<?php echo $balance; ?>");
        var amount = parseFloat(amountStr);

        if (amount > balance) {
            warningMessage.innerHTML = "Amount cannot be higher than the balance.";
            return false;
        }

        return true;
    }
</script>