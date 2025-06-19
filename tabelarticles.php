<?php
session_start();
$articles = require 'php/artikel/read.php';




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
</head>

<body>
<?php include 'sidebar.php'?>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Kutipan</th>
                <th>Kategori</th>
                <th>Tanggal Publikasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td>
                        <a href="readartikel.php?id=<?= urlencode($article['id']) ?>"
                            style="text-decoration: none; color: inherit;">
                            <?= htmlspecialchars($article['judul']) ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($article['gambar']): ?>
                            <img src="<?= htmlspecialchars($article['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="gambar"
                                style="max-width:100px;">
                        <?php endif; ?>
                    </td>
                    <td><?= nl2br(htmlspecialchars($article['kutipan'])) ?></td>
                    <td><?= htmlspecialchars($article['kategori']) ?></td>
                    <td><?= htmlspecialchars($article['tanggal_publikasi']) ?></td>
                    <td>
                        <a href="createarticle.php?id=<?= urlencode($article['id']) ?>" class="icon-btn edit-btn"
                            title="Edit">&#9998;</a>
                        <button class="icon-btn delete-btn" data-id="<?= $article['id'] ?>"
                            title="Delete">&#128465;</button>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>