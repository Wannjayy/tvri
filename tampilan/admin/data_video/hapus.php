<?php
session_start();
require_once('../../../config.php');

// Cek apakah user sudah login dan memiliki akses sebagai pegawai
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit();
}

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid.");
}

$id = intval($_GET['id']);

// Hapus data dari database
$query = "DELETE FROM video WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['berhasil'] = 'Data berhasil dihapus';
    header("Location: video.php");
    exit();
} else {
    $_SESSION['validasi'] = "Gagal menghapus data: " . mysqli_error($connection);
}

// Tutup koneksi
$stmt->close();
$connection->close();
?>
