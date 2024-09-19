<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location:../ATM.php');

    exit;
}


if (isset($_POST['ATM']) && isset($_POST['PIN'])) {

    $Card = $_POST['ATM'];
    $PIN = $_POST['PIN'];

    include 'dbcon.php';
    $query = "CALL geteverythingbyATM(?,?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $Card, $PIN);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (isset($result) && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $fname = $row['clientFName'];
        $lname = $row['clentsLName'];
        $account_balance = $row['account_balance'];
        $accountNo = $row['accountNo'];
    } else {

        header('Location: ../ATM.php');

        $_SESSION['ATMmessage'] = 'ATM number or PIN mismatch';

        exit;
    }

    if (!$result) {
        die("Error executing stored procedure: " . mysqli_error($con));
    }
} else {
    header('Location:../ATM.php');
    exit;
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATM Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/ATMstyle.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Account No: <?php echo $accountNo; ?></h4>
                        <h4>Account Name: <?php echo $fname . ' ' . $lname; ?></h4>
                        <h4>Balance: <?php echo $account_balance; ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="ATMwithdraw.php" method="post" onsubmit="return validateForm()">
                            <input type="hidden" name="fname" value="<?php echo $fname; ?>">
                            <input type="hidden" name="lname" value="<?php echo $lname; ?>">
                            <input type="hidden" name="accountNo" value="<?php echo $accountNo; ?>">
                            <input type="hidden" name="account_balance" value="<?php echo $account_balance; ?>">
                            <div class="form-group mb-3 text-center">
                                <label for="amount">Amount:</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter the amount">
                                <div id="amountError" class="text-danger"></div>
                            </div>

                            <div class="form-group mb-3 text-center">
                                <button type="submit" class="btn btn-success">Yes, Proceed</button>
                                <a href="../ATM.php" class="btn btn-danger">No, Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var amountInput = document.getElementById('amount');
            var amountError = document.getElementById('amountError');
            var amountValue = amountInput.value.trim();

            // Check if the amount is empty
            if (amountValue === '') {
                amountError.textContent = 'Amount cannot be empty';
                amountInput.focus();
                return false;
            }

            // Check if the amount contains only digits
            if (!/^\d+$/.test(amountValue)) {
                amountError.textContent = 'Amount must contain only digits';
                amountInput.focus();
                return false;
            }

            // Convert the balance and amount to numbers for comparison
            var balance = parseFloat(<?php echo $account_balance; ?>);
            var amount = parseFloat(amountValue);

            // Check if the amount is greater than the balance
            if (amount > balance) {
                amountError.textContent = 'Amount cannot be higher than the balance';
                amountInput.focus();
                return false;
            }

            // Clear any previous error messages
            amountError.textContent = '';

            // Form is valid, proceed with submission
            return true;
        }
    </script>
</body>

</html>