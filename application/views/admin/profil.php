<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Resepsionis</title>

    <!-- CSS Sidebar -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #1e1e1e;
        }

        .container {
            display: flex;
            min-height: 100vh;
            color: #1e293b;
        }

        .main {
            margin-left: 260px;
            /* sesuaikan dengan lebar sidebar kamu */
            flex: 1;
            background-color: #f8fafc;
            padding: 40px;
            transition: margin-left 0.3s ease;
        }

        .main h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .profile-card {
            background-color: #e0ebff;
            border-radius: 12px;
            padding: 35px 40px;
            max-width: 600px;
            display: flex;
            gap: 30px;
        }

        .profile-image {
            text-align: center;
        }

        .profile-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #cbd5e1;
        }

        .edit-text {
            font-size: 13px;
            color: #475569;
            margin-top: 8px;
            cursor: pointer;
            display: inline-block;
        }

        .profile-form {
            flex: 1;
        }

        .profile-form label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #334155;
        }

        .profile-form input {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper i {
            position: absolute;
            top: 9px;
            right: 12px;
            color: #475569;
            cursor: pointer;
        }

        .btn-ok {
            padding: 8px 20px;
            background-color: #0f172a;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-ok:hover {
            background-color: #1e293b;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .profile-card {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Sidebar dipanggil -->
        <?php $this->load->view('layout/sidebar_admin'); ?>

        <!-- Konten Profil -->
        <div class="main">
            <h1><i class="fa fa-user"></i> Profil</h1>

            <div class="profile-card">
                <div class="profile-image">
                    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile">
                    <div class="edit-text">✏️ Edit</div>
                </div>

                <div class="profile-form">
                    <form>
                        <label>Nama</label>
                        <input type="text" value="example123" disabled>

                        <label>Email</label>
                        <input type="email" value="example123@gmail.com" disabled>

                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" value="password123" disabled>
                            <i class="fa fa-eye"></i>
                        </div>

                        <button class="btn-ok">Ok</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>