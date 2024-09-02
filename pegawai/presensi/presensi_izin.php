<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

include_once("../../config.php");

if(isset($_POST["tombol_izin"])) {
    $user_id = $_POST['user_id'];
    $keterangan = $_POST['keterangan'];
    $catatan = $_POST['catatan'];
    $tanggal_hari_ini = date('Y-m-d');

    $result = mysqli_query($connection, "INSERT INTO ketidakhadiran(user_id, keterangan, catatan, tanggal) VALUES ('$user_id', '$keterangan', '$catatan', '$tanggal_hari_ini')");
    $result_presensi = mysqli_query($connection, "INSERT INTO presensi(user_id, tanggal_masuk, status, tanggal_keluar, catatan) VALUES ('$user_id', '$tanggal_hari_ini', '$keterangan', '$tanggal_hari_ini', '$catatan')");

    if($result) {
        header("Location: ../home/home.php");
        $_SESSION['berhasil'] = "Pengajuan izin berhasil";
    } else {
        header("Location: ../home/home.php");
        echo "<div class='alert alert-danger'>Pengajuan izin gagal</div>";
    }
}

?>
