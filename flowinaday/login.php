<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_open.php";


$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $dbc->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Verify hashed password
        if (password_verify($password, $row["password"])) {
            $_SESSION["userid"] = $row["id"]; 
            $_SESSION["username"] = $row["username"];
            $_SESSION["usertype"] = $row["usertype"];
            $_SESSION['logged_in'] = true;
            if ($row["usertype"] == "admin") {
                header("Location: usermanagement.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error_msg = "Username or password incorrect";
        }
    } else {
        $error_msg = "Username or password incorrect";
    }
    $stmt->close();
}
include "db_close.php";
?>
<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php'; ?>
    <title>Login</title>
    <script src="script.js"></script> 
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="login">
            <h1>Login</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="username">Username *</label>
                <input type="text" name="username" id="username" required><br>

                <label for="password">Password *</label>
                <input type="password" name="password" id="password" required><br>

                <input type="submit" value="Login">
                <?php
                    if (!empty($error_msg)) {
                        echo '<p style="color:red;">' . htmlspecialchars($error_msg) . '</p>';
                    }
                ?>
            </form>
            <p>
                Don't have an account?<br>
                <a href="register.php">Register here</a>
            </p>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
