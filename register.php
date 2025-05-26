<?php
session_start();
include 'koneksi.php';
$db = new database;
$conn = $db->koneksi;
$error = '';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']); // bisa username atau email
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Validasi input
    if (empty($login) || empty($password)) {
        $error = "Username/Email dan Password harus diisi!";
    } else {
        // Cek apakah login menggunakan email atau username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Login dengan email
            $stmt = $conn->prepare("SELECT id, nama_lengkap, username, email, password FROM users WHERE email = ?");
        } else {
            // Login dengan username
            $stmt = $conn->prepare("SELECT id, nama_lengkap, username, email, password FROM users WHERE username = ?");
        }
        
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Login berhasil - buat session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // Jika remember me dicentang, buat cookie
                if ($remember) {
                    $token = bin2hex(random_bytes(16));
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 hari
                    
                    // Simpan token ke database (opsional - untuk keamanan lebih)
                    $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                    $stmt->bind_param("si", $token, $user['id']);
                    $stmt->execute();
                }
                
                // Update last login
                $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                
                // Redirect ke dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Username atau Email tidak ditemukan!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login - SMK Negeri 6 Surakarta</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="Login - SMK Negeri 6 Surakarta" />
    <meta name="author" content="SMK Negeri 6 Surakarta" />
    <meta name="description" content="Halaman login website data SMK Negeri 6 Surakarta" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../../../dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <h1><b>SMK Negeri 6</b> Surakarta</h1>
        </div>
        <div class="card-body login-card-body">
          <p class="login-box-msg">Masuk ke Akun Anda<br>Website Data SMK Negeri 6 Surakarta</p>
          
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>

          <?php if (isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <strong>Berhasil!</strong> Registrasi berhasil! Silakan login dengan akun Anda.
            </div>
          <?php endif; ?>

          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="loginForm">
            
            <!-- Username atau Email -->
            <div class="input-group mb-3">
              <div class="form-floating">
                <input
                  name="login"
                  id="login"
                  type="text"
                  class="form-control"
                  required
                  placeholder="Username atau Email"
                  value="<?php echo isset($_POST['login']) ? htmlspecialchars($_POST['login']) : ''; ?>"
                />
                <label for="login">Username atau Email</label>
              </div>
              <div class="input-group-text"><span class="bi bi-person-circle"></span></div>
            </div>

            <!-- Password -->
            <div class="input-group mb-3">
              <div class="form-floating">
                <input 
                  name="password" 
                  id="password" 
                  type="password" 
                  class="form-control" 
                  required 
                  placeholder="Password"
                />
                <label for="password">Password</label>
              </div>
              <button type="button" class="input-group-text bg-white border-start-0" onclick="togglePassword()" style="cursor: pointer;">
                <i class="bi bi-eye-slash" id="toggleIcon"></i>
              </button>
            </div>

            <!--begin::Row-->
            <div class="row">
              <div class="col-8">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" />
                  <label class="form-check-label" for="remember">
                    Ingat saya
                  </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">Masuk</button>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </form>

          <div class="social-auth-links text-center mt-2 mb-3">
            <a href="#" class="btn btn-block btn-danger" onclick="alert('Fitur login dengan Google belum tersedia')">
              <i class="bi bi-google me-2"></i> Masuk dengan Google
            </a>
          </div>

          <p class="mb-1 text-center">
            <a href="forgot-password.php">Lupa password?</a>
          </p>
          <p class="mb-0 text-center">
            <a href="register.php" class="text-center">Belum punya akun? Daftar di sini</a>
          </p>
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->

    <script>
      function togglePassword() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");

        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleIcon.classList.remove("bi-eye-slash");
          toggleIcon.classList.add("bi-eye");
        } else {
          passwordInput.type = "password";
          toggleIcon.classList.remove("bi-eye");
          toggleIcon.classList.add("bi-eye-slash");
        }
      }

      // Validasi form sebelum submit
      document.getElementById("loginForm").addEventListener("submit", function(e) {
        const login = document.getElementById("login").value.trim();
        const password = document.getElementById("password").value;

        if (login === "" || password === "") {
          alert("Username/Email dan Password harus diisi!");
          e.preventDefault();
          return false;
        }

        if (password.length < 6) {
          alert("Password minimal 6 karakter!");
          e.preventDefault();
          return false;
        }
      });

      // Auto focus ke input pertama
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('login').focus();
      });

      // Enter key navigation
      document.getElementById('login').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.getElementById('password').focus();
        }
      });
    </script>

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="../../../dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
  </body>
</html>