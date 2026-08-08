<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_open.php";


// check login state
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];
$username = $_SESSION['username'];

// email update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_email"])) {
    $new_email = mysqli_real_escape_string($dbc, $_POST["email"]);
    $update_sql = "UPDATE user SET email = '$new_email' WHERE id = '$userid'";
    if (mysqli_query($dbc, $update_sql)) {
        $success_message = "Email updated successfully.";
    } else {
        $error_message = "Failed to update email: " . mysqli_error($dbc);
    }
}

// user info
$user_sql = "SELECT username, email FROM user WHERE id = '$userid'";
$user_result = mysqli_query($dbc, $user_sql);
$user_row = mysqli_fetch_assoc($user_result);

// booking cancle
if (isset($_GET['cancel_booking_id'])) {
    $cancel_id = intval($_GET['cancel_booking_id']);
    $delete_sql = "DELETE FROM bookings WHERE booking_id = '$cancel_id' AND userid = '$userid'";
    if (mysqli_query($dbc, $delete_sql)) {
        $success_message = "Booking canceled successfully.";
    } else {
        $error_message = "Failed to cancel booking: " . mysqli_error($dbc);
    }
}

// booking info
$bookings_sql = "SELECT booking_id, classid, booking_time FROM bookings WHERE userid = '$userid'";
$bookings_result = mysqli_query($dbc, $bookings_sql);

include "db_close.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'html_head.php'; ?>
    <title>User Profile</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="usercontainer">
        <div><h2>Welcome, <?php echo htmlspecialchars($user_row['username']); ?>!</h2></div>

        <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

        <section>
            <h3>User Information</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user_row['username']); ?></p>

            <form method="POST" style="margin-top:10px;">
                <label><strong>Email:</strong></label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user_row['email']); ?>" required>
                <button class="btn-updateemail" type="submit" name="update_email">Update Email</button>
            </form>
        </section>
        <br>
        <hr>
        
        <section>
            <h3>My Bookings</h3>
            <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                <table class="userprofiletable" border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Booking ID</th>
                        <th>Class ID</th>
                        <th>Booking Time</th>
                        <th>Cancel</th>
                    </tr>
                    <?php while ($booking = mysqli_fetch_assoc($bookings_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['classid']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                            <td><a href="?cancel_booking_id=<?php echo $booking['booking_id']; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
