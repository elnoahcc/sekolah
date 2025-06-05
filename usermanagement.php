<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

$db = new database();

$username = $_SESSION['username'] ?? '';

// Check if user is admin
$roleQuery = $db->koneksi->prepare("SELECT role FROM users WHERE username = ?");
$roleQuery->bind_param("s", $username);
$roleQuery->execute();
$roleResult = $roleQuery->get_result();
if ($roleResult->num_rows !== 1) {
    echo "User tidak ditemukan.";
    exit;
}
$userRole = $roleResult->fetch_assoc()['role'];
$roleQuery->close();

if ($userRole !== 'admin') {
    header('Location: index.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $new_username = trim($_POST['username']);
                    $nama_lengkap = trim($_POST['nama_lengkap']);
                    $email = trim($_POST['email']);
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $role = $_POST['role'];
                    
                    // Check if username already exists
                    $checkStmt = $db->koneksi->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
                    $checkStmt->bind_param("s", $new_username);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    $count = $checkResult->fetch_assoc()['count'];
                    $checkStmt->close();
                    
                    if ($count > 0) {
                        echo "<script>alert('Username sudah digunakan!'); window.location.reload();</script>";
                        break;
                    }
                    
                    $stmt = $db->koneksi->prepare("INSERT INTO users (username, nama_lengkap, email, password, role) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $new_username, $nama_lengkap, $email, $password, $role);
                    
                    if ($stmt->execute()) {
                        echo "<script>alert('User berhasil ditambahkan!'); window.location.reload();</script>";
                    } else {
                        throw new Exception($stmt->error);
                    }
                    $stmt->close();
                } catch (Exception $e) {
                    echo "<script>alert('Error menambah user: " . addslashes($e->getMessage()) . "');</script>";
                }
                break;
                
            case 'edit':
                try {
                    $edit_username = trim($_POST['username']);
                    $nama_lengkap = trim($_POST['nama_lengkap']);
                    $email = trim($_POST['email']);
                    $role = $_POST['role'];
                    
                    // Prevent admin from editing their own role to prevent lockout
                    if ($edit_username === $_SESSION['username'] && $role !== 'admin') {
                        echo "<script>alert('Anda tidak dapat mengubah role akun Anda sendiri!');</script>";
                        break;
                    }
                    
                    if (!empty($_POST['password'])) {
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmt = $db->koneksi->prepare("UPDATE users SET nama_lengkap = ?, email = ?, password = ?, role = ? WHERE username = ?");
                        $stmt->bind_param("sssss", $nama_lengkap, $email, $password, $role, $edit_username);
                    } else {
                        $stmt = $db->koneksi->prepare("UPDATE users SET nama_lengkap = ?, email = ?, role = ? WHERE username = ?");
                        $stmt->bind_param("ssss", $nama_lengkap, $email, $role, $edit_username);
                    }
                    
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            echo "<script>alert('User berhasil diupdate!'); window.location.reload();</script>";
                        } else {
                            echo "<script>alert('Tidak ada perubahan data atau user tidak ditemukan!');</script>";
                        }
                    } else {
                        throw new Exception($stmt->error);
                    }
                    $stmt->close();
                } catch (Exception $e) {
                    echo "<script>alert('Error mengupdate user: " . addslashes($e->getMessage()) . "');</script>";
                }
                break;
                
            case 'delete':
                try {
                    $delete_username = trim($_POST['username']);
                    
                    // Prevent admin from deleting their own account
                    if ($delete_username === $_SESSION['username']) {
                        echo "<script>alert('Anda tidak dapat menghapus akun Anda sendiri!');</script>";
                        break;
                    }
                    
                    // Check if user exists first
                    $checkStmt = $db->koneksi->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
                    $checkStmt->bind_param("s", $delete_username);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    $count = $checkResult->fetch_assoc()['count'];
                    $checkStmt->close();
                    
                    if ($count == 0) {
                        echo "<script>alert('User tidak ditemukan!');</script>";
                        break;
                    }
                    
                    // Begin transaction for safe deletion
                    $db->koneksi->begin_transaction();
                    
                    try {
                        // Delete the user
                        $stmt = $db->koneksi->prepare("DELETE FROM users WHERE username = ?");
                        $stmt->bind_param("s", $delete_username);
                        
                        if ($stmt->execute()) {
                            if ($stmt->affected_rows > 0) {
                                $db->koneksi->commit();
                                echo "<script>alert('User berhasil dihapus!'); window.location.reload();</script>";
                            } else {
                                $db->koneksi->rollback();
                                echo "<script>alert('User tidak ditemukan atau sudah dihapus!');</script>";
                            }
                        } else {
                            $db->koneksi->rollback();
                            throw new Exception($stmt->error);
                        }
                        $stmt->close();
                    } catch (Exception $e) {
                        $db->koneksi->rollback();
                        throw $e;
                    }
                    
                } catch (Exception $e) {
                    $error_message = $e->getMessage();
                    
                    // Handle foreign key constraint error
                    if (strpos($error_message, 'foreign key constraint') !== false || 
                        strpos($error_message, 'FOREIGN KEY') !== false ||
                        strpos($error_message, '1451') !== false) {
                        echo "<script>alert('Tidak dapat menghapus user ini karena masih memiliki data terkait di sistem!');</script>";
                    } else {
                        echo "<script>alert('Error menghapus user: " . addslashes($error_message) . "');</script>";
                    }
                }
                break;
        }
    }
}

// Fetch all users
$query = $db->koneksi->prepare("SELECT username, nama_lengkap, email, role FROM users ORDER BY username");
$query->execute();
$result = $query->get_result();

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE v4 | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
<?php include "navbar.php"; ?>
<?php include "sidebar.php"; ?>
<div class="content-wrapper">
  <main class="app-main">
    <div class="app-content-header py-3">
      <div class="container-fluid">
        <h3 class="mb-0">Manajemen Pengguna</h3>
      </div>
    </div>

    <div class="app-content">
      <div class="container-fluid">
        <div class="card shadow-sm rounded-3">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pengguna</h5>
            <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#addUserModal">
              <i class="bi bi-plus-lg"></i> Tambah Pengguna
            </button>
          </div>
          <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="table table-striped">
                  <tr>
                    <th style="width: 50px;">#</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th style="width: 100px;">Role</th>
                    <th style="width: 150px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  while ($user = $result->fetch_assoc()) {
                      $isCurrentUser = ($user['username'] === $_SESSION['username']);
                      echo "<tr" . ($isCurrentUser ? " class='table-info'" : "") . ">";
                      echo "<td>" . $no++ . "</td>";
                      echo "<td>" . htmlspecialchars($user['username']) . ($isCurrentUser ? " <small class='text-primary'>(Anda)</small>" : "") . "</td>";
                      echo "<td>" . htmlspecialchars($user['nama_lengkap']) . "</td>";
                      echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                      echo "<td><span class='badge " . ($user['role'] === 'admin' ? 'bg-danger' : 'bg-secondary') . "'>" . htmlspecialchars($user['role']) . "</span></td>";
                      echo "<td>
                              <button type='button' class='btn btn-sm btn-primary me-1' onclick='editUser(\"" . htmlspecialchars($user['username']) . "\", \"" . htmlspecialchars($user['nama_lengkap']) . "\", \"" . htmlspecialchars($user['email']) . "\", \"" . htmlspecialchars($user['role']) . "\")' title='Edit User'>
                                <i class='bi bi-pencil-square'></i>
                              </button>";
                      
                      if (!$isCurrentUser) {
                          echo "<button type='button' class='btn btn-sm btn-danger' onclick='deleteUser(\"" . htmlspecialchars($user['username']) . "\")' title='Hapus User'>
                                  <i class='bi bi-trash'></i>
                                </button>";
                      } else {
                          echo "<button type='button' class='btn btn-sm btn-secondary' disabled title='Tidak dapat menghapus akun sendiri'>
                                  <i class='bi bi-trash'></i>
                                </button>";
                      }
                      
                      echo "</td>";
                      echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
              <i class="bi bi-person-x display-4 text-muted"></i>
              <h5 class="mt-3 text-muted">Tidak ada pengguna ditemukan</h5>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

 
</div>
 
<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" onsubmit="return validateAddForm()">
        <div class="modal-body">
          <input type="hidden" name="action" value="add">
          <div class="mb-3">
            <label for="add_username" class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="add_username" name="username" required maxlength="50">
            <div class="form-text">Username harus unik</div>
          </div>
          <div class="mb-3">
            <label for="add_nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="add_nama_lengkap" name="nama_lengkap" required maxlength="100">
          </div>
          <div class="mb-3">
            <label for="add_email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="add_email" name="email" required maxlength="100">
          </div>
          <div class="mb-3">
            <label for="add_password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="add_password" name="password" required minlength="6">
            <div class="form-text">Minimal 6 karakter</div>
          </div>
          <div class="mb-3">
            <label for="add_role" class="form-label">Role <span class="text-danger">*</span></label>
            <select class="form-select" id="add_role" name="role" required>
              <option value="">Pilih Role</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" onsubmit="return validateEditForm()">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" id="edit_username_hidden" name="username">
          <div class="mb-3">
            <label for="edit_username" class="form-label">Username</label>
            <input type="text" class="form-control" id="edit_username" readonly>
          </div>
          <div class="mb-3">
            <label for="edit_nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required maxlength="100">
          </div>
          <div class="mb-3">
            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="edit_email" name="email" required maxlength="100">
          </div>
          <div class="mb-3">
            <label for="edit_password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="edit_password" name="password" minlength="6">
            <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
          </div>
          <div class="mb-3">
            <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
            <select class="form-select" id="edit_role" name="role" required>
              <option value="admin">Admin</option>
              <option value="siswa">Siswa</option>
            </select>
            <div id="edit_role_warning" class="form-text text-warning" style="display: none;">
              <i class="bi bi-exclamation-triangle"></i> Anda sedang mengedit akun Anda sendiri
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Hapus User -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" id="delete_username" name="username">
          <div class="text-center mb-3">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
          </div>
          <p class="text-center">Apakah Anda yakin ingin menghapus pengguna <strong id="delete_username_display"></strong>?</p>
          <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>

<script>
function editUser(username, nama_lengkap, email, role) {
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_username_hidden').value = username;
    document.getElementById('edit_nama_lengkap').value = nama_lengkap;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_password').value = '';
    
    // Show warning if editing own account
    const currentUser = '<?php echo $_SESSION['username']; ?>';
    const warningDiv = document.getElementById('edit_role_warning');
    if (username === currentUser) {
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }
    
    var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    editModal.show();
}

function deleteUser(username) {
    // Prevent deleting own account
    const currentUser = '<?php echo $_SESSION['username']; ?>';
    if (username === currentUser) {
        alert('Anda tidak dapat menghapus akun Anda sendiri!');
        return;
    }
    
    document.getElementById('delete_username').value = username;
    document.getElementById('delete_username_display').textContent = username;
    
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    deleteModal.show();
}

function validateAddForm() {
    const username = document.getElementById('add_username').value.trim();
    const password = document.getElementById('add_password').value;
    
    if (username.length < 3) {
        alert('Username harus minimal 3 karakter!');
        return false;
    }
    
    if (password.length < 6) {
        alert('Password harus minimal 6 karakter!');
        return false;
    }
    
    return true;
}

function validateEditForm() {
    const password = document.getElementById('edit_password').value;
    
    if (password && password.length < 6) {
        alert('Password baru harus minimal 6 karakter!');
        return false;
    }
    
    return true;
}

// Clear form when modal is closed
document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function () {
    document.querySelector('#addUserModal form').reset();
});

document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
    document.querySelector('#editUserModal form').reset();
});
</script>

</body>
</html>