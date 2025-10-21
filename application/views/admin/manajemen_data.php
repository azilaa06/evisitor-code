<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Kunjungan</title>

    <!-- CSS Terpisah -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Body umum */
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        /* Main content disesuaikan dengan sidebar */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Header Section */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .title-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .title-container h1 {
            font-size: 22px;
            color: #1e293b;
            font-weight: 700;
            margin: 0;
        }

        .header-icon {
            font-size: 22px;
            color: #1e293b;
        }

        .user-info {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Data Management Section */
        .data-management-section {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        /* Search & Filter Bar */
        .search-filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-input-group {
            display: flex;
            align-items: center;
            flex-grow: 1;
            min-width: 250px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .search-input-group:focus-within {
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-input-group i {
            color: #94a3b8;
            margin-right: 10px;
            font-size: 16px;
        }

        .search-input-group input {
            border: none;
            outline: none;
            flex-grow: 1;
            background-color: transparent;
            font-size: 14px;
            color: #1e293b;
        }

        .search-input-group input::placeholder {
            color: #94a3b8;
        }

        .filter-button {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        /* Table Modern */
        .data-table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }

        table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        table th {
            padding: 16px 20px;
            text-align: left;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        table th:first-child {
            border-top-left-radius: 12px;
        }

        table th:last-child {
            border-top-right-radius: 12px;
        }

        table td {
            padding: 16px 20px;
            color: #334155;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        table tbody tr {
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        table tbody tr:hover {
            background-color: #f8fafc;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        /* Status Badge Modern */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            min-width: 90px;
            text-transform: capitalize;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .status-badge.approve {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .status-badge.reject {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .status-badge.berkunjung {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }

        .status-badge.selesai {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
        }

        /* Action Button Modern */
        .action-button {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #475569;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-button:hover {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .action-button i {
            font-size: 12px;
        }

        /* Scan QR Button */
        .scan-qr-button {
            margin-top: 25px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            float: right;
        }

        .scan-qr-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.5);
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        }

        .scan-qr-button i {
            font-size: 18px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 16px;
            font-weight: 500;
        }

        /* Clear float */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>

    <?php $this->load->view('Layouts/sidebar_admin'); ?>

    <main class="main-content">
        <header class="top-header">
            <div class="title-container">
                <i class="fas fa-clipboard-list header-icon"></i>
                <h1>Manajemen Data Kunjungan<?= isset($status) ? ' - ' . ucfirst($status) : ''; ?></h1>
            </div>
            <div class="user-info">
                <?= $nama; ?>
            </div>
        </header>


        <form action="<?= base_url('index.php/manajemen_kunjungan/'); ?>" method="get" class="search-filter-bar">
            <div class="data-management-section">
                <div class="search-filter-bar">
                    <div class="search-input-group">
                        <i class="fas fa-search"></i>
                        <input
                            type="text"
                            name="keyword"
                            placeholder="Cari nama, NIK, atau instansi..."
                            value="<?= isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                    </div>
                    <button class="submit" class="filter-button">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>

                <div class="data-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Tanggal</th>
                                <th>Instansi</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($kunjungan)): ?>
                                <?php $no = 1;
                                foreach ($kunjungan as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['NAMA']); ?></td>
                                        <td><?= htmlspecialchars($row['NIK']); ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['TANGGAL'])); ?></td>
                                        <td><?= htmlspecialchars($row['INSTANSI']); ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            $status_text = '';
                                            switch ($row['status']) {
                                                case 'pending':
                                                    $status_class = 'pending';
                                                    $status_text = 'Menunggu';
                                                    break;
                                                case 'approved':
                                                    $status_class = 'berkunjung';
                                                    $status_text = 'Berkunjung';
                                                    break;
                                                case 'rejected':
                                                    $status_class = 'reject';
                                                    $status_text = 'Ditolak';
                                                    break;
                                                case 'completed':
                                                    $status_class = 'selesai';
                                                    $status_text = 'Selesai';
                                                    break;
                                                default:
                                                    $status_class = 'pending';
                                                    $status_text = ucfirst($row['status']);
                                            }
                                            ?>
                                            <span class="status-badge <?= $status_class; ?>">
                                                <?= $status_text; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <!-- mengirimkan visit_id dari setiap baris ke fungsi detail di controller Manajemen_kunjungan. -->
                                            <a href="<?= base_url('index.php/detail_kunjungan/detail/' . $row['visit_id']); ?>" class="action-button">
                                                <i class="fas fa-eye"></i> Details

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <p>Belum ada data kunjungan</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <script>
                    const searchInput = document.querySelector('input[name="keyword"]');
                    searchInput.addEventListener('input', function() {
                        if (this.value.trim() === '') {
                            // Kalau input kosong, reload halaman biar semua data muncul lagi
                            window.location.href = "<?= base_url('index.php/manajemen_kunjungan/'); ?>";
                        }
                    });
                </script>


                <!-- Tambahkan di bawah tombol Details (bagian HTML kamu yang ada tombol QR) -->
                <div class="clearfix">
                    <button id="scanButton" type="button" class="scan-qr-button">
                        <i class="fas fa-qrcode"></i> Scan QR Code
                    </button>
                </div>

                <!-- Modal untuk scanner -->
                <div id="qrModal" class="modal-scan" style="display:none;">
                    <div class="modal-content-scan">
                        <div id="preview" style="width: 300px; height: 300px;"></div>
                        <button id="closeModal" class="close-scan-btn">Tutup</button>
                    </div>
                </div>

                <!-- Library SweetAlert -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <!-- Library QR Code -->
                <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

                <script>
                    const scanButton = document.getElementById('scanButton');
                    const modal = document.getElementById('qrModal');
                    const closeModal = document.getElementById('closeModal');
                    let html5QrCode;

                    // Klik tombol scan
                    scanButton.addEventListener('click', () => {
                        Swal.fire({
                            title: "Mengaktifkan Kamera...",
                            text: "Tunggu sebentar ya",
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        modal.style.display = 'flex';
                        html5QrCode = new Html5Qrcode("preview");
                        html5QrCode.start({
                                facingMode: "environment"
                            }, {
                                fps: 10,
                                qrbox: 250
                            },
                            qrCodeMessage => {
                                html5QrCode.stop().then(() => {
                                    modal.style.display = 'none';
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Scan Berhasil!',
                                        text: 'QR Code terbaca dengan benar ✅',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    // Contoh redirect (opsional):
                                    // window.location.href 
                                });
                            },
                            errorMessage => {
                                console.log("Scanning...", errorMessage);
                            }
                        ).catch(err => {
                            console.error("Gagal membuka kamera:", err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Kamera Gagal Dibuka',
                                text: 'Pastikan izin kamera diizinkan.'
                            });
                        });
                    });

                    // Tutup modal
                    closeModal.addEventListener('click', () => {
                        if (html5QrCode) html5QrCode.stop();
                        modal.style.display = 'none';
                    });
                </script>

                <style>
                    /* Modal scanner */
                    .modal-scan {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.6);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                    }

                    .modal-content-scan {
                        background: #fff;
                        padding: 20px;
                        border-radius: 12px;
                        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
                        text-align: center;
                        width: 320px;
                    }

                    .close-scan-btn {
                        background: #ef4444;
                        color: #fff;
                        border: none;
                        padding: 8px 14px;
                        border-radius: 8px;
                        cursor: pointer;
                        margin-top: 10px;
                    }

                    .close-scan-btn:hover {
                        background: #dc2626;
                    }
                </style>