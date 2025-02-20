<?php
include "koneksijurusan.php";
$db = new databasejurusan();
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
            <th>Kode Jurusan</th>
            <th>Nama Jurusan</th>
            <th>Action</th>
        </tr>
        <?php
        $no = 1;
        foreach ($db->tampil_data_show_jurusan() as $x) {
        ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo isset($x['namajurusan']) ? $x['namajurusan'] : 'Tidak Diketahui'; ?></td>
                <td>
                    <a href="edit_siswa.php?kodejurusan=<?php echo $x['kodejurusan']; ?>&aksi=edit">Edit</a> 
                    <a href="proses.php?kodejurusan=<?php echo $x['kodejurusan']; ?>&aksi=hapus">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="Tambah.php">Tambah Data Siswa</a>
</body>
</html>