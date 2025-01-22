<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])) {
  header("Location: ../../../auth/login.php?pesan=belum_login");
} else if($_SESSION["role"] != 'admin'){
  header("Location: ../../../auth/login.php?pesan=tolak_akses");
}

$judul = "Edit Pegawai";
include('../layout/header.php');
require_once('../../../config.php');

$id_pegawai = $_GET['id']; // Mengambil ID Pegawai dari parameter URL

// Query untuk mengambil data pegawai
$pegawai_query = mysqli_query($connection, "SELECT * FROM pegawai WHERE id = '$id_pegawai'");
$pegawai = mysqli_fetch_assoc($pegawai_query);

// Jika data pegawai tidak ditemukan
if (!$pegawai) {
    $_SESSION['error'] = "Data pegawai tidak ditemukan.";
    header("Location: pegawai.php");
    exit;
}

// Query untuk mengambil data username yang berelasi dengan pegawai
$user_query = mysqli_query($connection, "SELECT * FROM users WHERE id_pegawai = '$id_pegawai'");
$user = mysqli_fetch_assoc($user_query);

if(isset($_POST['submit'])) {

    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $lokasi_presensi = htmlspecialchars($_POST['lokasi_presensi']);
    
    // Proses upload foto (jika ada)
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file = $_FILES['foto'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../../assets/img/foto_pegawai/" . $nama_file;

        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $ekstensi_diizinkan = ['jpg', "png", "jpeg"];
        $max_ukuran_file = 10 * 1024 * 1024; // Maksimal 10MB

        if (in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan) && $ukuran_file <= $max_ukuran_file) {
            // Hapus foto lama sebelum mengupload yang baru
            $old_foto = "../../../assets/img/foto_pegawai/" . $pegawai['foto'];
            if (file_exists($old_foto)) {
                unlink($old_foto);
            }

            move_uploaded_file($file_tmp, $file_direktori);
        } else {
            $_SESSION['error'] = 'Ekstensi file tidak diizinkan atau ukuran file terlalu besar!';
        }
    } else {
        // Jika foto tidak diubah, gunakan foto lama
        $nama_file = $pegawai['foto'];
    }

    // Update data pegawai
    $query = "UPDATE pegawai SET 
              nama = '$nama', 
              jenis_kelamin = '$jenis_kelamin', 
              alamat = '$alamat', 
              no_handphone = '$no_handphone', 
              jabatan = '$jabatan', 
              lokasi_presensi = '$lokasi_presensi', 
              foto = '$nama_file' 
              WHERE id = '$id_pegawai'";

    $update_pegawai = mysqli_query($connection, $query);

    if (!$update_pegawai) {
        die("Error pada query UPDATE pegawai: " . mysqli_error($connection));
    }

    // Update data user
    $update_user = mysqli_query($connection, "UPDATE users SET 
                                             username = '$username', 
                                             status = '$status', 
                                             role = '$role' 
                                             WHERE id_pegawai = '$id_pegawai'");

    if (!$update_user) {
        die("Error pada query UPDATE user: " . mysqli_error($connection));
    }

    $_SESSION['berhasil'] = 'Data berhasil diperbarui';
    header("Location: pegawai.php");
    exit;
}

?>

<div class="page-body">
    <div class="container-xl">
        <form action="edit.php?id=<?= $id_pegawai ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="card col-md-6">
                    <div class="card-body">

                        <!-- Nama -->
                        <div class="mb-3">
                            <label for="">Nama</label>
                            <input type="text" class="form-control" name="nama" value="<?= $pegawai['nama'] ?>" required>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mb-3">
                            <label for="">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control">
                                <option value="Laki-laki" <?= $pegawai['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= $pegawai['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="">Alamat</label>
                            <input type="text" class="form-control" name="alamat" value="<?= $pegawai['alamat'] ?>" required>
                        </div>

                        <!-- No Handphone -->
                        <div class="mb-3">
                            <label for="">No Handphone</label>
                            <input type="text" class="form-control" name="no_handphone" value="<?= $pegawai['no_handphone'] ?>" required>
                        </div>

                        <!-- Jabatan -->
                        <div class="mb-3">
                            <label for="">Jabatan</label>
                            <select name="jabatan" class="form-control" required>
                                <?php
                                $ambil_jabatan = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY jabatan ASC");
                                while ($jabatan = mysqli_fetch_assoc($ambil_jabatan)){
                                    $selected = ($pegawai['jabatan'] == $jabatan['jabatan']) ? 'selected' : '';
                                    echo '<option value="' . $jabatan['jabatan'] . '" ' . $selected . '>' . $jabatan['jabatan'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Foto -->
                        <div class="mb-3">
                            <label for="">Foto</label>
                            <input type="file" class="form-control" name="foto">
                            <br>
                            <img src="../../../assets/img/foto_pegawai/<?= $pegawai['foto'] ?>" alt="Foto Pegawai" width="150">
                        </div>
                    </div>
                </div>

                <div class="card col-md-6">
                    <div class="card-body">
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" required>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="pegawai" <?= $user['role'] == 'pegawai' ? 'selected' : '' ?>>Pegawai</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="aktif" <?= $user['status'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="non-aktif" <?= $user['status'] == 'non-aktif' ? 'selected' : '' ?>>Non-Aktif</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="">Ulangi Password</label>
                            <input type="password" class="form-control" name="ulangi_password">
                        </div>

                        <!-- Lokasi Presensi -->
                        <div class="mb-3">
                            <label for="">Lokasi Presensi</label>
                            <input type="text" class="form-control" name="lokasi_presensi" value="<?= $pegawai['lokasi_presensi'] ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary" name="submit">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include('../layout/footer.php'); ?>
