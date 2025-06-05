<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php";
$db = new database();

$username = $_SESSION['username'] ?? 'Guest';
?>

<!-- Navbar AdminLTE 4 - Enhanced Responsive -->
<nav class="main-header navbar navbar-expand-lg navbar-white navbar-light shadow-sm border-bottom">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="bi bi-list"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="index.php" class="nav-link">
        <i class="bi bi-house-door me-1"></i> 
        <span class="d-none d-md-inline">Home</span>
      </a>
    </li>
  </ul>

  <!-- Brand/Logo for mobile (optional) -->
  <div class="navbar-brand d-block d-lg-none ms-auto me-auto">
    <span class="brand-text fw-bold">Admin Panel</span>
  </div>

  <!-- Mobile navbar toggler -->
  <button class="navbar-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Right navbar - Collapsible on mobile -->
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto">
      <!-- Mobile Home Link -->
      <li class="nav-item d-block d-sm-none">
        <a href="index.php" class="nav-link">
          <i class="bi bi-house-door me-2"></i> Home
        </a>
      </li>

      <!-- Dark Mode Toggle -->
      <li class="nav-item">
        <a class="nav-link" href="#" id="darkModeToggle" title="Toggle Dark Mode">
          <i class="bi bi-moon" id="darkModeIcon"></i>
          <span class="d-inline d-lg-none ms-2">Dark Mode</span>
        </a>
      </li>

      <!-- Fullscreen -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-widget="fullscreen" role="button" title="Fullscreen">
          <i class="bi bi-arrows-fullscreen"></i>
          <span class="d-inline d-lg-none ms-2">Fullscreen</span>
        </a>
      </li>

      <!-- Profile Dropdown -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle me-2"></i>
          <span class="username-text"><?= htmlspecialchars($username) ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg">
          <!-- User header -->
          <li class="user-header bg-primary text-white text-center py-3">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
            </div>
            <p class="mb-0 fw-bold"><?= htmlspecialchars($username) ?></p>
          </li>
          <!-- Menu Body (optional additional menu items) -->
          <li class="dropdown-divider"></li>
          <!-- Menu Footer-->
          <li class="user-footer p-3">
            <div class="d-grid gap-2">
              <a href="profile.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-person me-1"></i> Profile
              </a>
              <form action="logout.php" method="POST" class="mb-0">
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                  <i class="bi bi-box-arrow-right me-1"></i> Sign out
                </button>
              </form>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Enhanced Dark Mode CSS -->
<style>
  /* Base responsive styles */
  .navbar-toggler {
    border: 1px solid #dee2e6;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
    background: transparent;
  }

  .navbar-toggler:focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    width: 1.5em;
    height: 1.5em;
  }

  .brand-text {
    font-size: 1.1rem;
    color: #495057;
  }

  .username-text {
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  /* Responsive adjustments */
  @media (max-width: 576px) {
    .main-header.navbar {
      padding: 0.5rem 1rem;
    }
    
    .username-text {
      max-width: 80px;
    }
    
    .dropdown-menu-lg {
      min-width: 250px;
      right: 0 !important;
      left: auto !important;
    }
  }

  @media (max-width: 768px) {
    .navbar-nav {
      padding-top: 0.5rem;
    }
    
    .navbar-nav .nav-item {
      margin-bottom: 0.25rem;
    }
    
    .dropdown-menu {
      position: static !important;
      float: none !important;
      width: 100% !important;
      margin-top: 0.5rem !important;
      box-shadow: none !important;
      border: 1px solid #dee2e6 !important;
    }
  }

  @media (min-width: 992px) {
    .navbar-nav .nav-link {
      padding: 0.5rem 1rem;
    }
  }

  /* Dark mode styles using data-bs-theme attribute (AdminLTE 4 way) */
  [data-bs-theme="dark"] {
    --bs-body-bg: #1a1a1a;
    --bs-body-color: #e0e0e0;
    --bs-border-color: #404040;
  }

  body.dark-mode {
    background-color: #1a1a1a !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .main-header.navbar {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
  }

  body.dark-mode .navbar-light .navbar-nav .nav-link,
  body.dark-mode .navbar-light .navbar-brand,
  body.dark-mode .brand-text {
    color: #e0e0e0 !important;
  }

  body.dark-mode .navbar-light .navbar-nav .nav-link:hover,
  body.dark-mode .navbar-light .navbar-nav .nav-link:focus {
    color: #ffffff !important;
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-radius: 4px;
  }

  body.dark-mode .navbar-toggler {
    border-color: #6c757d !important;
  }

  body.dark-mode .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28224, 224, 224, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
  }

  body.dark-mode .dropdown-menu {
    background-color: #333333 !important;
    border-color: #404040 !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5) !important;
  }

  body.dark-mode .dropdown-item {
    color: #e0e0e0 !important;
  }

  body.dark-mode .dropdown-item:hover,
  body.dark-mode .dropdown-item:focus {
    background-color: #404040 !important;
    color: #ffffff !important;
  }

  body.dark-mode .dropdown-divider {
    border-color: #404040 !important;
  }

  body.dark-mode .user-header {
    background-color: #404040 !important;
  }

  body.dark-mode .btn-outline-secondary {
    border-color: #6c757d !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .btn-outline-secondary:hover {
    background-color: #6c757d !important;
    color: #ffffff !important;
  }

  body.dark-mode .btn-outline-danger {
    border-color: #dc3545 !important;
    color: #dc3545 !important;
  }

  body.dark-mode .btn-outline-danger:hover {
    background-color: #dc3545 !important;
    color: #ffffff !important;
  }

  /* Sidebar dark mode (jika ada) */
  body.dark-mode .main-sidebar {
    background-color: #2d2d2d !important;
  }

  body.dark-mode .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link {
    color: #e0e0e0 !important;
  }

  body.dark-mode .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
    background-color: #404040 !important;
    color: #ffffff !important;
  }

  /* Content wrapper dark mode */
  body.dark-mode .content-wrapper {
    background-color: #1a1a1a !important;
  }

  /* Card dark mode */
  body.dark-mode .card {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
  }

  body.dark-mode .card-header {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .card-body {
    color: #e0e0e0 !important;
  }

  /* Table dark mode - Fix untuk DataTables dan table biasa */
  body.dark-mode .table,
  body.dark-mode .table th,
  body.dark-mode .table td {
    color: #e0e0e0 !important;
    background-color: transparent !important;
  }

  body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: rgba(255, 255, 255, 0.05) !important;
  }

  body.dark-mode .table-hover > tbody > tr:hover > td {
    background-color: rgba(255, 255, 255, 0.1) !important;
  }

  /* DataTables dark mode */
  body.dark-mode .dataTables_wrapper {
    color: #e0e0e0 !important;
  }

  body.dark-mode .dataTables_info,
  body.dark-mode .dataTables_length label {
    color: #e0e0e0 !important;
  }

  body.dark-mode .dataTables_filter label {
    color: #e0e0e0 !important;
  }

  body.dark-mode .dataTables_filter input {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .dataTables_length select {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .page-link {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .page-link:hover {
    background-color: #404040 !important;
    color: #ffffff !important;
  }

  body.dark-mode .page-item.active .page-link {
    background-color: #007bff !important;
    border-color: #007bff !important;
  }

  /* Form dark mode */
  body.dark-mode .form-control,
  body.dark-mode .form-select {
    background-color: #333333 !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  body.dark-mode .form-control:focus,
  body.dark-mode .form-select:focus {
    background-color: #404040 !important;
    border-color: #007bff !important;
    color: #ffffff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
  }

  body.dark-mode .form-label {
    color: #e0e0e0 !important;
  }

  /* Footer dark mode */
  body.dark-mode .main-footer {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  /* Alert dark mode */
  body.dark-mode .alert {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
    color: #e0e0e0 !important;
  }

  /* Badge dark mode */
  body.dark-mode .badge {
    color: #000 !important;
  }

  /* Modal dark mode */
  body.dark-mode .modal-content {
    background-color: #2d2d2d !important;
    border-color: #404040 !important;
  }

  body.dark-mode .modal-header {
    border-bottom-color: #404040 !important;
  }

  body.dark-mode .modal-footer {
    border-top-color: #404040 !important;
  }
</style>

<!-- Enhanced Dark Mode Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    
    // Check untuk dark mode yang tersimpan
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
      darkModeIcon.className = 'bi bi-sun';
    }

    toggleBtn.addEventListener('click', (e) => {
      e.preventDefault();
      
      // Toggle dark mode class
      document.body.classList.toggle('dark-mode');
      
      // Update icon
      if (document.body.classList.contains('dark-mode')) {
        darkModeIcon.className = 'bi bi-sun';
        localStorage.setItem('darkMode', 'enabled');
      } else {
        darkModeIcon.className = 'bi bi-moon';
        localStorage.setItem('darkMode', 'disabled');
      }
    });

    // Handle dropdown positioning on mobile
    const handleDropdownPosition = () => {
      const dropdowns = document.querySelectorAll('.dropdown-menu');
      dropdowns.forEach(dropdown => {
        if (window.innerWidth <= 768) {
          dropdown.classList.add('dropdown-menu-start');
          dropdown.classList.remove('dropdown-menu-end');
        } else {
          dropdown.classList.add('dropdown-menu-end');
          dropdown.classList.remove('dropdown-menu-start');
        }
      });
    };

    // Initial check and resize listener
    handleDropdownPosition();
    window.addEventListener('resize', handleDropdownPosition);

    // Auto-collapse mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const navbar = document.querySelector('.navbar-collapse');
      const toggler = document.querySelector('.navbar-toggler');
      
      if (!navbar.contains(event.target) && !toggler.contains(event.target)) {
        if (navbar.classList.contains('show')) {
          toggler.click();
        }
      }
    });
  });
</script>