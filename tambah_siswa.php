<?php
require_once 'koneksi.php';
$db = new mysqli('localhost', 'root', '', 'sekolah');

if(isset($_POST['simpan'])){
    // Sanitasi input biar lebih aman
    $nisn = htmlspecialchars($_POST['nisn']);
    if (strlen($nisn) > 10) {
        echo "<script>alert('NISN tidak boleh lebih dari 10 digit!');</script>";
        echo "<script>alert('DATA BERHASIL DIMASUKKAN');</script>";
        exit();
    }
    if (!ctype_digit($nisn)) {
        echo "<script>alert('NISN harus berupa angka!');</script>";
        exit();
    }
    $nama = htmlspecialchars($_POST['nama']);
    $jeniskelamin = isset($_POST['jeniskelamin']) ? htmlspecialchars($_POST['jeniskelamin']) : '';
    $kelas = htmlspecialchars($_POST['kelas']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $nohp = htmlspecialchars($_POST['nohp']);
    if (!ctype_digit($nohp)) {
        echo "<script>alert('Nomor HP harus berupa angka!');</script>";
        exit();
    }
    $kodejurusan = isset($_POST['kodejurusan']) ? htmlspecialchars($_POST['kodejurusan']) : '';
    $agama = htmlspecialchars($_POST['agama']);

    // Check if any data is empty
    if (!empty($nisn) && !empty($nama) && !empty($jeniskelamin) && !empty($kelas) && !empty($alamat) && !empty($nohp) && !empty($kodejurusan) && !empty($agama)) {
        // Prepare and bind
        $stmt = $db->prepare("INSERT INTO siswa (nisn, nama, jeniskelamin, kelas, alamat, nohp, kodejurusan, agama) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nisn, $nama, $jeniskelamin, $kelas, $alamat, $nohp, $kodejurusan, $agama);

        // Execute the statement
        if ($stmt->execute()) {
            header("location:data_siswa.php");
            exit();
        } else {
            echo "<script>alert('Gagal menambah siswa!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Harap isi semua field!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .field label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .input, .textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .button {
            width: 100%;
            background-color: #3273dc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: 0.3s;
        }
        .button:hover {
            background-color: #2759a5;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #3273dc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title is-4 has-text-centered">Tambah Siswa</h2>
        <form method="post" action="">
            <div class="field">
                <label class="label" for="nisn">NISN:</label>
                <div class="control">
                    <input class="input" type="text" id="nisn" name="nisn" placeholder="Masukkan NISN" required pattern="\d+" title="Hanya angka diperbolehkan">
                </div>
            </div>
            <div class="field">
                <label class="label" for="nama">Nama:</label>
                <div class="control">
                    <input class="input" type="text" id="nama" name="nama" placeholder="Masukkan Nama" required>
                </div>
            </div>
            <div class="field">
                <label class="label" for="jeniskelamin">Jenis Kelamin:</label>
                <div class="control">
                    <label><input type="radio" name="jeniskelamin" value="L" required> Laki-laki</label>
                    <label><input type="radio" name="jeniskelamin" value="P" required> Perempuan</label>
                </div>
            </div>
            <div class="field">
                <label class="label" for="kelas">Kelas:</label>
                <div class="control">
                    <input class="input" type="text" id="kelas" name="kelas" placeholder="Masukkan Kelas" required>
                </div>
            </div>
            <div class="field">
                <label class="label" for="alamat">Alamat:</label>
                <div class="control">
                    <textarea class="textarea" id="alamat" name="alamat" placeholder="Masukkan Alamat" required></textarea>
                </div>
            </div>
            <div class="field">
                <label class="label" for="nohp">No HP:</label>
                <div class="control">
                    <input class="input" type="text" id="nohp" name="nohp" placeholder="Masukkan No HP" required pattern="\d+" title="Hanya angka diperbolehkan">
                </div>
            </div>
            <div class="field">
                <label class="label" for="kodejurusan">Jurusan:</label>
                <div class="control">
                    <input class="input" type="text" id="kodejurusan" name="kodejurusan" placeholder="Masukkan Kode Jurusan" required>
                </div>
            </div>
            <div class="field">
                <label class="label" for="agama">Agama:</label>
                <div class="control">
                    <input class="input" type="text" id="agama" name="agama" placeholder="Masukkan Agama" required>
                </div>
            </div>
            <div class="field">
                <button class="button is-primary is-fullwidth">Tambah Siswa</button>
            </div>
        </form>
        <a class="back-link" href="data_siswa.php">Lihat Data Siswa</a>
    </div>
</body>
</html>