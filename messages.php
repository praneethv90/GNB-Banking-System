<?php include 'Components/adminsession.php'; ?>
<?php include 'Components/header.php'; ?>
<?php include 'Components/navigation.php'; ?>

<div class="container">
    <?php
    if (isset($_SESSION['Adminmessage'])) {
        echo '<div class="alert alert-info">' . $_SESSION['Adminmessage'] . '</div>';
        unset($_SESSION['Adminmessage']);
    }
    ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Unread Messages</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Message ID</th>
                        <th>From</th>
                        <th>Receiver</th>
                        <th>Message</th>
                        <th>Sent Date</th>
                        <th> Actions</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $manager = 'manager';
                    include 'Functions/dbcon.php';
                    $query = "CALL viewUnreadMessages(?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "s", $manager);
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
                        echo '<form action="adminReplyMessage.php" method="post">';
                        echo '<input type="hidden" name="accountNo" value="' . $row['sender'] . '">';
                        echo '<button type="submit" class="btn btn-warning m-2">Reply</button>';
                        echo '</form>';

                        echo '<form action="creditDebit.php" method="post">';
                        echo '<input type="hidden" name="accountNo" value="' . $row['sender'] . '">';
                        echo '<button type="submit" class="btn btn-info m-2">View Account</button>';
                        echo '</form>';

                        echo '<form action="Functions\markAsRead.php" method="post">';
                        echo '<input type="hidden" name="messageID" value="' . $row['messageID'] . '">';
                        echo '<button type="submit" class="btn btn-success m-2">Mark as Read</button>';
                        echo '</form>';

                        echo '<form action="oldMseesages.php" method="post">';
                        echo '<input type="hidden" name="accountNo" value="' . $row['sender'] . '">';
                        echo '<button type="submit" class="btn btn-dark m-2">Old Messages</button>';
                        echo '</form>';
                        echo '</div>';
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'Components/footer.php'; ?>