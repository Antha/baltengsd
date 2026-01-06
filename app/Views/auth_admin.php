<?php $this->extend('/templates/template_main') ?>

<?php $this->section('content') ?>

    <div id="main-wrapper" class="login-form min-vh-100 d-flex flex-column bg-login">
        <div class="container my-auto">
            <div class="row g-0 justify-content-center">
                <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-8 py-5">
                    <div class="row">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col-11 col-sm-9 col-md-7 col-lg-5 col-xl-7 m-auto px-3 py-4 rounded" style="background-color: #fff;">
                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col-8">
                                                <div class="logo text-center mb-3"> 
                                                    <img src="<?= esc(base_url('/assets/images/logo.png')); ?>" alt="logo" class="img-fluid">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <form id="form-verifikasi">
                                                    <?= csrf_field() ?>
                                                    <div class="vertical-input-group">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control top-input" id="username" required placeholder="Username">
                                                        </div>
                                                        <div class="input-group position-relative">
                                                            <input type="password" class="form-control bot-input" id="password" required placeholder="Password">
                                                        </div>
                                                    </div>
                                                    <div class="d-grid my-4">
                                                        <button class="btn btn-primary shadow-none" type="submit">Login</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-2 footer-wrapper">
            <p class="text-center text-2 text-muted mb-0 copyright" style="color: #fff !important;">Copyright © 2025 BTS. All Rights Reserved.</p>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#form-verifikasi').submit(function(e) {
                e.preventDefault();

                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true); // disable tombol

                let username = $('#username').val().trim();
                let password = $('#password').val().trim();
                let csrfTokenName = '<?= csrf_token() ?>';
                let csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

                // Validasi username: hanya huruf dan angka
                const usernameValid = /^[a-zA-Z0-9]+$/.test(username);

                // Password boleh karakter apapun, tapi tetap required dan minimal 6 karakter (misalnya)
                const passwordValid = password.length >= 6;

                if (!username || !usernameValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Input tidak valid',
                        text: 'Username hanya boleh huruf dan angka, dan tidak boleh kosong.'
                    });
                    btn.prop('disabled', false);
                    return;
                }

                if (!password || !passwordValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Input tidak valid',
                        text: 'Password minimal 6 karakter.'
                    });
                    btn.prop('disabled', false);
                    return;
                }

                Swal.fire({
                    title: 'Memverifikasi...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "<?= base_url('/auth/cek_ajax') ?>",
                    type: "POST",
                    data: {
                        username: username,
                        password: password,
                        [csrfTokenName]: csrfHash
                    },
                    dataType: "json",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // <<< WAJIB untuk lolos isAJAX()
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Login berhasil. Mengalihkan ke dashboard...',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                            btn.prop('disabled', false);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.close();
                        console.error("AJAX Error:");
                        console.error("Status: ", textStatus);
                        console.error("Error Thrown: ", errorThrown);
                        console.error("Response Text: ", jqXHR.responseText);

                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan Server',
                            text: 'Terjadi kesalahan saat memproses.'
                        });
                        btn.prop('disabled', false);
                    }
                });
            });

        });

        function togglePassword() {
            const passwordField = document.getElementById("password");
            console.log(passwordField);
            const toggleIcon = document.getElementById("toggleIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>

<?php $this->endSection() ?>
