<?php
session_start();

$koneksi = @mysqli_connect("localhost", "root", "", "db_ujian");
if (!$koneksi) { $koneksi = @mysqli_connect("127.0.0.1", "root", "", "db_ujian"); }
if (!$koneksi) { $koneksi = @mysqli_connect("localhost", "admin", "123456", "db_ujian"); }

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password']));

    $username = mysqli_real_escape_string($koneksi, $username);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($query && mysqli_num_rows($query) > 0) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Ujian Sekolah</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; color: #1f2937; }
        .login-card { background: white; border-radius: 12px; padding: 35px 30px; box-shadow: 0 10px 25px rgba(220, 38, 38, 0.15); border-top: 6px solid #dc2626; width: 100%; max-width: 380px; }
        .login-header { text-align: center; margin-bottom: 25px; }
        .login-header svg { fill: #dc2626; width: 48px; height: 48px; margin-bottom: 10px; }
        .login-header h2 { font-size: 20px; color: #991b1b; font-weight: 700; }
        .login-header p { font-size: 13px; color: #6b7280; margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .password-wrapper { position: relative; }
        .form-control { width: 100%; padding: 11px 45px 11px 14px; font-size: 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; outline: none; transition: 0.2s; }
        .form-control:focus { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
        .btn-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 12px; font-weight: 700; color: #dc2626; cursor: pointer; }
        .btn-login { width: 100%; background: #dc2626; color: white; border: none; padding: 12px; font-size: 15px; font-weight: 700; border-radius: 8px; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-login:hover { background: #b91c1c; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 18px; text-align: center; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6l9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
        <h2>Portal Ujian Sekolah</h2>
        <p>SMK Telkom Malang</p>
    </div>
    <?php if($error): ?>
        <div class="alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" id="passInput" name="password" class="form-control" placeholder="Masukkan password" required>
                <button type="button" class="btn-toggle" onclick="togglePassword()">Lihat</button>
            </div>
        </div>
        <button type="submit" name="login" class="btn-login">Masuk System</button>
    </form>
</div>

<script>
function togglePassword() {
    var field = document.getElementById("passInput");
    var btn = document.querySelector(".btn-toggle");
    if (field.type === "password") {
        field.type = "text";
        btn.textContent = "Sembunyi";
    } else {
        field.type = "password";
        btn.textContent = "Lihat";
    }
}
</script>
</body>
</html>
