<?php
session_start();
$categories = require '../php/categories/read.php';
if ($_SESSION['role'] !== 'admin') {
  header("Location: ../index.php");
  exit(); // Hentikan eksekusi script

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The Blog Nest - Categories</title>
  <link rel="stylesheet" href="../css/author.css">
</head>

<body>
  <?php include 'sidebar.php'; ?>
  <div class="main-content" style="margin-left: 220px; padding: 20px;">
    <button onclick="openModal(true)" class="btn btn-primary" style="margin-bottom: 20px;">
      ➕ Add Category
    </button>

    <div class="table">
      <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $category): ?>
            <tr>
              <td>
                <?= htmlspecialchars($category['nama']) ?>
              </td>
              <td class="action-buttons">
                <a href="#" class="icon-btn edit-btn" data-id="<?= $category['id'] ?>"
                  data-nama="<?= htmlspecialchars($category['nama'], ENT_QUOTES, 'UTF-8') ?>" title="Edit">&#9998;</a>

                <button class="icon-btn delete-btn" data-id="<?= $category['id'] ?>" title="Delete">&#128465;</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal Edit Category -->
  <div class="modal" id="edit-profile-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Category</h2>
        <button class="close-modal" id="close-modal">×</button>
      </div>

      <form id="edit-profile-form" action="../php/categories/update.php" method="POST">
        <input type="hidden" name="id" id="profile-id">
        <div class="form-group">
          <label for="profile-name">Name</label>
          <input type="text" id="profile-name" name="name" class="form-control" required>
        </div>
        <div class="form-group text-center">
          <button type="submit" class="btn btn-primary">Update Category</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(isAdd = false) {
  const modal = document.getElementById("edit-profile-modal");
  const form = document.getElementById("edit-profile-form");

  if (isAdd) {
    modal.querySelector("h2").innerText = "Add Category";
    form.action = "../php/categories/create.php";
    form.reset();
    document.getElementById("profile-id").value = "";
  } else {
    modal.querySelector("h2").innerText = "Edit Category";
    form.action = "../php/categories/update.php";
  }

  modal.style.display = "block";

  // Tutup modal saat klik tombol close
  document.getElementById("close-modal").addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Tutup modal saat klik di luar modal content
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
}


      document.addEventListener("DOMContentLoaded", () => {
        // Delete
        document.querySelectorAll(".delete-btn").forEach(button => {
          button.addEventListener("click", function (e) {
            e.preventDefault();
            const id = button.dataset.id;
            if (confirm("Do you really want to delete this category?")) {
              fetch("../php/categories/delete.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${encodeURIComponent(id)}`
              })
                .then(res => res.json())
                .then(data => {
                  if (data.success) {
                    alert("Category deleted successfully.");
                    window.location.reload();
                  } else {
                    alert("Failed to delete: " + (data.message || "Something went wrong."));
                  }
                })
                .catch(err => {
                  console.error(err);
                  alert("Cannot delete: This category is still used in one or more articles..");
                });
            }
          });
        });

        // Edit
        document.querySelectorAll(".edit-btn").forEach(button => {
          button.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("profile-id").value = button.dataset.id;
            document.getElementById("profile-name").value = button.dataset.nama;
            openModal();
          });
        });
      });
  </script>
</body>

</html>