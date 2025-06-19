
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
</head>

<body>

    <header class="site-header">
        <div class="container header-container">
            <div class="logo"><a href="index.php">The Blog Nest</a></div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="search.php">Search</a></li>
                    <?php if (isset($_SESSION['nama'])) {
                        echo '<li><a href="profile.php">Profile</a></li>';
                    } ?>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php
                if (isset($_SESSION['nama'])) {
                    echo '<span>Welcome, ' . htmlspecialchars($_SESSION['nama']) . '</span>';
                } else {
                    echo '<a href="login.php" class="btn btn-secondary">Login</a>';
                    echo '<a href="register.php" class="btn btn-primary">Register</a>';
                }
                ?>
            </div>
        </div>
    </header>
    <div class="nav-overlay"></div>
</body>

</html>