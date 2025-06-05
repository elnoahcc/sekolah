<?php
session_start();

include 'koneksi.php';

$db = new database();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    error_log("Login validation failed. Session data: " . print_r($_SESSION, true));
    header('Location: index.php');
    exit;
}

// Query untuk menghitung total
$q_siswa = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM siswa");
$jumlah_siswa = mysqli_fetch_assoc($q_siswa)['total'];

$q_jurusan = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM kodejurusan");
$jumlah_jurusan = mysqli_fetch_assoc($q_jurusan)['total'];

$q_agama = mysqli_query($db->koneksi, "SELECT COUNT(*) as total FROM kodeagama");
$jumlah_agama = mysqli_fetch_assoc($q_agama)['total'];

// Query untuk grafik gender
$q_gender = mysqli_query($db->koneksi, "SELECT jeniskelamin, COUNT(*) as jumlah FROM siswa GROUP BY jeniskelamin");
$data_gender = [];
while($row = mysqli_fetch_assoc($q_gender)) {
    $data_gender[] = $row;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- jQuery (diperlukan untuk dropdown) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<?php include "navbar.php"; ?>
  <div class="wrapper">
<?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3>Dashboard</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
              <div class="inner">
                <h3><?= $jumlah_siswa; ?></h3>
                <p>Total Siswa</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M11.7 1.61a1.875 1.875 0 01.6 0l10.5 3a.75.75 0 010 1.42l-10.5 3a1.875 1.875 0 01-.6 0l-10.5-3a.75.75 0 010-1.42l10.5-3zM21 9.348v4.652a2.25 2.25 0 01-1.5 2.13v3.12a.75.75 0 01-1.5 0v-2.846a11.94 11.94 0 01-12 0v2.846a.75.75 0 01-1.5 0v-3.12A2.25 2.25 0 013 14V9.348l8.4 2.4a3.375 3.375 0 001.2 0l8.4-2.4z"/></svg>
              <a href="datasiswa.php" class="small-box-footer">Data Siswa <i class="bi bi-link-45deg"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
              <div class="inner">
                <h3><?= $jumlah_jurusan; ?></h3>
                <p>Total Jurusan</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 7v13h18V7l-9-5zM8 18v-5h8v5H8zm0-7v-3l4-2 4 2v3H8z"/></svg>
              <a href="datajurusan.php" class="small-box-footer">Data Jurusan <i class="bi bi-link-45deg"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
              <div class="inner">
                <h3><?= $jumlah_agama; ?></h3>
                <p>Total Agama</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/></svg>
              <a href="dataagama.php" class="small-box-footer text-dark">Data Agama <i class="bi bi-link-45deg"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
              <div class="inner">
                <h1><b>Profile</b></h1>
                <p>Lihat data anda!</p>
              </div>
              <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              <a href="profile.php" class="small-box-footer">More info <i class="bi bi-link-45deg"></i></a>
            </div>
          </div>
        </div>

        <!-- Content Section -->
        <div class="row mt-4">
          <!-- Grafik Gender -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="bi bi-person-check me-1"></i>
                  Distribusi Siswa Berdasarkan Gender
                </h3>
              </div>
              <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                  <canvas id="genderChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Google Maps Lokasi Sekolah -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="bi bi-geo-alt-fill me-1"></i>
                  Lokasi SMK Negeri 6 Surakarta
                </h3>
              </div>
              <div class="card-body">
                <div class="map-container" style="position: relative; height: 300px; border-radius: 8px; overflow: hidden;">
                  <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.020179246913!2d110.7966373!3d-7.5532081!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a14248f7a300b%3A0x8147b47e94da5fa2!2sSMK%20Negeri%206%20Surakarta!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid"
                   width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                  </iframe>
                </div>
                <div class="mt-3">
                  <h6><i class="bi bi-building"></i> SMK Negeri 6 Surakarta</h6>
                  <p class="text-muted mb-2">
                    <i class="bi bi-geo-alt"></i>
                    Jl. LU Adisucipto No.42, Kentingan, Jebres, Kota Surakarta, Jawa Tengah
                  </p>
                  <div class="d-flex gap-2">
                    <a href="https://g.co/kgs/Niz73mE" target="_blank" class="btn btn-primary btn-sm">
                      <i class="bi bi-map"></i> Lihat di Google Maps
                    </a>
                    <a href="https://smkn6solo.sch.id/" target="_blank" class="btn btn-outline-secondary btn-sm">
                      <i class="bi bi-globe"></i> Website Sekolah
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-12 mt-4">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="bi bi-newspaper me-1"></i>
                  Berita Terbaru dari Website Sekolah
                </h3>
              </div>
              <div class="card-body">
                <!-- Feed RSS -->
               <iframe src="https://smkn6solo.sch.id/category/berita/" width="100%" height="600" style="border:none;"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  </div>
<footer class="main-footer text-center text-sm">
  <strong>
    Copyright &copy; 2023
    <a href="https://smkn6solo.sch.id/" class="text-decoration-none">SMK Negeri 6 Surakarta</a>.
    All rights reserved.
  </strong> 
  <div class="float-end d-none d-sm-inline">
    <b>Version</b> 1.0.0
  </div>
</footer> 
</div>

<!-- Bootstrap JS harus dimuat SEBELUM AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>

<script>
// Data untuk grafik gender
const genderData = <?= json_encode($data_gender) ?>;
const genderLabels = genderData.map(item => item.jeniskelamin === 'L' ? 'Laki-laki' : 'Perempuan');
const genderValues = genderData.map(item => parseInt(item.jumlah));

// Konfigurasi grafik gender
const genderCtx = document.getElementById('genderChart').getContext('2d');
const genderChart = new Chart(genderCtx, {
    type: 'pie',
    data: {
        labels: genderLabels,
        datasets: [{
            data: genderValues,
            backgroundColor: [
                '#36A2EB',
                '#FF6384'
            ],
            borderColor: [
                '#36A2EB',
                '#FF6384'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} siswa (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Script untuk memastikan dropdown berfungsi
$(document).ready(function() {
    console.log('Dashboard loaded, initializing dropdown...');
    
    // Force initialize all dropdowns
    $('.dropdown-toggle').each(function() {
        if (!$(this).attr('data-bs-toggle')) {
            $(this).attr('data-bs-toggle', 'dropdown');
        }
    });
    
    // Manual dropdown handler
    $('.dropdown-toggle').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Dropdown clicked');
        
        const $parent = $(this).parent('.dropdown');
        const $menu = $parent.find('.dropdown-menu');
        
        // Close all other dropdowns
        $('.dropdown-menu').not($menu).removeClass('show');
        $('.dropdown-toggle').not(this).attr('aria-expanded', 'false');
        
        // Toggle this dropdown
        if ($menu.hasClass('show')) {
            $menu.removeClass('show');
            $(this).attr('aria-expanded', 'false');
        } else {
            $menu.addClass('show');
            $(this).attr('aria-expanded', 'true');
        }
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').attr('aria-expanded', 'false');
        }
    });
    
    // Prevent dropdown from closing when clicking inside
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });
});
</script>

</body>
</html>