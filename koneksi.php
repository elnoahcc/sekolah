<?php

class database{
    var $host = "localhost";
    var $user = "root";
    var $password = "";
    var $database = "sekolah";

    function __construct(){
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->database // <- Tambahin database di sini
        );
    
        if (!$this->koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }
    
    public function tampil_data_show_siswa() {
        $data = [];
        $query = "
        SELECT siswa.*, 
            CASE
                WHEN siswa.jeniskelamin='L' THEN 'Laki-laki'
                ELSE 'Perempuan'
            END as jeniskelamin,
            kodejurusan.namajurusan, 
            kodeagama.namaagama 
        FROM siswa 
        LEFT JOIN kodejurusan ON siswa.kodejurusan = kodejurusan.kodejurusan 
        LEFT JOIN kodeagama ON siswa.agama = kodeagama.kodeagama";
    
        $result = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function tampil_data_show_agama() {
        $data = [];
        $query = "SELECT * FROM kodeagama";
        $result = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function tampil_data_show_jurusan() {
        $data = [];
        $query = "SELECT * FROM kodejurusan";
        $result = mysqli_query($this->koneksi, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    function tambah_jurusan($kodejurusan, $namajurusan) {
        // Check if kodejurusan already exists
        $checkQuery = "SELECT kodejurusan FROM kodejurusan WHERE kodejurusan = '$kodejurusan'";
        $checkResult = mysqli_query($this->koneksi, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            die("Kodejurusan '$kodejurusan' sudah ada, tidak bisa ditambahkan lagi.");
        }

        $query = "INSERT INTO kodejurusan (kodejurusan, namajurusan) 
                  VALUES ('$kodejurusan', '$namajurusan')";

        if (!mysqli_query($this->koneksi, $query)) {
            die("Query gagal: " . mysqli_error($this->koneksi) . " - Query: " . $query);
        }
    }

    // Menambah data agama
    function tambah_agama($kodeagama, $namaagama) {
        // Check if kodeagama already exists
        $checkQuery = "SELECT kodeagama FROM kodeagama WHERE kodeagama = '$kodeagama'";
        $checkResult = mysqli_query($this->koneksi, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            die("Kodeagama '$kodeagama' sudah ada, tidak bisa ditambahkan lagi.");
        }

        $query = "INSERT INTO kodeagama (kodeagama, namaagama) VALUES ('$kodeagama', '$namaagama')";

        if (!mysqli_query($this->koneksi, $query)) {
            die("Query gagal: " . mysqli_error($this->koneksi) . " - Query: " . $query);
        }
    }

    public function tambah_data_siswa($nisn, $nama, $jeniskelamin, $jurusan, $kelas, $alamat, $agama, $nohp) {
        $query = "INSERT INTO siswa (nisn, nama, jeniskelamin, kodejurusan, kelas, alamat, agama, nohp) 
                  VALUES ('$nisn', '$nama', '$jeniskelamin', '$jurusan', '$kelas', '$alamat', '$agama', '$nohp')";
        $result = mysqli_query($this->koneksi, $query);
    
        if (!$result) {
            echo "Query Error: " . mysqli_error($this->koneksi);
        }
    
        return $result;
    }
    
    
}

?>