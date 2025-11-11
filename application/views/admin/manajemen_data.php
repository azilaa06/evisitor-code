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

    <!-- SweetAlert CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



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

        /* Modal Scanner Styles */
        .modal-scan {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content-scan {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        #preview {
            width: 100%;
            height: 300px;
            border: 2px dashed #3b82f6;
            border-radius: 12px;
            margin: 15px 0;
            background-color: #f8fafc;
        }

        .close-scan-btn {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .close-scan-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .scan-instruction {
            color: #64748b;
            font-size: 14px;
            margin: 10px 0;
        }

        /* tambahan */
        /* Tambahkan di bagian style */
        #preview {
            width: 100%;
            height: 300px;
            border: 2px dashed #3b82f6;
            border-radius: 12px;
            margin: 15px 0;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        #preview::before {
            content: "🔄 Memuat Kamera...";
            position: absolute;
            color: #64748b;
            font-size: 16px;
        }

        #preview video {
            border-radius: 12px;
        }

        /* Placeholder saat kamera loading */
        .camera-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }

        .camera-placeholder i {
            font-size: 48px;
            margin-bottom: 10px;
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <?php $this->load->view('Layout/sidebar_admin'); ?>

    <!-- 🔔 Notifikasi sukses setelah penghapusan -->
    <?php if ($this->session->flashdata('success')): ?>
        <div id="alert-success" style="background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:10px; border-radius:5px; margin:20px 280px 0 280px; text-align:center;">
            <?= $this->session->flashdata('success'); ?>
        </div>

        <script>
            // Hilangkan notifikasi otomatis setelah 3 detik
            setTimeout(() => {
                const alertBox = document.getElementById('alert-success');
                if (alertBox) alertBox.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

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

        <div class="data-management-section">
            <!-- Search & Filter -->
            <form action="<?= base_url('index.php/manajemen_kunjungan/'); ?>" method="get" class="search-filter-bar">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="keyword" placeholder="Cari nama, NIK, atau instansi..." value="<?= isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                </div>
                <button type="submit" class="filter-button">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>

            <!-- Table -->
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
                                                $status_class = 'approve';
                                                $status_text = 'Approved';
                                                break;
                                            case 'rejected':
                                                $status_class = 'reject';
                                                $status_text = 'Ditolak';
                                                break;
                                            case 'berkunjung':
                                                $status_class = 'berkunjung';
                                                $status_text = 'Berkunjung';
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
                                        <a href="<?= base_url('index.php/detail_kunjungan/detail/' . $row['visit_id']); ?>" class="action-button">
                                            <i class="fas fa-eye"></i> Details
                                        </a>
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

            <!-- Scan QR Button -->
            <div class="clearfix">
                <button id="scanButton" type="button" class="scan-qr-button">
                    <i class="fas fa-qrcode"></i> Scan QR Code
                </button>
            </div>
        </div>

        <!-- Modal Scanner -->
        <div id="qrModal" class="modal-scan">
            <div class="modal-content-scan">
                <h3 style="margin-bottom: 15px; color: #1e293b;">Scan QR Code Pengunjung</h3>
                <p class="scan-instruction">Arahkan kamera ke QR Code untuk memindai</p>
                <div id="preview"></div>
                <button id="closeModal" class="close-scan-btn">
                    <i class="fas fa-times"></i> Tutup Scanner
                </button>
            </div>
        </div>
    </main>

    <!-- LOAD LIBRARY HANYA SATU KALI DI BODY -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        // Tunggu sampai library benar-benar terload
        function waitForScannerLibrary(callback) {
            if (typeof Html5Qrcode !== 'undefined') {
                callback();
            } else {
                setTimeout(() => waitForScannerLibrary(callback), 100);
            }
        }

        // QR Code Scanner Functionality
        const scanButton = document.getElementById('scanButton');
        const modal = document.getElementById('qrModal');
        const closeModal = document.getElementById('closeModal');
        let html5QrCode = null;

        scanButton.addEventListener('click', async () => {
            try {
                // Tampilkan loading
                Swal.fire({
                    title: "Mengaktifkan Kamera...",
                    text: "Mohon tunggu sebentar",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                // Tampilkan modal
                modal.style.display = 'flex';

                // Inisialisasi scanner
                html5QrCode = new Html5Qrcode("preview");

                // Konfigurasi camera
                const config = {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0,
                    // Tambahkan konfigurasi experimental untuk performa
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    }
                };



                // Tutup SweetAlert loading
                Swal.close();


                // Coba dengan berbagai konfigurasi kamera
                try {
                    // Coba kamera belakang (environment) dulu
                    await html5QrCode.start({
                        facingMode: "environment"
                    }, config, onScanSuccess, onScanFailure);
                } catch (environmentError) {
                    console.log("Kamera belakang tidak tersedia, coba kamera depan:", environmentError);

                    // Fallback ke kamera depan (user)
                    await html5QrCode.start({
                        facingMode: "user"
                    }, config, onScanSuccess, onScanFailure);
                }

            } catch (err) {
                console.error("Error starting scanner:", err);
                modal.style.display = 'none';

                // Tampilkan error yang lebih spesifik
                let errorMessage = 'Pastikan Anda memberikan izin akses kamera dan perangkat Anda mendukung.';

                if (err.name === 'NotAllowedError') {
                    errorMessage = 'Izin akses kamera ditolak. Silakan izinkan akses kamera di pengaturan browser Anda.';
                } else if (err.name === 'NotFoundError') {
                    errorMessage = 'Tidak ada kamera yang ditemukan di perangkat Anda.';
                } else if (err.name === 'NotSupportedError') {
                    errorMessage = 'Browser Anda tidak mendukung akses kamera. Coba gunakan browser lain seperti Chrome atau Firefox.';
                } else if (err.name === 'NotReadableError') {
                    errorMessage = 'Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi lain yang mungkin menggunakan kamera.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Dapat Diakses',
                    text: 'Pastikan Anda memberikan izin akses kamera dan perangkat Anda mendukung.',
                    confirmButtonText: 'Mengerti'
                });
            }
        });

        // ... (fungsi onScanSuccess, onScanFailure, processCheckIn tetap sama)
        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code scanned:", decodedText);

            html5QrCode.stop().then(() => {
                modal.style.display = 'none';

                Swal.fire({
                    icon: 'success',
                    title: 'QR Code Berhasil Di-scan!',
                    html: `Data: <strong>${decodedText}</strong>`,
                    showCancelButton: true,
                    confirmButtonText: 'Proses Check-In',
                    cancelButtonText: 'Scan Lagi',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return processCheckIn(decodedText);
                    }
                }).then((result) => {
                    if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                        setTimeout(() => scanButton.click(), 500);
                    }
                });
            }).catch(err => {
                console.error("Error stopping scanner:", err);
            });
        }

        function onScanFailure(error) {
               // Error handling untuk scanning
    // Biasanya error ini terjadi terus menerus saat mencari QR code
    // Kita tidak perlu menampilkan error di sini, tapi bisa untuk debugging
    if (error && !error.toString().includes('NotFoundException')) {
        console.log("Scan error (biasanya normal):", error);
    }
        }

        function processCheckIn(qrData) {
            return fetch('<?= base_url('index.php/scan/process_checkin'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `qr_data=${encodeURIComponent(qrData)}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Check-In Berhasil!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                        return data;
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan saat proses check-in');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Check-In Gagal',
                        text: error.message || 'Terjadi kesalahan, silakan coba lagi'
                    });
                    throw error;
                });
        }

        // Tutup modal
        closeModal.addEventListener('click', () => {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => {
                    modal.style.display = 'none';
                }).catch(err => {
                    console.error("Error stopping scanner:", err);
                    modal.style.display = 'none';
                });
            } else {
                modal.style.display = 'none';
            }
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => {
                        modal.style.display = 'none';
                    });
                } else {
                    modal.style.display = 'none';
                }
            }
        });

        // Search functionality
        const searchInput = document.querySelector('input[name="keyword"]');
        searchInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                window.location.href = "<?= base_url('index.php/manajemen_kunjungan/'); ?>";
            }
        });
    </script>