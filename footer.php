<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>
    <footer class="site-footer">
        <div class="container footer-container">
            <div class="footer-col">
                <h4>About The Blog Nest</h4>
                <p>The Blog Nest is a community-driven platform dedicated to sharing knowledge and empowering writers
                    and readers worldwide.</p>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="search.php">Search</a></li>
                    <?php if (isset($_SESSION['nama'])): ?>
                        <li><a href="profile.php">Profile</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact Us</h4>
                <p>Email: support@theblognest.com</p>
                <p>Phone: +1 234 567 8901</p>
                <p>Address: 123 Knowledge Rd, Learning City</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 The Blog Nest. All rights reserved.</p>
        </div>
    </footer>
    <div class="footer-overlay"></div>

</body>

</html>