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

$judul = "Edit Data Jabatan";
include('../layout/header.php');
require_once('../../config.php');

// Initialize $id
$id = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
}

if (isset($_POST['Update'])) {
    $jabatan = htmlspecialchars($_POST['jabatan']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($jabatan)) {
            $pesan_kesalahan = "Nama jabatan wajib diisi";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = $pesan_kesalahan;
        } else {
            $result = mysqli_query($connection, "UPDATE jabatan SET jabatan= '$jabatan' WHERE id=$id");
            $_SESSION['berhasil'] = "Data Berhasil diupdate";
            header("Location: jabatan.php");
            exit;
        }
    }
}

// Make sure $id is set before querying the database
if ($id !== null) {
    $result = mysqli_query($connection, "SELECT * FROM jabatan WHERE id=$id");

    if ($result) {
        $jabatan = mysqli_fetch_array($result);
        if ($jabatan) {
            $nama_jabatan = $jabatan['jabatan'];
        } else {
            // Handle case where the jabatan doesn't exist
            $_SESSION['error'] = "Data jabatan tidak ditemukan.";
            header("Location: jabatan.php");
            exit;
        }
    } else {
        // Handle SQL error
        $_SESSION['error'] = "Terjadi kesalahan saat mengambil data.";
        header("Location: jabatan.php");
        exit;
    }
} else {
    $_SESSION['error'] = "ID jabatan tidak ditemukan.";
    header("Location: jabatan.php");
    exit;
}

?>

<!-- Page body -->
<div class="page-body">
  <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_jabatan/edit.php')?>" method="POST">
                    <div class="mb-3">
                        <label for="">Nama Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="<?= $nama_jabatan ?>">
                    </div>
                    <input type="hidden" value="<?= $id ?>" name="id">
                    <button type="submit" name="Update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
  </div>
</div>
<?php include('../layout/footer.php'); ?>
