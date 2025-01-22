<?php 

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "absensi";

$connection = mysqli_connect("bnocn1lwwt8py8mfppa5-mysql.services.clever-cloud.com","ub06khnwdvfq3epj","THxMIhqsFI058glOd2Uf","bnocn1lwwt8py8mfppa5");

if(!$connection) {
    echo "Koneksi ke database gagal" . mysqli_connect_error();
}


function base_url($url = null)
{
    $base_url = 'http://localhost:80/kawan-pani';
    if($url != null){
        return $base_url . '/' .$url;
    } else {
        return $base_url;
    }
}
?>