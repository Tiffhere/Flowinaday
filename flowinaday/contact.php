<?php
session_start();
include "db_open.php";

// Function definition 
function addContactData($dbc, $firstName, $lastName, $email, $phone, $message) {
    $sql = 'INSERT INTO Inquiry (FirstName, LastName, Email, Phone, Message) VALUES ("'.trim($firstName).'", "'.trim($lastName).'", "'.trim($email).'", "'.trim($phone).'", "'.trim($message).'")';
    if ($dbc->query($sql) === TRUE) {
        // Success
    } else {
        echo "Error: " . $sql . "<br>" . $dbc->error;
    }
}

// Initialize variables
$firstName = $lastName = $email = $phone = $message = "";
$firstNameErr = $lastNameErr = $emailErr = $phoneErr = "";

// Helper function
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Only process if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation
    if (empty($_POST["firstName"])) {
        $firstNameErr = "First name is required";
    } else {
        $firstName = test_input($_POST["firstName"]);
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $firstName)) {
            $firstNameErr = "Only letter, number and white space allowed";
        }
    }

    if (empty($_POST["lastName"])) {
        $lastNameErr = "Last name is required";
    } else {
        $lastName = test_input($_POST["lastName"]);
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $lastName)) {
            $lastNameErr = "Only letter, number and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    $phone = test_input($_POST["phone"]);
    if (!empty($phone) && !preg_match("/^[0-9\-\(\)\/\+\s]*$/", $phone)) {
        $phoneErr = "Invalid phone number format";
    }

    $message = test_input($_POST["message"]);

    // Only insert if no errors
    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($phoneErr)) {
        $_SESSION['form_data'] = [
            'firstName' => $firstName,
            'lastName'  => $lastName,
            'email'     => $email,
            'phone'     => $phone,
            'message'   => $message
        ];

        // Now insert into database
        addContactData($dbc, $firstName, $lastName, $email, $phone, $message);

        header("Location: thankyou.php");
        exit();
    }
}

include "db_close.php";
?>
<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php' ?>
</head>
<body class="contact body">
    <?php include 'header.php' ?>
    
    <main>
        <section class="contact">
            <h1>Contact Us</h1>
            <div class="contact-container">
            <div class="contact-image">
                <img src="images/contactimage.jpg" alt="Yoga Image">
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
                <label for="firstName">First Name *</label><br>
                <input type="text" name="firstName" value="<?php echo $firstName; ?>">
                <span class="error"><?php echo $firstNameErr; ?></span><br>

                <label for="lastName">Last Name *</label><br>
                <input type="text" name="lastName" value="<?php echo $lastName; ?>">
                <span class="error"><?php echo $lastNameErr; ?></span><br>

                <label for="email">Email Address *</label><br>
                <input type="text" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr; ?></span><br>

                <label for="phone">Phone Number</label><br>
                <input type="tel" name="phone" value="<?php echo $phone; ?>">
                <span class="error"><?php echo $phoneErr; ?></span><br>

                <label for="message">Message</label><br>
                <textarea name="message"><?php echo $message; ?></textarea><br>

                <input type="Submit" value="Submit">
            </form>
            </div>
        </section>
    </main>

    <?php include 'footer.php' ?>
</body>
</html>