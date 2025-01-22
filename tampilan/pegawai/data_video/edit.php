<?php 
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Edit Video";
include('../layout/header.php');
require_once('../../../config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM video WHERE id = $id";
    $result = mysqli_query($connection, $query);
    $video = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (!$video) {
        $_SESSION['validasi'] = "Data video tidak ditemukan.";
        header("Location: video.php");
        exit;
    }
} else {
    header("Location: video.php");
    exit;
}

if (isset($_POST['submit'])) {
    $judul_video = htmlspecialchars($_POST['judul_video']);
    $link_video = htmlspecialchars($_POST['link_video']);
    $platform = htmlspecialchars($_POST['platform']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    $tanggal_upload = htmlspecialchars($_POST['tanggal_upload']);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $pesan_kesalahan = [];
        if (empty($judul_video)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Judul Video Wajib diisi";
        }
        if (empty($link_video)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Link Video Wajib diisi";
        }
        if (empty($platform)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Platform Wajib diisi";
        }
        if (empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Keterangan Wajib diisi";
        }
        if (empty($tanggal_upload)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Tanggal Upload Wajib diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $updateQuery = "UPDATE video SET 
                judul_video = '$judul_video', 
                link_video = '$link_video', 
                platform = '$platform', 
                keterangan = '$keterangan', 
                tanggal_upload = '$tanggal_upload' 
                WHERE id = $id";

            if (mysqli_query($connection, $updateQuery)) {
                $_SESSION['berhasil'] = 'Data berhasil diupdate';
                header("Location: video.php");
                exit;
            } else {
                $_SESSION['validasi'] = "Gagal mengupdate data: " . mysqli_error($connection);
            }
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('tampilan/pegawai/data_video/edit.php?id=' . $id) ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="card col-md-12">
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="">Judul Video</label>
                            <input type="text" class="form-control" name="judul_video" value="<?= htmlspecialchars($video['judul_video']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Link Video</label>
                            <input type="text" class="form-control" name="link_video" value="<?= htmlspecialchars($video['link_video']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Platform</label>
                            <input type="text" class="form-control" name="platform" value="<?= htmlspecialchars($video['platform']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" value="<?= htmlspecialchars($video['keterangan']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="">Tanggal Upload</label>
                            <input type="date" class="form-control" name="tanggal_upload" value="<?= htmlspecialchars($video['tanggal_upload']) ?>">
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Update</button>
                        <a href="video.php" class="btn btn-secondary">Batal</a>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('../layout/footer.php'); ?>
