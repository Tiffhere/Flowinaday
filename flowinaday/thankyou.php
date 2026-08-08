<?php
// Start session 
session_start();

// Check if form data exists
if (!isset($_SESSION['form_data'])) {
    header("Location: contact.php"); // Redirect back if no data
    exit();
}

// Retrieve and clear session data
$formData = $_SESSION['form_data'];
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include 'html_head.php' ?>
    </head>
    <body>
        <?php include 'header.php' ?> 
        <main class="thankyou-page">
            <section>
                <h1>Thank You,&nbsp;<?php echo htmlspecialchars($formData['firstName'])?> </h1>
                <p>Name: <?php echo htmlspecialchars($formData['firstName'] . ' ' . $formData['lastName']) ?></p>
                <p>Email: <?php echo htmlspecialchars($formData['email']) ?></p>
                <?php if (!empty($formData['phone'])): ?>
                    <p>Phone: <?php echo htmlspecialchars($formData['phone']) ?></p>
                <?php endif; ?>
                <?php if (!empty($formData['message'])): ?>
                    <p>Message: <?php echo nl2br(htmlspecialchars($formData['message'])) ?></p>
                <?php endif; ?>
            </section>
        </main>
        <?php include 'footer.php' 
        
        ?>
    </body>
</html>
