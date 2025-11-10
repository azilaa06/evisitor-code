<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - Safety Induction</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: auto;
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            margin-left: 280px; /* Space for sidebar on desktop */
        }
        .container {
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            text-align: right;
            font-size: 14px;
        }
        .qr-section {
            text-align: center;
            padding: 40px 20px 20px;
            background: white;
        }
        .qr-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            color: #333;
            margin-bottom: 30px;
        }
        .qr-icon svg {
            width: 20px;
            height: 20px;
        }
        .qr-code-wrapper {
            display: inline-block;
            border: 3px solid #000;
            padding: 15px;
            border-radius: 8px;
            background: white;
            margin-bottom: 25px;
        }
        .qr-code-wrapper img {
            display: block;
            width: 200px;
            height: 200px;
        }
        
        /* Visitor Info Box */
        .visitor-info-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin: 0 20px 30px;
            text-align: left;
        }
        .visitor-info-title {
            font-weight: bold;
            font-size: 13px;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
            font-size: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 140px;
        }
        .info-value {
            color: #212529;
            flex: 1;
        }
        
        .content-section {
            background-color: #e8e8e8;
            padding: 30px 25px;
        }
        .content-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 8px;
            color: #333;
        }
        .company-name {
            font-size: 12px;
            margin-bottom: 20px;
            color: #555;
        }
        .main-title {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
        .subtitle {
            font-size: 11px;
            text-align: center;
            margin-bottom: 25px;
            color: #555;
            line-height: 1.5;
        }
        .list-section {
            font-size: 11px;
            line-height: 1.8;
            color: #333;
        }
        .list-section ol {
            padding-left: 20px;
        }
        .list-section li {
            margin-bottom: 6px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-approved {
            background: rgba(74, 222, 128, 0.2);
            color: #15803d;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            body {
                margin-left: 250px; /* Adjust for smaller sidebar */
            }
        }

        @media (max-width: 768px) {
            body {
                margin-left: 0; /* Remove sidebar space on mobile */
                padding: 15px;
            }
            
            .container {
                max-width: 600px;
                margin: 0 auto;
            }
            
            .header {
                padding: 12px 15px;
                font-size: 13px;
            }
            
            .qr-section {
                padding: 30px 15px 15px;
            }
            
            .qr-code-wrapper {
                padding: 12px;
            }
            
            .qr-code-wrapper img {
                width: 180px;
                height: 180px;
            }
            
            .visitor-info-box {
                margin: 0 15px 25px;
                padding: 15px;
            }
            
            .info-row {
                flex-direction: column;
                gap: 4px;
                padding: 10px 0;
            }
            
            .info-label {
                min-width: auto;
                margin-bottom: 2px;
            }
            
            .content-section {
                padding: 25px 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .qr-code-wrapper img {
                width: 160px;
                height: 160px;
            }
            
            .visitor-info-box {
                margin: 0 10px 20px;
                padding: 12px;
            }
            
            .content-section {
                padding: 20px 15px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
                margin-left: 0;
            }
            .container {
                box-shadow: none;
                max-width: 600px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <?php 
                // Prioritas: username dari session > visitor_username dari data > fullname
                if (isset($username) && !empty($username)) {
                    echo htmlspecialchars($username);
                } elseif (isset($visit['visitor_username']) && !empty($visit['visitor_username'])) {
                    echo htmlspecialchars($visit['visitor_username']);
                } elseif (isset($visit['fullname']) && !empty($visit['fullname'])) {
                    echo htmlspecialchars($visit['fullname']);
                } else {
                    echo 'Guest';
                }
            ?>
        </div>
        
        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                </svg>
                Lihat QR Code
            </div>
            
            <div class="qr-code-wrapper">
                <?php
                // PERBAIKAN: Generate QR token dengan prioritas yang lebih baik
                $qr_data = '';
                
                // Prioritas 1: qr_token yang sudah ada
                if (isset($qr_token) && !empty($qr_token)) {
                    $qr_data = $qr_token;
                } 
                // Prioritas 2: qr_token dari data visit
                elseif (isset($visit['qr_token']) && !empty($visit['qr_token'])) {
                    $qr_data = $visit['qr_token'];
                } 
                // Prioritas 3: Generate dari ID kunjungan (fallback untuk data lama)
                elseif (isset($visit['id']) && !empty($visit['id'])) {
                    // Generate unique token dari ID dan timestamp
                    $created_at = isset($visit['created_at']) ? $visit['created_at'] : date('Y-m-d H:i:s');
                    $qr_data = 'VISIT-' . $visit['id'] . '-' . substr(md5($visit['id'] . $created_at), 0, 8);
                } 
                // Prioritas 4: Generate dari NIK (jika ID tidak ada)
                elseif (isset($visit['id_number']) && !empty($visit['id_number'])) {
                    $qr_data = 'NIK-' . $visit['id_number'] . '-' . substr(md5($visit['id_number']), 0, 8);
                } 
                // Fallback terakhir
                else {
                    $qr_data = 'NO_VISIT_DATA_' . time();
                }
                ?>
                <img id="qrcode" 
                     src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qr_data); ?>" 
                     alt="QR Code"
                     onerror="this.src='https://via.placeholder.com/200x200?text=QR+Not+Available'">
            </div>
        </div>

        <!-- Visitor Information Box -->
        <div class="visitor-info-box">
            <div class="visitor-info-title">INFORMASI PENGUNJUNG</div>
            
            <div class="info-row">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">: <?= isset($visit['fullname']) ? htmlspecialchars($visit['fullname']) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">NIK</span>
                <span class="info-value">: <?= isset($visit['id_number']) ? htmlspecialchars($visit['id_number']) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">No. Telepon</span>
                <span class="info-value">: <?= isset($visit['phone']) ? htmlspecialchars($visit['phone']) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Instansi</span>
                <span class="info-value">: <?= isset($visit['institution']) ? htmlspecialchars($visit['institution']) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tujuan Ke</span>
                <span class="info-value">: <?= isset($visit['to_whom']) ? htmlspecialchars($visit['to_whom']) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Keperluan</span>
                <span class="info-value">: <?= isset($visit['purpose']) ? htmlspecialchars($visit['purpose']) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Kunjungan</span>
                <span class="info-value">: <?= isset($visit['scheduled_date']) ? date('d F Y, H:i', strtotime($visit['scheduled_date'])) : '-'; ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">: <span class="status-badge status-approved">Disetujui</span></span>
            </div>
        </div>
        
        <!-- Content Section -->
        <div class="content-section">
            <div class="content-title">SAFETY INDUCTION</div>
            <div class="company-name">PT. PINDAD</div>
            
            <div class="main-title">BERDASARKAN QUALITY SISTEM MANAGEMENT ISO</div>
            <div class="subtitle">HAL-HAL YANG HARUS DI PERHATIKAN</div>
            
            <div class="list-section">
                <ol>
                    <li>Ketahui jalur evakuasi dan titik kumpul saat darurat.</li>
                    <li>Ketahui lokasi APAR dan gunakan bila terjadi kebakaran.</li>
                    <li>Hindari menyentuh kabel atau peralatan listrik yang rusak.</li>
                    <li>Jaga kebersihan dan kerapian area kerja.</li>
                    <li>Atur posisi meja, kursi, dan layar komputer agar nyaman.</li>
                    <li>Gunakan masker bila sedang sakit.</li>
                    <li>Gunakan tangga untuk mengambil barang di tempat tinggi.</li>
                    <li>Laporkan segera kondisi berbahaya atau kerusakan.</li>
                    <li>Patuhi SOP dan hindari risiko yang membahayakan.</li>
                    <li>Selalu waspada dan bantu rekan tetap aman.</li>
                </ol>
            </div>
        </div>
    </div>
</body>
</html>