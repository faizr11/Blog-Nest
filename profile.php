<?php
$articles = require 'php/artikel/readbyiduser.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit(); // Hentikan eksekusi script
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="nav-overlay"></div>

    <!-- Profile Section -->
    <section class="section">
        <div class="container">
            <div class="profile-header">
                <img src="https://via.placeholder.com/200" alt="Profile Avatar" class="profile-avatar">
                <div class="profile-info">
                    <h1><?php echo $_SESSION['nama']; ?></h1>

                    <p class="profile-bio">Web Developer & Technical Writer. Passionate about teaching and sharing
                        knowledge on web development technologies.</p>
                </div>
            </div>

            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number">15</span>
                    <span class="stat-label">Articles</span>
                </div>

                <div class="stat-item">
                    <span class="stat-number">1.2k</span>
                    <span class="stat-label">Views</span>
                </div>

                <div class="stat-item">
                    <span class="stat-number">Jan 2022</span>
                    <span class="stat-label">Joined</span>
                </div>
            </div>

            <div class="profile-actions mb-30">
                <a href="manage-articles.html" class="btn btn-primary">Manage Articles</a>
                <a href="#" class="btn btn-secondary" id="edit-profile-btn">Edit Profile</a>
            </div>

            <h2>Articles by <?php echo $_SESSION['nama']; ?></h2>

            <?php
            foreach ($articles as $article): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($article['judul']) ?></h2>
                    <?php if ($article['gambar']): ?>
                        <img src="<?= htmlspecialchars($article['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="gambar"
                                style="max-width:200px;">
                    <?php endif; ?>
                    <p><?= nl2br(htmlspecialchars($article['isi'])) ?></p>
                    <p><strong>Kategori:</strong> <?= htmlspecialchars($article['kategori']) ?></p>
                    <p><strong>Tanggal:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>

</html>