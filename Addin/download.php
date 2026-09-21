<?php
session_start();
if (!isset($_SESSION['login'])) {
    die("Akses ditolak!");
}

if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $filepath = __DIR__ . "/uploads/" . $file;

    if (file_exists($filepath)) {
        // Header untuk memaksa browser mengunduh file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo "File tidak ditemukan di server.";
    }
}
?>
