<?php
$data = require 'php/artikel/readbyiduser.php';

$articles = $data['articles'];
$profile = $data['profile'];


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
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="nav-overlay"></div>

    <!-- Profile Section -->
    <section class="section">
        <div class="container">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($profile['foto'], ENT_QUOTES, 'UTF-8') ?>" alt="gambar"
                    style="max-width:200px;">
                <div class="profile-info">
                    <h1><?php echo $_SESSION['nama']; ?></h1>

                    <p class="profile-bio"><?php echo $_SESSION['bio']; ?></p>
                </div>
            </div>

            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number"> <?= htmlspecialchars($profile['total_artikel']) ?></span>
                    <span class="stat-label">Articles</span>
                </div>

                <div class="stat-item">
                    <span class="stat-number"> <?= htmlspecialchars($profile['tanggal_bergabung']) ?></span>
                    <span class="stat-label">Joined</span>
                </div>
            </div>

            <div class="profile-actions mb-30">
                <a onclick="openModal()" class="btn btn-primary">Edit Profile</a>
                <form action="php/logout.php" method="get"
                    onsubmit="return confirm('Are you sure you want to log out?');" style="display: inline;">
                    <button type="submit" class="btn btn-secondary">
                        Logout
                    </button>
                </form>

            </div>

            <h2>Articles by <?php echo $_SESSION['nama']; ?></h2>

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
                                <a href="createarticle.php?id=<?= urlencode($article['id']) ?>" class="icon-btn edit-btn"
                                    title="Edit">&#9998;</a>
                                <button class="icon-btn delete-btn" data-id="<?= $article['id'] ?>"
                                    title="Delete">&#128465;</button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    </section>





    <!-- Modal Edit Profile -->
    <div class="modal" id="edit-profile-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="close-modal" id="close-modal">×</button>
            </div>

            <form id="edit-profile-form" action="php/user/update.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile-name" class="form-label">Name</label>
                    <input type="text" id="profile-name" name="name" class="form-control"
                        value="<?= htmlspecialchars($_SESSION['nama']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="profile-email" class="form-label">Email</label>
                    <input type="email" id="profile-email" name="email" class="form-control"
                        value="<?= htmlspecialchars($_SESSION['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="profile-password" class="form-label">Password</label>
                    <input type="password" id="profile-password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="profile-bio" class="form-label">Bio</label>
                    <textarea id="profile-bio" name="bio" class="form-control"
                        rows="4"><?= htmlspecialchars($_SESSION['bio'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="profile-avatar" class="form-label">Upload Profile Picture</label>
                    <input type="file" id="profile-avatar" name="avatar" class="form-control" accept="image/*">
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>


    <?php include 'footer.php'; ?>

    <script>
        function openModal() {
            document.getElementById("edit-profile-modal").style.display = "block";
        }

        document.getElementById("close-modal").addEventListener("click", function () {
            document.getElementById("edit-profile-modal").style.display = "none";
        });

        window.addEventListener("click", function (event) {
            const modal = document.getElementById("edit-profile-modal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        document.addEventListener("DOMContentLoaded", () => {
            const deleteButtons = document.querySelectorAll(".delete-btn");

            deleteButtons.forEach(button => {
                button.addEventListener("click", (e) => {
                    e.preventDefault();

                    const articleId = button.dataset.id;

                    if (confirm("Do you really want to delete this article?")) {
                        fetch("php/artikel/delete.php", {
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