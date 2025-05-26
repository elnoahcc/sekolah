<?php
include "koneksi.php";
$db = new database();

$username = $_SESSION['username'] ?? null;

?>

<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <!-- Kiri -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="dashboard.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="dashboard.php" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Kanan -->
    <li class="nav-item me-3">
  <span class="nav-link">
    Hi, <strong><?= htmlspecialchars($username ?? 'Guest'); ?></strong>!
  </span>
</li>
    <ul class="navbar-nav ms-auto"> 
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
    </ul>
  </div>
</nav>
