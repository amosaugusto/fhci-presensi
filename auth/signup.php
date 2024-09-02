<?php 

session_start();

require_once('../config.php'); ?>

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
    .btn-daftar {
      width: 392px;
      height: 48px;
      background-image: linear-gradient(to top, rgba(229, 27, 112, 1), rgba(240, 78, 53, 1));
      border-radius: 8px;
      font-weight: 600;
      font-size: 16px;
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

      <div class="card card-md rounded-4 shadow">
        <div class="card-body">

          <h5 class="card-title mx-auto text-center">
            <img src="<?= base_url('assets/img/logoo.png') ?>" width="159px" height="99px" alt="FHCI">
          </h5>


          <h3 class="h3 text-center mb-4 text-danger">Selangkah Lebih Dekat Dengan Suksesmu</h3>
          <?php
          if (isset($_POST["submit"])) {
            $nama = $_POST["nama"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $cpassword = $_POST["cpassword"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($nama) or empty($email) or empty($password) or empty($cpassword)) {
              array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
              array_push($errors, "Password must be at least 8 charactes long");
            }
            if ($password !== $cpassword) {
              array_push($errors, "Password does not match");
            }
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($connection, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
              array_push($errors, "Email already exists!");
            }
            if (count($errors) > 0) {
              foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
              }
            } else {

              $sql = "INSERT INTO users (nama, email, password) VALUES ( ?, ?, ? )";
              $stmt = mysqli_stmt_init($connection);
              $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
              if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
              } else {
                die("Something went wrong");
              }
            }
          }
          ?>
          <form action="signup.php" method="POST" autocomplete="off" novalidate>
            <div class="mb-3">
              <label class="form-label ">Nama</label>
              <input type="text" class="form-control" autofocus name="nama" placeholder="Masukkan Nama" autocomplete="off">
            </div>
            <div class="mb-3">
              <label class="form-label font-weight-bold">Email</label>
              <input type="email" class="form-control" name="email" placeholder="Masukkan Email" autocomplete="off">
            </div>
            <div class="mb-3">
              <label class="form-label">
                Password
              </label>
              <div class="input-group input-group-flat">
                <input type="password" class="form-control" name="password" placeholder="Masukkan Password" autocomplete="off">
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
            <div class="mb-3">
              <label class="form-label">
                Konfirmasi Password
              </label>
              <div class="input-group input-group-flat">
                <input type="password" class="form-control" name="cpassword" placeholder="Masukkan Konfirmasi Password" autocomplete="off">
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
              <button type="submit" name="submit" class="btn-daftar btn btn-danger w-100 rounded-3">Daftar</button>
            </div>
          </form>
        </div>
        <div class="text-center text-secondary mb-5">
          Sudah ada akun? <a href="../index.php" tabindex="-1" class="text-danger">Masuk</a>
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
</body>

</html>