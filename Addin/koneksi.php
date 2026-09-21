<?php
mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = mysqli_connect("127.0.0.1", "root", "", "db_siswa");
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>
