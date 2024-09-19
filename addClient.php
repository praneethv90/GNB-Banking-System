<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>

<div class="card-body border">
    <h3 style="font-family: sans-serif;" class="m-2">Add a New Client</h3>
    <form action="Functions/addClient.php" method="post"
        onsubmit="return confirm('Please double check the NIC number. Once added, it cannot be changed. Are you sure you want to proceed?');">
        <div class="fields m-2 fluid">
            <div class="form-group">
                <input type="text" class="form-control m-2" placeholder="First Name" name="clientFname" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" placeholder="Last Name" name="clientLname" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" placeholder="NIC" name="clientNIC" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control m-2" placeholder="Contact Number" name="clientContact" required>
            </div>
            <div class="form-group">
                <textarea class="form-control m-2" placeholder="Address" name="clientAddress" required></textarea>
            </div>
            <div class="form-group">
                <input type="date" class="form-control m-2" placeholder="Birthday" name="clientBday" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control m-2" placeholder="Email" name="clientEmail" required>
            </div>
            <div class="form-group m-2">
                <select class="form-control" name="accountType" required>
                    <option value="saving">Saving</option>
                    <option value="current">Current</option>
                </select>
            </div>
        </div>
        <div class="form-group"><button class="submit btn btn-info form-control m-2" type="submit"
                name="addNewClient">Add New Client</button></div>
        <a href="admindashboard.php" class="btn btn-warning form-control m-2">Back to Dashboard</a>
    </form>
</div>

<?php include 'Components/footer.php' ?>