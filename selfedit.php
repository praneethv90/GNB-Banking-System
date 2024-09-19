<?php include '<Components/usersession.php' ?>
<?php include 'Components/userheader.php' ?>
<?php include 'Components/usernavigation.php' ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $clientNIC = $_POST["clientNIC"];

    include 'Functions/dbcon.php';
    $query = "CALL allClientInfo(?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $clientNIC);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error executing stored procedure: " . mysqli_error($con));
    }
} else {
    echo "No Client NIC Provided";
}



if (isset($result) && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); {


        $clientNIC = $row['clientNIC'];
        $clientFName = $row['clientFName'];
        $clentsLName = $row['clentsLName'];
        $clientContact = $row['clientContact'];
        $cleintAddress = $row['cleintAddress'];
        $clientBday = $row['clientBday'];
        $clentEmail = $row['clentEmail'];
    }
}


?>
<div class="card-body border text-center">
    <h3 style="font-family: sans-serif;" class="m-2">Edit Client</h3>
    <p>Please Enter your details and press the Edit button at the bottom</p>
    <form action="Functions/selfedit.php" method="post">
        <div class="fields m-2 fluid">
            <div class="form-group">
                <input type="hidden" class="form-control m-2" value="<?php echo $clientNIC; ?>" name="clientNIC">
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" value="<?php echo $clientFName; ?>" name="clientFname">
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" value="<?php echo $clentsLName; ?>" name="clientLname">
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" value="<?php echo $clientContact; ?>" name="clientContact">
            </div>
            <div class="form-group">
                <textarea class="form-control m-2" name="clientAddress"> <?php echo $cleintAddress; ?></textarea>
            </div>
            <div class="form-group">
                <input type="date" class="form-control m-2" value="<?php echo $clientBday; ?>" name="clientBday">
            </div>
            <div class="form-group">
                <input type="email" class="form-control m-2" value="<?php echo $clentEmail; ?>" name="clientEmail">
            </div>

        </div>
        <div class="form-group"><button class="submit btn btn-info form-control m-2" type="submit" name="editClient">Edit My Details</button></div>
    </form>
</div>


<?php include 'Components/userfooter.php' ?>