<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$email = $_SESSION["email"];

include('../layout/header.php');
include_once("../../config.php");

date_default_timezone_set('Asia/Jakarta');

?>

<style>
    .parent_date {
        display: grid;
        grid-template-columns: auto auto auto auto auto auto auto;
        font-size: 16px;
        text-align: center;
        justify-content: center;
        font-weight: 500;
    }

    .parent_clock {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 24px;
        font-weight: 600;
        text-align: center;
        font-weight: bold;
        justify-content: center;
        background: -webkit-linear-gradient(#E51B70, #F04E35);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-top: 15px;
    }

    .presensi {
        justify-content: center;

    }

    .absen {
        text-align: center;
    }

    .button-checkin {
        width: 223px;
        height: 66px;
        background-image: linear-gradient(to top, rgba(229, 27, 112, 1), rgba(240, 78, 53, 1));
        border-radius: 15px;
    }

    .button-checkout {
        width: 223px;
        height: 66px;
        background-image: linear-gradient(to top, rgba(229, 27, 112, 1), rgba(240, 78, 53, 1));
        border-radius: 15px;
    }

    .btn-checkIn-off {
        width: 223px;
        height: 66px;
        background-image: rgba(224, 224, 224, 1);
        border-radius: 15px;
        color: rgba(71, 71, 71, 1);
        font-size: 16;
        font-weight: 500;
    }

    .btn-checkOut-off {
        width: 223px;
        height: 66px;
        background-image: rgba(224, 224, 224, 1);
        border-radius: 15px;
        color: rgba(71, 71, 71, 1);
        font-size: 16;
        font-weight: 500;
    }

    .modal-body {
        display: grid;
        grid-template-rows: auto auto auto auto;
    }

    .tombol-izin {
        width: 165px;
        height: 52px;
        background-image: linear-gradient(to top, rgba(229, 27, 112, 1), rgba(240, 78, 53, 1));
        border-radius: 8px;
    }

    .tombol-batal-izin {
        width: 165px;
        height: 52px;
        background-image: linear-gradient(to top, rgba(255, 203, 225, 1), rgba(255, 216, 210, 1));
        border-radius: 8px;
        color: rgba(229, 27, 112, 1);
    }

    .nama-user {
        font-weight: 600;
        font-size: 20;
    }

    .thead {
        background-color: #DDDDDD;
        border: 1px black;
    }
    .profile-card {
        width: 32rem;
    }
    .presensi-card {
        width: 32rem;
    }

    @media (max-width: 650px) {
        .card-table {
            margin-top: 20px
            
        }
        th {
            display: none;
        }
        td {
            display: grid;
            gap: 0.5rem;
            grid-template-columns: 15ch auto;
            padding: 0.75rem 1rem;
        }
        td:first-child {
            padding-top: 2rem;
        }
        td:last-child {
            padding-bottom: 2rem;
        }
        td::before {
            content: attr(data-cell) ": ";
            font-weight: 700;
            text-transform: capitalize;
        }
        .profile-card {
            width: 25rem;
        }
        .presensi-card {
            width: 25rem;
        }
        .card-table {
            width: 25rem;
        }
        .modal-izin {
            width: 26rem;
        }
        .tombol-izin {
            width: 120px;
            height: 52px;
        }

        .tombol-batal-izin {
            width: 120px;
            height: 52px;
        }
    }
</style>

<div class="page-wrapper mt-5">
    <div class="container-xl">
        <div class="row">
            <div class="col-xl-5">
                <div class="row">
                    <?php

                    $getUser = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email'");
                    if (mysqli_num_rows($getUser) === 1) {
                        $result = mysqli_fetch_assoc($getUser);
                    }

                    ?>
                    <div class="col-md-4">
                        <div class="card profile-card rounded-3" >
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <img src="<?= base_url('assets/img/avatar.png') ?>" alt="">
                                    </div>
                                    <div class="col-10">
                                        <div class="nama-user row">
                                            <h5 class="card-title"><?php echo $result['nama'] ?></h5>
                                        </div>
                                        <div class="row">
                                            <p class="card-text"><?php echo $result['email'] ?></p>
                                        </div>

                                    </div>

                                </div>
                                <div class="row border rounded-3 mt-3 font">
                                    <a href="<?= base_url('auth/logout.php') ?>" class="text-center p-2">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card presensi-card rounded-3">
                            <div class="card-header">
                                <div class="d-flex flex-column">
                                    <h1 class="text-presensi ">Presensi</h1>
                                    <p class="text-justify">Lakukan Check in dan Check out untuk melengkapi daftar hadir harian anda</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                $user_id = $_SESSION['id'];
                                $tanggal_hari_ini = date('Y-m-d');

                                $cek_presensi_masuk = mysqli_query($connection, "SELECT * FROM presensi WHERE user_id = '$user_id' AND tanggal_masuk = '$tanggal_hari_ini'");
                                ?>
                                <div class="parent_date">
                                    <div id="hari"></div>
                                    <div class="ms-1"></div>
                                    <div id="tanggal"></div>
                                    <div class="ms-1"></div>
                                    <div id="bulan"> </div>
                                    <div class="ms-1"></div>
                                    <div id="tahun"></div>
                                </div>

                                <div class="parent_clock">
                                    <div id="jam"></div>
                                    <div>:</div>
                                    <div id="menit"></div>
                                    <div>:</div>
                                    <div id="detik"></div>
                                </div>
                                <div class="presensi d-flex flex-row">
                                    <?php
                                    if (mysqli_num_rows($cek_presensi_masuk) === 0) {

                                    ?>
                                        <div>
                                            <form method="POST" action="<?= base_url('pegawai/presensi/presensi_masuk.php') ?>">

                                                <input type="hidden" value="<?= $_SESSION['id'] ?>" name="id">
                                                <input type="hidden" value="<?= date('Y-m-d') ?>" name="tanggal_masuk">
                                                <input type="hidden" value="<?= date('H:i:s') ?>" name="jam_masuk">
                                                <button type="submit" name="tombol_masuk" class="button-checkin btn btn-danger mt-3  mr-2">
                                                    <div class="row mt-3">
                                                        <div class="col">
                                                            <h3 class="">Check-In</h3>
                                                        </div>
                                                    
                                                    </div>
                                                   
                                                </button>

                                            </form>
                                        </div>

                                    <?php } else { ?>
                                        <button type="submit" name="tombol_masuk" class="btn-checkIn-off btn btn-secondary mt-3 mr-2" disabled>
                                            <div class="container">
                                                <div class="row mt-5">
                                                    <div class="col">
                                                        <h3 class="">Check-In</h3>
                                                    </div>

                                                </div>
                                                <div class="row mb-3">
                                                    <?php $ambil_jam_masuk = mysqli_fetch_array($cek_presensi_masuk);

                                                    ?>
                                                    <p><?= $ambil_jam_masuk['jam_masuk'] ?></p>

                                                </div>
                                            </div>
                                        </button>
                                    <?php } ?>
                                    <?php
                                    $ambil_data_presensi = mysqli_query($connection, "SELECT * FROM presensi WHERE user_id = '$user_id' AND tanggal_masuk = '$tanggal_hari_ini'");
                                    if (mysqli_num_rows($ambil_data_presensi) === 0) { ?>
                                        <button type="submit" name="tombol_keluar" class="btn-checkOut-off btn btn-secondary mt-3 mr-2" disabled>
                                            <div class="container">
                                                <div class="row mt-5">
                                                    <div class="col">
                                                        <h3 class="">Check-Out</h3>
                                                    </div>

                                                </div>
                                                <div class="row mb-3">
                                                    

                                                </div>
                                            </div>
                                        </button>
                                    <?php } else { ?>
                                        <?php while ($cek_presensi_keluar = mysqli_fetch_array($ambil_data_presensi)) { ?>

                                            <?php if (($cek_presensi_keluar['tanggal_masuk']) && $cek_presensi_keluar['tanggal_keluar'] == '0000-00-00') { ?>
                                                <div>
                                                    <form method="POST" action="<?= base_url('pegawai/presensi/presensi_keluar.php') ?>">
                                                        <input type="hidden" value="<?= $cek_presensi_keluar['id'] ?>" name="id">
                                                        <input type="hidden" value="<?= $_SESSION['id'] ?>" name="user_id">
                                                        <input type="hidden" value="<?= date('Y-m-d') ?>" name="tanggal_keluar">
                                                        <input type="hidden" value="<?= date('H:i:s') ?>" name="jam_keluar">
                                                        <button type="submit" name="tombol_keluar" class="button-checkout btn btn-secondary mt-3">Check-Out</button>
                                                    </form>
                                                </div>

                                            <?php } else { ?>
                                                <button type="submit" name="tombol_keluar" class="btn-checkOut-off btn btn-secondary mt-3 mr-2" disabled>
                                                    <div class="container">
                                                        <div class="row mt-5">
                                                            <div class="col">
                                                                <h3 class="">Check-Out</h3>
                                                            </div>

                                                        </div>
                                                        <div class="row mb-3">
                                                            <?php
                                                            $ambil_data_keluar = mysqli_query($connection, "SELECT * FROM presensi WHERE user_id = '$user_id' AND tanggal_masuk = '$tanggal_hari_ini'");
                                                            $ambil_jam_keluar = mysqli_fetch_array($ambil_data_keluar);

                                                            ?>
                                                            <p><?= $ambil_jam_keluar['jam_keluar'] ?></p>

                                                        </div>
                                                    </div>
                                                </button>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php

                                ?>
                                <p class="absen mt-3">Tidak Hadir ? <a href="" class="text-danger" data-toggle="modal" data-target="#exampleModalCenter">Klik Disini</a>
                                </p>
                            </div>
                            <!-- Modal -->
                            <div class="modal modal-izin fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <form method="POST" action="<?= base_url('pegawai/presensi/presensi_izin.php') ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="d-flex flex-column mt-3">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Lapor Presensi Forum Human Capital Indonesia</h5>
                                                    <p class="mt-3">Form ini digunakan untuk melaporkan kehadiran tanpa check in</p>
                                                </div>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" value="<?= $_SESSION['id'] ?>" name="user_id">
                                                <label for="keterangan">Keterangan :</label>
                                                <select name="keterangan" id="keterangan">
                                                    <option value="">--Pilih Keterangan--</option>
                                                    <option value="Izin">Izin</option>
                                                    <option value="Sakit">Sakit</option>
                                                    <option value="Cuti">Cuti</option>
                                                </select>
                                                <label class="mt-3" for="catatan">Catatan</label>
                                                <textarea type="text" name="catatan" id="catatan"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="tombol-batal-izin btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" name="tombol_izin" class="tombol-izin btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="card card-table rounded-3">
                    <div class="card-body">
                        <h1 class="">Riwayat Presensi</h1>

                        <?php
                        $user_id = $_SESSION['id'];
                        $ambil_data_presensi = mysqli_query($connection, "SELECT * FROM presensi WHERE user_id = '$user_id'");
                        $ambil_data_ketidakhadiran = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE user_id = '$user_id'");
                        ?>
                        <table class="table table-bordered  h-100 w-100">
                            <tr class="thead">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu Check-In</th>
                                <th>Waktu Check-Out</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>

                            <?php
                            if (mysqli_num_rows($ambil_data_presensi) === 0) {
                            ?>
                                <tr>
                                    <td colspan="6">Data Presensi masih kosong.</td>
                                </tr>
                            <?php } else { ?>

                                <?php $no = 1;
                                while ($rekap = mysqli_fetch_array($ambil_data_presensi)) { ?>
                                    <tr>
                                        <td data-cell="No"><?= $no++ ?></td>
                                        <td data-cell="Tanggal"><?= date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>

                                        <td data-cell="Waktu Check-In"><?= $rekap['jam_masuk'] ?></td>
                                        <td data-cell="Waktu Check-Out"><?= $rekap['jam_keluar'] ?></td>

                                        <td data-cell="Status"><?= $rekap['status'] ?></td>

                                        <td data-cell="Catatan"><?= $rekap['catatan'] ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.setTimeout("waktuMasuk()", 1000);
        namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        namaHari = ["Minggu, ", "Senin, ", "Selasa, ", "Rabu, ", "Kamis, ", "Jumat, ", "Sabtu, "];

        function waktuMasuk() {
            const waktu = new Date();

            setTimeout("waktuMasuk()", 1000);

            document.getElementById("hari").innerHTML = namaHari[waktu.getDay()];
            document.getElementById("tanggal").innerHTML = waktu.getDate();
            document.getElementById("bulan").innerHTML = namaBulan[waktu.getMonth()];
            document.getElementById("tahun").innerHTML = waktu.getFullYear();

            document.getElementById("jam").innerHTML = (waktu.getHours() < 10 ? "0" : "") + waktu.getHours();
            document.getElementById("menit").innerHTML = (waktu.getMinutes() < 10 ? "0" : "") + waktu.getMinutes();
            document.getElementById("detik").innerHTML = (waktu.getSeconds() < 10 ? "0" : "") + waktu.getSeconds();

        }
    </script>

    <?php include('../layout/footer.php'); ?>