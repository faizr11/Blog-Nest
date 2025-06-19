<?php
session_start();
$articles = require 'php/artikel/read.php';
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

    <section class="section featured-articles">
        <div class="container">
            <div class="section-header">
                <h2>Featured Articles</h2>
            </div>
            <div class="row">
                <?php foreach ($articles as $article): ?>
                    <a class="card" href="readartikel.php?id=<?= urlencode($article['id']) ?>"
                        style="text-decoration: none; color: inherit;">
                        <div  style="cursor: pointer;">
                            <h2><?= htmlspecialchars($article['judul']) ?></h2>
                            <?php if ($article['gambar']): ?>
                                <img src="<?= htmlspecialchars($article['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="gambar">
                            <?php endif; ?>
                            <p><strong>kutipan:</strong><?= nl2br(htmlspecialchars($article['kutipan'])) ?></p>
                            <p class="kategori-text"><strong>Kategori:</strong> <?= htmlspecialchars($article['kategori']) ?></p>
                            <p><strong>Tanggal:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>