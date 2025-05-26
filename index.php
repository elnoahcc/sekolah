<?php
session_start();

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Inisialisasi variabel
$error = '';
$success = '';
$debug_info = [];

try {
    // Cek apakah file koneksi.php ada
    if (!file_exists('koneksi.php')) {
        throw new Exception('File koneksi.php tidak ditemukan!');
    }
    
    include_once 'koneksi.php';
    
    // Inisialisasi database - pastikan class database ada di koneksi.php
    if (!class_exists('database')) {
        throw new Exception('Class database tidak ditemukan di koneksi.php!');
    }
    
    $db = new database();
    
    // Cek koneksi database
    if (!isset($db->koneksi) || !$db->koneksi) {
        throw new Exception("Koneksi database gagal!");
    }
    
    $debug_info[] = "Database connection: OK";
    
    // Cek apakah user sudah login
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        header("Location: dashboard.php");
        exit();
    }
    
    // Generate CSRF token jika belum ada
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $debug_info[] = "POST request received";
        
        // Validasi CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = "Token keamanan tidak valid!";
            $debug_info[] = "CSRF token validation failed";
        } else {
            $debug_info[] = "CSRF token validation: OK";
            
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember_me = isset($_POST['remember_me']) ? true : false;

            $debug_info[] = "Username: " . $username;
            $debug_info[] = "Password length: " . strlen($password);

            // Validasi input
            if (empty($username) || empty($password)) {
                $error = "Username dan password harus diisi!";
            } elseif (strlen($username) < 3) {
                $error = "Username minimal 3 karakter!";
            } elseif (strlen($password) < 6) {
                $error = "Password minimal 6 karakter!";
            } else {
                $debug_info[] = "Input validation: OK";
                
                // Cek tabel users ada atau tidak
                $check_table = $db->koneksi->query("SHOW TABLES LIKE 'users'");
                if ($check_table->num_rows === 0) {
                    throw new Exception("Tabel 'users' tidak ada!");
                }
                $debug_info[] = "Table 'users' exists: OK";
                
                // Query dengan prepared statement
                $sql = "SELECT id, username, password, email FROM users WHERE username = ? LIMIT 1";
                $stmt = $db->koneksi->prepare($sql);
                
                if (!$stmt) {
                    throw new Exception("Query preparation failed: " . $db->koneksi->error);
                }
                
                $debug_info[] = "Query prepared: OK";
                
                $stmt->bind_param("s", $username);
                if (!$stmt->execute()) {
                    throw new Exception("Query execution failed: " . $stmt->error);
                }
                
                $result = $stmt->get_result();
                $debug_info[] = "Query executed. Rows found: " . $result->num_rows;

                if ($user = $result->fetch_assoc()) {
                    $debug_info[] = "User found: " . $user['username'];
                    
                    // Verifikasi password
                    $login_success = false;
                    
                    // Cek apakah password di database sudah di-hash atau masih plain text
                    if (password_get_info($user['password'])['algo'] === null) {
                        // Password belum di-hash, bandingkan langsung
                        $debug_info[] = "Password is plain text, comparing directly";
                        if ($password === $user['password']) {
                            $debug_info[] = "Password match (plain text)";
                            $login_success = true;
                        } else {
                            $debug_info[] = "Password mismatch (plain text)";
                        }
                    } else {
                        // Password sudah di-hash, gunakan password_verify
                        $debug_info[] = "Password is hashed, using password_verify";
                        if (password_verify($password, $user['password'])) {
                            $debug_info[] = "Password match (hashed)";
                            $login_success = true;
                        } else {
                            $debug_info[] = "Password mismatch (hashed)";
                        }
                    }
                    
                    if ($login_success) {
                        $debug_info[] = "Login successful";
                        
                        // Regenerate session ID untuk keamanan
                        session_regenerate_id(true);
                        
                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'] ?? '';
                        $_SESSION['login_time'] = time();
                        $_SESSION['last_activity'] = time();
                        
                        $stmt->close();
                        
                        // FIXED: Direct redirect instead of using refresh header
                        header("Location: dashboard.php");
                        exit(); // Important: Always exit after redirect
                    } else {
                        $error = "Password salah!";
                        $debug_info[] = "Login failed: Password mismatch";
                    }
                } else {
                    $error = "Username tidak ditemukan!";
                    $debug_info[] = "Login failed: User not found";
                    
                    // Debug: Cek semua user yang ada
                    $all_users = $db->koneksi->query("SELECT username FROM users LIMIT 5");
                    $usernames = [];
                    while ($row = $all_users->fetch_assoc()) {
                        $usernames[] = $row['username'];
                    }
                    $debug_info[] = "Available usernames: " . implode(', ', $usernames);
                }
                
                if (isset($stmt)) {
                    $stmt->close();
                }
            }
        }
    }
    
} catch (Exception $e) {
    $error = "Terjadi kesalahan sistem: " . $e->getMessage();
    $debug_info[] = "Exception caught: " . $e->getMessage();
    error_log("Login error: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <title>Login Page - SMK Negeri 6 Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Halaman login website data SMK Negeri 6 Surakarta" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="dist/css/adminlte.css" />
    <style>
      .login-box {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 10px;
      }
      .card {
        border: none;
        border-radius: 10px;
      }
      .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
      }
      .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
      }
      .debug-info {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
        margin: 10px 0;
        font-family: monospace;
        font-size: 12px;
        max-height: 300px;
        overflow-y: auto;
      }
    </style>
  </head>
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo">
        <b>SMK</b> Negeri 6 Surakarta
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">
            Login Website Data<br />
            <small class="text-muted">SMK NEGERI 6 SURAKARTA</small>
          </p>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill"></i>
              <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <!-- Debug Information -->
          <?php if (!empty($debug_info)): ?>
            <div class="debug-info">
              <strong>Debug Information:</strong><br>
              <?php foreach ($debug_info as $info): ?>
                <?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?><br>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>" method="POST" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>" />
            
            <div class="input-group mb-3">
              <input
                name="username"
                type="text"
                class="form-control"
                placeholder="Username"
                value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : '' ?>"
                required
                minlength="3"
                maxlength="50"
                autocomplete="username"
              />
              <div class="input-group-text">
                <span class="bi bi-person"></span>
              </div>
            </div>
            
            <div class="input-group mb-3">
              <input
                name="password"
                type="password"
                class="form-control"
                placeholder="Password"
                required
                minlength="6"
                maxlength="255"
                autocomplete="current-password"
              />
              <div class="input-group-text">
                <span class="bi bi-lock-fill"></span>
              </div>
            </div>
            
            <div class="row">
              <div class="col-8">
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="remember_me"
                    id="remember_me"
                    value="1"
                  />
                  <label class="form-check-label" for="remember_me">
                    Remember Me
                  </label>
                </div>
              </div>
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                  </button>
                </div>
              </div>
            </div>
          </form>

          <div class="text-center mt-3">
            <p class="mb-1">
              <a href="forgot-password.php" class="text-decoration-none">
                <i class="bi bi-question-circle"></i> Lupa password?
              </a>
            </p>
            <p class="mb-0">
              <a href="register.php" class="text-decoration-none">
                <i class="bi bi-person-plus"></i> Daftar akun baru
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.js"></script>
    
    <script>
      // Form validation
      document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = document.querySelector('input[name="username"]').value.trim();
        const password = document.querySelector('input[name="password"]').value;
        
        if (username.length < 3) {
          e.preventDefault();
          alert('Username minimal 3 karakter!');
          return false;
        }
        
        if (password.length < 6) {
          e.preventDefault();
          alert('Password minimal 6 karakter!');
          return false;
        }
      });

      // Auto hide alerts after 10 seconds
      setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        });
      }, 10000);
    </script>
  </body>
</html>