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
            <span class="author"><strong>Penulis:</strong> <?= htmlspecialchars($article['creator_nama']) ?></span>
            <span class="category"><strong>Kategori:</strong> <?= htmlspecialchars($article['kategori_nama']) ?></span>
            <span class="date"><strong>Tanggal:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></span>
        </div>

        <?php if (!empty($article['gambar'])): ?>
            <div class="image-container">
                <img src="<?= "/TheBlogNest/" . htmlspecialchars($article['gambar']) ?>" alt="Gambar Artikel" class="featured-image">
            </div>
        <?php endif; ?>

        <div class="article-content">
            <?= nl2br(htmlspecialchars($article['isi'])) ?>
            </div>

            <div class="back-link">
                <a href="index.php">&larr; Kembali ke Daftar Artikel</a>
            </div>
    </div>
</body>
</html>
