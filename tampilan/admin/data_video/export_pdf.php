<?php
require_once('../../../vendor/autoload.php');
require_once('../../../config.php');

use Dompdf\Dompdf;

session_start();

// Cek login dan akses
if(!isset($_SESSION["login"]) || $_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit();
}

// Ambil parameter tanggal mulai dan selesai
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';

if (!$tanggal_mulai || !$tanggal_selesai) {
    die("Tanggal tidak valid.");
}

$tanggal_condition = "tanggal_upload BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
$query = "SELECT id, judul_video, link_video, platform, keterangan, tanggal_upload FROM video WHERE $tanggal_condition";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query error: " . mysqli_error($connection));
}

// HTML template untuk PDF
$html = '<h1>Laporan Data Video</h1>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">';
$html .= '<tr>';
$html .= '<th>No.</th>';
$html .= '<th>Judul Video</th>';
$html .= '<th>Link</th>';
$html .= '<th>Platform</th>';
$html .= '<th>Keterangan</th>';
$html .= '<th>Tanggal Upload</th>';
$html .= '</tr>';

$no = 1;
while ($video = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td>' . $no++ . '</td>';
    $html .= '<td>' . $video['judul_video'] . '</td>';
    $html .= '<td>' . $video['link_video'] . '</td>';
    $html .= '<td>' . $video['platform'] . '</td>';
    $html .= '<td>' . $video['keterangan'] . '</td>';
    $html .= '<td>' . date('d M Y', strtotime($video['tanggal_upload'])) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Output PDF
$dompdf->stream('Laporan_Data_Video.pdf');
