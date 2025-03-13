<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jurusan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 450px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        }
        .button {
            width: 100%;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title is-4 has-text-centered">Tambah Jurusan</h2>
        <form method="post" action="">
            <div class="field">
                <label class="label" for="namajurusan">Nama Jurusan:</label>
                <div class="control">
                    <input class="input" type="text" id="namajurusan" name="namajurusan" placeholder="Masukkan Nama Jurusan" required>
                </div>
            </div>
            <div class="field">
                <button class="button is-primary is-fullwidth">Tambah</button>
            </div>
        </form>
        <a class="back-link has-text-primary" href="data_jurusan.php">Lihat Data Jurusan</a>
    </div>
</body>
</html>
