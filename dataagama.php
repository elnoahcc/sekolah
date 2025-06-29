<?php
include "koneksi.php";
$db = new database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kodeagama = $_POST['kodeagama'];
    $namaagama = isset($_POST['namaagama']) ? $_POST['namaagama'] : '';

    // Jika ada tombol hapus, panggil method hapus
    if (isset($_POST['hapus_agama'])) {
        $db->hapus_data_agama($kodeagama);
    }
    // Jika ada tombol edit, panggil method update
    if (isset($_POST['edit_agama'])) {
        $db->update_data_agama($kodeagama, $namaagama);
    }

    // Redirect biar gak nge-submit ulang kalo refresh
    header("Location: dataagama.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Data Agama</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
<?php include "navbar.php"; ?>
<?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3>Data Agama</h3></div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data Agama</li> </ol>
            </div>
        </div>
      </div>
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
                    <h3 class="card-title">Data Agama</h3>
                    <?php 
                    // Cek apakah user yang login bukan siswa
                    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') : 
                    ?>
                    <a href="tambahagama.php" class="btn btn-primary float-end">
                      Tambah Data
                    </a>
                    <?php endif; ?>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">No.</th>
                          <th bgcolor="Green">Kode Agama</th>
                          <th bgcolor="Green">Nama Agama</th>
                          <th style="width: 40px">Opsi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
$no = 1;
foreach($db->tampil_data_show_agama() as $X){
    ?>
 <tr class="align-middle">
    <td><?php echo $no++; ?></td>
    <td><?php echo $X['kodeagama'];?></td>
    <td><?php echo $X['namaagama'];?></td>

    <td>
<?php 
// Cek apakah user yang login bukan siswa untuk menampilkan tombol Edit dan Hapus
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') : 
?>
<!-- Tombol Edit (trigger modal) -->
<button class="btn btn-warning mb-2" 
        data-bs-toggle="modal" 
        data-bs-target="#modalEdit<?= $X['kodeagama']; ?>">
  Edit
</button>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit<?= $X['kodeagama']; ?>" tabindex="-1" aria-labelledby="labelEdit<?= $X['kodeagama']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <form method="POST">
                                      <div class="modal-header bg-warning">
                                        <h5 class="modal-title" id="labelEdit<?= $X['kodeagama']; ?>">Edit Data Agama</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                      </div>
                                      <div class="modal-body">
                                        <input type="hidden" name="kodeagama" value="<?= $X['kodeagama']; ?>">
                                        <div class="mb-3">
                                          <label for="namaagama<?= $X['kodeagama']; ?>" class="form-label">Nama Agama</label>
                                          <input type="text" class="form-control" name="namaagama" value="<?= $X['namaagama']; ?>" required>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit_agama" class="btn btn-warning">Simpan Perubahan</button>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>

<!-- Tombol Hapus (trigger modal) -->
<button class="btn btn-danger mb-2" 
        data-bs-toggle="modal" 
        data-bs-target="#modalHapus<?= $X['kodeagama']; ?>">
  Hapus
</button>

<div class="modal fade" id="modalHapus<?= $X['kodeagama']; ?>" tabindex="-1" aria-labelledby="labelHapus<?= $X['kodeagama']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <form method="POST">
                                      <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="labelHapus<?= $X['kodeagama']; ?>">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                      </div>
                                      <div class="modal-body">
                                        <p>Yakin ingin menghapus data berikut?</p>
                                        <ul>
                                          <li><strong>Kode:</strong> <?= $X['kodeagama']; ?></li>
                                          <li><strong>Nama:</strong> <?= $X['namaagama']; ?></li>
                                        </ul>
                                        <input type="hidden" name="kodeagama" value="<?= $X['kodeagama']; ?>">
                                        <input type="hidden" name="hapus_agama" value="1">
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
<?php else: ?>
<!-- Jika user adalah siswa, tampilkan pesan atau tombol view saja -->
<span class="text-muted">View Only</span>
<?php endif; ?>

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
    
      <!--end::Footer-->
    </div> 
    
    <?php include "footer.php"; ?>
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
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>