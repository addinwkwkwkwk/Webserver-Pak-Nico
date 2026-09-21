<?php session_start(); if (!isset($_SESSION["login"])) { header("Location: login.php"); exit(); } ?>
<?php
$host = "localhost";
$user = "admin";
$pass = "123456";
$db   = "db_ujian";

$koneksi = @mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { $koneksi = @mysqli_connect("127.0.0.1", "root", "", $db); }

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT berkas FROM peserta WHERE id='$id'"));
    if (!empty($data['berkas']) && file_exists('uploads/' . $data['berkas'])) {
        unlink('uploads/' . $data['berkas']);
    }
    mysqli_query($koneksi, "DELETE FROM peserta WHERE id='$id'");
}
header("Location: index.php");
exit();
?>
