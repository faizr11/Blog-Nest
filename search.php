<?php
session_start();
$articles = require 'php/artikel/read.php';

// Tangkap query dari form
$query = trim($_GET['q'] ?? '');

// Filter artikel berdasarkan judul
$filteredArticles = [];

if ($query !== '') {
    foreach ($articles as $article) {
        if (stripos($article['judul'], $query) !== false) {
            $filteredArticles[] = $article;
        }
    }
} else {
    $filteredArticles = $articles; // jika tidak ada query, tampilkan semua
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="css/gaya.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="section">
        <div class="container">
            <h1 class="text-center">Search Articles</h1>

            <form class="search-bar search-form mb-30" method="GET" action="">
                <input
                    type="text"
                    class="form-control search-input"
                    name="q"
                    placeholder="Search articles..."
                    value="<?= htmlspecialchars($query) ?>"
                >
                <button type="submit" class="search-button">🔍</button>
            </form>

            <?php if ($query !== ''): ?>
                <p class="text-center">Showing results for "<strong><?= htmlspecialchars($query) ?></strong>"</p>
            <?php endif; ?>

            <div class="row">
                <?php if (count($filteredArticles) > 0): ?>
                    <?php foreach ($filteredArticles as $article): ?>
                        <a class="card" href="readartikel.php?id=<?= urlencode($article['id']) ?>"
                            style="text-decoration: none; color: inherit;">
                            <div style="cursor: pointer;">
                                <h2><?= htmlspecialchars($article['judul']) ?></h2>
                                <?php if ($article['gambar']): ?>
                                    <img src="<?= htmlspecialchars($article['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="image">
                                <?php endif; ?>
                                <p><?= nl2br(htmlspecialchars($article['kutipan'])) ?></p>
                                <p><strong>Categorie:</strong> <?= htmlspecialchars($article['kategori']) ?></p>
                                <p><strong>Date:</strong> <?= htmlspecialchars($article['tanggal_publikasi']) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No articles found for your search.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>
