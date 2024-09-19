<?php
session_start();


if (isset($_SESSION['UserName'])) {
    // Check the last activity time (assuming you have previously set 'last_activity' in the session)
    $last_activity = isset($_SESSION['last_activity']) ? $_SESSION['last_activity'] : 0;

    // Check if the user has been inactive for 15 minutes (900 seconds)
    if (time() - $last_activity > 900) {
        // Unset the session variable
        unset($_SESSION['UserName']);
    }

    // Update the last activity time
    $_SESSION['last_activity'] = time();
}

unset($_SESSION['UserName']);
// Destroy the session
session_destroy();

// Redirect the user to a login page or any other desired page
header("Location: ../adminlogin.php");
exit();
