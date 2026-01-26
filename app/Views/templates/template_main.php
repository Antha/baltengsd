<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTS</title>
    <meta name="description" content="BALTENG SATU DATA">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <!-- SCRIPT -->
    <script type="text/javascript" src="<?php echo base_url('/script/jquery-3.7.1.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('/script/bootstrap-datepicker.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="<?php echo base_url('/script/html2canvas.js') ?>"></script>
    
    <!--Font Awesome-->
    <link href="<?php echo base_url('/fontawesome/css/fontawesome.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/fontawesome/css/brands.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('/fontawesome/css/solid.css') ?>" rel="stylesheet" />

    <!-- STYLES -->
    <link rel="stylesheet" href="<?php echo base_url('/bootstrap/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('/css/custom.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('/css/sticky.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('/css/datepicker.css') ?>">
</head>
<body class="fade-in">
    <?= $this->include('/includes/loading_spinner'); ?>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead th {
            position: sticky;
            background: white;   /* supaya tidak transparan */
            z-index: 2;          /* pastikan di atas isi tabel */
        }

        /* baris pertama header */
        thead tr:nth-child(1) th {
            top: 0;
        }

        /* baris kedua header */
        thead tr:nth-child(2) th {
            top: 27px;   /* sesuaikan dengan tinggi baris pertama */
        }

        /* baris ketiga header */
        thead tr:nth-child(3) th {
            top: 55px;   /* sesuaikan dengan tinggi baris pertama+kedua */
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
    <?= $this->renderSection('content'); ?>

    <script>
        window.addEventListener('load', function () {
            // Fade out loading spinner
            const spinner = document.getElementById('loading-spinner');
            spinner.classList.add('fade-out');

            // Fade in body
            document.body.classList.add('show');
        });
    </script>
</body>

</html>
