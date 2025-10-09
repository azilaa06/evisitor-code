<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f6fa;
        }

        main {
            margin-left: 220px;
            padding: 40px;
        }

        .profil-container {
            max-width: 500px;
            background-color: #dbe9ff;
            padding: 70px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin: 0;
            /* biar nempel kiri */
            margin-top: 20px;
        }

        .profil-header i {
            font-size: 24px;
            margin-right: 10px;
        }

        .profil-picture {
            position: relative;
            width: 100px;
            height: 100px;
            margin-bottom: 90px;
        }

        .profil-picture img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }


        .profil-form label {
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .profil-form input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            /* form putih melengkung */
            border: 1px solid #ccc;
            /* garis tipis di form putih */
            outline: none;
        }

        .profil-form .password-wrapper {
            position: relative;
        }

        .profil-form .password-wrapper i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .profil-body {
            display: flex;
            gap: 30px;
            /* jarak untuk ke kanan dari profil */
            align-items: flex-start;
            /* supaya sejajar di atas */
        }

        .detail-container {
            display: flex;
            align-items: center;
            /* biar teks & icon rata tengah */
            gap: 10px;
            /* jarak antara icon sama teks */
            margin-bottom: 20px;
            /* jarak ke bawah (kotak biru) */
        }

        .detail-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        .detail-container i {
            font-size: 28px;
            color: #333;
        }

        .profil-actions {
            display: flex;
            justify-content: flex-end;
            /* biar nempel ke kanan */
            margin-top: 20px;
            gap: 10px;
            /* jarak antar tombol */
        }

        .profil-actions button {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #333;
            background-color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .profil-actions .btn-ok {
            background-color: #e0e0e0;
        }

        .profil-actions .btn-save {
            background-color: #4a90e2;
            color: white;
            border: none;
        }
    </style>
</head>

<body>

    <?php $this->load->view('layout/sidebar_admin'); ?>

    <main>

        <div class="main-content">
            <div class="detail-container">
                <i class="fas fa-user"></i>
                <h2 class="detail-title">Profil</h2>
            </div>


            <div class="profil-container">
                <div class="profil-body">
                    <div class="profil-picture">
                        <img src="<?= base_url('assets/img/download.jpeg'); ?>" alt="Profil">
                        <span class="edit"><i class="fas fa-edit"></i> Edit</span>
                    </div>

                    <form class="profil-form">
                        <label>Nama</label>
                        <input type="text" value="example123">

                        <label>Username</label>
                        <input type="text" value="example123@gmail.com">

                        <label>Password</label>
                        <div class="password-wrapper">
                            <input type="password" value="********">
                            <i class="fas fa-eye"></i>
                        </div>

                        <!-- Tambahan tombol pojok kanan bawah -->
                        <div class="profil-actions">
                            <button type="button" class="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
    </main>

</body>

</html>