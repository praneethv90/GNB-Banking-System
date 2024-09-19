<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

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
<div class="card-body border">
    <h3 style="font-family: sans-serif;" class="m-2">Edit Client</h3>
    <form action="Functions/editClient.php" method="post"
        onsubmit="return confirm('Are you sure you want to edit these details?');">
        <div class="fields m-2 fluid">
            <div class="form-group">
                <input type="hidden" class="form-control m-2" value="<?php echo $clientNIC; ?>" name="clientNIC"
                    required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" value="<?php echo $clientFName; ?>" name="clientFname"
                    required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" value="<?php echo $clentsLName; ?>" name="clientLname"
                    required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" value="<?php echo $clientContact; ?>" name="clientContact"
                    required>
            </div>
            <div class="form-group">
                <textarea class="form-control m-2" name="clientAddress"
                    required><?php echo $cleintAddress; ?></textarea>
            </div>
            <div class="form-group">
                <input type="date" class="form-control m-2" value="<?php echo $clientBday; ?>" name="clientBday"
                    required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control m-2" value="<?php echo $clentEmail; ?>" name="clientEmail"
                    required>
            </div>
        </div>
        <div class="form-group"><button class="submit btn btn-info form-control m-2" type="submit"
                name="editClient">Edit Client</button></div>
        <a href="admindashboard.php" class="btn btn-warning form-control m-2">Back to Dashboard</a>
    </form>
</div>

<?php include 'Components/footer.php' ?>