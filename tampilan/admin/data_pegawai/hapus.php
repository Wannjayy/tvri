<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])) {
    header("Location: ../../../auth/login.php?pesan=belum_login");
} else if($_SESSION["role"] != 'admin'){
    header("Location: ../../../auth/login.php?pesan=tolak_akses");
}

require_once('../../../config.php');

// Pastikan ada ID pegawai yang dikirimkan
if(isset($_GET['id'])) {
    $id_pegawai = $_GET['id'];

    // Hapus data foto pegawai terlebih dahulu jika ada
    $ambil_foto = mysqli_query($connection, "SELECT foto FROM pegawai WHERE id = '$id_pegawai'");
    if (mysqli_num_rows($ambil_foto) > 0) {
        $data_foto = mysqli_fetch_assoc($ambil_foto);
        $foto_pegawai = $data_foto['foto'];
        
        // Hapus file foto dari direktori
        if(file_exists("../../../assets/img/foto_pegawai/".$foto_pegawai) && $foto_pegawai != "") {
            unlink("../../../assets/img/foto_pegawai/".$foto_pegawai);
        }
    }

    // Hapus data pegawai dari tabel pegawai
    $hapus_pegawai = mysqli_query($connection, "DELETE FROM pegawai WHERE id = '$id_pegawai'");

    if ($hapus_pegawai) {
        // Setelah data pegawai dihapus, hapus data user terkait
        $hapus_user = mysqli_query($connection, "DELETE FROM users WHERE id_pegawai = '$id_pegawai'");
        
        if ($hapus_user) {
            $_SESSION['berhasil'] = 'Data pegawai berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus data user';
        }
    } else {
        $_SESSION['error'] = 'Gagal menghapus data pegawai';
    }

    // Redirect ke halaman pegawai setelah proses hapus selesai
    header("Location: pegawai.php");
    exit;
} else {
    $_SESSION['error'] = 'ID pegawai tidak ditemukan';
    header("Location: pegawai.php");
    exit;
}
?>
