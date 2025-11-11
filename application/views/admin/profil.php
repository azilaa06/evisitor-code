<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-light: #f5f7fa;
            --bg-card-light: #ffffff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --primary: #3b82f6;
            --primary-dark: #2563eb;

            --bg-dark: #0f172a;
            --bg-card-dark: #1e293b;
            --text-dark-mode: #f1f5f9;
            --text-light-mode: #cbd5e1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark-mode);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px 40px;
            min-height: 100vh;
            transition: background-color 0.3s ease;
        }

        /* Header Section */
        .profil-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .profil-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profil-title h1 {
            font-size: 28px;
            font-weight: 700;
        }

        .profil-title i {
            font-size: 28px;
            color: var(--primary);
        }

        .theme-toggle {
            background: var(--bg-card-light);
            border: 2px solid #e2e8f0;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        body.dark-mode .theme-toggle {
            background: var(--bg-card-dark);
            border-color: #334155;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
        }

        /* Profil Container */
        .profil-container {
            background: var(--bg-card-light);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        body.dark-mode .profil-container {
            background: var(--bg-card-dark);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        /* Profil Body - Layout */
        .profil-body {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        /* Foto Profil Section */
        .profil-picture {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .profile-avatar {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar i {
            font-size: 48px;
            color: #cbd5e1;
        }

        body.dark-mode .profile-avatar {
            background: linear-gradient(135deg, #334155 0%, #475569 100%);
        }

        .profile-avatar-input {
            display: none;
        }

        .edit-photo-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edit-photo-btn:hover {
            background: var(--primary-dark);
        }

        /* Form Section */
        .profil-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            padding: 12px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
        }

        body.dark-mode .form-group input {
            background: #1e293b;
            border-color: #334155;
            color: var(--text-dark-mode);
        }

        body.dark-mode .form-group input:focus {
            background: #334155;
            border-color: var(--primary);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-light);
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .password-wrapper i:hover {
            color: var(--primary);
        }

        .password-wrapper input {
            padding-right: 45px;
        }

        /* Action Buttons */
        .profil-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
        }

        body.dark-mode .profil-actions {
            border-top-color: #334155;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .btn-save {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .btn-delete:hover {
            background: #fecaca;
            transform: translateY(-2px);
        }

        body.dark-mode .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .profil-container {
                padding: 25px;
            }

            .profil-body {
                flex-direction: column;
                align-items: center;
                gap: 30px;
            }

            .profil-form {
                width: 100%;
            }

            .profil-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <?php $this->load->view('Layout/sidebar_admin'); ?>

    <main class="main-content">
        <div class="profil-header">
            <div class="profil-title">
                <i class="fas fa-user-circle"></i>
                <h1>Profil Saya</h1>
            </div>
            <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <div class="profil-container">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div id="successAlert" style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle"></i>
                    <span><?= $this->session->flashdata('success'); ?></span>
                    <button type="button" onclick="document.getElementById('successAlert').style.display='none'" style="background: none; border: none; color: inherit; cursor: pointer; margin-left: auto; font-size: 18px;">×</button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div id="errorAlert" style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= $this->session->flashdata('error'); ?></span>
                    <button type="button" onclick="document.getElementById('errorAlert').style.display='none'" style="background: none; border: none; color: inherit; cursor: pointer; margin-left: auto; font-size: 18px;">×</button>
                </div>
            <?php endif; ?>

            <form class="profil-body" id="profilForm" method="post" action="<?= base_url('index.php/profil_admin/update'); ?>" enctype="multipart/form-data">
                <!-- Foto Profil -->
                <div class="profil-picture">
                    <div class="profile-avatar" id="profileAvatar">
                        <?php if (!empty($user->foto)): ?>
                            <img src="<?= base_url('index.php/uploads/profil/' . $user->foto); ?>" alt="Profil">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <input type="file" id="profileAvatarInput" name="foto" class="profile-avatar-input" accept="image/*">
                    <button type="button" class="edit-photo-btn" id="editPhotoBtn">
                        <i class="fas fa-camera"></i> Ubah Foto
                    </button>
                </div>

                <!-- Form Fields -->
                <div class="profil-form">
                    <div class="form-group">
                        <label for="fullname">Nama Lengkap</label>
                        <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user->fullname ?? ''); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->username ?? ''); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" value="<?= htmlspecialchars($user->password ?? ''); ?>" readonly placeholder="Kosong untuk tidak mengubah">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="profil-actions">
                        <button type="button" class="btn btn-delete" id="deleteBtn">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                        <button type="button" class="btn btn-save" id="editBtn">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="submit" class="btn btn-save" id="saveBtn" style="display: none;">
                            <i class="fas fa-check"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Dark Mode Toggle
        const themeToggle = document.getElementById('themeToggle');
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            document.body.classList.add('dark-mode');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }

        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            themeToggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });

        // Password Toggle dengan Icon Mata
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePassword');

        togglePasswordBtn.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePasswordBtn.classList.toggle('fa-eye');
            togglePasswordBtn.classList.toggle('fa-eye-slash');
        });

        // Edit Mode
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const deleteBtn = document.getElementById('deleteBtn');
        const fullnameInput = document.getElementById('fullname');
        const usernameInput = document.getElementById('username');
        const passwordInput2 = document.getElementById('password');

        editBtn.addEventListener('click', () => {
            fullnameInput.removeAttribute('readonly');
            usernameInput.removeAttribute('readonly');
            passwordInput2.removeAttribute('readonly');
            editBtn.style.display = 'none';
            deleteBtn.style.display = 'none';
            saveBtn.style.display = 'inline-flex';
        });

        // Photo Upload
        const editPhotoBtn = document.getElementById('editPhotoBtn');
        const profileAvatarInput = document.getElementById('profileAvatarInput');
        const profileAvatar = document.getElementById('profileAvatar');

        editPhotoBtn.addEventListener('click', () => {
            profileAvatarInput.click();
        });

        profileAvatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    profileAvatar.innerHTML = `<img src="${event.target.result}" alt="Profil">`;
                };
                reader.readAsDataURL(file);
            }
        });

        profileAvatar.addEventListener('click', () => {
            profileAvatarInput.click();
        });

        // Delete Account
        deleteBtn.addEventListener('click', () => {
            if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
                window.location.href = '<?= base_url('index.php/profil_admin/delete_account'); ?>';
            }
        });
    </script>

</body>

</html>