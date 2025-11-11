<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login/Register</title>

    <!-- Custom fonts -->
    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900"
        rel="stylesheet">

    <!-- Custom styles -->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>css/custom.css" rel="stylesheet">

    <style>
        body {
            background: url('<?= base_url("assets/img/bg.jpg"); ?>') no-repeat center center fixed;
            background-size: cover;
        }

        .switch-link {
            font-size: 0.85rem;
            cursor: pointer;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .error-message.show {
            display: block;
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
    </style>
</head>

<body class="bg-white-primary">

    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row -->
                        <div class="row">
                            <div class="col-lg">
                                <div class="p-5">

                                    <!-- Header -->
                                    <div class="text-center">
                                        <h1 class="h4 text-white-900 mb-4 judul-evisitor">E-Visitor</h1>
                                        <img src="<?= base_url('assets/img/footer-logo.png'); ?>"
                                            alt="Logo"
                                            class="foto-kiri">
                                    </div>

                                    <!-- ALERT untuk Multiple Errors -->
                                    <?php if ($this->session->userdata('validation_errors')): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0 pl-3">
                                                <?php foreach ($this->session->userdata('validation_errors') as $error): ?>
                                                    <li><?= $error; ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php
                                        // Hapus error setelah ditampilkan
                                        $this->session->unset_userdata('validation_errors');
                                        ?>
                                    <?php endif; ?>

                                    <!-- ALERT Success -->
                                    <?php if ($this->session->flashdata('success')): ?>
                                        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                                    <?php endif; ?>

                                    <?php
                                    // Cek form mana yang harus ditampilkan
                                    $show_form = $this->session->userdata('show_form');
                                    $show_register = ($show_form === 'register') ? true : false;

                                    // Ambil old input
                                    $old_email = $this->session->userdata('old_email');
                                    $old_username = $this->session->userdata('old_username');
                                    ?>

                                    <!-- Login Form -->
                                    <form id="loginForm" method="post" action="<?= base_url('index.php/tamu/login'); ?>"
                                        style="<?= $show_register ? 'display:none;' : ''; ?>" novalidate>
                                        <div class="form-group">
                                            <p class="label">Email</p>
                                            <input type="email" class="form-control form-control-user"
                                                id="loginEmail" name="email" placeholder="Enter Email..."
                                                value="<?= !$show_register && $old_email ? htmlspecialchars($old_email) : ''; ?>" required>
                                            <div class="error-message" id="loginEmailError">Email harus diisi!</div>
                                        </div>
                                        <div class="form-group">
                                            <p class="label">Password</p>
                                            <div class="password-wrapper">
                                                <input type="password" id="password" class="form-control" placeholder="Password">
                                                <span class="password-toggle" id="passwordToggle">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
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

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>

                                    <!-- Register Form -->
                                    <form id="registerForm" method="post" action="<?= base_url('index.php/tamu/register'); ?>"
                                        style="<?= $show_register ? '' : 'display:none;'; ?>" novalidate>
                                        <div class="form-group">
                                            <p class="label">Email</p>
                                            <input type="email" class="form-control form-control-user"
                                                id="registerEmail" name="email" placeholder="Enter Email..."
                                                value="<?= $show_register && $old_email ? htmlspecialchars($old_email) : ''; ?>" required>
                                            <div class="error-message" id="registerEmailError">Email harus diisi dan berakhiran .com!</div>
                                        </div>
                                        <div class="form-group">
                                            <p class="label">Username</p>
                                            <input type="text" class="form-control form-control-user"
                                                id="registerUsername" name="username" placeholder="Enter Username..."
                                                value="<?= $show_register && $old_username ? htmlspecialchars($old_username) : ''; ?>" required minlength="4">
                                            <div class="error-message" id="registerUsernameError">Username harus diisi dan minimal 4 karakter!</div>
                                        </div>
                                        <div class="form-group">
                                            <p class="label">Password</p>
                                            <input type="password" class="form-control form-control-user"
                                                id="registerPassword" name="password" placeholder="Enter Password" required minlength="8">
                                            <div class="error-message" id="registerPasswordError">Password harus diisi dan minimal 8 karakter!</div>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-user btn-block">
                                            Register
                                        </button>
                                    </form>

                                    <!-- Switch Link -->
                                    <div class="text-end mt-3">
                                        <span class="switch-link text-muted" id="switchToRegister"
                                            style="<?= $show_register ? 'display:none;' : ''; ?>">Belum punya akun? Register</span>
                                        <span class="switch-link text-muted" id="switchToLogin"
                                            style="<?= $show_register ? '' : 'display:none;'; ?>">Sudah punya akun? Login</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

    <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        // Switch ke Register
        switchToRegister.onclick = () => {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            switchToRegister.style.display = 'none';
            switchToLogin.style.display = 'inline';

            // Reset error messages dan form
            hideAllErrors();
            registerForm.reset();
        };

        // Switch ke Login
        switchToLogin.onclick = () => {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            switchToRegister.style.display = 'inline';
            switchToLogin.style.display = 'none';

            // Reset error messages dan form
            hideAllErrors();
            loginForm.reset();
        };

        // Function untuk hide semua error
        function hideAllErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
            });
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }

        // Validasi Login Form
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            hideAllErrors();

            const email = document.getElementById('loginEmail');
            const password = document.getElementById('loginPassword');

            // Validasi Email kosong
            if (!email.value.trim()) {
                document.getElementById('loginEmailError').classList.add('show');
                email.classList.add('is-invalid');
                isValid = false;
            }

            // Validasi Password kosong
            if (!password.value.trim()) {
                document.getElementById('loginPasswordError').classList.add('show');
                password.classList.add('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Validasi Register Form
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            hideAllErrors();

            const email = document.getElementById('registerEmail');
            const username = document.getElementById('registerUsername');
            const password = document.getElementById('registerPassword');

            // Validasi Email kosong dan harus .com
            if (!email.value.trim()) {
                document.getElementById('registerEmailError').textContent = 'Email harus diisi!';
                document.getElementById('registerEmailError').classList.add('show');
                email.classList.add('is-invalid');
                isValid = false;
            } else if (email.value.trim() && !email.value.endsWith('.com')) {
                document.getElementById('registerEmailError').textContent = 'Email harus berakhiran .com!';
                document.getElementById('registerEmailError').classList.add('show');
                email.classList.add('is-invalid');
                isValid = false;
            }

            // Validasi Username kosong dan minimal 4 karakter
            if (!username.value.trim()) {
                document.getElementById('registerUsernameError').textContent = 'Username harus diisi!';
                document.getElementById('registerUsernameError').classList.add('show');
                username.classList.add('is-invalid');
                isValid = false;
            } else if (username.value.trim() && username.value.trim().length < 4) {
                document.getElementById('registerUsernameError').textContent = 'Username minimal 4 karakter!';
                document.getElementById('registerUsernameError').classList.add('show');
                username.classList.add('is-invalid');
                isValid = false;
            }

            // Validasi Password kosong dan minimal 8 karakter
            if (!password.value.trim()) {
                document.getElementById('registerPasswordError').textContent = 'Password harus diisi!';
                document.getElementById('registerPasswordError').classList.add('show');
                password.classList.add('is-invalid');
                isValid = false;
            } else if (password.value.trim() && password.value.trim().length < 8) {
                document.getElementById('registerPasswordError').textContent = 'Password minimal 8 karakter!';
                document.getElementById('registerPasswordError').classList.add('show');
                password.classList.add('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Real-time validation untuk menghapus error saat user mengetik
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    const errorId = this.id + 'Error';
                    const errorElement = document.getElementById(errorId);
                    if (errorElement) {
                        errorElement.classList.remove('show');
                    }
                }
            });
        });
    </script>

</body>

</html>