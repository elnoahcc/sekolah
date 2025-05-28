<?php
include "koneksi.php";
$db = new database();

if (isset($_POST['simpan'])) { 
$db->tambah_agama(
        $_POST['kodeagama'],
        $_POST['namaagama'],
       
    );
    
    header("Location: data_agama.php");
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Tambah Agama</title>
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
        
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Tambah Data Agama</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="dashboard.html">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tambah Data Agama</li>
                </ol>
              </div>
              <div class="col-12">
                <div class="callout callout-info">
                  Mohon isi data dengan huruf kapital.
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
    <div class="card-title">Formulir Data Agama</div>
  </div>
  <!--end::Header-->  
  <!--begin::Form-->
        <form action=" " method="POST">
  <!--begin::Body-->
  <div class="card-body">
    <div class="mb-3">
      <label for="kodeagama" class="form-label">Kode Agama</label>
      <input type="text" class="form-control" id="kodeagama" name="kodeagama" required />
    </div>  
    <div class="mb-3">
      <label for="namaagama" class="form-label">Nama Agama</label>
      <input type="text" class="form-control" id="namaagama" name="namaagama" required />
    </div>

    
            <input href="dataagama.php" type="submit" name="simpan" value="Tambah Agama" class="btn btn-primary mb-2-button">
        </form>
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