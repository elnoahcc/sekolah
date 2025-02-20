<?php

class database{
    var $host = "localhost";
    var $username = "root";
    var $password = "";
    var $database = "sekolah";
}
    function __construct(){
        $this->koneksi = mysqli_connect(
            $this->host, $this->username, $this->password);
        $cekdb = mysql_select_db($this->koneksi,$this->database);

        if($cekdb){
            echo "Database berhasil terhubung";
        }else{
            echo "Database tidak terhubung";
        }
    }
?>