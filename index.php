<?php
$articles = require 'php/artikel/read.php';
$categories = [
    ['slug' => 'webdev', 'name' => 'Web Development', 'icon' => '🌐', 'count' => 125],
    ['slug' => 'javascript', 'name' => 'JavaScript', 'icon' => '📜', 'count' => 98],
    ['slug' => 'python', 'name' => 'Python', 'icon' => '🐍', 'count' => 87],
    ['slug' => 'react', 'name' => 'React', 'icon' => '⚛️', 'count' => 76],
    ['slug' => 'database', 'name' => 'Databases', 'icon' => '💾', 'count' => 65],
    ['slug' => 'design', 'name' => 'UI/UX Design', 'icon' => '🎨', 'count' => 54]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="css/gaya.css">

</head>

<body>

    <?php include 'navbar.php'; ?>

    <section class="hero">
        <div class="container">
            <h1>Welcome to The Blog Nest</h1>
            <p>A Platform for Sharing Knowledge and Insights with the World</p>
            <div class="hero-buttons">
                <?php if (isset($_SESSION['nama'])) {
                    echo '<a href="createarticle.php" class="btn btn-primary">Create Now</a>';
                } else {
                    echo '<a href="register.php" class="btn btn-primary">Join Now</a>';
                    echo '<a href="login.php" class="btn btn-secondary">Login</a>';
                } ?>

            </div>
        </div>
    </section>

    <section class="section featured-articles">
        <div class="container">
            <div class="section-header">
                <h2>Featured Articles</h2>
                <a href="articles.php" class="view-all-link">View All Articles</a>
            </div>
            <div class="row">
                <?php foreach (array_slice($articles, 0, 5) as $article): ?>
                    <a href="readartikel.php?id=<?= urlencode($article['id']) ?>"
                        style="text-decoration: none; color: inherit;">
                        <div class="card" style="cursor: pointer;">
                            <h2><?= htmlspecialchars($article['judul']) ?></h2>
                            <?php if ($article['gambar']): ?>
                                <img src="<?= htmlspecialchars($article['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="gambar"
                                    style="max-width:200px;">
                            <?php endif; ?>
                            <p><?= nl2br(htmlspecialchars($article['kutipan'])) ?></p>
                            <p><strong>Kategori:</strong> <?= htmlspecialchars($article['kategori']) ?></p>
                            <p><strong>Tanggal:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section bg-light categories-section">
        <div class="container">
            <div class="section-header">
                <h2>Categories</h2>
                <a href="categories.php" class="view-all-link">View All Categories</a>
            </div>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <div class="category-icon"><?= htmlspecialchars($category['icon']) ?></div>
                        <h3><?= htmlspecialchars($category['name']) ?></h3>
                        <p><?= htmlspecialchars($category['count']) ?> Articles</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>



    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>