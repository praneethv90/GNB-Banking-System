<?php
require 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];




    // Connect to your MySQL database ($con should be defined elsewhere)
    // Example: $con = mysqli_connect("hostname", "username", "password", "database");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Initialize a variable to store the adminFname value
    $adminFnameValue = '';

    // Adding the client details to the clientinfo table using stored procedures.
    if ($stmt1 = mysqli_prepare($con, "CALL adminlogin(?,?)")) {
        mysqli_stmt_bind_param($stmt1, "ss", $username, $password);

        if (mysqli_stmt_execute($stmt1)) {
            // Get the result set as a regular MySQL result resource
            $result = mysqli_stmt_get_result($stmt1);

            // Check if there are any rows in the result set
            if (mysqli_num_rows($result) > 0) {
                // Fetch the first row and access the adminFname column
                $row = mysqli_fetch_assoc($result);
                $adminFnameValue = $row['adminFname'];
            }

            // Free the result set
            mysqli_free_result($result);
        } else {
            echo "Error executing first stored procedure: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt1);
    }

    // Close the database connection
    mysqli_close($con);

    // Start a session
    session_start();

    // Check if $adminFnameValue is empty (no results)
    if (empty($adminFnameValue)) {
        // Redirect to adminlogin.php if there are no results
        header("Location: ../adminlogin.php");
        $_SESSION['message'] = 'Please enter a valid username and Password';
        exit(); // Terminate the script
    }

    // Assign $adminFnameValue to the session variable 'UserName'
    $_SESSION['UserName'] = $adminFnameValue;
    $_SESSION['message'] = 'Welcome ' . $adminFnameValue;


    // Redirect to another page or perform other actions as needed
    header("Location: ../admindashboard.php");
}
