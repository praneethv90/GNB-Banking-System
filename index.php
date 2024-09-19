<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goliath National Bank Homepage</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/indexpage.css">

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Goliath National Bank</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#section1">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#section2">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#section3">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminlogin.php">Admin Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="userlogin.php">User Login</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section id="section1" class="jumbotron text-center">
        <h1>Welcome to Goliath National Bank</h1>
        <p>Your trusted banking partner</p>
    </section>

    <section id="section2" class="container">
        <h2>Our Services</h2>
        <p>Explore the wide range of financial services we offer.</p>
    </section>

    <section id="section3">
        <div id="pictureCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#pictureCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#pictureCarousel" data-slide-to="1"></li>
                <li data-target="#pictureCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images\carousel_1.jpg" class="d-block w-100" alt="Image 1">
                </div>
                <div class="carousel-item">
                    <img src="images\carousel_2.jpg" class="d-block w-100" alt="Image 2">
                </div>
                <div class="carousel-item">
                    <img src="images\carousel_3.jpg" class="d-block w-100" alt="Image 3">
                </div>
            </div>
            <a class="carousel-control-prev" href="#pictureCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#pictureCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </section>

    <section id="section3" class="container">
        <h2>About Us</h2>
        <p>Learn more about our bank's history and mission.</p>
    </section>

    <section id="section4" class="container">
        <h2>ATM</h2>
        <p>Enter here for ATM transactions</p>
        <a href="ATM.php">ATM Simulator</a>
    </section>

    <!-- Add Bootstrap JavaScript and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>