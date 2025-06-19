<?php
session_start();
$articles = require '../php/artikel/read.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit(); // Hentikan eksekusi script

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="../css/author.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="table">
        <table border="1" cellpadding="10" cellspacing="0" style=" border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Excerpt</th>
                    <th>Categories</th>
                    <th>Publication Date</th>
                    <th>Actions</th>
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
                                <img src="<?= htmlspecialchars($article['gambar'], ENT_QUOTES, 'UTF-8') ?>" alt="image"
                                    style="max-width:100px;">
                            <?php endif; ?>
                        </td>
                        <td class="kutipan"><?= nl2br(htmlspecialchars($article['kutipan'])) ?></td>
                        <td><?= htmlspecialchars($article['kategori']) ?></td>
                        <td><?= htmlspecialchars($article['tanggal_publikasi']) ?></td>
                        <td class="action-buttons">
                            <a href="../createarticle.php?id=<?= urlencode($article['id']) ?>" class="icon-btn edit-btn"
                                title="Edit">&#9998;</a>
                            <button class="icon-btn delete-btn" data-id="<?= $article['id'] ?>"
                                title="Delete">&#128465;</button>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>

        document.addEventListener("DOMContentLoaded", () => {
            const deleteButtons = document.querySelectorAll(".delete-btn");

            deleteButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const articleId = button.dataset.id;

                    if (confirm("Do you really want to delete this article?")) {
                        fetch("../php/artikel/delete.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `id=${encodeURIComponent(articleId)}`
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    alert("Article deleted successfully.");
                                    window.location.reload();
                                } else {
                                    alert("Failed to delete: " + (data.message || "Something went wrong."));
                                }
                            })

                            .catch(err => {
                                console.error(err);
                                alert("An error occurred during deletion.");
                            });
                    }
                });
            });
        });
    </script>


</body>

</html>