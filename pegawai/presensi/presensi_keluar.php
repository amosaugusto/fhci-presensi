<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

include_once("../../config.php");

if(isset($_POST["tombol_keluar"])) {
    $id_presensi = $_POST["id"];
    $user_id = $_POST['user_id'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $jam_keluar = $_POST['jam_keluar'];

    $result = mysqli_query($connection, "UPDATE presensi SET tanggal_keluar='$tanggal_keluar', jam_keluar='$jam_keluar' WHERE id=$id_presensi ");

    if($result) {
        header("Location: ../home/home.php");
        $_SESSION['berhasil'] = "Check-Out berhasil";
    } else {
        header("Location: ../home/home.php");
        echo "<div class='alert alert-danger'>Check-Out gagal</div>";
    }
}

?>
