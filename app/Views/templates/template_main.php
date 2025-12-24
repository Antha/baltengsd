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
    <link rel="stylesheet" href="<?php echo base_url('/css/datepicker.css') ?>">
</head>
<body class="fade-in">
    <?= $this->include('/includes/loading_spinner'); ?>

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
