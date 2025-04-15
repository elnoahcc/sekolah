<?php
class databasejurusan{
    var $host = "localhost";
    var $user = "root";
    var $password = "";
    var $database = "sekolah";

    function __construct(){
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->user, 
            $this->password,
            $this->database
        );

        $cekdb = mysqli_select_db(
            $this->koneksi, $this->database);
        
        

        
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
}

?>