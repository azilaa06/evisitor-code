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
        /* Body umum */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        /* Main content disesuaikan dengan sidebar baru */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Saat layar kecil, konten penuh */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /*  jarak ke bawha Username pojok kanan atas */
        .username {
            text-align: right;
            margin-bottom: 20px;
        }

        .username h2 {
            font-size: 16px;
            color: #1e293b;
            font-weight: normal;
            margin: 0;
        }

        /* Judul dashboard */
        .main-content h1 {
            font-size: 22px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e293b;
            font-weight: 700;
        }

        .main-content h1 i {
            font-size: 22px;
        }

        /* Container semua card */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 18px;
            margin-top: 25px;
            max-width: 1300px;
        }



        /* Card dengan desain seperti gambar */
        .card {
            position: relative;
            padding: 20px 22px;
            border: none;
            border-radius: 10px;
            text-align: left;
            transition: all 0.3s ease;
            overflow: hidden;
            min-height: 85px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Warna berbeda untuk setiap card */
        .card:nth-child(1) {
            background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
        }

        .card:nth-child(2) {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }

        .card:nth-child(3) {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }

        .card:nth-child(4) {
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        }

        .card:nth-child(5) {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Icon besar di pojok kanan atas */
        .card-icon {
            position: absolute;
            top: 18px;
            right: 18px;
            font-size: 42px;
            color: rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .card:hover .card-icon {
            transform: scale(1.1);
            color: rgba(255, 255, 255, 0.4);
        }

        /* Konten card */
        .card-content {
            position: relative;
            z-index: 1;
        }

        .card-number {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Link dalam card */
        .card a {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cards {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .username h2 {
                font-size: 18px;
            }

            .main-content h1 {
                font-size: 24px;
            }

            .card {
                padding: 25px 20px;
                min-height: 100px;
            }

            .card-number {
                font-size: 28px;
            }

            .card-icon {
                font-size: 40px;
            }
        }
        
    </style>
</head>

<body>

    <!-- Sidebar Resepsionis -->
    <?php $this->load->view('Layout/sidebar_admin'); ?>

    <!-- Konten Utama -->
    <div class="main-content">
        <div class="username">
            <h2> <?php
                    $email = $this->session->userdata('username');
                    $nama = explode('@', $email)[0];
                    echo htmlspecialchars($nama) . "";
                    ?>
            </h2>
        </div>

        <h1><i class="fas fa-home"></i> Dashboard</h1>

        <div class="cards">
            <!-- Card 1: Permohonan Ditolak -->
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/ditolak') ?>">
                    <i class="fas fa-times-circle card-icon"></i>
                    <div class="card-content">
                        <div class="card-number"><?= isset($total_ditolak) ? $total_ditolak : 0 ?></div>
                        <div class="card-label">Permohonan Ditolak</div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Sedang Berkunjung -->
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/berkunjung') ?>">
                    <i class="fas fa-user-clock card-icon"></i>
                    <div class="card-content">
                        <div class="card-number"><?= isset($total_berkunjung) ? $total_berkunjung : 0 ?></div>
                        <div class="card-label">Sedang Berkunjung</div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Menunggu Approval -->
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/menunggu') ?>">
                    <i class="fas fa-hourglass-half card-icon"></i>
                    <div class="card-content">
                        <div class="card-number"><?= isset($total_menunggu) ? $total_menunggu : 0 ?></div>
                        <div class="card-label">Menunggu Approval</div>
                    </div>
                </a>
            </div>

            <!-- Card 4: Telah Berkunjung -->
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/selesai') ?>">
                    <i class="fas fa-check-circle card-icon"></i>
                    <div class="card-content">
                        <div class="card-number"><?= isset($total_selesai) ? $total_selesai : 0 ?></div>
                        <div class="card-label">Telah Berkunjung</div>
                    </div>
                </a>
            </div>

            <!-- Card 5: Pengunjung Hari Ini -->
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/Pengunjung_Hari_Ini') ?>">
                    <i class="fas fa-calendar-day card-icon"></i>
                    <div class="card-content">
                        <div class="card-number"><?= isset($total_today) ? $total_today : 0 ?></div>
                        <div class="card-label">Pengunjung Hari Ini</div>
                    </div>
                </a>
            </div>

            <!-- Card 6: Approved -->
            <div class="card">
                <a href="<?= base_url('index.php/manajemen_kunjungan/data/Approved') ?>">
                    <i class="fas fa-calendar-day card-icon"></i>
                    <div class="card-content">
                        <div class="card-number"><?= isset($total_approved) ? $total_approved : 0 ?></div>
                        <div class="card-label">APPROVED</div>
                    </div>
                </a>
            </div>

        </div>
    </div>

</body>

</html>