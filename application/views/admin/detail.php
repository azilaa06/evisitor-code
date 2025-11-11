<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kunjungan</title>
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #000B23;
            --primary: #3b82f6;
            --primary-light: #60a5fa;
            --primary-dark: #1d4ed8;
            --secondary: #6366f1;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #0f172a;
            --gray: #64748b;
            --border: #e2e8f0;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f5f9;
            color: var(--dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
        }

        .main-content {
            margin-left: 280px;
            padding: 25px;
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 950px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid var(--border);
            width: 100%;
        }

        .card-header {
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1e293b 100%);
            color: white;
            padding: 22px 30px;
            position: relative;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-back:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .card-title {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            flex: 1;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(59, 130, 246, 0.3);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid rgba(59, 130, 246, 0.5);
        }

        .card-body {
            padding: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
            margin-bottom: 30px;
        }

        .info-group {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: var(--sidebar-bg);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label i {
            font-size: 16px;
            color: var(--primary);
            width: 18px;
            text-align: center;
        }

        .info-value {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 15px;
            color: var(--dark);
            min-height: 46px;
            display: flex;
            align-items: center;
        }

=======
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

>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        .full-width {
            grid-column: 1 / -1;
        }

<<<<<<< HEAD
        .textarea-value {
            min-height: 90px;
            resize: none;
            line-height: 1.5;
        }

        .action-section {
            border-top: 1px solid var(--border);
            padding-top: 25px;
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn i {
            font-size: 15px;
        }

        .btn-approve {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-reject {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-hapus {
            background: linear-gradient(135deg, var(--gray) 0%, #475569 100%);
            color: white;
        }

        .btn-hapus:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(100, 116, 139, 0.3);
        }

        .btn-checkout {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(59, 130, 246, 0.3);
        }

        /* Modal Konfirmasi - Lebih Kecil */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 20px;
            max-width: 320px;
            width: 85%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: modalSlideIn 0.2s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-icon {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .modal-message {
            color: var(--gray);
            margin-bottom: 20px;
            line-height: 1.4;
            font-size: 14px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .modal-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 13px;
            flex: 1;
        }

        .modal-btn-cancel {
            background: #f1f5f9;
            color: var(--gray);
        }

        .modal-btn-cancel:hover {
            background: #e2e8f0;
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .modal-btn-confirm:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(59, 130, 246, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .card-header {
                padding: 18px 22px;
            }

            .header-content {
                flex-direction: column;
                gap: 12px;
            }

            .card-title {
                font-size: 20px;
            }

            .card-body {
                padding: 22px;
            }

            .action-section {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .modal-actions {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                padding: 15px 18px;
            }

            .card-body {
                padding: 18px;
            }

            .info-value {
                padding: 10px 12px;
                font-size: 14px;
            }

            .modal-content {
                padding: 18px;
                max-width: 280px;
            }

            .modal-icon {
                font-size: 28px;
                margin-bottom: 10px;
            }

            .modal-title {
                font-size: 15px;
            }

            .modal-message {
                font-size: 13px;
                margin-bottom: 18px;
            }

            .modal-btn {
                padding: 7px 16px;
                font-size: 12px;
            }
=======
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
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        }
    </style>
</head>

<body>
<<<<<<< HEAD
    <!-- SIDEBAR ANDA DI SINI (tetap seperti yang Anda berikan) -->

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="header-content">
                        <a href="<?= base_url('index.php/manajemen_kunjungan'); ?>" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <h1 class="card-title">Detail Kunjungan</h1>
                        <div class="status-badge">
                            <i class="fas fa-clock"></i>
                            <?= isset($pengunjung['status']) ? $pengunjung['status'] : 'Pending' ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form action="<?= base_url('index.php/detail_kunjungan/update_status/' . (isset($pengunjung['visit_id']) ? $pengunjung['visit_id'] : '')); ?>" method="post">
                        <div class="info-grid">
                            <div class="info-group">
                                <div class="info-label">
                                    <i class="fas fa-user"></i>
                                    <span>Nama Lengkap</span>
                                </div>
                                <div class="info-value"><?= isset($pengunjung['fullname']) ? $pengunjung['fullname'] : 'Data tidak tersedia' ?></div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">
                                    <i class="fas fa-phone"></i>
                                    <span>No. Telepon</span>
                                </div>
                                <div class="info-value"><?= isset($pengunjung['phone']) ? $pengunjung['phone'] : 'Data tidak tersedia' ?></div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">
                                    <i class="fas fa-id-card"></i>
                                    <span>NIK</span>
                                </div>
                                <div class="info-value"><?= isset($pengunjung['id_number']) ? $pengunjung['id_number'] : 'Data tidak tersedia' ?></div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">
                                    <i class="fas fa-user-tag"></i>
                                    <span>Tujuan ke</span>
                                </div>
                                <div class="info-value"><?= isset($pengunjung['to_whom']) ? $pengunjung['to_whom'] : 'Data tidak tersedia' ?></div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">
                                    <i class="fas fa-building"></i>
                                    <span>Instansi</span>
                                </div>
                                <div class="info-value"><?= isset($pengunjung['institution']) ? $pengunjung['institution'] : 'Data tidak tersedia' ?></div>
                            </div>

                            <div class="info-group">
                                <div class="info-label">
                                    <i class="fas fa-calendar"></i>
                                    <span>Tanggal</span>
                                </div>
                                <div class="info-value">
                                    <?= isset($pengunjung['scheduled_date']) ? date('Y-m-d', strtotime($pengunjung['scheduled_date'])) : 'Data tidak tersedia' ?>
                                </div>
                            </div>

                            <div class="info-group full-width">
                                <div class="info-label">
                                    <i class="fas fa-bullseye"></i>
                                    <span>Tujuan</span>
                                </div>
                                <div class="info-value textarea-value"><?= isset($pengunjung['purpose']) ? $pengunjung['purpose'] : 'Data tidak tersedia' ?></div>
                            </div>
                        </div>

                        <div class="action-section">
                            <?php if (isset($pengunjung['status']) && $pengunjung['status'] === 'pending') : ?>
                                <button type="submit" name="status" value="approved" class="btn btn-approve">
                                    <i class="fas fa-check-circle"></i> Approve
                                </button>
                                <button type="submit" name="status" value="rejected" class="btn btn-reject">
                                    <i class="fas fa-times-circle"></i> Reject
                                </button>
                            <?php endif; ?>

                            <a href="<?= base_url('index.php/detail_kunjungan/delete/' . (isset($pengunjung['visit_id']) ? $pengunjung['visit_id'] : '')); ?>"
                                class="btn btn-hapus"
                                id="btnHapus">
                                <i class="fas fa-trash"></i> Hapus
                            </a>

                            <?php if ($pengunjung['status'] === 'pending' || $pengunjung['status'] === 'approved') : ?>
                                <a href="<?= base_url('index.php/detail_kunjungan/checkout/' . (isset($pengunjung['visit_id']) ? $pengunjung['visit_id'] : '')); ?>"
                                    class="btn btn-checkout"
                                    id="btnCheckout">
                                    <i class="fas fa-sign-out-alt"></i> Check-Out
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-trash"></i>
            </div>
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <p class="modal-message">Yakin ingin menghapus data kunjungan ini?</p>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelDelete">Batal</button>
                <button class="modal-btn modal-btn-confirm" id="confirmDelete">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Check-Out -->
    <div class="modal-overlay" id="checkoutModal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <h3 class="modal-title">Konfirmasi Check-Out</h3>
            <p class="modal-message">Yakin ingin melakukan Check-Out untuk tamu ini?</p>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-cancel" id="cancelCheckout">Batal</button>
                <button class="modal-btn modal-btn-confirm" id="confirmCheckout">Ya, Check-Out</button>
            </div>
        </div>
    </div>



    <script>
        // Variabel untuk menyimpan URL
        let checkoutUrl = '';
        let deleteUrl = '';

        // Modal Check-Out mengambil elemen HTML modal Check-Out (tombol batal/tombol ya/lanjutkan)
        const checkoutModal = document.getElementById('checkoutModal');
        const btnCheckout = document.getElementById('btnCheckout');
        const cancelCheckout = document.getElementById('cancelCheckout');
        const confirmCheckout = document.getElementById('confirmCheckout');

        if (btnCheckout) { // minta kofirmasi dari user dlu saat cekout
            btnCheckout.addEventListener('click', function(e) {
                e.preventDefault();
                checkoutUrl = this.getAttribute('href');
                checkoutModal.style.display = 'flex';
            });
        }

        if (cancelCheckout) { //Kalau klik Batal, modal ditutup.
            cancelCheckout.addEventListener('click', function() {
                checkoutModal.style.display = 'none';
            });
        }

        if (confirmCheckout) { //Kalau klik Ya, barulah diarahkan ke halaman Check-Out
            confirmCheckout.addEventListener('click', function() {
                if (checkoutUrl) {
                    window.location.href = checkoutUrl;
                }
            });
        }

        // Modal Hapus mengambil elemen HTML modal Check-Out (tombol batal/tombol ya/lanjutkan)
        const deleteModal = document.getElementById('deleteModal');
        const btnHapus = document.getElementById('btnHapus');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');

        if (btnHapus) {
            btnHapus.addEventListener('click', function(e) {
                e.preventDefault();
                deleteUrl = this.getAttribute('href');
                deleteModal.style.display = 'flex';
            });
        }

        if (cancelDelete) {
            cancelDelete.addEventListener('click', function() {
                deleteModal.style.display = 'none';
            });
        }

        if (confirmDelete) {
            confirmDelete.addEventListener('click', function() {
                if (deleteUrl) {
                    window.location.href = deleteUrl + "?deleted=1";
                }
            });
        }

      

        // Notifikasi hapus berhasil
        const params = new URLSearchParams(window.location.search);
        if (params.get("deleted") === "success") {
            alert("Data berhasil dihapus!");
        }
    </script>
=======
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


>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
</body>

</html>