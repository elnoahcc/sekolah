<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Handle logout confirmation
if (isset($_GET['confirm_logout']) && $_GET['confirm_logout'] === 'yes') {
    // Destroy all session data
    $_SESSION = [];
    session_destroy();
    
    // Prevent back button access by setting no-cache headers
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
    
    // Redirect to index.php after logout
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fixed Sidebar - SMK Negeri 6 Surakarta</title>
  
  <!-- AdminLTE CSS -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css"
  />
  
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
  />
  
  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  
  <style>
    /* Clean Sidebar Styling */
    .main-sidebar {
      background: #2c3e50;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .brand-link {
      background: #34495e;
      border-bottom: 1px solid #3d566e;
      padding: 15px 20px;
    }
    
    .brand-text {
      color: #ecf0f1 !important;
      font-weight: 600;
      font-size: 16px;
    }
    
    .brand-image {
      border: 2px solid #3d566e;
    }
    
    /* Navigation */
    .sidebar {
      padding-top: 10px;
    }
    
    .nav-sidebar {
      padding: 0 15px;
    }
    
    .nav-item {
      margin-bottom: 2px;
    }
    
    .nav-link {
      color: #bdc3c7 !important;
      padding: 12px 15px;
      border-radius: 6px;
      transition: all 0.2s ease;
      margin-bottom: 2px;
    }
    
    .nav-link:hover {
      background: #34495e;
      color: #ffffff !important;
    }
    
    .nav-link.active {
      background: #3498db;
      color: #ffffff !important;
    }
    
    .nav-icon {
      width: 20px;
      text-align: center;
      margin-right: 10px;
      font-size: 16px;
    }
    
    /* TreeView */
    .nav-treeview {
      background: #243342;
      border-radius: 6px;
      margin: 5px 0;
      padding: 5px 0;
    }
    
    .nav-treeview .nav-link {
      padding: 10px 15px 10px 45px;
      font-size: 14px;
      margin-bottom: 1px;
    }
    
    .nav-treeview .nav-link:hover {
      background: #2c3e50;
    }
    
    .nav-treeview .nav-link.active {
      background: #2980b9;
    }
    
    /* Header */
    .nav-header {
      color: #95a5a6;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 15px 15px 8px 15px;
      margin-top: 10px;
    }
    
    /* Arrow icon for treeview */
    .has-treeview > .nav-link .right {
      transition: transform 0.2s ease;
    }
    
    .has-treeview.menu-open > .nav-link .right {
      transform: rotate(-90deg);
    }
    
    /* Logout special styling */
    .logout-link {
      border: 1px solid #e74c3c;
      background: rgba(231, 76, 60, 0.1);
    }
    
    .logout-link:hover {
      background: #e74c3c !important;
      color: #ffffff !important;
    }

    /* Logout Modal Styles */
    .logout-modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(8px);
      animation: fadeIn 0.3s ease-out;
    }

    .logout-modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .logout-modal-content {
      background: #fff;
      margin: auto;
      padding: 0;
      border: none;
      border-radius: 20px;
      width: 90%;
      max-width: 450px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      overflow: hidden;
      position: relative;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(60px) scale(0.9);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .logout-modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 25px 30px;
      text-align: center;
      color: white;
      position: relative;
    }

    .logout-modal-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
      background-size: 200% 100%;
      animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes shimmer {
      0%, 100% { background-position: 0% 0%; }
      50% { background-position: 200% 0%; }
    }

    .logout-emoji {
      font-size: 60px;
      margin-bottom: 15px;
      display: inline-block;
      animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0) rotate(0deg);
      }
      10% {
        transform: translateY(-8px) rotate(-3deg);
      }
      30% {
        transform: translateY(-12px) rotate(3deg);
      }
      40% {
        transform: translateY(-8px) rotate(-2deg);
      }
      60% {
        transform: translateY(-4px) rotate(1deg);
      }
    }

    .logout-modal-title {
      font-size: 24px;
      font-weight: 700;
      margin: 0;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .logout-modal-body {
      padding: 30px;
      text-align: center;
    }

    .logout-message {
      font-size: 18px;
      color: #4a5568;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .logout-message strong {
      color: #e74c3c;
      font-weight: 600;
    }

    .logout-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .logout-btn {
      padding: 14px 28px;
      border: none;
      border-radius: 50px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      min-width: 130px;
      justify-content: center;
    }

    .logout-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.5s;
    }

    .logout-btn:hover::before {
      left: 100%;
    }

    .logout-btn-cancel {
      background: linear-gradient(45deg, #4299e1, #3182ce);
      color: white;
      box-shadow: 0 8px 25px rgba(66, 153, 225, 0.3);
    }

    .logout-btn-cancel:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(66, 153, 225, 0.4);
      color: white;
      text-decoration: none;
    }

    .logout-btn-confirm {
      background: linear-gradient(45deg, #f56565, #e53e3e);
      color: white;
      box-shadow: 0 8px 25px rgba(245, 101, 101, 0.3);
    }

    .logout-btn-confirm:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(245, 101, 101, 0.4);
      color: white;
      text-decoration: none;
    }

    .logout-btn:active {
      transform: translateY(0);
    }

    /* Close button */
    .logout-close {
      position: absolute;
      top: 15px;
      right: 20px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .logout-close:hover {
      color: white;
      background: rgba(255, 255, 255, 0.1);
      transform: rotate(90deg);
    }

    /* Heart animation */
    .heart-break {
      position: absolute;
      top: 15px;
      left: 20px;
      font-size: 20px;
      color: rgba(255, 255, 255, 0.8);
      animation: heartbreak 3s ease-in-out infinite;
    }

    @keyframes heartbreak {
      0%, 100% {
        transform: scale(1);
        opacity: 0.8;
      }
      50% {
        transform: scale(1.2);
        opacity: 1;
      }
    }

    /* Responsive */
    @media (max-width: 480px) {
      .logout-modal-content {
        width: 95%;
        margin: 20px auto;
      }

      .logout-modal-header {
        padding: 20px 25px;
      }

      .logout-emoji {
        font-size: 50px;
      }

      .logout-modal-title {
        font-size: 20px;
      }

      .logout-modal-body {
        padding: 25px 20px;
      }

      .logout-message {
        font-size: 16px;
      }

      .logout-buttons {
        flex-direction: column;
        gap: 12px;
      }

      .logout-btn {
        width: 100%;
        padding: 16px 24px;
      }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="./dashboard.php" class="brand-link">
        <img
          src="dist/assets/img/AdminLTELogo.png"
          alt="AdminLTE Logo"
          class="brand-image img-circle elevation-3"
          style="opacity: .8"
        />
        <span class="brand-text">SMKN 6 Surakarta</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul
            class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false"
          >
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link">
                <i class="nav-icon bi bi-house-door"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Data Section -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-database"></i>
                <p>
                  Data
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="datasiswa.php" class="nav-link">
                    <i class="nav-icon bi bi-people"></i>
                    <p>Data Siswa</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="datajurusan.php" class="nav-link">
                    <i class="nav-icon bi bi-mortarboard"></i>
                    <p>Data Jurusan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="dataagama.php" class="nav-link">
                    <i class="nav-icon bi bi-moon-stars"></i>
                    <p>Data Agama</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Forms Section -->
            <?php if ($role !== 'siswa'): ?>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-plus-circle"></i>
                <p>
                  Forms
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="tambahsiswa.php" class="nav-link">
                    <i class="nav-icon bi bi-person-plus"></i>
                    <p>Tambah Siswa</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="tambahjurusan.php" class="nav-link">
                    <i class="nav-icon bi bi-book"></i>
                    <p>Tambah Jurusan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="tambahagama.php" class="nav-link">
                    <i class="nav-icon bi bi-plus-square"></i>
                    <p>Tambah Agama</p>
                  </a>
                </li>
              </ul>
            </li>
            <?php endif; ?>

            <!-- Users Header -->
            <li class="nav-header">USERS</li>

            <!-- Auth Section -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-person-gear"></i>
                <p>
                  Auth
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="profile.php" class="nav-link">
                    <i class="nav-icon bi bi-person-circle"></i>
                    <p>Profile</p>
                  </a>
                </li>
                <?php if ($role !== 'siswa'): ?>
                <li class="nav-item">
                  <a href="usermanagement.php" class="nav-link">
                    <i class="nav-icon bi bi-people-fill"></i>
                    <p>User Management</p>
                  </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                  <a
                    href="#"
                    class="nav-link logout-link"
                    onclick="showLogoutModal()"
                  >
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <p>Sign Out</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="logout-modal">
      <div class="logout-modal-content">
        <div class="logout-modal-header">
          <div class="heart-break">💔</div>
          <span class="logout-close" onclick="hideLogoutModal()">&times;</span>
          <div class="logout-emoji">😢</div>
          <h2 class="logout-modal-title">Tunggu Dulu!</h2>
        </div>
        <div class="logout-modal-body">
          <p class="logout-message">
            Apakah kamu yakin ingin keluar?<br>
            <strong>Admin sedih loh... 😭</strong>
          </p>
          <div class="logout-buttons">
            <a href="#" class="logout-btn logout-btn-cancel" onclick="hideLogoutModal()">
              <span>❤️</span>
              Tidak, Aku Tetap Di Sini
            </a>
            <a href="?confirm_logout=yes" class="logout-btn logout-btn-confirm">
              <span>👋</span>
              Ya, Sampai Jumpa
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (required by AdminLTE) -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
    ></script>

    <!-- Bootstrap 5 Bundle JS -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"
    ></script>

    <!-- AdminLTE JS -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"
    ></script>

    <script>
      $(document).ready(function () {
        // Highlight menu sesuai halaman aktif otomatis
        var current = location.pathname.split('/').pop();

        $('.nav-link').each(function () {
          var $this = $(this);
          if ($this.attr('href') === current) {
            $this.addClass('active');

            var treeview = $this.closest('.nav-treeview');
            if (treeview.length) {
              treeview.addClass('menu-open');
              treeview
                .closest('.has-treeview')
                .find('> a.nav-link')
                .addClass('active');
            }
          }
        });
      });

      // Logout Modal Functions
      function showLogoutModal() {
        document.getElementById('logoutModal').classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function hideLogoutModal() {
        document.getElementById('logoutModal').classList.remove('show');
        document.body.style.overflow = 'auto';
      }

      // Close modal when clicking outside
      window.onclick = function(event) {
        var modal = document.getElementById('logoutModal');
        if (event.target == modal) {
          hideLogoutModal();
        }
      }

      // Close modal with Escape key
      document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
          hideLogoutModal();
        }
      });

      // Add interactive effects
      document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to modal
        const modal = document.querySelector('.logout-modal-content');
        if (modal) {
          modal.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
          });
          
          modal.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
          });
        }

        // Add effects to buttons
        const buttons = document.querySelectorAll('.logout-btn');
        buttons.forEach(btn => {
          btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
          });
          
          btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
          });
        });
      });
    </script>
  </div>
</body>
</html>