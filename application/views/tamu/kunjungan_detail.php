<div class="main-content" style="margin-left:280px; padding:30px 40px;">
    <div class="form-container" style="background:#fff; border-radius:12px; padding:30px; box-shadow:0 5px 15px rgba(0,0,0,.1)">
        <h2 class="form-title"><i class="fas fa-clipboard-list"></i> Status Kunjungan</h2>

        <?php if ($visit): ?>
            <div style="background:#f8fafc; padding:20px; border-radius:10px; margin-top:15px;">
                <p><strong>Nama Lengkap :</strong> <?= htmlspecialchars($visit['fullname'] ?? '-') ?></p>
                <p><strong>NIK :</strong> <?= htmlspecialchars($visit['id_number'] ?? '-') ?></p>
                <p><strong>No. Telepon :</strong> <?= htmlspecialchars($visit['phone'] ?? '-') ?></p>
                <p><strong>Instansi :</strong> <?= htmlspecialchars($visit['institution'] ?? '-') ?></p>
                <p><strong>Tujuan ke :</strong> <?= htmlspecialchars($visit['to_whom'] ?? '-') ?></p>
                <p><strong>Tujuan :</strong> <?= htmlspecialchars($visit['purpose'] ?? '-') ?></p>
                <p><strong>Tanggal :</strong>
                    <?= isset($visit['scheduled_date']) ? date('l, d F Y', strtotime($visit['scheduled_date'])) : '-' ?>
                </p>
                <p><strong>Status :</strong>
<<<<<<< HEAD
                    <?php
                    // Bersihkan status dari spasi dan ubah ke lowercase
                    $status = strtolower(trim($visit['status'] ?? ''));

                    if ($status == 'approved'):
                    ?>
                        <span style="color:white; background:linear-gradient(135deg, #10b981 0%, #059669 100%); padding:6px 14px; border-radius:8px; font-weight:600; font-size:13px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 20px rgba(16,185,129,0.5), 0 0 30px rgba(16,185,129,0.3);">✔ Disetujui</span>
                    <?php elseif ($status == 'rejected'): ?>
                        <span style="color:white; background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding:6px 14px; border-radius:8px; font-weight:600; font-size:13px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 20px rgba(239,68,68,0.5), 0 0 30px rgba(239,68,68,0.3);">✖ Ditolak</span>
                    <?php elseif ($status == 'completed'): ?>
                        <span style="color:white; background:linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding:6px 14px; border-radius:8px; font-weight:600; font-size:13px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 20px rgba(6,182,212,0.5), 0 0 30px rgba(6,182,212,0.3);">✓ Selesai</span>
                    <?php elseif ($status == 'pending'): ?>
                        <span style="color:white; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding:6px 14px; border-radius:8px; font-weight:600; font-size:13px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 20px rgba(245,158,11,0.5), 0 0 30px rgba(245,158,11,0.3);">⏳ Pending</span>
                    <?php else: ?>
                        <span style="color:white; background:linear-gradient(135deg, #6b7280 0%, #4b5563 100%); padding:6px 14px; border-radius:8px; font-weight:600; font-size:13px; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 14px rgba(107,114,128,0.4);">? <?= ucfirst($status) ?></span>
=======
                    <?php if (($visit['status'] ?? '') == 'approved'): ?>
                        <span style="color:white; background:green; padding:4px 10px; border-radius:6px;">✔ Disetujui</span>
                    <?php else: ?>
                        <span style="color:white; background:orange; padding:4px 10px; border-radius:6px;">⏳ Pending</span>
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
                    <?php endif; ?>
                </p>
                <p><strong>Penanggung Jawab :</strong> <?= htmlspecialchars($visit['penanggung_jawab'] ?? '-') ?></p>
            </div>

            <div style="margin-top:20px; display:flex; gap:15px;">
<<<<<<< HEAD
                <a href="<?= base_url('kunjungan/download_qr/' . $visit['visit_id']) ?>" class="btn btn-primary">
                    <i class="fas fa-qrcode"></i> Download QR
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <a href="<?= base_url('kunjungan/daftar_kunjungan') ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
=======
                <a href="<?= base_url('index.php/kunjungan/download_qr/' . $visit['visit_id']) ?>" class="btn btn-primary">Download QR</a>
                <button onclick="window.print()" class="btn btn-secondary">Cetak</button>
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
            </div>
        <?php else: ?>
            <p style="margin-top:20px;">Belum ada data kunjungan.</p>
        <?php endif; ?>
    </div>
</div>
<<<<<<< HEAD

<style>
    /* Reset & Body */
=======
<style>
    /* Reset & Body - WAJIB sama seperti dashboard */
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: rgba(255, 255, 255, 0.9);
        min-height: 100vh;
        display: flex;
<<<<<<< HEAD
=======
        /* INI YANG PENTING! */
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    }

    /* Main Content Area */
    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 30px 40px;
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        max-width: 900px;
    }

    .form-title {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
    }

    .form-title i {
        margin-right: 10px;
        color: #3b82f6;
    }

<<<<<<< HEAD
    /* Info Card */
=======
    /* Info Card - Light Blue Background */
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    .main-content>.form-container>div[style*="background:#f8fafc"],
    .main-content>.form-container>div[style*="background: #f8fafc"] {
        background: #e8f2ff !important;
        padding: 25px !important;
        border-radius: 10px !important;
        margin-top: 15px !important;
        border: 1px solid #d1e3f9 !important;
    }

<<<<<<< HEAD
    /* Paragraf dalam info card */
=======
    /* Styling untuk paragraf dalam info card */
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    .main-content .form-container p {
        display: flex;
        padding: 8px 0;
        margin: 0;
        font-size: 14px;
        line-height: 1.8;
    }

    .main-content .form-container p strong {
        font-weight: 600;
        color: #1a202c;
        min-width: 180px;
        display: inline-block;
    }

<<<<<<< HEAD
=======
    /* Status Badge - Green Disetujui */
    .main-content .form-container span[style*="background:green"],
    .main-content .form-container span[style*="background: green"] {
        background: #22c55e !important;
        color: white !important;
        padding: 4px 12px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    /* Status Badge - Orange Pending */
    .main-content .form-container span[style*="background:orange"],
    .main-content .form-container span[style*="background: orange"] {
        background: #f59e0b !important;
        color: white !important;
        padding: 4px 12px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    /* Button Container */
    .main-content .form-container>div[style*="margin-top:20px"],
    .main-content .form-container>div[style*="margin-top: 20px"] {
        margin-top: 25px !important;
        display: flex !important;
        gap: 12px !important;
    }

    /* Button Styling */
    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 120px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .btn-primary {
        background: #3b82f6 !important;
        color: white !important;
    }

    .btn-primary:hover {
        background: #2563eb !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background: #6b7280 !important;
        color: white !important;
    }

    .btn-secondary:hover {
        background: #4b5563 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

<<<<<<< HEAD
    .btn-back {
        background: #94a3b8 !important;
        color: white !important;
    }

    .btn-back:hover {
        background: #64748b !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(148, 163, 184, 0.3);
    }

=======
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    /* Responsive Mobile */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .form-container {
            padding: 25px !important;
        }

        .main-content .form-container p {
            flex-direction: column;
            gap: 2px;
        }

        .main-content .form-container p strong {
            min-width: auto;
        }

        .btn {
            width: 100%;
        }
<<<<<<< HEAD

        .main-content .form-container>div[style*="display:flex"] {
            flex-direction: column;
        }
=======
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    }

    /* Print Styles */
    @media print {
<<<<<<< HEAD
        body {
            margin: 0;
            padding: 20px;
            background: white;
        }

        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .form-container {
            box-shadow: none !important;
            padding: 20px !important;
        }

        /* Sembunyikan tombol saat print */
        .main-content .form-container>div[style*="margin-top"]:last-child,
        .main-content .form-container>div[style*="display:flex"] {
            display: none !important;
        }

        .form-title {
            margin-bottom: 20px !important;
        }
=======
        .main-content {
            margin-left: 0;
        }

        .main-content .form-container>div[style*="margin-top"] {
            display: none !important;
        }
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
    }
</style>