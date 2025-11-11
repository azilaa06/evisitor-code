<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Override CSS -->
    <link href="<?= base_url('assets/'); ?>css/custom.css" rel="stylesheet">
    <style>
        body {
            background: url('<?= base_url("assets/img/bg.jpg"); ?>') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
        }

        .card-login {
            border-radius: 15px;
            overflow: hidden;
        }

        /*Password */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .form-control {
            padding-right: 40px;
        }

        /* Error Messages */
        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #28a745 !important;
        }
    </style>


</head>

<body class="bg-white-primary"> <!-- warna latar -->

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-lg-4"> <!-- ukuran latar putih -->

                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg"> <!-- colom di dalem putih penuh -->
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-white-900 mb-4 judul-evisitor">E-visitor</h1>
                                        <img src="<?= base_url('assets/img/footer-logo.png'); ?>" alt="Foto User" class="foto-kiri">
                                    </div>

                                    <!-- Pesan error login -->
                                    <?php if ($this->session->flashdata('error')): ?>
                                        <div class="alert alert-danger text-center mt-2">
                                            <?= $this->session->flashdata('error'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="<?= base_url('index.php/auth/login_proses'); ?>" method="post" id="loginForm">
                                        <div class="form-group">
                                            <p class="label">Username</p>
                                            <input type="text" class="form-control form-control-user"
                                                id="username" name="username"
                                                placeholder="Enter username Address...">
                                            <div class="error-message" id="usernameError"></div>
                                        </div>
                                        <!-- Password -->
                                        <div class="form-group">
                                            <p class="label">Password</p>
                                            <div class="password-wrapper">
                                                <input type="password" id="password" class="form-control" placeholder="Password">
                                                <span class="password-toggle" id="passwordToggle">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                            <div class="error-message" id="passwordError"></div>
                                        </div>

                                        <script>
                                            document.getElementById('passwordToggle').addEventListener('click', function() {
                                                const passwordInput = document.getElementById('password');
                                                const icon = this.querySelector('i');

                                                if (passwordInput.type === 'password') {
                                                    passwordInput.type = 'text';
                                                    icon.classList.remove('fa-eye');
                                                    icon.classList.add('fa-eye-slash');
                                                } else {
                                                    passwordInput.type = 'password';
                                                    icon.classList.remove('fa-eye-slash');
                                                    icon.classList.add('fa-eye');
                                                }
                                            });
                                        </script>

                                        <div class="form-group">
                                            <p class="label">Nama Lengkap</p>
                                            <input type="text" class="form-control form-control-user"
                                                id="nama_lengkap" name="nama_lengkap" placeholder="example">
                                            <div class="error-message" id="namaLengkapError"></div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <div class="text-center role-teks">
                                        <a href="<?= base_url('index.php/auth/user'); ?>" class="text-white-5">user</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
        <script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

        <script>
                // Toggle show/hide password
                document.getElementById('passwordToggle').addEventListener('click', function() {
                    const passwordInput = document.getElementById('password');
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });

            // Form Validation
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                let isValid = true;

                // Get form elements
                const username = document.getElementById('username');
                const password = document.getElementById('password');
                const namaLengkap = document.getElementById('nama_lengkap');

                // Get error elements
                const usernameError = document.getElementById('usernameError');
                const passwordError = document.getElementById('passwordError');
                const namaLengkapError = document.getElementById('namaLengkapError');

                // Reset previous errors
                username.classList.remove('is-invalid');
                password.classList.remove('is-invalid');
                namaLengkap.classList.remove('is-invalid');
                usernameError.classList.remove('show');
                passwordError.classList.remove('show');
                namaLengkapError.classList.remove('show');

                // Validasi Username
                if (!username.value.trim()) {
                    usernameError.textContent = 'Username harus diisi!';
                    usernameError.classList.add('show');
                    username.classList.add('is-invalid');
                    isValid = false;
                }

                // Validasi Password
                if (!password.value.trim()) {
                    passwordError.textContent = 'Password harus diisi!';
                    passwordError.classList.add('show');
                    password.classList.add('is-invalid');
                    isValid = false;
                }

                // Validasi Nama Lengkap
                if (!namaLengkap.value.trim()) {
                    namaLengkapError.textContent = 'Nama lengkap harus diisi!';
                    namaLengkapError.classList.add('show');
                    namaLengkap.classList.add('is-invalid');
                    isValid = false;
                }

                // Jika validasi gagal, cegah form submit
                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Optional: Real-time validation saat user mengetik
            document.getElementById('username').addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    document.getElementById('usernameError').classList.remove('show');
                }
            });

            document.getElementById('password').addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    document.getElementById('passwordError').classList.remove('show');
                }
            });

            document.getElementById('nama_lengkap').addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    document.getElementById('namaLengkapError').classList.remove('show');
                }
            });
        </script>

</body>

</html>