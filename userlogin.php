<?php include 'Components/userheader.php' ?>
<?php include 'Components/usernavigation.php' ?>



<div class="login-container">

    <form action="Functions/userlogin.php" method="post">
        <div class="input-container">
            <input type="text" placeholder="username" name="username" required>
        </div>
        <div class="input-container">
            <input type="password" placeholder="Password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="index.php" class="btn btn-danger">Home</a>
    </form>
</div>

<?php

// Check if the message session variable is set and not empty
if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
    echo '<div class="message">' . $_SESSION['message'] . '</div>';
    // Clear the message so it doesn't show again on subsequent visits
    unset($_SESSION['message']);
}

?>

<?php include 'Components/userfooter.php' ?>