<?php
$article = require 'php/artikel/readbyidartikel.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['judul']) ?></title>
    <link rel="stylesheet" href="css/readartikel.css">
</head>
<body>
    <div class="container">
        <h1 class="article-title"><?= htmlspecialchars($article['judul']) ?></h1>

        <div class="meta">
            <span class="author"><strong>by</strong> <?= htmlspecialchars($article['creator_nama']) ?></span>
            <span class="category"><strong>Categorie:</strong> <?= htmlspecialchars($article['kategori']) ?></span>
            <span class="date"><strong>Date:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></span>
        </div>

        <?php if (!empty($article['gambar'])): ?>
            <div class="image-container">
                <img src="<?= htmlspecialchars($article['gambar']) ?>" alt="Image" class="featured-image">
            </div>
        <?php endif; ?>

        <div class="article-content">
            <?= nl2br(htmlspecialchars($article['isi'])) ?>
            </div>

            <div class="back-link">
                <a href="index.php">&larr; Back to Article List</a>
            </div>
    </div>
</body>
</html>
