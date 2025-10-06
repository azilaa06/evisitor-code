<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Resepsionis</title>

    <!-- CSS Terpisah -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS Internal -->
    <style>
        /* Body umum halaman */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #222;
        }

        /* Konten utama */
        .main-content {
            margin-left: 220px;
            /* lebar sidebar */
            padding: 20px;
            background: white;
            min-height: 100vh;
        }

        /* Username pojok kanan atas */
        .username {
            text-align: right;
            font-size: 14px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Judul dashboard */
        .main-content h1,
        .header h2 {
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Container semua card */
        .cards {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            justify-content: center;
            /* rata tengah */
        }

        /* Card */
        .card {
            flex: 1 1 220px;
            min-width: 200px;
            max-width: 250px;
            padding: 30px;
            border: 1px solid #333;
            border-radius: 20px;
            text-align: center;
            font-size: 14px;
            background-color: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Link dalam card */
        .card a {
            display: block;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
        }

        .card a:hover {
            color: #007bff;
        }
    </style>
</head>

<body>

    <!-- Include Sidebar -->
    <?php $this->load->view('layout/sidebar_admin'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="username">
            <h2>Selamat datang, <?= $this->session->userdata('nama_lengkap'); ?>!</h2>
        </div>

        <h1><i class="fas fa-home"></i> Dashboard</h1>

        <div class="cards">
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/ditolak') ?>">
                    Permohonan Ditolak
                </a>
            </div>

            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/berkunjung') ?>">
                    Sedang Berkunjung
                </a>
            </div>

            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/menunggu') ?>">
                    Menunggu Approval
                </a>
            </div>

            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/selesai') ?>">
                    Telah Berkunjung
                </a>
            </div>


            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/Pengunjung Hari Ini') ?>">
                    Pengunjung Hari Ini
                </a>
            </div>
        </div>
    </div>

</body>

</html>