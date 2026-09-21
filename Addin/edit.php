<?php session_start(); if (!isset($_SESSION["login"])) { header("Location: login.php"); exit(); } ?>
<?php
$host = "localhost";
$user = "admin";
$pass = "123456";
$db   = "db_ujian";

$koneksi = @mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { $koneksi = @mysqli_connect("127.0.0.1", "root", "", $db); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($koneksi, "SELECT * FROM peserta WHERE id='$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $nis     = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas   = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);

    if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] == 0) {
        if (!empty($data['berkas']) && file_exists('uploads/' . $data['berkas'])) {
            unlink('uploads/' . $data['berkas']);
        }
        $berkas_nama = time() . '_' . $_FILES['berkas']['name'];
        move_uploaded_file($_FILES['berkas']['tmp_name'], 'uploads/' . $berkas_nama);
        $sql = "UPDATE peserta SET nis='$nis', nama='$nama', kelas='$kelas', jurusan='$jurusan', berkas='$berkas_nama' WHERE id='$id'";
    } else {
        $sql = "UPDATE peserta SET nis='$nis', nama='$nama', kelas='$kelas', jurusan='$jurusan' WHERE id='$id'";
    }

    mysqli_query($koneksi, $sql);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta Ujian</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f3f4f6; padding: 30px 15px; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: white; border-radius: 12px; padding: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #f3f4f6; }
        .card-title { display: flex; align-items: center; justify-content: space-between; font-size: 17px; font-weight: 700; color: #dc2626; margin-bottom: 20px; border-bottom: 2px solid #fee2e2; padding-bottom: 12px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13.5px; font-weight: 600; color: #4b5563; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 11px 14px; font-size: 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; outline: none; transition: 0.2s; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        .btn-submit { width: 100%; background: #d97706; color: white; border: none; padding: 13px; font-size: 15px; font-weight: 700; border-radius: 8px; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-submit:hover { background: #b45309; }
        .btn-back { color: #6b7280; text-decoration: none; font-size: 13px; font-weight: 600; }
        .btn-back:hover { color: #111827; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-title">
            <span>Edit Data Peserta</span>
            <a href="index.php" class="btn-back">&larr; Batal</a>
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" class="form-control" value="<?= htmlspecialchars($data['nis']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap Peserta</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control" value="<?= htmlspecialchars($data['kelas']); ?>" required>
            </div>
            <div class="form-group">
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control" value="<?= htmlspecialchars($data['jurusan']); ?>" required>
            </div>
            <div class="form-group">
                <label>Ganti Berkas (Opsional)</label>
                <input type="file" name="berkas" class="form-control">
                <?php if($data['berkas']): ?>
                    <small style="color:#6b7280; margin-top:5px; display:block;">File saat ini: <?= htmlspecialchars($data['berkas']); ?></small>
                <?php endif; ?>
            </div>
            <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>
