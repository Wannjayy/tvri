<?php 
session_start();
if(!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");
} else if($_SESSION["role"] != 'admin') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Data Video";
include('../layout/header.php');
require_once('../../../config.php');

// Ambil parameter tanggal mulai dan selesai
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';

$tanggal_condition = '';
if ($tanggal_mulai && $tanggal_selesai) {
    $tanggal_condition = "AND tanggal_upload BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
}

$result = mysqli_query($connection, "SELECT id, judul_video, link_video, platform, keterangan, tanggal_upload FROM video WHERE 1 $tanggal_condition");

if (!$result) {
    die("Query error: " . mysqli_error($connection));
}
?>

<div class="page-body">
    <div class="container-xl">
        <div class="d-flex justify-content-between mt-3">
            <form method="GET" action="" class="me-2">
                <input
                    type="date"
                    name="tanggal_mulai"
                    value="<?= htmlspecialchars($tanggal_mulai) ?>"
                    required="required">
                <span>sampai</span>
                <input
                    type="date"
                    name="tanggal_selesai"
                    value="<?= htmlspecialchars($tanggal_selesai) ?>"
                    required="required">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <a
                href="export_pdf.php?tanggal_mulai=<?= urlencode($tanggal_mulai) ?>&tanggal_selesai=<?= urlencode($tanggal_selesai) ?>"
                class="btn btn-secondary">Export PDF</a>
        </div>
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No.</th>
                <th>Judul Video</th>
                <th>Link Video</th>
                <th>Platform</th>
                <th>Keterangan</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>

            <?php if(mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="6">Data kosong, tambahkan data baru</td>
            </tr>
        <?php } else { ?>
            <?php $no = 1;
                while ($video = mysqli_fetch_array($result, MYSQLI_ASSOC)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $video['judul_video']?></td>
                <td><a href="<?= $video['link_video'] ?>" target="_blank"><?= $video['link_video'] ?></a></td>
                <td><?= $video['platform']?></td>
                <td><?= $video['keterangan']?></td>
                <td><?= strftime('%d %B %Y', strtotime($video['tanggal_upload'])) ?></td>
                <td class="text-center">
                    <a
                        href="<?= base_url('tampilan/admin/data_video/hapus.php?id=' .$video['id'])?>"
                        class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>
<?php include('../layout/footer.php'); ?>