<?php
session_start();
include "db_open.php";

// Function to add user data
function addUserData($dbc, $username, $email, $password, $usertype) {
    $sql = 'INSERT INTO user (username, email, password, usertype) VALUES 
    ("'.trim($username).'", "'.trim($email).'", "'.trim($password).'", "'.trim($usertype).'")';   
    if ($dbc->query($sql) === TRUE) {
        // Success
    } else {
        echo "Error: " . $sql . "<br>" . $dbc->error;
    }
}

// Initialize variables
$username = $email = $password = $confirm_password = "";
$usernameErr = $emailErr = $passwordErr = $confirmPasswordErr = "";

// Sanitizing user data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Only process if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Username validation
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $username)) {
            $usernameErr = "Only letter, number and white space allowed";
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Password validation
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        } elseif (!preg_match("/[A-Z]/", $password)) {
            $passwordErr = "Password must include at least one uppercase letter";
        } elseif (!preg_match("/[a-z]/", $password)) {
            $passwordErr = "Password must include at least one lowercase letter";
        } elseif (!preg_match("/[0-9]/", $password)) {
            $passwordErr = "Password must include at least one number";
        } elseif (!preg_match("/[\W]/", $password)) { // \W matches any non-word character (special character)
            $passwordErr = "Password must include at least one special character";
        }
    }

    // Confirm password validation
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirm_password = test_input($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    // Only insert if no errors
    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        $_SESSION['form_data'] = [
            'username' => $username,
            'email'    => $email
        ];

        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $usertype = 'user'; // Set default user type
        addUserData($dbc, $username, $email, $hashedPassword, $usertype);
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['usertype'] = 'user';

        header("Location: index.php");
        exit();
    }
}

include "db_close.php";
?>
<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php' ?>
    <script src="script.js"></script> 
</head>
<body>
    <?php include 'header.php' ?>
    
    <main>
        <section class="contact">
            <h1>Register</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <label for="username">Username *</label><br>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
                <span class="error" id="usernameError"><?php echo $usernameErr; ?></span><br>

                <label for="email">Email Address *</label><br>
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error" id="emailError"><?php echo $emailErr; ?></span><br>

                <label for="password">Password *</label><br>
                <input type="password" name="password" id="password" value="">
                <span class="error" id="passwordError"><?php echo $passwordErr; ?></span><br>

                <label for="confirm_password">Confirm Password *</label><br>
                <input type="password" name="confirm_password" id="confirm_password" value="">
                <span class="error" id="confirmPasswordError"><?php echo $confirmPasswordErr; ?></span><br>

                <input type="Submit" value="Submit">
            </form>
        </section>
    </main>

    <?php include 'footer.php' ?>
</body>
</html>
