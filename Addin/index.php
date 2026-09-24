<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "admin";
$pass = "123456";
$db   = "db_ujian";

$koneksi = @mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    $koneksi = @mysqli_connect("127.0.0.1", "root", "", $db);
}

$pesan = "";
if (isset($_POST['daftar'])) {
    $nis     = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kelas   = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    
    $berkas_nama = "";
    if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] == 0) {
        // Buat folder uploads jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $berkas_nama = time() . '_' . $_FILES['berkas']['name'];
        move_uploaded_file($_FILES['berkas']['tmp_name'], 'uploads/' . $berkas_nama);
    }

    $sql = "INSERT INTO peserta (nis, nama, kelas, jurusan, berkas) VALUES ('$nis', '$nama', '$kelas', '$jurusan', '$berkas_nama')";
    if (mysqli_query($koneksi, $sql)) {
        $pesan = "<div class='alert alert-success'>Data pendaftaran berhasil disimpan!</div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal menyimpan data ke database!</div>";
    }
}

$data_peserta = mysqli_query($koneksi, "SELECT * FROM peserta ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftaran Ujian Sekolah</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f3f4f6; padding: 20px 10px; color: #1f2937; }
        .container { max-width: 950px; margin: 0 auto; width: 100%; }
        .banner { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.25); margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .banner-left { display: flex; align-items: center; gap: 15px; }
        .banner-icon { width: 45px; height: 45px; fill: white; flex-shrink: 0; }
        .banner h2 { font-size: 20px; font-weight: 700; margin-bottom: 2px; }
        .banner p { font-size: 13px; color: #fca5a5; }
        .btn-logout { background: rgba(255, 255, 255, 0.2); color: white; text-decoration: none; padding: 8px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 600; border: 1px solid rgba(255,255,255,0.4); transition: 0.2s; white-space: nowrap; }
        .btn-logout:hover { background: white; color: #dc2626; }
        .card { background: white; border-radius: 12px; padding: 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #f3f4f6; margin-bottom: 20px; }
        .card-title { display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 700; color: #dc2626; margin-bottom: 18px; border-bottom: 2px solid #fee2e2; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 12px; font-size: 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; outline: none; transition: 0.2s; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        .file-box { border: 2px dashed #fca5a5; background: #fff5f5; border-radius: 8px; padding: 12px; text-align: center; }
        .file-box input[type=file] { width: 100%; cursor: pointer; }
        
        /* CSS Live Preview Foto Form */
        .preview-container { margin-top: 10px; display: none; text-align: center; }
        .img-form-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #dc2626; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        
        /* CSS Foto Tabel */
        .foto-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1.5px solid #e5e7eb; display: block; }
        .no-foto { font-size: 11px; color: #9ca3af; font-style: italic; }

        .btn-submit { width: 100%; background: #dc2626; color: white; border: none; padding: 12px; font-size: 15px; font-weight: 700; border-radius: 8px; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
        .btn-submit:hover { background: #b91c1c; }
        .alert { padding: 12px 15px; border-radius: 8px; font-size: 13.5px; font-weight: 600; margin-bottom: 18px; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; min-width: 650px; }
        th, td { padding: 10px 12px; font-size: 13.5px; text-align: left; border-bottom: 1px solid #f3f4f6; vertical-align: middle; white-space: nowrap; }
        th { background: #fef2f2; color: #991b1b; font-weight: 700; }
        tr:hover { background: #fff1f2; }
        .btn-act { display: inline-block; padding: 5px 9px; color: white; text-decoration: none; border-radius: 5px; font-size: 11.5px; font-weight: 600; margin-right: 2px; }
        .btn-dl { background: #16a34a; }
        .btn-edit { background: #d97706; }
        .btn-del { background: #dc2626; }
        @media (max-width: 600px) {
            body { padding: 10px 5px; }
            .banner { flex-direction: column; align-items: flex-start; }
            .banner-left { width: 100%; }
            .btn-logout { align-self: flex-start; margin-top: 5px; }
            .card { padding: 16px; }
            .banner h2 { font-size: 18px; }
            .banner p { font-size: 12px; }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="banner">
        <div class="banner-left">
            <svg class="banner-icon" viewBox="0 0 24 24"><path fill="currentColor" d="M12 3L1 9l11 6l9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            <div>
                <h2>Portal Pendaftaran Ujian</h2>
                <p>SMK Telkom Malang</p>
            </div>
        </div>
        <a href="logout.php" class="btn-logout">Logout (<?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?>)</a>
    </div>

    <?= $pesan; ?>

    <div class="card">
        <div class="card-title">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            Form Pendaftaran Peserta
        </div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap Peserta</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
            </div>
            <div class="form-group">
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
            </div>
            <div class="form-group">
                <label>Upload Foto Siswa</label>
                <div class="file-box">
                    <input type="file" name="berkas" id="inputBerkas" class="form-control" accept="image/*" onchange="previewImage(event)" required>
                    <div class="preview-container" id="previewContainer">
                        <p style="font-size:12px; color:#6b7280; margin-bottom:5px;">Preview Foto:</p>
                        <img id="imagePreview" class="img-form-preview" src="#" alt="Preview Foto">
                    </div>
                </div>
            </div>
            <button type="submit" name="daftar" class="btn-submit">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                Daftarkan Peserta
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-title">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
            Daftar Peserta Terdaftar
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Berkas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($r = mysqli_fetch_array($data_peserta)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <?php if (!empty($r['berkas']) && file_exists('uploads/' . $r['berkas'])): ?>
                                <a href="uploads/<?= htmlspecialchars($r['berkas']); ?>" target="_blank" title="Klik untuk lihat foto penuh">
                                    <img src="uploads/<?= htmlspecialchars($r['berkas']); ?>" alt="Foto <?= htmlspecialchars($r['nama']); ?>" class="foto-thumb">
                                </a>
                            <?php else: ?>
                                <span class="no-foto">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r['nis']); ?></td>
                        <td><?= htmlspecialchars($r['nama']); ?></td>
                        <td><?= htmlspecialchars($r['kelas']); ?></td>
                        <td><?= htmlspecialchars($r['jurusan']); ?></td>
                        <td>
                            <?php if (!empty($r['berkas'])): ?>
                                <a href="download.php?file=<?= urlencode($r['berkas']); ?>" class="btn-act btn-dl">Download</a>
                            <?php else: ?>
                                <span style="color:#9ca3af;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $r['id']; ?>" class="btn-act btn-edit">Edit</a>
                            <a href="hapus.php?id=<?= $r['id']; ?>" class="btn-act btn-del" onclick="return confirm('Hapus peserta ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
// JavaScript untuk Live Preview foto sebelum form di-submit
function previewImage(event) {
    const input = event.target;
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>

</body>
</html>
