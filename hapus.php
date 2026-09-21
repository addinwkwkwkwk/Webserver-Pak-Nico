<?php
include 'koneksi.php';
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit(); }

$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT foto FROM siswa WHERE id='$id'"));
if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])) {
    unlink('uploads/' . $data['foto']);
}
mysqli_query($koneksi, "DELETE FROM siswa WHERE id='$id'");
header("Location: index.php");
exit();
?>
