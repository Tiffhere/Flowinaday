<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db_open.php";

function addUserData($dbc, $username, $email, $hashedPassword, $usertype) {
    $query = "INSERT INTO user (username, email, password, usertype) VALUES (?, ?, ?, ?)";
    $stmt = $dbc->prepare($query);
    if (!$stmt) {
        return "Prepare failed: " . $dbc->error;
    }
    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $usertype);
    if (!$stmt->execute()) {
        return "Error adding user: " . $stmt->error;
    }
    $stmt->close();
    return true;
}

$SuccessMessage = "";
$usernameErr = $emailErr = $passwordErr = $usertypeErr = "";
$username = $email = $password = $usertype = "";

// Helper function
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = test_input($_POST["username"]);
    $password = test_input($_POST["password"]);
    $usertype = isset($_POST["usertype"]) ? test_input($_POST["usertype"]) : "";
    $email = test_input($_POST["email"]);

    do {
        // Validate Username
        if (empty($username)) {
            $usernameErr = "Username is required";
            break;
        } elseif (!preg_match("/^[a-zA-Z0-9-' ]*$/", $username)) {
            $usernameErr = "Only letters, numbers, and spaces allowed";
            break;
        }

        // Validate Email
        if (empty($email)) {
            $emailErr = "Email is required";
            break;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            break;
        }

        // Validate Password
        if (empty($password)) {
            $passwordErr = "Password is required";
            break;
        } elseif (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
            break;
        } elseif (!preg_match("/[A-Z]/", $password)) {
            $passwordErr = "Need at least one uppercase letter";
            break;
        } elseif (!preg_match("/[a-z]/", $password)) {
            $passwordErr = "Need at least one lowercase letter";
            break;
        } elseif (!preg_match("/[0-9]/", $password)) {
            $passwordErr = "Need at least one number";
            break;
        } elseif (!preg_match("/[\W]/", $password)) {
            $passwordErr = "Need at least one special character";
            break;
        }

        // Validate Usertype
        if (empty($usertype)) {
            $usertypeErr = "Select a user type";
            break;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Add to database
        $result = addUserData($dbc, $username, $email, $hashedPassword, $usertype);
        if ($result === true) {
            $SuccessMessage = "User added successfully";
            $username = $email = $password = $usertype = "";
        } else {
            $emailErr = $result; // or display elsewhere
        }

        $SuccessMessage = "User added successfully";
        // Reset fields
        $username = $email = $password = $usertype = "";
    } while(false);
}
?>

<!DOCTYPE html>
    <head>
        <?php include 'html_head.php' ?>
    </head>
    <body>
        <?php include 'header.php' ?>
       
    <main>
        <section class="createuser">
                <h2>New User</h2>

                
                <form action="" method="post">
                    <label>Username</label><br>
                    <input type="text" name="username" value="<?php echo $username ?>">
                    <span class="error"><?php echo $usernameErr ?></span><br>

                    <label>Email</label><br>
                    <input type="email" name="email" value="<?php echo $email ?>">
                    <span class="error"><?php echo $emailErr ?></span><br>

                    <label>Usertype</label><br>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="usertype" value="admin" <?php echo ($usertype == 'admin') ? 'checked' : '' ?>> Admin
                            </label>
                            <label>
                                <input type="radio" name="usertype" value="user" <?php echo (empty($usertype) || $usertype == 'user') ? 'checked' : ''; ?>> User
                            </label>
                        </div>
                        <span class="error"><?php echo $usertypeErr ?></span><br>
                    

                    <label>Password</label><br>
                    <input type="password" name="password" value="<?php echo $password ?>">
                    <span class="error"><?php echo $passwordErr ?></span><br>

                    <input type="submit" value="Submit">
                    
                    <?php if(!empty($SuccessMessage)): ?>
                        <div class="success">
                            <?php echo $SuccessMessage ?><br>
                            <a href="usermanagement.php">Back to Admin</a>
                        </div>
                    <?php endif; ?>
                 </form>
                
            
            
        </section>
    </main>
        <?php include 'footer.php' ?>
    </body>
</html>
<?php
include "db_close.php";
?>