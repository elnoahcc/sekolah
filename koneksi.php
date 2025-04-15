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
        );

        $cekdb = mysqli_select_db(
            $this->koneksi, $this->database);
        


        
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

    function tambah_siswa($nisn, $nama, $jeniskelamin, $jurusan, $kelas, $alamat, $agama, $nohp) {
        $query = "INSERT INTO siswa (nisn, nama, jeniskelamin, kodejurusan, kelas, alamat, agama, nohp) VALUES ('$nisn', '$nama', '$jeniskelamin', '$jurusan', '$kelas', '$alamat', '$agama', '$nohp')";
        mysqli_query($this->koneksi, "insert into siswa (nisn, nama, jeniskelamin, kodejurusan, kelas, alamat, agama, nohp) values ('$nisn', '$nama', '$jeniskelamin', '$jurusan', '$kelas', '$alamat', '$agama', '$nohp')");
        header("Location: data_siswa.php");
        exit();
    }
        
        



    }
    ?>