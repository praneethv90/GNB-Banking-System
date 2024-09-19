<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand">Goliath National Bank User Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end float-right" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
                <a class="nav-link active" aria-current="page" href="userdashboard.php"
                    class="btn btn-outline-danger btn-sm justify-content-end float-right">Dashboard</a>

                <?php
                if (isset($_SESSION['UserNickName'])) {
                    $username = $_SESSION['UserNickName'];
                    echo '<div class="nav-link active float-right">Hi, ' . $username . ' <a href="Functions/logout.php" class="btn btn-outline-danger btn-sm justify-content-end float-right">Log Out</a></div>';
                }
                ?>
            </div>
        </div>


    </div>
    </div>
    </div>
</nav>