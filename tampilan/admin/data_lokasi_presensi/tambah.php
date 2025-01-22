<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");
}else if($_SESSION["role"] != 'admin'){
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Tambah Lokasi Absensi";
include('../layout/header.php');
require_once('../../config.php');

if(isset($_POST['submit'])) {
    $Nama_Lokasi = htmlspecialchars($_POST['Nama_Lokasi']);
    $Alamat_Lokasi = htmlspecialchars($_POST['Alamat_Lokasi']);
    $Tipe_Lokasi = isset($_POST['Tipe_Lokasi']) ? htmlspecialchars($_POST['Tipe_Lokasi']) : '';
    $Latitude = htmlspecialchars($_POST['Latitude']);
    $Longitude = htmlspecialchars($_POST['Longitude']);
    $Radius = htmlspecialchars($_POST['Radius']);
    $Zona_Waktu = htmlspecialchars($_POST['Zona_Waktu']);
    $Jam_Masuk = htmlspecialchars($_POST['Jam_Masuk']);
    $Jam_Pulang = htmlspecialchars($_POST['Jam_Pulang']);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(empty($Nama_Lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Nama Lokasi Wajib diisi";
        }
        if(empty($Alamat_Lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Alamat Lokasi Wajib diisi";
        }
        if(empty($Tipe_Lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Tipe Lokasi Wajib diisi";
        }
        if(empty($Latitude)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Latitude Wajib diisi";
        }
        if(empty($Longitude)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Longitude Wajib diisi";
        }
        if(empty($Radius)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Radius Wajib diisi";
        }
        if(empty($Zona_Waktu)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Zona Waktu Wajib diisi";
        }
        if(empty($Jam_Masuk)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Jam Masuk Wajib diisi";
        }
        if(empty($Jam_Pulang)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Jam Pulang Wajib diisi";
        }

        if(!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else{
            $result = mysqli_query($connection, "INSERT INTO lokasi_Presensi (Nama_Lokasi, Alamat_Lokasi, Tipe_Lokasi, Latitude, Longitude, Radius, Zona_Waktu, Jam_Masuk, Jam_Pulang) VALUES ('$Nama_Lokasi', '$Alamat_Lokasi', '$Tipe_Lokasi', '$Latitude', '$Longitude', '$Radius', '$Zona_Waktu', '$Jam_Masuk', '$Jam_Pulang')");
            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header("Location: lokasi_presensi.php");
            exit;
        }
    }
}

?>

<div class="page-body">
    <div class="container-xl">

    <div class="card col-md-6">
        <div class="card-body">
            <form action="<?= base_url('admin/data_lokasi_presensi/tambah.php') ?>" method="POST">
                <div class="mb-3">
                    <label for="">Nama Lokasi</label>
                    <input type="text" class="form-control" name="Nama_Lokasi" value="<?php if(isset($_POST['Nama_Lokasi'])) echo $_POST['Nama_Lokasi'] ?>">
                </div>

                <div class="mb-3">
                    <label for="">Alamat Lokasi</label>
                    <input type="text" class="form-control" name="Alamat_Lokasi"value="<?php if(isset($_POST['Alamat_Lokasi'])) echo $_POST['Alamat_Lokasi'] ?>">
                </div>

                <div class="mb-3">
                    <label for="">Tipe Lokasi</label>
                    <select name="Tipe_Lokasi" class="form-control">
                        <option value="">--Pilih Tipe Lokasi</option>
                        <option <?php if(isset($_POST['Tipe_Lokasi']) && $_POST['Tipe_Lokasi']== 'Pusat') {
                            echo 'selected';
                        } ?> value="Pusat">Pusat</option>

                         <option <?php if(isset($_POST['Tipe_Lokasi']) && $_POST['Tipe_Lokasi']== 'Cabang') {
                            echo 'selected';
                        } ?> value="Cabang">Cabang</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="">Latitude</label>
                    <input type="text" class="form-control" name="Latitude"value="<?php if(isset($_POST['Latitude'])) echo $_POST['Latitude'] ?>">
                </div>

                <div class="mb-3">
                    <label for="">Longitude</label>
                    <input type="text" class="form-control" name="Longitude"value="<?php if(isset($_POST['Longitude'])) echo $_POST['Longitude'] ?>">
                </div>

                <div class="mb-3">
                    <label for="">Radius</label>
                    <input type="number" class="form-control" name="Radius"value="<?php if(isset($_POST['Radius'])) echo $_POST['Radius'] ?>">
                </div>

                <div class="mb-3">
                    <label for="">Zona Waktu</label>
                    <select name="Zona_Waktu" class="form-control">
                        <option value="">--Pilih Zona Waktu</option>
                        <option <?php if(isset($_POST['Zona_Waktu']) && $_POST['Zona_Waktu']== 'WIB') {
                            echo 'selected';
                        } ?> value="WIB">WIB</option>

                         <option <?php if(isset($_POST['Zona_Waktu']) && $_POST['Zona_Waktu']== 'WITA') {
                            echo 'selected';
                        } ?> value="WITA">WITA</option>

                        <option <?php if(isset($_POST['Zona_Waktu']) && $_POST['Zona_Waktu']== 'WIT') {
                            echo 'selected';
                        } ?> value="WIT">WIT</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="">Jam Masuk</label>
                    <input type="time" class="form-control" name="Jam_Masuk"value="<?php if(isset($_POST['Jam_Masuk'])) echo $_POST['Jam_Masuk'] ?>">
                </div>

                <div class="mb-3">
                    <label for="">Jam Pulang</label>
                    <input type="time" class="form-control" name="Jam_Pulang"value="<?php if(isset($_POST['Jam_Pulang'])) echo $_POST['Jam_Pulang'] ?>">
                </div>

                <button type="submit" class="btn btn-primary" name="submit">Simpan</button>

            </form>
        </div>
    </div>

    </div>
</div>

<?php include('../layout/footer.php');
?>