<?php include '<Components/adminsession.php' ?>
<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>


<div class="container col text-center">

    <div class="d-flex m-3 text-center">

        <div class="card m-5" style="max-width: 18rem;">
            <img src="images\dash3.jpg" class="card-img-top" alt="Transactions">
            <div class="card-body">
                <h5 class="card-title">Transactions</h5>
                <p class="card-text">All sorts of services of all accounts including Deposits and Withdrawals</p>
                <a href="creditDebit.php" class="btn btn-primary m-3">Enter</a>
            </div>
        </div>

        <div class="card m-5" style="max-width: 18rem;">
            <img src="images\dash1.jpg" class="card-img-top" alt="Client Info">
            <div class="card-body">
                <h5 class="card-title">Client Information</h5>
                <p class="card-text">Search a client by their National Identity Card Number. Edit their details if
                    needed.</p>
                <a href="clientSummary.php" class="btn btn-primary m-3">Search Client</a>
            </div>
        </div>

        <div class="card m-5" style="max-width: 18rem;">
            <img src="images\dash5.jpg" class="card-img-top" alt="Account Opening">
            <div class="card-body">
                <h5 class="card-title">Account Opening</h5>
                <p class="card-text">Open Saving and Current Accounts for new clients.</p>
                <a href="addClient.php" class="btn btn-primary m-3">Open Account</a>
            </div>
        </div>

        <div class="card m-5" style="max-width: 18rem;">
            <img src="images\dash6.jpg" class="card-img-top" alt="Transactions">
            <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <p class="card-text">Correspondence portal with Clients for all their inquiries.</p>
                <a href="messages.php" class="btn btn-primary m-3">Messages</a>
            </div>
        </div>
    </div>
</div>
<?php include 'Components/footer.php' ?>