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

    <style>
        /* FILE: assets/css/detail_kunjungan.css */

        :root {
            --color-main-bg: #f5f6fa;
            --color-text-dark: #333;
            --color-primary: #5d5dff;
            --color-reject: #e53935;
            --color-card-bg: #e6f0ff;
            /* Latar belakang card detail (Biru muda) */
        }

        /* Gaya Konten Utama (dari Layout), background abu*/
        .main-content {
            flex-grow: 1;
            padding: 30px;
            background-color: var(--color-main-bg);
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .top-header h1 {
            font-size: 1.8em;
            color: var(--color-text-dark);
        }

        .user-info {
            font-weight: 600;
            color: var(--color-text-dark);
        }

        /* --- Gaya Card Detail --- */
        .detail-container {
            padding: 20px;
        }

        .detail-card {
            background-color: var(--color-card-bg);
            /* Warna biru muda */
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .detail-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            /* Jarak antar form group */
        }

        /* --- Form Group & Input Styling --- */
        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full-width {
            width: 100%;
        }

        .half-width {
            width: calc(50% - 10px);
            /* 50% dikurangi setengah gap */
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            /* Input berwarna putih */
            font-size: 1em;
            color: var(--color-text-dark);
            /* Input dibaca (readonly) tapi tetap styling-nya */
        }

        /* Gaya Khusus untuk area Tujuan */
        .large-text-area {
            width: calc(60% - 10px);
        }

        /* Gaya Khusus untuk area Tanggal */
        .date-area {
            width: calc(40% - 10px);
        }

        .date-input-group {
            display: flex;
            align-items: center;
            position: relative;
        }

        .date-input-group input {
            padding-right: 40px;
            /* Ruang untuk ikon */
            width: 100%;
        }

        .calendar-icon {
            position: absolute;
            right: 15px;
            color: #777;
            font-size: 1.1em;
        }

        /* --- Tombol Aksi --- */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            /* Posisikan tombol di kanan */
            gap: 15px;
            padding-top: 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 1em;
            transition: opacity 0.3s, transform 0.1s;
        }

        .btn-reject {
            background-color: var(--color-reject);
            color: #fff;
        }

        .btn-approve {
            background-color: var(--color-primary);
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /*menegaskan lagi latar belakang abu-abu supaya full tinggi layar. */
        .main-content {
            margin-left: 250px;/* samain sama lebar sidebar */
            padding: 20px;
            background-color: #f5f6fa;/* abu-abu */
            min-height: 100vh;/* minimal penuh setinggi layar */
        }

        html,
        body {
            height: 100%;
            margin: 0;
            background-color: #f5f6fa;/* abu-abu juga untuk nutup area kosong */
        }
    </style>

</head>

<body>

    <?php $this->load->view('Layouts/sidebar_admin'); ?>

    <div class="main-content">
        <div class="detail-container">
            <h2 class="detail-title">Detail Kunjungan</h2>

            <div class="detail-card">
                <form class="detail-form">
                    <div class="form-group full-width">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="" readonly>
                    </div>

                    <div class="form-group half-width">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" value="" readonly>
                    </div>
                    <div class="form-group half-width">
                        <label for="no_telepon">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" value="" readonly>
                    </div>

                    <div class="form-group half-width">
                        <label for="instansi">Instansi</label>
                        <input type="text" id="instansi" name="instansi" value="" readonly>
                    </div>
                    <div class="form-group half-width">
                        <label for="tujuan_ke">Tujuan ke</label>
                        <input type="text" id="tujuan_ke" name="tujuan_ke" value="" readonly>
                    </div>

                    <div class="form-group large-text-area">
                        <label for="tujuan">Tujuan</label>
                        <textarea id="tujuan" name="tujuan" rows="5" readonly></textarea>
                    </div>
                    <div class="form-group date-area">
                        <label for="tanggal">Tanggal</label>
                        <div class="date-input-group">
                            <input type="text" id="tanggal" name="tanggal" value="" readonly>
                            <i class="fas fa-calendar-alt calendar-icon"></i>
                        </div>
                    </div>

                    <div class="form-actions full-width">
                        <button type="button" class="btn btn-reject">Rejected</button>
                        <button type="button" class="btn btn-approve">Approve</button>
                    </div>
                </form>
            </div>
        </div>
</body>

</html>