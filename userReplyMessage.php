<?php include 'Components/usersession.php' ?>
<?php include 'Components/userheader.php' ?>
<?php include 'Components/usernavigation.php' ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $accountNo = $_POST['accountNo'];


?>
    <div class="container text-center">
        <h2 class="mt-5">Send Your Message</h2>
        <form action="Functions\usersendmessage.php" method="post" class="col-5 mt-4 mx-auto">

            <div class="form-group">
                <input type="text" class="form-control" id="input1" name="accountNo" value="<?php echo $accountNo; ?> " readonly>
            </div>

            <div class="form-group">
                <label for="yourMessage">Your Message</label>
                <textarea class="form-control" id="yourMessage" name="message" title="Please enter your message (maximum 1000 characters)" maxlength="1000" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary m-2">Send</button>
        </form>
    </div>
<?php
}
?>


<?php include 'Components/userfooter.php' ?>