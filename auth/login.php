<?php
session_start();

require_once('../config.php');
?>


<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>FHCI-Presensi</title>
  <!-- CSS files -->
  <link href="<?= base_url('assets/css/tabler.min.css?1692870487') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/tabler-vendors.min.css?1692870487') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/demo.min.css?1692870487') ?>" rel="stylesheet" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
    .h3 {
      font-weight: 600;
      font-size: 18px;
      background: -webkit-linear-gradient(#E51B70, #F04E35);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .btn-login {
      width: 390px;
      height: 48px;
      background-image: linear-gradient(to top, rgba(229, 27, 112, 1), rgba(240, 78, 53, 1));
      border-radius: 8px;
      font-weight: 600;
      font-size: 16px;
      gap: 8px;
    }
  </style>
</head>

<body>
  <header class="navbar navbar-expand-md d-print-none px-2 py-2">
    <div class=" container-xl d-flex " style="justify-content:center;">
      <img src="<?= base_url('assets/img/logoo.png') ?>" width="88px" height="50px" alt="FHCI">
    </div>
  </header>
  <script src="<?= base_url('assets/js/demo-theme.min.js?1692870487') ?>"></script>
  <div class="page page-center">
    <div class="container container-tight py-4">

      <?php

      if (isset($_GET['pesan'])) {
        if ($_GET['pesan'] == "belum_login") {
          echo "<div class='alert alert-danger'>Anda belum login</div>";
        } else if ($_GET['pesan'] == "tolak_akses") {
          echo "<div class='alert alert-danger'>Akses ke halaman ini ditolak</div>";
        }
      }

      ?>
      <div class="card card-md rounded-4 shadow">
        <div class="card-body">

          <h5 class="card-title mx-auto text-center">
            <img src="<?= base_url('assets/img/logoo.png') ?>" width="159px" height="99px" alt="FHCI">
          </h5>


          <h3 class="h3 text-center mb-4 text-danger">Selangkah Lebih Dekat Dengan Suksesmu</h3>
          <?php

          if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];

            $result = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email'");

            if (mysqli_num_rows($result) === 1) {
              $row = mysqli_fetch_assoc($result);

              if (password_verify($password, $row["password"])) {
                
                $_SESSION["login"] = true;
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama'];

                if ($row['role'] === 'admin') {
                  header("Location: ../admin/home/home.php");
                  exit();
                } else {
                  header("Location: ../pegawai/home/home.php");
                  exit();
                }
                
              } else {
                echo "<div class='alert alert-danger'>Password salah, silahkan coba lagi!</div>";
              }
            } else {
              echo "<div class='alert alert-danger'>Email salah, silahkan coba lagi!</div>";
            }
          }

          ?>
          <form action="login.php" method="POST" autocomplete="off" novalidate>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" autofocus name="email" placeholder="Masukkan Email" autocomplete="off" required>
            </div>
            <div class="mb-2">
              <label class="form-label">
                Password
              </label>
              <div class="input-group input-group-flat">
                <input type="password" class="form-control" name="password" placeholder="Masukkan Password" autocomplete="off" required>
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                    </svg>
                  </a>
                </span>
              </div>
            </div>

            <div class="form-footer">
              <button type="submit" name="login" class="btn-login btn btn-danger w-100 rounded-3">Masuk</button>
            </div>
          </form>
        </div>
        <div class="text-center text-secondary mb-5">
          Belum ada akun? <a href="./signup.php" tabindex="-1" class="text-danger">Daftar disini</a>
        </div>

      </div>

    </div>
  </div>
  <!-- Libs JS -->
  <!-- Tabler Core -->
  <script src=" <?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487') ?>" defer></script>
  <script src=" <?= base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487') ?>" defer></script>
  <script src=" <?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487') ?>" defer></script>
  <script src=" <?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487') ?>" defer></script>
  <!-- Tabler Core -->
  <script src=" <?= base_url('assets/js/tabler.min.js?1692870487') ?>" defer></script>
  <script src=" <?= base_url('assets/js/demo.min.js?1692870487') ?>" defer></script>

  <!-- Sweet Alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>