<?php
session_start();
$articles = [];
if (isset($_GET['id'])) {
    $articles = require 'php/artikel/readbyidartikel.php';
}
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Blog Nest - A Platform for Sharing Knowledge</title>
    <link rel="stylesheet" href="css/gaya.css">
    <link rel="stylesheet" href="css/createarticle.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 id="form-title"><?php echo isset($articles['id']) ? 'Edit Article' : 'Create New Article'; ?>
                    </h1>

                    <form id="article-form">
                        <input type="hidden" id="article-id" value="<?php echo $articles['id'] ?? ''; ?>">

                        <div class="form-group">
                            <label for="judul" class="form-label">Title</label>
                            <input type="text" id="judul" class="form-control" placeholder="Enter article title"
                                required value="<?php echo htmlspecialchars($articles['judul'] ?? ''); ?>">
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Categories</label>
                            <div id="kategori_id" class="checkbox-grid">
                                <!-- Kategori akan dimuat dengan JavaScript -->
                            </div>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="gambar" class="form-label">Featured Image</label>
                            <input type="file" id="gambar" class="form-control" accept=".jpg, .jpeg, .png">
                        </div>

                        <div class="form-group">
                            <label for="kutipan" class="form-label">Excerpt</label>
                            <textarea id="kutipan" class="form-control" rows="3" placeholder="Brief summary..."
                                required><?php echo htmlspecialchars($articles['kutipan'] ?? ''); ?></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="isi" class="form-label">Content</label>
                            <textarea id="isi" class="form-control" rows="15" placeholder="Write article content..."
                                required><?php echo htmlspecialchars($articles['isi'] ?? ''); ?></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group text-center">
                            <button type="button" class="btn btn-secondary"
                                onclick="window.location.href='index.php'">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Article</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const existingData = <?php echo json_encode($articles); ?>;

        document.addEventListener('DOMContentLoaded', function () {
            fetch('php/kategori.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('kategori_id');

                    data.forEach(category => {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'categories[]';
                        checkbox.value = category.id;
                        checkbox.id = `category-${category.id}`;
                        checkbox.classList.add('form-check-input');

                        const label = document.createElement('label');
                        label.htmlFor = checkbox.id;
                        label.classList.add('form-check-label');
                        label.textContent = category.nama;

                        const wrapper = document.createElement('div');
                        wrapper.classList.add('form-check');
                        wrapper.appendChild(checkbox);
                        wrapper.appendChild(label);

                        container.appendChild(wrapper);
                    });

                    // Tandai kategori yang sudah dipilih jika sedang edit
                    if (existingData.kategori_id) {
                        const selected = Array.isArray(existingData.kategori_id)
                            ? existingData.kategori_id
                            : JSON.parse(existingData.kategori_id);

                        selected.forEach(id => {
                            const checkbox = document.querySelector(`input[value="${id}"]`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }

                    // Isi data artikel jika mode edit
                    if (existingData.id) {
                        document.getElementById('judul').value = existingData.judul || '';
                        document.getElementById('kutipan').value = existingData.kutipan || '';
                        document.getElementById('isi').value = existingData.isi || '';
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        });

        document.getElementById('article-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            const articleId = document.getElementById('article-id').value;
            const title = document.getElementById('judul').value;
            const excerpt = document.getElementById('kutipan').value;
            const content = document.getElementById('isi').value;
            const imageFile = document.getElementById('gambar').files[0];

            formData.append('judul', title);
            formData.append('kutipan', excerpt);
            formData.append('isi', content);
            if (imageFile) {
                formData.append('gambar', imageFile);
            }

            const selectedCategories = [];
            document.querySelectorAll('input[name="categories[]"]:checked').forEach(cb => {
                selectedCategories.push(cb.value);
            });

            formData.append('kategori_id', JSON.stringify(selectedCategories));
            if (articleId) formData.append('id', articleId); // untuk update

            const url = articleId ? 'php/artikel/update.php' : 'php/artikel/create.php';

            fetch(url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Artikel berhasil disimpan!');
                        if ('<?= $_SESSION['role'] ?>' === 'admin') {
                            window.location.href = 'admin/articles.php';
                        } else {
                            window.location.href = 'index.php';
                        }
                    } else {
                        alert('Gagal menyimpan artikel: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error submitting article:', error);
                    alert('Terjadi kesalahan saat mengirim data.');
                });
        });
    </script>


    <?php include 'footer.php'; ?>
</body>

</html>