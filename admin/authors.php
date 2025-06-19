<?php
session_start();
$users = require '../php/user/read.php';
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
                    <th>Name</th>
                    <th>Image</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Bio</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <a href="readartikel.php?id=<?= urlencode($user['id']) ?>"
                                style="text-decoration: none; color: inherit;">
                                <?= htmlspecialchars($user['nama']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if ($user['foto_profil']): ?>
                                <img src="<?= htmlspecialchars($user['foto_profil'], ENT_QUOTES, 'UTF-8') ?>" alt="image"
                                    style="max-width:100px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['status']) ?></td>
                        <td><?= htmlspecialchars($user['bio']) ?></td>
                        <td class="action-buttons">
                            <a href="#" class="icon-btn edit-btn" data-id="<?= $user['id'] ?>"
                                data-nama="<?= htmlspecialchars($user['nama'], ENT_QUOTES, 'UTF-8') ?>"
                                data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"
                                data-status="<?= htmlspecialchars($user['status'], ENT_QUOTES, 'UTF-8') ?>"
                                data-bio="<?= htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8') ?>">
                                &#9998;
                            </a>

                            <button class="icon-btn delete-btn" data-id="<?= $user['id'] ?>"
                                title="Delete">&#128465;</button>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="modal" id="edit-profile-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="close-modal" id="close-modal">×</button>
            </div>

            <form id="edit-profile-form" action="../php/user/updatebyadmin.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="profile-id"> <!-- hidden id -->

                <div class="form-group">
                    <label for="profile-name">Name</label>
                    <input type="text" id="profile-name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="profile-email">Email</label>
                    <input type="email" id="profile-email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="profile-status" class="form-label">Status</label>
                    <select id="profile-status" name="status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="profile-bio">Bio</label>
                    <textarea id="profile-bio" name="bio" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>

        </div>
    </div>

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
            // 🟡 Tombol Hapus
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function (e) {
                    e.preventDefault();
                    const id = button.dataset.id;
                    if (confirm("Do you really want to delete this User?")) {
                        fetch("../php/user/delete.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `id=${encodeURIComponent(id)}`
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    alert("User deleted successfully.");
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

            // 🟢 Tombol Edit
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function (e) {
                    e.preventDefault();

                    // Ambil data dari atribut data-*
                    document.getElementById("profile-id").value = button.dataset.id;
                    document.getElementById("profile-name").value = button.dataset.nama;
                    document.getElementById("profile-email").value = button.dataset.email;
                    document.getElementById("profile-status").value = button.dataset.status;
                    document.getElementById("profile-bio").value = button.dataset.bio;

                    openModal();
                });
            });
        });
    </script>


</body>

</html>