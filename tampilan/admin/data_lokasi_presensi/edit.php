<?php 
session_start();
ob_start();

if(!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if($_SESSION["role"] != 'admin'){
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Edit Data Lokasi Absensi";
include('../layout/header.php');
require_once('../../config.php');

// Initialize $id
$id = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
}

if ($id) {
    // Fetch data from the database
    $result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE id=$id");
    $lokasi = mysqli_fetch_array($result);
    // Assign fetched data to variables (make sure these are set before using them)
    $Nama_Lokasi = $lokasi ['nama_lokasi'];
    $Alamat_Lokasi = $lokasi ['alamat_lokasi'];
    $Tipe_Lokasi = $lokasi ['tipe_lokasi'];
    $Latitude = $lokasi['latitude'];
    $Longitude = $lokasi ['longitude'];
    $Radius = $lokasi ['radius'];
    $Zona_Waktu = $lokasi ['zona_waktu'];
    $Jam_Masuk = $lokasi['jam_masuk'];
    $Jam_Pulang = $lokasi ['jam_pulang'];
    }

if (isset($_POST['Update'])) {
    $Nama_Lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $Alamat_Lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $Tipe_Lokasi = htmlspecialchars($_POST['Tipe_Lokasi']);
    $Latitude = htmlspecialchars($_POST['latitude']);
    $Longitude = htmlspecialchars($_POST['longitude']);
    $Radius = htmlspecialchars($_POST['radius']);
    $Zona_Waktu = htmlspecialchars($_POST['Zona_Waktu']); 
    $Jam_Masuk = htmlspecialchars($_POST['Jam_Masuk']);
    $Jam_Pulang = htmlspecialchars($_POST['Jam_Pulang']);
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validation checks...
        if (empty($Nama_Lokasi)) {
            $pesan_kesalahan[] = "Nama Lokasi Wajib diisi";
        }
        if (empty($Alamat_Lokasi)) {
            $pesan_kesalahan[] = "Alamat Lokasi Wajib diisi";
        }
        if (empty($Tipe_Lokasi)) {
            $pesan_kesalahan[] = "Tipe Lokasi Wajib diisi";
        }
        if (empty($Latitude)) {
            $pesan_kesalahan[] = "Latitude Wajib diisi";
        }
        if (empty($Longitude)) {
            $pesan_kesalahan[] = "Longitude Wajib diisi";
        }
        if (empty($Radius)) {
            $pesan_kesalahan[] = "Radius Wajib diisi";
        }
        if (empty($Zona_Waktu)) {
            $pesan_kesalahan[] = "Zona Waktu Wajib diisi";
        }
        if (empty($Jam_Masuk)) {
            $pesan_kesalahan[] = "Jam Masuk Wajib diisi";
        }
        if (empty($Jam_Pulang)) {
            $pesan_kesalahan[] = "Jam Pulang Wajib diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            // Update the data in the database
            $result = mysqli_query($connection, "UPDATE lokasi_presensi SET 
                Nama_Lokasi = '$Nama_Lokasi',
                Alamat_Lokasi = '$Alamat_Lokasi',
                Tipe_Lokasi = '$Tipe_Lokasi',
                Latitude = '$Latitude',
                Longitude = '$Longitude',
                Radius = '$Radius',
                Zona_Waktu = '$Zona_Waktu',
                Jam_Masuk = '$Jam_Masuk',
                Jam_Pulang = '$Jam_Pulang'
            WHERE id = $id");

            // Check if update was successful
            if ($result) {
                $_SESSION['berhasil'] = "Data Berhasil diupdate";
                header("Location: lokasi_presensi.php");
                exit;
            } else {
                $_SESSION['gagal'] = "Gagal mengupdate data";
            }
        }
    }
}
?>

<!-- Page body -->
<div class="page-body">
  <div class="container-xl">
    <div class="card col-md-6">
      <div class="card-body">
        <form action="<?= base_url('admin/data_lokasi_presensi/edit.php')?>" method="POST">
            <div class="mb-3">
                <label for="">Nama Lokasi</label>
                <input type="text" class="form-control" name="nama_lokasi" value="<?= $Nama_Lokasi ?>">
            </div>

            <div class="mb-3">
                <label for="">Alamat Lokasi</label>
                <input type="text" class="form-control" name="alamat_lokasi" value="<?= $Alamat_Lokasi ?>">
            </div>

            <div class="mb-3">
                <label for="">Tipe Lokasi</label>
               <select name="Tipe_Lokasi" class="form-control">
                <option value="">--Pilih Tipe Lokasi</option>
                <option <?php if($Tipe_Lokasi == 'Pusat') { echo 'selected'; } ?> value="Pusat">Pusat</option>
                <option <?php if($Tipe_Lokasi == 'Cabang') { echo 'selected'; } ?> value="Cabang">Cabang</option>
               </select>
            </div>

            <div class="mb-3">
                <label for="">Latitude</label>
                <input type="text" class="form-control" name="latitude" value="<?= $Latitude ?>">
            </div>

            <div class="mb-3">
                <label for="">Longitude</label>
                <input type="text" class="form-control" name="longitude" value="<?= $Longitude ?>">
            </div>

            <div class="mb-3">
                <label for="">Radius</label>
                <input type="text" class="form-control" name="radius" value="<?= $Radius ?>">
            </div>

            <div class="mb-3">
                <label for="">Zona Waktu</label>
                <select name="Zona_Waktu" class="form-control">
                    <option value="">--Pilih Zona Waktu</option>
                    <option <?php if($Zona_Waktu == 'WIB') echo 'selected'; ?> value="WIB">WIB</option>
                    <option <?php if($Zona_Waktu == 'WITA') echo 'selected'; ?> value="WITA">WITA</option>
                    <option <?php if($Zona_Waktu == 'WIT') echo 'selected'; ?> value="WIT">WIT</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="">Jam Masuk</label>
                <input type="text" class="form-control" name="Jam_Masuk" value="<?= $Jam_Masuk ?>">
            </div>

            <div class="mb-3">
                <label for="">Jam Pulang</label>
                <input type="text" class="form-control" name="Jam_Pulang" value="<?= $Jam_Pulang ?>">
            </div>

            <input type="hidden" value="<?= $id ?>" name="id">
            <button type="submit" name="Update" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include('../layout/footer.php'); ?>
