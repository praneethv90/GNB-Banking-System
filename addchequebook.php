<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $accountNo = $_POST['accountNo'];


?>
<div class="container align-center">
    <h2 class="mt-5">Add Cheques to Account</h2>
    <form action="Functions\addchequestoaccount.php" method="post" class="mt-4">
        <div class="form-group">
            <input type="text" class="form-control" id="input1" name="accountNo" value="<?php echo $accountNo; ?>">
        </div>

        <div class="form-group">
            <label for="chequebookNO">Chequebook No</label>
            <input type="text" class="form-control" id="chequebookNO" name="chequebookNO" pattern="[0-9]{6}"
                title="Please enter a 6-digit ChequeBook number" required>
        </div>

        <div class="form-group">
            <label for="StartingNO">Starting No</label>
            <input type="text" class="form-control" id="StartingNO" name="StartingNO" pattern="[0-9]{6}"
                title="Please enter a valid 6-digit number higher than 10000" required>
        </div>

        <div class="form-group">
            <label>Select the Number of cheques:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="numberOption" id="radio25" value="25">
                <label class="form-check-label" for="radio25">25</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="numberOption" id="radio50" value="50">
                <label class="form-check-label" for="radio50">50</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="numberOption" id="radio100" value="100">
                <label class="form-check-label" for="radio100">100</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php
}
?>


<?php include 'Components/footer.php' ?>