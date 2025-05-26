<?php
session_start();
require_once 'koneksi.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$db = new database();
$username = $_SESSION['username'];

// Ambil data user
$query = $db->koneksi->prepare("SELECT username, email, nama_lengkap FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows !== 1) {
    echo "User tidak ditemukan.";
    exit;
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="dist/css/adminlte.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">
  <?php include 'navbar.php'; ?>
  <?php include 'sidebar.php'; ?>

  <main class="app-main">
    <div class="app-content-header py-3">
      <div class="container-fluid">
        <h3 class="mb-0">Profil Pengguna</h3>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        <div class="card shadow-sm rounded-3">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Akun</h5>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-4">Username</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['username']) ?></dd>

              <dt class="col-sm-4">Nama Lengkap</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['nama_lengkap'] ?: '-') ?></dd>

              <dt class="col-sm-4">Email</dt>
              <dd class="col-sm-8"><?= htmlspecialchars($user['email'] ?: '-') ?></dd>
            </dl>
            <a href="edit_profile.php" class="btn btn-sm btn-outline-primary mt-3">
              <i class="bi bi-pencil-square"></i> Edit Profil
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
</div>

<script src="dist/js/adminlte.js"></script>
</body>
</html>
