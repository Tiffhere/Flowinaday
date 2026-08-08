<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

// Initialize variables
$id = $username = $email = $usertype = "";
$usernameErr = $emailErr = $usertypeErr = $passwordErr = "";
$SuccessMessage = $errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Show the form with current user data
    if (!isset($_GET["id"])) {
        header("Location: usermanagement.php");
        exit();
    }

    $id = intval($_GET["id"]);
    $sql = "SELECT * FROM user WHERE id = $id";
    $result = $dbc->query($sql);

    if (!$result || $result->num_rows == 0) {
        header("Location: usermanagement.php");
        exit();
    }

    $row = $result->fetch_assoc();
    $username = $row["username"];
    $usertype = $row["usertype"];
    $email = $row["email"];
    // Do NOT prefill password for security
} else {
    // Handle form submission (update)
    $id = $_POST["id"];
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $usertype = isset($_POST["usertype"]) ? $_POST["usertype"] : "";
    $email = trim($_POST["email"]);

    // Validation
    if (empty($username)) {
        $usernameErr = "Username is required";
    }
    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }
    if (empty($usertype)) {
        $usertypeErr = "User type is required";
    }
    // Password is optional for edit, but if provided, validate
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        }
    }

    // If no validation errors, proceed
    if (empty($usernameErr) && empty($emailErr) && empty($usertypeErr) && empty($passwordErr)) {
        // Only update password if provided
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET username = ?, usertype = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bind_param("ssssi", $username, $usertype, $email, $hashedPassword, $id);
        } else {
            $sql = "UPDATE user SET username = ?, usertype = ?, email = ? WHERE id = ?";
            $stmt = $dbc->prepare($sql);
            $stmt->bind_param("sssi", $username, $usertype, $email, $id);
        }

        if ($stmt->execute()) {
            $SuccessMessage = "User updated successfully";
            $stmt->close();
            header("Location: usermanagement.php");
            exit();
        } else {
            $errorMessage = "Error updating user: " . $dbc->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php'; ?>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="edituser">
            <div>
                <h2>Edit User</h2>
                <?php if (!empty($errorMessage)): ?>
                    <div class="error"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    
                    <label>Username</label><br>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span class="error"><?php echo $usernameErr; ?></span><br>

                    <label>Email</label><br>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <span class="error"><?php echo $emailErr; ?></span><br>

                    <label>Usertype</label><br>
                    <input type="radio" name="usertype" value="admin" <?php echo ($usertype == 'admin') ? 'checked' : ''; ?>> Admin
                    <input type="radio" name="usertype" value="user" <?php echo ($usertype == 'user') ? 'checked' : ''; ?>> User<br>
                    <span class="error"><?php echo $usertypeErr; ?></span><br>

                    <label>Password (leave blank to keep unchanged)</label><br>
                    <input type="password" name="password" value="">
                    <span class="error"><?php echo $passwordErr; ?></span><br>

                    <input type="submit" value="Update">
                    
                    <?php if (!empty($SuccessMessage)): ?>
                        <div class="success">
                            <?php echo $SuccessMessage; ?>
                            <a href="usermanagement.php">Back to User Management</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
include "db_close.php";
?>
