<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Goliath National Bank Administration Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end float-right" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
                <a class="nav-link active" aria-current="page" href="admindashboard.php">Dashboard</a>


                <?php
                if (isset($_SESSION['UserName'])) {
                    $username = $_SESSION['UserName'];
                    echo '<div class="nav-link active float-right">Hi, ' . $username . ' <a href="Functions/adminlogout.php" class="btn btn-outline-danger btn-sm justify-content-end float-right">Log Out</a></div>';
                }
                ?>
            </div>
        </div>

    </div>
    </div>
    </div>
</nav>