<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php";
$db = new database();

$username = $_SESSION['username'] ?? 'Guest';
?>

<!-- Navbar AdminLTE 4 -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm border-bottom">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="bi bi-list"></i></a>
    </li>
    <li class="nav-item">
      <a href="index.php" class="nav-link"><i class="bi bi-house-door me-1"></i> Home</a>
    </li>
  </ul>

  <!-- Right navbar -->
  <ul class="navbar-nav ms-auto">
    <!-- Dark Mode Toggle -->
    <li class="nav-item">
      <a class="nav-link" href="#" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="bi bi-moon"></i>
      </a>
    </li>

    <!-- Fullscreen -->
    <li class="nav-item">
      <a class="nav-link" href="#" data-widget="fullscreen" role="button" title="Fullscreen">
        <i class="bi bi-arrows-fullscreen"></i>
      </a>
    </li>

    <!-- Profile Dropdown -->
    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="d-none d-md-inline"><?= htmlspecialchars($username) ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg">
        <!-- User image -->
        <li class="user-header bg-primary text-white text-center">
          <img src="../../../dist/assets/img/user2-160x160.jpg" class="img-circle elevation-2 mb-2" alt="User Image" />
          <p class="mb-0"><?= htmlspecialchars($username) ?> <br><small>Web Developer</small></p>
        </li>
        <!-- Menu Footer-->
        <li class="user-footer text-center">
          <a href="profile.php" class="btn btn-outline-secondary btn-sm w-100 mb-1">Profile</a>
          <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Sign out</button>
          </form>
        </li>
      </ul>
    </li>
  </ul>
</nav>

<!-- Optional Dark Mode Script -->
<script>
  const toggleBtn = document.getElementById('darkModeToggle');
  toggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    document.querySelector('nav').classList.toggle('navbar-dark');
    document.querySelector('nav').classList.toggle('navbar-white');
    document.querySelector('nav').classList.toggle('bg-dark');
  });
</script>
