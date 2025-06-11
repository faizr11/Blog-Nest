document.addEventListener("DOMContentLoaded", function () {
  // === LOGIN FORM HANDLING ===
  const loginForm = document.getElementById("loginForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const emailError = document.getElementById("emailError");
  const passwordError = document.getElementById("passwordError");

  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      emailError.style.display = "none";
      passwordError.style.display = "none";

      const email = emailInput.value.trim();
      const password = passwordInput.value.trim();
      let valid = true;

      if (email === "") {
        emailError.style.display = "block";
        valid = false;
      }
      if (password === "") {
        passwordError.style.display = "block";
        valid = false;
      }

      if (!valid) return;

      try {
        const response = await fetch("php/login.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({ email, password }),
        });

        const result = await response.text();

        if (response.ok) {
          if (result === "success") {
            alert("Login berhasil!");
            window.location.href = "index.html";
          } else {
            alert("Login gagal: " + result);
          }
        } else {
          alert("Terjadi kesalahan server.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Gagal menghubungi server.");
      }
    });
  }

  // === LOAD NAVBAR ===
  const navbarContainer = document.getElementById("navbar-container");
  if (navbarContainer) {
    fetch("navbar.php")
      .then(res => res.text())
      .then(html => {
        navbarContainer.innerHTML = html;
      })
      .catch(err => console.error("Gagal memuat navbar:", err));
  }

  // === LOAD CATEGORIES ===
  const categorySelect = document.getElementById("category");
  if (categorySelect) {
    fetch("php/kategori.php")
      .then(response => response.json())
      .then(data => {
        data.forEach(category => {
          const option = document.createElement("option");
          option.value = category.id;
          option.textContent = category.nama;
          categorySelect.appendChild(option);
        });
      })
      .catch(error => {
        console.error("Error fetching categories:", error);
      });
  }
});
