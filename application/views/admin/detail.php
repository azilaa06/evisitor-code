<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kunjungan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
            /* hapus padding biar gak ganggu posisi konten */
        }

        /* Tambahkan container utama agar tidak ketutup sidebar */
        .main-content {
            margin-left: 250px;
            /* sesuaikan dengan lebar sidebar */
            padding: 40px;
        }

        /* Kotak detail kunjungan */
        .detail-container {
            max-width: 900px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #081a48;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .detail-grid label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .detail-grid input,
        .detail-grid textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }

        .detail-grid textarea {
            min-height: 80px;
            resize: none;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .button-group {
            text-align: center;
            margin-top: 25px;
        }

        .btn {
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-approve {
            background-color: #0b2b74;
        }

        .btn-approve:hover {
            background-color: #0e3a94;
        }

        .btn-reject {
            background-color: #c0392b;
            margin-right: 15px;
        }

        .btn-reject:hover {
            background-color: #e74c3c;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                /* kalau sidebar disembunyikan di mobile */
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="detail-container">
            <h2>Detail Kunjungan</h2>

            <form action="<?= base_url('index.php/detail_kunjungan/update_status/' . $pengunjung['visit_id']); ?>" method="post">
                <div class="detail-grid">

                    <!-- Jadi semua field otomatis terisi sesuai data pengunjung yang kamu klik. -->
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-input" value="<?= $pengunjung['fullname']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" class="form-input" value="<?= $pengunjung['phone']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" class="form-input" value="<?= $pengunjung['id_number']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tujuan ke</label>
                    <input type="text" class="form-input" value="<?= $pengunjung['to_whom']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Instansi</label>
                    <input type="text" class="form-input" value="<?= $pengunjung['institution']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" class="form-input" value="<?= date('Y-m-d', strtotime($pengunjung['scheduled_date'])); ?>" readonly>
                </div>

                <div class="form-group full-width">
                    <label>Tujuan</label>
                    <textarea class="form-input" readonly><?= $pengunjung['purpose']; ?></textarea>
                </div>

            </div>

            <div class="button-group">
                <button type="submit" name="status" value="approved" class="btn btn-approve">Approve</button>
                <button type="submit" name="status" value="rejected" class="btn btn-reject">Reject</button>
            </div>
        </form>
    </div>


</body>

</html>