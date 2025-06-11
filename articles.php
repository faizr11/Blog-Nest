<?php
$articles = require 'php/artikel/read.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="css/gaya.css">

</head>

<body>

    <?php include 'navbar.php';
    foreach ($articles as $article): ?>
        <div class="card">
            <h2><?= htmlspecialchars($article['judul']) ?></h2>
            <?php if ($article['gambar']): ?>
                <img src="<?= htmlspecialchars($article['gambar']) ?>" alt="gambar" style="max-width:200px;">
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($article['isi'])) ?></p>
            <p><strong>Kategori:</strong> <?= htmlspecialchars($article['kategori']) ?></p>
            <p><strong>Tanggal:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></p>
        </div>
    <?php endforeach; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>