<?php
include 'koneksi.php';
$db = new database();

$tambahjurusan = $db->tampil_data_show_jurusan();
// Ambil data agama
$tambahagama = $db->tampil_data_show_agama();

if (isset($_POST['simpan'])) {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kodejurusan = $_POST['kodejurusan'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $nohp = $_POST['nohp'];

    if (!empty($nisn) && !empty($nama) && !empty($jeniskelamin) && !empty($kodejurusan) && !empty($kelas) && !empty($alamat) && !empty($agama) && !empty($nohp)) {
        if ($db->tambah_data_siswa($nisn, $nama, $jeniskelamin, $kodejurusan, $kelas, $alamat, $agama, $nohp)) {
            echo "<script>alert('Siswa berhasil ditambahkan!'); window.location='datasiswa.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan siswa!');</script>";
        }
    } else {
        echo "<script>alert('Semua bidang harus diisi!');</script>";
    }
}

?>



<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Tambah Siswa</title>
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
      <?php include "navbar.php"; ?>
      <!--end::Header-->
      <?php include "sidebar.php"; ?>
      <!--begin::App Main-->
      <main class="app-main">
        
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Tambah Data Siswa</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tambah Data Siswa</li>
                </ol>
              </div>
              <div class="col-12">
                <div class="callout callout-info">
                  Mohon isi data dengan benar dan lengkap.
                  <br />
                  <strong>Note:</strong> Formulir ini menggunakan
                  <a
                    href="https://getbootstrap.com/docs/5.3/forms/overview/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="callout-link"
                  >
                    Bootstrap Form
                  </a>
                </div>
              </div>
            </div>
            <div class="card card-info card-outline mb-4">
  <!--begin::Header-->
  <div class="card-header">
    <div class="card-title">Formulir Data Siswa</div>
  </div>
  
  <!--end::Header-->
  <!--begin::Form-->
  <form action="" method="POST" class="row g-3 needs-validation" novalidate>
    <!--begin::Body-->
    <div class="card-body">
      <!--begin::Row-->
      <div class="row g-3">
        <!--begin::Col-->
        <div class="col-md-6">
          <label for="nisn" class="form-label">NISN</label>
          <input
            type="text"
            class="form-control"
            id="nisn"
            placeholder="Masukkan NISN (10 digit)"
            maxlength="10"
            name="nisn"
            pattern="\d{10}"
            required
          />
          <div class="invalid-feedback">NISN must be exactly 10 digits.</div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6">
          <label for="nama"  class="form-label">Nama Lengkap</label>
          <input
            type="text"
            placeholder="Nama Lengkap harus diawali huruf kapital"
            class="form-control"
            id="nama" name="nama"
            maxlength="40"
            pattern="[A-Za-z\s]+"
            required
            style="text-transform: capitalize;"
          />
          <script>
  const input = document.getElementById('nama');
  input.addEventListener('input', () => {
    input.value = input.value
      .toLowerCase()
      .replace(/\b\w/g, huruf => huruf.toUpperCase());
  });
</script>
          <div class="valid-feedback">Nama anda bagus!</div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6">

  <label class="form-label">Jenis Kelamin</label>
  <div class="form-check">
    <input
      class="form-check-input"
      type="radio"
      name="jeniskelamin"
      id="L"
      value="L"
      required
    />
    <label class="form-check-label" for="L">Laki-laki</label>
  </div>
  <div class="form-check">
    <input
      class="form-check-input"
      type="radio"
      name="jeniskelamin"
      id="P"
      value="P"
      required
    />
    <label class="form-check-label" for="P">Perempuan</label>
  </div>
  <div class="invalid-feedback">Please choose a gender.</div>
</div>

        <div class="col-md-5">
          <label for="jurusan"  class="form-label">Jurusan</label>
          <select class="form-select" id="jurusan" name="kodejurusan"required>
            <option selected disabled value="">Pilih Jurusan...</option>
            <?php
            foreach($db->tampil_data_show_jurusan() as $x){
              echo '<option value="'.$x['kodejurusan'].'">'.$x['namajurusan'].'</option>';
            }
            ?>
          </select>
          <div class="invalid-feedback">Please select a valid Jurusan.</div>
        </div>
        <!--end::Col-->

        <!--begin::Col-->
        <div class="col-md-6">
          <label for="kelas" class="form-label">Kelas</label>
          <select class="form-select" id="kelas" name="kelas"  required>
            <option selected disabled value="">Pilih Kelas...</option>
            <option>X</option>
            <option>XI</option>
            <option>XII</option>
          </select>
          <div class="invalid-feedback">Please select a valid Kelas.</div>
        </div>

        <div class="col-md-6">
          <label for="agama"  class="form-label">Agama</label>
          <select class="form-select" id="agama" name="agama" required>
            <option selected disabled value="">Pilih Agama...</option>
            <?php
            foreach($db->tampil_data_show_agama() as $x){
              echo '<option value="'.$x['kodeagama'].'">'.$x['namaagama'].'</option>';
            }
            ?>
          </select>
          <div class="invalid-feedback">Please select a valid Jurusan.</div>
          <div class="valid-feedback">Looks good!</div>
        </div>

        <div class="card-body">
      <!--begin::Row-->
      <div class="row g-3">
        <!--begin::Col-->
        <div class="col-md-6">
          <label for="alamat"  class="form-label">Domisili</label>
          <input
            type="text"
            class="form-control"
            id="alamat" name="alamat"
            placeholder="Masukkan alamat lengkap"
            maxlength="30"
            required
          />
          <div class="valid-feedback">Looks good!</div>
        </div>

        <div class="col-md-6">
          <label for="nohp" class="form-label">Nomor Handphone</label>
          <input
            type="text"
            class="form-control"
            id="nohp"
            name="nohp"
            pattern="\+62\d{9,13}"
            required
             inputmode="numeric"
             maxlength="14"
            placeholder="Contoh: +6281234567890"
          />
          <div class="invalid-feedback">Nomor Handphone harus dimulai dengan +62 dan memiliki 9-13 digit angka.</div>
        </div>

        
        <!--end::Col--> 
        <!--end::Col-->

        <!--begin::Col-->
       
        

      </div>
      <!--end::Row-->
    </div>
    <!--end::Body-->
    <!--begin::Footer-->
    <div class="card-footer">
    <button class="btn btn-info" type="submit" id="simpan" name="simpan">Submit form</button>
    </div>
    <!--end::Footer-->
  </form>
  <!--end::Form-->

  <!--begin::JavaScript-->
  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');

      Array.from(forms).forEach((form) => {
        form.addEventListener('submit', (event) => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
  <!--end::JavaScript-->
</div>

          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
    
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
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>