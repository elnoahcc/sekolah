<?php
include "koneksi.php";
$db = new database();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <table border="1">
        <tr>
            <th>NO</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Jurusan</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>NO HP</th>
            <th>Option</th>
        </tr>
        <?php
        $no = 1;
        foreach ($db->tampil_data_show_siswa() as $x) {
        ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $x['nisn']; ?></td>
                <td><?php echo $x['nama']; ?></td>
                <td><?php echo $x['jeniskelamin']; ?></td>
                <td><?php echo isset($x['namajurusan']) ? $x['namajurusan'] : 'Tidak Diketahui'; ?></td>
                <td><?php echo $x['kelas']; ?></td>
                <td><?php echo $x['alamat']; ?></td>
                <td><?php echo isset($x['namaagama']) ? $x['namaagama'] : 'Tidak Diketahui'; ?></td>
                <td><?php echo $x['nohp']; ?></td>
                <td>
                    <a href="edit_siswa.php?idsiswa=<?php echo $x['idsiswa']; ?>&aksi=edit">Edit</a> 
                    <a href="proses.php?idsiswa=<?php echo $x['idsiswa']; ?>&aksi=hapus">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="Tambah.php">Tambah Data Siswa</a>
</body>
</html>