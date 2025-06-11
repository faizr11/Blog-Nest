
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
        <h2>Form Registrasi</h2>
        <form id="registerForm" method="POST" action="php/register.php">
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Daftar</button>
        </form>
        <p id="message"></p>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch("php/register.php", {
                method: "POST",
                body: formData
            });
            const result = await response.json();
            const message = document.getElementById("message");
            message.textContent = result.message;
            message.style.color = result.success ? "green" : "red";

            if (result.success && result.redirect) {
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1500);
            }
        });
    </script>
</body>
</html>
