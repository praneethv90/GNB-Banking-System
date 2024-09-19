<?php include 'Components/usersession.php' ?>
<?php include 'Components/userheader.php' ?>
<?php include 'Components/usernavigation.php' ?>

<?php $accountNo = ''; ?>
<div class="container">
    <?php
    if (isset($_SESSION['usermessage'])) {
        echo '<div class="alert alert-info">' . $_SESSION['usermessage'] . '</div>';
        unset($_SESSION['usermessage']);
    }
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Unread Messages</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Message ID</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Message</th>
                        <th>Sent Date</th>
                        <th>Actions</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET["accountNo"])) {
                        $accountNo = $_GET["accountNo"];

                        include 'Functions/dbcon.php';
                        $query = "CALL viewUnreadMessages(?)";
                        $stmt = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($stmt, "s", $accountNo);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (!$result) {
                            die("Error executing stored procedure: " . mysqli_error($con));
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['messageID'] . "</td>";
                            echo "<td>" . $row['sender'] . "</td>";
                            echo "<td>" . $row['reciever'] . "</td>";
                            echo "<td>" . $row['message'] . "</td>";
                            echo "<td>" . $row['sentDate'] . "</td>";

                            echo "<td>";
                            echo '<div class="btn-group" role="group" aria-label="Action buttons">';
                            echo '<form action="userReplyMessage.php" method="post">';
                            echo '<input type="hidden" name="accountNo" value="' . $accountNo . '">';
                            echo '<button type="submit" class="btn btn-warning m-2">Reply</button>';
                            echo '</form>';

                            echo '<form action="Functions\usermarkAsRead.php" method="post">';
                            echo '<input type="hidden" name="messageID" value="' . $row['messageID'] . '">';
                            echo '<input type="hidden" name="reciever" value="' . $row['reciever'] . '">';
                            echo '<button type="submit" class="btn btn-success m-2">Mark as Read</button>';
                            echo '</form>';


                            echo '</div>';
                            echo "</td>";

                            echo "</tr>";
                        }
                        if (mysqli_num_rows($result) == 0) {
                            echo '<tr><td colspan="6">No messages available</td></tr>';
                            echo '<form action="userReplyMessage.php" method="post">';
                            echo '<input type="hidden" name="accountNo" value="' . $accountNo . '">';
                            echo '<button type="submit" class="btn btn-success m-2">Send Message</button>';
                            echo '</form>';

                            echo '<form action="useroldMessages.php" method="post">';
                            echo '<input type="hidden" name="accountNo" value="' . $accountNo . '">';
                            echo '<button type="submit" class="btn btn-dark m-2">Old Messages</button>';
                            echo '</form>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No messages available</td></tr>';
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'Components/userfooter.php' ?>