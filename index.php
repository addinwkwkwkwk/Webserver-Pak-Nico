<?php
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'addin';
}

// Inisialisasi data dummy peserta dengan preview foto bawaan
if (!isset($_SESSION['peserta'])) {
    // Placeholder image base64 untuk dummy data
    $dummy_img = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="%23d32f2f"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>';
    
    $_SESSION['peserta'] = [
        [
            'nis' => '1709',
            'nama' => 'Silver Aileen',
            'kelas' => 'XI Medsa',
            'jurusan' => 'Medical Science',
            'foto_name' => 'silver.jpg',
            'foto_data' => $dummy_img
        ],
        [
            'nis' => '1009',
            'nama' => 'Rafka Febriansyah',
            'kelas' => 'XI TKJ 8',
            'jurusan' => 'Teknik Komputer Jaringan',
            'foto_name' => 'rafka.jpg',
            'foto_data' => $dummy_img
        ]
    ];
}

// Proses Tambah Data Peserta & Upload Foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $kelas = trim($_POST['kelas']);
    $jurusan = trim($_POST['jurusan']);

    if (!empty($nis) && !empty($nama) && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileType = $_FILES['foto']['type'];

        // Mengubah foto menjadi format Base64 agar preview & download langsung jalan tanpa kendala izin folder di Linux
        $fileData = file_get_contents($fileTmpPath);
        $base64Data = 'data:' . $fileType . ';base64,' . base64_encode($fileData);

        $_SESSION['peserta'][] = [
            'nis' => $nis,
            'nama' => $nama,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
            'foto_name' => $fileName,
            'foto_data' => $base64Data
        ];

        header("Location: index.php");
        exit();
    }
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    if (isset($_SESSION['peserta'][$index])) {
        array_splice($_SESSION['peserta'], $index, 1);
    }
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftaran Ujian Sekolah</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 1050px;
            margin: 0 auto;
        }

        /* Top Header */
        .header-bar {
            background: #d32f2f;
            color: white;
            padding: 20px 25px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.2);
            margin-bottom: 24px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-left svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        .header-title h1 {
            font-size: 20px;
            font-weight: 700;
        }

        .header-title p {
            font-size: 12px;
            opacity: 0.9;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Card Section */
        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #d32f2f;
            font-size: 16px;
            font-weight: 700;
            padding-bottom: 15px;
            border-bottom: 1px solid #ffebee;
            margin-bottom: 20px;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-group input[type="text"]:focus {
            border-color: #d32f2f;
        }

        .file-upload-box {
            border: 1px dashed #ef9a9a;
            background-color: #fff5f5;
            padding: 14px;
            border-radius: 8px;
        }

        .btn-submit {
            width: 100%;
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: #b71c1c;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background-color: #ffebee;
            color: #b71c1c;
            font-weight: 700;
            text-align: left;
            padding: 12px 16px;
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            vertical-align: middle;
        }

        /* Container & Preview Foto */
        .foto-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .img-preview {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #ffebee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Action Buttons */
        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-download { background-color: #2e7d32; }
        .btn-download:hover { background-color: #1b5e20; }

        .btn-edit { background-color: #e65100; }
        .btn-edit:hover { background-color: #ef6c00; }

        .btn-hapus { background-color: #d32f2f; }
        .btn-hapus:hover { background-color: #b71c1c; }
    </style>
</head>
<body>

<div class="container">
    <!-- Top Header -->
    <div class="header-bar">
        <div class="header-left">
            <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            <div class="header-title">
                <h1>Portal Pendaftaran Ujian</h1>
                <p>SMK Telkom Malang</p>
            </div>
        </div>
        <a href="logout.php" class="btn-logout">Logout (<?= htmlspecialchars($_SESSION['user']) ?>)</a>
    </div>

    <!-- Form Registration -->
    <div class="card">
        <div class="card-header">
            ☰ Form Pendaftaran Peserta
        </div>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" placeholder="Masukkan NIS" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap Peserta</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" name="kelas" placeholder="Contoh: XII RPL 1" required>
            </div>
            <div class="form-group">
                <label>Jurusan</label>
                <input type="text" name="jurusan" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
            </div>
            <div class="form-group">
                <label>Upload Foto Peserta (.jpg / .png)</label>
                <div class="file-upload-box">
                    <input type="file" name="foto" accept="image/*" required>
                </div>
            </div>
            <button type="submit" name="tambah" class="btn-submit">
                ➤ Daftarkan Peserta
            </button>
        </form>
    </div>

    <!-- Registered List Table -->
    <div class="card">
        <div class="card-header">
            ☰ Daftar Peserta Terdaftar
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Foto Peserta</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['peserta'])): ?>
                    <?php foreach ($_SESSION['peserta'] as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item['nis']) ?></td>
                            <td><?= htmlspecialchars($item['nama']) ?></td>
                            <td><?= htmlspecialchars($item['kelas']) ?></td>
                            <td><?= htmlspecialchars($item['jurusan']) ?></td>
                            <td>
                                <div class="foto-cell">
                                    <img src="<?= $item['foto_data'] ?>" alt="Foto" class="img-preview">
                                    <a href="<?= $item['foto_data'] ?>" download="<?= htmlspecialchars($item['foto_name']) ?>" class="btn-action btn-download">Download</a>
                                </div>
                            </td>
                            <td>
                                <a href="#" class="btn-action btn-edit">Edit</a>
                                <a href="?hapus=<?= $index ?>" class="btn-action btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #888;">Belum ada peserta terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
