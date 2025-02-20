<?php
class databaseagama{
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
        
        if($cekdb){
            echo "Koneksi ke database berhasil";
        }else{
            echo "Koneksi ke database gagal";
        }

        
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
}

?>