<header>
        <nav>
            <a href="index.php"><img src="images/logo.png" class="logo"></a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="classes.php">Classes</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
                        <li>
                            <a href="usermanagement.php">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>'s Admin Profile
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="userProfile.php">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>'s Profile
                            </a>
                        </li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
            <button class="nav-toggle">
            &#9776; 
        </button>
        </nav>
</header> 