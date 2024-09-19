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
    <div class="register-photo">
        <div class="form-container">
            <div class="image-holder">
                <img src="images/ATM.jpg" alt="ATM Image">
            </div>




            <form method="post" action="Functions/ATMverify.php">
                <?php
                session_start();
                if (isset($_SESSION['ATMmessage'])) {
                    echo '<div class="alert alert-info">' . $_SESSION['ATMmessage'] . '</div>';
                    unset($_SESSION['ATMmessage']);
                }
                ?>
                <h2 class="text-center"><strong>Enter</strong> ATM details</h2>
                <div class="form-group">
                    <input class="form-control" type="text" name="ATM" pattern="[0-9]{16}" title="ATM must be 16 digits" placeholder="Enter your 16 digit ATM No" required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="PIN" pattern="[0-9]{4}" title="PIN must be 4 digits" placeholder="Enter your 4 digit PIN" required>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Go!</button>
                    <a href="index.php" class="btn btn-warning btn-block">Home</a>

                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>