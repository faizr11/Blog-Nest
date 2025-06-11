
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Interaktif</title>
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
  <div class="login-container" role="main">
    <h2>Login</h2>
    <form id="loginForm" novalidate>
      <div class="input-group">
        <input type="text" id="email" name="email" placeholder=" " autocomplete="email" required />
        <label for="email">Email</label>
        <div class="error-message" id="emailError" style="display:none;">
          Email tidak boleh kosong.
        </div>
      </div>
      <div class="input-group">
        <input type="password" id="password" name="password" placeholder=" " autocomplete="current-password" required />
        <label for="password">Password</label>
        <div class="error-message" id="passwordError" style="display:none;">
          Password tidak boleh kosong.
        </div>
      </div>
      <button type="submit" class="btn-login">Masuk</button>
    </form>
    <p id="message" style="text-align:center; margin-top:15px;"></p>
  </div>

  <script>
    document.getElementById("loginForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const message = document.getElementById("message");
      message.textContent = ""; // reset pesan

      // Bisa tambah validasi sederhana di sini jika mau

      const formData = new FormData(this);

      try {
        const response = await fetch("php/login.php", {
          method: "POST",
          body: formData,
        });

        if (!response.ok) {
          throw new Error("Terjadi kesalahan pada server.");
        }

        const result = await response.json();

        message.textContent = result.message;
        message.style.color = result.success ? "green" : "red";

        if (result.success && result.redirect) {
          setTimeout(() => {
            window.location.href = result.redirect;
          }, 1500);
        }
      } catch (error) {
        message.textContent = "Gagal menghubungi server. Coba lagi nanti.";
        message.style.color = "red";
      }
    });
  </script>
</body>

</html>