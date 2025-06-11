<?php if (isset($_SESSION['id'])) {
    header('Location:login.php');
    exit;
} ?>
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
                    <h1 id="form-title">Create New Article</h1>

                    <form id="article-form">
                        <input type="hidden" id="article-id">

                        <div class="form-group">
                            <label for="judul" class="form-label">Title</label>
                            <input type="text" id="judul" class="form-control" placeholder="Enter article title"
                                required>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Categories</label>
                            <div id="kategori_id" class="checkbox-grid">
                            </div>
                            <div class="error-message"></div>
                        </div>



                        <div class="form-group">
                            <label for="gambar" class="form-label">Featured Image</label>
                            <input type="file" id="gambar" class="form-control" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label for="kutipan" class="form-label">Excerpt</label>
                            <textarea id="kutipan" class="form-control" rows="3"
                                placeholder="Brief summary of the article (shown in listings)" required></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="isi" class="form-label">Content</label>
                            <textarea id="isi" class="form-control" rows="15"
                                placeholder="Write your article content here..." required></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group text-center">
                            <button type="button" class="btn btn-secondary"
                                onclick="window.location.href='manage-articles.html'">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Article</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
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
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        });
    </script>
    <script>
        document.getElementById('article-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData();

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

            // Ambil semua kategori yang dicentang
            const selectedCategories = [];
            document.querySelectorAll('input[name="categories[]"]:checked').forEach(cb => {
                selectedCategories.push(cb.value);
            });

            formData.append('kategori_id', JSON.stringify(selectedCategories));

            fetch('php/artikel/create.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Artikel berhasil disimpan!');
                        window.location.href = 'index.php';
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