<?php
include 'koneksi.php';
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit(); }

if (isset($_POST['submit'])) {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    
    $filename = $_FILES['foto']['name'];
    if ($filename != "") {
        $rand = rand();
        $foto_name = $rand . '_' . $filename;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto_name);
    } else {
        $foto_name = "";
    }

    mysqli_query($koneksi, "INSERT INTO siswa VALUES(NULL, '$nisn', '$nama', '$kelas', '$foto_name')");
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Siswa</title>
    <style>
        body { font-family: sans-serif; background: #fef2f2; padding: 30px; }
        .form-box { max-width: 400px; margin: auto; background: white; padding: 25px; border-radius: 10px; border-top: 5px solid #dc2626; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        label { display: block; font-weight: bold; margin-top: 10px; color: #991b1b; }
        input[type=text], input[type=file] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #fca5a5; border-radius: 4px; box-sizing: border-box; }
        button { background: #dc2626; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
<div class="form-box">
    <h3 style="color:#991b1b; margin-top:0;">Tambah Data Siswa</h3>
    <form method="POST" enctype="multipart/form-data">
        <label>NISN:</label>
        <input type="text" name="nisn" required>
        <label>Nama:</label>
        <input type="text" name="nama" required>
        <label>Kelas:</label>
        <input type="text" name="kelas" required>
        <label>Foto:</label>
        <input type="file" name="foto" accept="image/*">
        <button type="submit" name="submit">Simpan</button>
    </form>
</div>
</body>
</html>
