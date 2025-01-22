<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])) {
  header("Location: ../../../auth/login.php?pesan=belum_login");
} else if($_SESSION["role"] != 'pegawai') {
  header("Location: ../../../auth/login.php?pesan=tolak_akses");
}

$judul = "Tambah Video";
include('../layout/header.php');
require_once('../../../config.php');

if(isset($_POST['submit'])) {

    $judul_video = htmlspecialchars($_POST['judul_video']);
    $link_video = htmlspecialchars($_POST['link_video']);
    $platform = htmlspecialchars($_POST['platform']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $tanggal_upload = htmlspecialchars($_POST['tanggal_upload']);


    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(empty($judul_video)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Judul Video Wajib diisi";
        }
        if(empty($link_video)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Link Video Wajib diisi";
        }
        if(empty($platform)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Platform Wajib diisi";
        }
        if(empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Keterangan Wajib diisi";
        }
        if(empty($tanggal_upload)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Tanggal Upload Wajib diisi";
        }

        if(!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $video = mysqli_query($connection, "INSERT INTO video (judul_video, link_video, platform, keterangan, tanggal_upload) 
            VALUES ('$judul_video', '$link_video', '$platform', '$keterangan', '$tanggal_upload')");

            if (!$video) {
                die("Error pada query INSERT video: " . mysqli_error($connection));
            }

            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header("Location: video.php");
            exit;
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('tampilan/pegawai/data_video/tambah.php') ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="">Judul Video</label>
                            <input type="text" class="form-control" name="judul_video" value="<?php if(isset($_POST['judul_video'])) echo $_POST['judul_video'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Link Video</label>
                            <input type="text" class="form-control" name="link_video" value="<?php if(isset($_POST['link_video'])) echo $_POST['link_video'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Platform</label>
                            <input type="text" class="form-control" name="platform" value="<?php if(isset($_POST['platform'])) echo $_POST['platform'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="<?php if(isset($_POST['keterangan'])) echo $_POST['keterangan'] ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Tanggal Upload</label>
                            <input type="date" class="form-control" name="tanggal_upload" value="<?php if(isset($_POST['tanggal_upload'])) echo $_POST['tanggal_upload'] ?>">
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Simpan</button>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
