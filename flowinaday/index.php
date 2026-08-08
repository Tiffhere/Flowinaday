<?php
session_start(); 

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'html_head.php' ?>
    <link rel="stylesheet" href="style.css">
</head>
<body class="indexbody">
    <?php include 'header.php' ?>

    <script>
        window.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        console.log('User logged in:', window.isLoggedIn);
    </script>

    <main class="indexmain">
        <section>
            <?php if (!$isLoggedIn): ?>
            <h1>Flow in a Day</h1>
            <p>A Yoga Experience for Everyone</p>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>
            <p class="greeting">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <?php else: ?>
            <?php endif; ?>
        </section>

        <div class="classesBox">
            <a href="classes.php" class="class">
                <h2>Check all available classes here</h2>
            </a>
        </div>

        <div class="signInBox">
            <?php if ($isLoggedIn): ?>
                <!-- fixed it -->
            <?php else: ?>
                <a href="register.php" class="signin">
                    <h2>Join our community</h2>
                </a>
            <?php endif; ?>
        </div>
    </main>

    <script src="script.js" ></script>
    <?php include 'footer.php' ?>
</body>
</html>
