<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$error = '';
$debug_info = [];

try {
    if (!file_exists('koneksi.php')) {
        throw new Exception('File koneksi.php tidak ditemukan!');
    }
    include_once 'koneksi.php';

    if (!class_exists('database')) {
        throw new Exception('Class database tidak ditemukan!');
    }
    $db = new database();

    if (!isset($db->koneksi) || !$db->koneksi) {
        throw new Exception("Koneksi database gagal!");
    }

    $debug_info[] = "Database connection: OK";

    if (isset($_SESSION['username'])) {
        header("Location: dashboard.php");
        exit();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = "Token keamanan tidak valid!";
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Username dan password harus diisi!";
            } else {
                $sql = "SELECT id, username, password, email FROM users WHERE username = ? LIMIT 1";
                $stmt = $db->koneksi->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($user = $result->fetch_assoc()) {
                    $login_success = false;

                    if (password_get_info($user['password'])['algo'] === null) {
                        $login_success = $password === $user['password'];
                    } else {
                        $login_success = password_verify($password, $user['password']);
                    }

                    if ($login_success) {
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['email'] = $user['email'];
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Password salah!";
                    }
                } else {
                    $error = "Username tidak ditemukan!";
                }
                $stmt->close();
            }
        }
    }
} catch (Exception $e) {
    $error = "Terjadi kesalahan: " . $e->getMessage();
    $debug_info[] = $e->getMessage();
}
?>

<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>Login - SMK Negeri 6 Surakarta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Halaman login website data SMK Negeri 6 Surakarta" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #3B82F6;
            --primary-dark: #1E40AF;
            --primary-light: #93C5FD;
            --secondary-color: #64748B;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --info-color: #06B6D4;
            --light-color: #F8FAFC;
            --dark-color: #0F172A;
            --border-color: #E2E8F0;
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 400;
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Main Container */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            position: relative;
        }

        /* Video Section - Left Side */
        .video-section {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.7;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.4) 0%, rgba(30, 64, 175, 0.6) 100%);
            backdrop-filter: blur(2px);
        }

        .video-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
            padding: 2rem;
            max-width: 500px;
        }

        .school-logo-main {
            width: 120px;
            height: 120px;
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            backdrop-filter: blur(20px);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        .main-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: slideInLeft 1s ease-out;
        }

        .main-subtitle {
            font-size: 1.5rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 2rem;
            animation: slideInLeft 1s ease-out 0.2s both;
        }

        .school-motto {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.8;
            font-style: italic;
            animation: slideInLeft 1s ease-out 0.4s both;
        }

        /* Login Section - Right Side */
        .login-section {
            flex: 0 0 480px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .login-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(30px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
            z-index: 10;
            animation: slideInRight 1s ease-out;
        }

        .login-header {
            text-align: center;
            padding: 2.5rem 2rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: backgroundMove 20s linear infinite;
        }

        @keyframes backgroundMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(20px, 20px); }
        }

        .login-logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .login-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 2rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            color: var(--secondary-color);
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            height: 60px;
            padding: 1rem 1rem 1rem 3.5rem;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-2px);
        }

        .form-label {
            position: absolute;
            left: 3.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: 12px;
            font-size: 0.75rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
            font-size: 1.25rem;
            transition: all 0.3s ease;
            z-index: 5;
        }

        .form-control:focus ~ .input-icon {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .password-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .form-check {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .form-check-label {
            color: var(--secondary-color);
            font-weight: 500;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -12px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.5s ease-out;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #DC2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            background: linear-gradient(135deg, #9CA3AF 0%, #6B7280 100%);
        }

        .btn-login.loading .btn-text {
            opacity: 0;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Particles Effect */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: particleMove 8s linear infinite;
        }

        @keyframes particleMove {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(200px);
                opacity: 0;
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .login-section {
                flex: 0 0 420px;
            }
            
            .main-title {
                font-size: 3rem;
            }
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .video-section {
                flex: 0 0 40vh;
                min-height: 300px;
            }
            
            .login-section {
                flex: 1;
                min-height: 60vh;
            }
            
            .main-title {
                font-size: 2.5rem;
            }
            
            .main-subtitle {
                font-size: 1.25rem;
            }
            
            .video-content {
                padding: 1rem;
            }
            
            .school-logo-main {
                width: 80px;
                height: 80px;
            }
        }

        @media (max-width: 480px) {
            .login-body {
                padding: 1.5rem;
            }
            
            .form-control {
                height: 56px;
                padding: 0.875rem 0.875rem 0.875rem 3rem;
            }
            
            .form-label {
                left: 3rem;
            }
            
            .input-icon {
                left: 1rem;
                font-size: 1.1rem;
            }
            
            .main-title {
                font-size: 2rem;
            }
            
            .main-subtitle {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Video Section - Left Side -->
        <div class="video-section">
            <div class="video-container">
                <!-- Background Video -->
                <video class="background-video" autoplay muted loop playsinline>
                    <source src="../dist/assets/img/bg-video-login.mp4" type="video/mp4">
                </video>
                
                <!-- Video Overlay -->
                <div class="video-overlay"></div>
                
                <!-- Particles Effect -->
                <div class="particles" id="particles"></div>
                
                <!-- Content -->
                <div class="video-content">
                    <div class="school-logo-main">
                        <i class="bi bi-mortarboard-fill" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h1 class="main-title">SMK Negeri 6</h1>
                    <p class="main-subtitle">Surakarta</p>
                    <p class="school-motto">"Visioner Inovasi Sinergi Kreatif Amanah"</p>
                </div>
            </div>
        </div>

        <!-- Login Section - Right Side -->
        <div class="login-section">
            <div class="login-card">
                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">
                        <i class="bi bi-person-fill-gear" style="font-size: 1.5rem; color: white;"></i>
                    </div>
                    <h1 class="login-title">Portal Login</h1>
                    <p class="login-subtitle">Sistem Informasi Sekolah</p>
                </div>

                <!-- Body -->
                <div class="login-body">
                    <div class="welcome-text">
                        <h2 class="welcome-title">Selamat Datang!</h2>
                        <p class="welcome-subtitle">Silakan masuk untuk melanjutkan</p>
                    </div>

                    <!-- Show Error/Success Messages -->
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span><?php echo htmlspecialchars($error); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <span><?php echo htmlspecialchars($success); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="POST" action="" id="loginForm" novalidate>
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        
                        <!-- Username Field -->
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                placeholder=" "
                                required
                                minlength="3"
                                maxlength="50"
                                autocomplete="username"
                                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                            />
                            <label class="form-label" for="username">Username</label>
                            <i class="bi bi-person-fill input-icon"></i>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="form-group">
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder=" "
                                required
                                minlength="6"
                                maxlength="255"
                                autocomplete="current-password"
                            />
                            <label class="form-label" for="password">Password</label>
                            <i class="bi bi-lock-fill input-icon"></i>
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember_me"
                                id="remember_me"
                                value="1"
                                <?php echo isset($_POST['remember_me']) ? 'checked' : ''; ?>
                            />
                            <label class="form-check-label" for="remember_me">
                                Ingat saya selama 30 hari
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-login" id="loginBtn">
                            <span class="btn-text">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Masuk ke Sistem
                            </span>
                        </button>

                        <a href="terms.php" class="text-secondary d-block text-center mt-3" target="_blank">
                            Dengan masuk , Anda menyetujui Syarat dan Ketentuan kami
                    </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('.background-video');
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            const particlesContainer = document.getElementById('particles');

            // Create particles effect
            function createParticles() {
                for (let i = 0; i < 20; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 8 + 's';
                    particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
                    particlesContainer.appendChild(particle);
                }
            }
            
            createParticles();

            // Video event handlers
            if (video) {
                video.addEventListener('loadeddata', function() {
                    console.log('Video loaded successfully');
                });

                video.addEventListener('error', function(e) {
                    console.log('Video failed to load, using fallback background');
                });

                video.addEventListener('canplay', function() {
                    video.play().catch(function(error) {
                        console.log('Auto-play prevented:', error);
                    });
                });
            }

            // Password toggle functionality
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.className = type === 'password' ? 'bi bi-eye-fill' : 'bi bi-eye-slash-fill';
            });

            // Form validation and submission
            loginForm.addEventListener('submit', function(e) {
                // e.preventDefault();
                
                const username = usernameInput.value.trim();
                const password = passwordInput.value;
                
                // Remove existing alerts
                const existingAlert = document.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                // Client-side validation
                if (username.length < 3) {
                    showAlert('Username minimal 3 karakter!', 'danger');
                    usernameInput.focus();
                    e.preventDefault();
                    return false;
                }
                
                if (password.length < 6) {
                    showAlert('Password minimal 6 karakter!', 'danger');
                    passwordInput.focus();
                    e.preventDefault();
                    return false;
                }

                // Add loading state
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
                
                // Allow form to submit normally for server-side processing
                // Remove simulated login process

            // Input animations and interactions
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(function(input) {
                // Focus effects
                input.addEventListener('focus', function() {
                    this.parentNode.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentNode.classList.remove('focused');
                });

                // Real-time validation
                input.addEventListener('input', function() {
                    const alertElement = document.querySelector('.alert');
                    if (alertElement) {
                        alertElement.style.opacity = '0';
                        setTimeout(() => {
                            if (alertElement.parentNode) {
                                alertElement.remove();
                            }
                        }, 300);
                    }
                });
            });

            // Enhanced alert function
            function showAlert(message, type = 'danger') {
                const existingAlert = document.querySelector('.alert');
                if (existingAlert) {
                    existingAlert.remove();
                }

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type}`;
                alertDiv.innerHTML = `
                    <i class="bi bi-${type === 'danger' ? 'exclamation-triangle-fill' : type === 'success' ? 'check-circle-fill' : 'info-circle-fill'}"></i>
                    <span>${message}</span>
                `;

                const welcomeText = document.querySelector('.welcome-text');
                welcomeText.insertAdjacentElement('afterend', alertDiv);

                // Auto-hide after 4 seconds
                setTimeout(function() {
                    if (alertDiv.parentNode) {
                        alertDiv.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        alertDiv.style.opacity = '0';
                        alertDiv.style.transform = 'translateY(-10px)';
                        setTimeout(function() {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 500);
                    }
                }, 4000);
            }

            // Add some interactive hover effects
            const loginCard = document.querySelector('.login-card');
            loginCard.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });

            loginCard.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });

            // Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && document.activeElement.tagName !== 'BUTTON') {
                    const form = document.getElementById('loginForm');
                    if (form.checkValidity()) {
                        loginBtn.click();
                    }
                }
            });
        });
    </script>
</body>
</html>