<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

include_once("../../config.php");

if(isset($_POST["tombol_masuk"])) {
    $user_id = $_POST['id'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jam_masuk = $_POST['jam_masuk'];

    $result = mysqli_query($connection, "INSERT INTO presensi(user_id, tanggal_masuk, jam_masuk) VALUES ('$user_id', '$tanggal_masuk', '$jam_masuk')");

    if($result) {
        header("Location: ../home/home.php");
        $_SESSION['berhasil'] = "Check-In berhasil";
    } else {
        header("Location: ../home/home.php");
        echo "<div class='alert alert-danger'>Check-In gagal</div>";
    }
}

?>
