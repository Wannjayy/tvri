<?php 
session_start();
if(!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if($_SESSION["role"] != 'admin'){
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Home";
include('../layout/header.php');

$total_video_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM video");
$total_video = 0;
if ($total_video_result) {
    $row = mysqli_fetch_assoc($total_video_result);
    $total_video = $row['total'];
}

$current_month = date('m');
$current_year = date('Y');

$video_bulan_ini_result = mysqli_query($connection, "SELECT * FROM video WHERE MONTH(tanggal_upload) = '$current_month' AND YEAR(tanggal_upload) = '$current_year'");

$jumlah_video_bulan_ini = mysqli_num_rows($video_bulan_ini_result);

$youtube_video_result = mysqli_query($connection, "SELECT COUNT(*) AS total_youtube FROM video WHERE platform = 'YouTube'");
$total_youtube_video = 0;
if ($youtube_video_result) {
    $row = mysqli_fetch_assoc($youtube_video_result);
    $total_youtube_video = $row['total_youtube'];
}

$total_tiktok_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM video WHERE platform = 'TikTok'");
$total_tiktok_video = 0;
if ($total_tiktok_result) {
    $row = mysqli_fetch_assoc($total_tiktok_result);
    $total_tiktok_video = $row['total'];
}

$total_pegawai_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM pegawai");
$total_pegawai = 0;
if ($total_pegawai_result) {
    $row = mysqli_fetch_assoc($total_pegawai_result);
    $total_pegawai = $row['total'];
}

?>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-info text-white avatar">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <?= $total_pegawai ?>
                                            Pegawai
                                        </div>
                                        <div class="text-secondary">
                                            Total data pegawai
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <i class="fa-solid fa-video-camera"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <?= $total_video ?>
                                            Video
                                        </div>
                                        <div class="text-secondary">
                                            Total video diunggah
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar">
                                            <i class="fa-solid fa-calendar"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <?= $jumlah_video_bulan_ini ?>
                                            Video
                                        </div>
                                        <div class="text-secondary">
                                            Video diunggah bulan ini
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-youtube text-white avatar">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                                            <i class="fa-brands fa-youtube"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <?= $total_youtube_video ?>
                                            Video
                                        </div>
                                        <div class="text-secondary">
                                            Pada platform Youtube
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-black text-white avatar">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                            <i class="fa-brands fa-tiktok"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <?= $total_tiktok_video ?>
                                            Video
                                        </div>
                                        <div class="text-secondary">
                                            Pada platform Tiktok
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php');
?>