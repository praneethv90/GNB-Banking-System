<?php include 'Components/header.php' ?>
<?php include 'Components/navigation.php' ?>



<div class="login-container">

    <form action="Functions/adminlogin.php" method="post">
        <div class="input-container">
            <input type="text" placeholder="Username" name="username" required>
        </div>
        <div class="input-container">
            <input type="password" placeholder="Password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="index.php" class="btn btn-danger">Home</a>
    </form>
</div>

<?php


if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
    echo '<div class="message">' . $_SESSION['message'] . '</div>';

    unset($_SESSION['message']);
}

?>

<?php include 'Components/footer.php' ?>