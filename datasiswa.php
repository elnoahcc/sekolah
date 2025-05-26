<?php
include "koneksi.php";
$db = new database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $nohp = $_POST['nohp'];
    $jurusan = $_POST['jurusan'];
    $agama = $_POST['agama'];

    // Pastikan kamu punya method update di class database()
    $db->update_data_siswa($nisn, $nama, $jeniskelamin, $kelas, $alamat, $nohp, $jurusan, $agama);

    // Redirect biar gak nge-submit ulang kalo refresh
    header("Location: datasiswa.php");
    exit();
}
?>


<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Siswa</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Simple Tables" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS (modal butuh JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
    <link rel="stylesheet" href="dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      
      <!--end::Header-->
      <?php include "sidebar.php"; ?>

      <?php include "navbar.php"; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Data Siswa</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Data Siswa</li>
                </ol>
                
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-md-12">
                <!-- /.card -->
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title">Data Siswa</h3>
                    <a href="tambahsiswa.php" class="btn btn-primary float-end">
                      Tambah Data
                    </a>
             
            </button>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">No.</th>
      <th bgcolor="Green">NISN</th>
     <th bgcolor="Green">Nama</th>
     <th bgcolor="Green">Jenis Kelamin</th>
      <th bgcolor="Green">Jurusan</th>
      <th bgcolor="Green">Kelas</th>
      <th bgcolor="Green">Alamat</th>
      <th bgcolor="Green">Agama</th>
     <th bgcolor="Green">No HP</th>
                          <th style="width: 40px">Opsi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
$no = 1;
foreach($db ->tampil_data_show_siswa() as $X){
    ?>
 <tr class="align-middle">
    <td><?php echo $no++; ?></td>
    <td><?php echo $X['nisn'];?></td>
    <td><?php echo $X['nama'];?></td>
    <td><?php echo $X['jeniskelamin'];?></td>
    <td><?php echo $X['namajurusan'];?></td>
    <td><?php echo $X['kelas'];?></td>
    <td><?php echo $X['alamat'];?></td>
    <td><?php echo $X['namaagama'];?></td>
    <td><?php echo $X['nohp'];?></td>

    <td>
<!-- Tombol Edit (trigger modal) -->
<button class="btn btn-warning mb-2" 
        data-bs-toggle="modal" 
        data-bs-target="#modalEdit<?= $X['nisn']; ?>">
  Edit
</button>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit<?= $X['nisn']; ?>" 
     data-bs-backdrop="static" 
     data-bs-keyboard="false" 
     tabindex="-1" 
     aria-labelledby="labelEdit<?= $X['nisn']; ?>" 
     aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header bg-warning">
          <h5 class="modal-title" id="labelEdit<?= $X['nisn']; ?>">Edit Data Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="nisn" value="<?= $X['nisn']; ?>">
          <div class="mb-3">
            <label for="nama<?= $X['nisn']; ?>" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama<?= $X['nisn']; ?>" name="nama" value="<?= $X['nama']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jeniskelamin" class="form-select">
              <option value="L" <?= $X['jeniskelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
              <option value="P" <?= $X['jeniskelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jurusan</label>
            <select name="jurusan" class="form-select" required>
              <?php foreach ($db->tampil_data_show_jurusan() as $jur) : ?>
                <option value="<?= $jur['kodejurusan']; ?>" <?= $jur['kodejurusan'] == $X['kodejurusan'] ? 'selected' : ''; ?>>
                  <?= $jur['namajurusan']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Kelas</label>
            <input type="text" class="form-control" name="kelas" value="<?= $X['kelas']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" class="form-control" name="alamat" value="<?= $X['alamat']; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Agama</label>
            <select name="agama" class="form-select" required>
              <?php foreach ($db->tampil_data_show_agama() as $agm) : ?>
                <option value="<?= $agm['kodeagama']; ?>" <?= $agm['kodeagama'] == $X['agama'] ? 'selected' : ''; ?>>
                  <?= $agm['namaagama']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" class="form-control" name="nohp" value="<?= $X['nohp']; ?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Tombol Hapus (triger modal) -->
<button class="btn btn-danger mb-2" 
        data-bs-toggle="modal" 
        data-bs-target="#modalHapus<?= $X['nisn']; ?>">
  Hapus
</button>
<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalHapus<?= $X['nisn']; ?>" 
     data-bs-backdrop="static" 
     data-bs-keyboard="false" 
     tabindex="-1" 
     aria-labelledby="labelHapus<?= $X['nisn']; ?>" 
     aria-hidden="true">
 <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="labelHapus<?= $X['nisn']; ?>">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin menghapus data siswa ini?</p>
        <ul class="list-unstyled">
          <li><strong>NISN:</strong> <?= $X['nisn']; ?></li>
          <li><strong>Nama:</strong> <?= $X['nama']; ?></li>
          <li><strong>Jenis Kelamin:</strong> <?= $X['jeniskelamin']; ?></li>
          <li><strong>Jurusan:</strong> <?= $X['namajurusan']; ?></li>
          <li><strong>Kelas:</strong> <?= $X['kelas']; ?></li>
          <li><strong>Alamat:</strong> <?= $X['alamat']; ?></li>
          <li><strong>Agama:</strong> <?= $X['namaagama']; ?></li>
          <li><strong>No HP:</strong> <?= $X['nohp']; ?></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="hapus_siswa.php?nisn=<?= $X['nisn']; ?>" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>



        </td>
        </tr>
        <?php
        }
        ?> 
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
     <?php include "footer.php"; ?>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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
      });3
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>