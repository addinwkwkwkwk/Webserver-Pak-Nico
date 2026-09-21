<?php
include 'koneksi.php';
$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Data Siswa</title>
    <style>
        body { font-family: sans-serif; background: #fef2f2; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(220, 38, 38, 0.15); width: 320px; border-top: 6px solid #dc2626; }
        h2 { text-align: center; color: #991b1b; margin-top: 0; margin-bottom: 20px; }
        label { font-weight: bold; font-size: 13px; color: #7f1d1d; }
        input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 8px 0 16px; border: 1px solid #fca5a5; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; background: #dc2626; color: white; border: none; padding: 11px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
        button:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 13px; margin-bottom: 12px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
<div class="login-card">
    <h2>Login System</h2>
    <?php if($error): ?><div class="error"><?= $error; ?></div><?php endif; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Masukkan username">
        <label>Password</label>
        <input type="password" name="password" required placeholder="Masukkan password">
        <button type="submit" name="login">MASUK</button>
    </form>
</div>
</body>
</html>
