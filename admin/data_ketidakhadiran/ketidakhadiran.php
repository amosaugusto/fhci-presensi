<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Data ketidakhadiran";
include('../layout/header.php');
require_once("../../config.php");

$result = mysqli_query($connection, "SELECT ketidakhadiran.*, users.nama FROM ketidakhadiran JOIN users ON ketidakhadiran.user_id = users.id ORDER BY id DESC");
?>
<style>
    .thead {
        background-color: #DDDDDD;
        border: 1px black;
    }
    @media (max-width: 650px) {
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
    }
</style>

<div class="page-body">
    <div class="container-xl">
        <table class="table table-bordered mt-2">
            <tr class="text-center thead">
                <th>No.</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Catatan</th>
                <th>Status Pengajuan</th>
            </tr>
            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="5">Data ketidakhadiran masih kosong</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($data = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td data-cell="No"><?= $no++ ?></td>
                        <td data-cell="Nama"><?= $data['nama'] ?></td>
                        <td data-cell="Tanggal"><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                        <td data-cell="Keterangan"><?= $data['keterangan'] ?></td>
                        <td data-cell="Catatan"><?= $data['catatan'] ?></td>
                        <td data-cell="Status Pengajuan" class="text-center">
                            <?php if ($data['status_pengajuan'] == 'PENDING') : ?>
                                <a class="badge badge-pill bg-warning" href="<?= base_url('admin/data_ketidakhadiran/detail.php?id='. $data['id']) ?>">PENDING</a>
                            <?php elseif ($data['status_pengajuan'] == 'REJECTED') : ?>
                                <a class="badge badge-pill bg-danger" href="<?= base_url('admin/data_ketidakhadiran/detail.php?id='. $data['id']) ?>">REJECTED</a>
                            <?php else : ?>
                                <a class="badge badge-pill bg-success" href="<?= base_url('admin/data_ketidakhadiran/detail.php?id='. $data['id']) ?>">APPROVED</a>
                            <?php endif; ?>


                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>

</div>


<?php

include("../layout/footer.php");

?>