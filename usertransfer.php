<?php include '<Components/usersession.php' ?>
<?php include 'Components/userheader.php' ?>
<?php include 'Components/usernavigation.php' ?>
<?php include 'Functions/accountDetailsbyAccNo.php' ?>
<?php

if (isset($_GET['accountNo'])) {

    $accountNo = $_GET['accountNo'];
} else {

    echo "Account number not provided in the query string.";
}
?>


<div class="col m-5">

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card_header">

                        <form method="post" name="accountTo" class="form-control" onsubmit="return validateAccount()">
                            <div class="form-group mb-3 text-center">
                                <input type="text" name="accountTo" class="form-control">
                                <input type="hidden" class="form-control" name="accountNo"
                                    value="<?php echo isset($accountNo) ? htmlspecialchars($accountNo) : ''; ?>">
                                <button type="submit" class="btn btn-info m-3">Verify</button>

                                <a href="userdashboard.php" class="btn btn-danger form-control ">Back</a>


                            </div>

                        </form>

                        <!--  -->
                        <?php
                        // Check if the form has been submitted
                        $accountTo = '';
                        $accountRet = '';
                        $Tofname = '';
                        $Tolname = '';
                        if (isset($_POST['accountTo'])) {
                            // Get the account number from the form
                            $accountTo = htmlspecialchars($_POST['accountTo']);

                            // Include your database connection code here
                            include 'Functions/dbcon.php';

                            // Prepare and execute your SQL query
                            $query = "CALL getEverythingByAccNo(?)";
                            $stmt = mysqli_prepare($con, $query);
                            mysqli_stmt_bind_param($stmt, "s", $accountTo);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            if (!$result) {
                                die("Error executing stored procedure: " . mysqli_error($con));
                            }

                            // Display the fetched data, you can modify this part as needed
                            if (isset($result) && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $Tofname = $row['clientFName'];
                                $Tolname = $row['clentsLName'];
                                $accountRet = $row['accountNo'];
                            } else {
                                echo "<p>No Record Found</p>";
                            }

                            // Close the database connection
                            mysqli_close($con);
                        }
                        ?>
                        <div class="card-header text-center">
                            <h4>Account No: <?php echo $accountRet; ?></h4>
                            <h4>Account Name: <?php echo $Tofname . ' ' . $Tolname; ?></h4>

                        </div>
                        <div id="warningMessage2" class="text-danger justify-content-center align-items-center">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">



                <div class="card">
                    <?php


                    include 'Functions/dbcon.php';

                    // Prepare and execute your SQL query
                    $query = "CALL getEverythingByAccNo(?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "s", $accountNo);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (!$result) {
                        die("Error executing stored procedure: " . mysqli_error($con));
                    }

                    // Display the fetched data, you can modify this part as needed
                    if (isset($result) && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        // Display the data here
                    } else {
                        echo "<p>No Record Found</p>";
                    }

                    // Close the database connection
                    mysqli_close($con);

                    ?>
                    <?php
                    mysqli_data_seek($result, 0);
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
                        <form action="Functions/userfundTransfer.php" method="post"
                            onsubmit="return validateWithdrawal()">
                            <div class="form-group mb-3">
                                <label for="toAccount">To Account</label>
                                <input type="text" id="toAccount" placeholder="To Account" class="form-control"
                                    name="toAccount" value="<?php echo $accountRet ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="amount">Amount</label>
                                <input type="text" id="amount" placeholder="Amount" class="form-control" name="amount">
                                <input type="hidden" value="<?php echo $accountNo; ?>" class="form-control"
                                    name="account">
                            </div>

                            <div id="warningMessage" class="text-danger justify-content-center align-items-center">
                            </div>
                            <div class="text-center">
                                <button class="btn btn-info">Transfer</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>

        <?php include 'Components/userfooter.php' ?>

        <script>
        function validateWithdrawal() {
            var amountInput = document.querySelector('input[name="amount"]');
            var accountToInput = document.querySelector('input[name="toAccount"]');
            var warningMessage = document.getElementById('warningMessage');

            // Get the user-entered withdrawal amount and "To account" value as strings
            var amountStr = amountInput.value.trim();
            var accountToStr = accountToInput.value.trim();

            // Check if either input is empty or not a number
            if (amountStr === '' || isNaN(amountStr) || parseFloat(amountStr) === 0) {
                warningMessage.innerHTML = "Please enter a valid non-zero numeric amount.";
                return false;
            }

            if (accountToStr === '') { // Check if "To account" is empty
                warningMessage.innerHTML = "Please verify the account.";
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


        function validateAccount() {


            var accountToInput = document.querySelector('input[name="accountTo"]');
            var warningMessage2 = document.getElementById('warningMessage2');
            var accountNo = "<?php echo $accountNo; ?>";

            // Get the user-entered accountTo value
            var accountToStr = accountToInput.value.trim();

            // Check if it's not empty and contains only digits
            if (/^\d+$/.test(accountToStr)) {
                if (accountToStr === accountNo) {
                    warningMessage2.innerHTML = "You cannot transfer money to the same account.";
                    return false;
                } else {
                    return true;
                }
            } else {
                warningMessage2.innerHTML = "Please enter a valid account number.";
                return false;
            }


        }
        </script>