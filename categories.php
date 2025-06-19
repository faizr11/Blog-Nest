<?php
session_start();
$categories = require 'php/categories/read.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="css/gaya.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <section class="section bg-light categories-section">
        <div class="container">
            <div class="section-header">
                <h2>Categories</h2>
            </div>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                    <div class="category-card">
                        <h3><?= htmlspecialchars($category['nama']) ?></h3>
                        <p><?= htmlspecialchars($category['count']) ?> Articles</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>