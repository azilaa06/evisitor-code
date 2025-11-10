<div class="main-content" style="margin-left:280px; padding:30px 40px;">
    <div class="form-container" style="background:#fff; border-radius:12px; padding:30px; box-shadow:0 5px 15px rgba(0,0,0,.1)">
        <a href="<?= base_url('kunjungan/daftar_kunjungan') ?>" class="btn-back-top">
            <i class="fas fa-arrow-left"></i>
        </a>
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
                <?php endif; ?>
            </p>
            <p><strong>Penanggung Jawab :</strong> <?= htmlspecialchars($visit['penanggung_jawab'] ?? '-') ?></p>
        </div>

        <!-- QR Code Section -->
        <?php if ($status == 'approved'): ?>
            <div class="qr-section">
                <div class="qr-card">
                    <div class="qr-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <div class="qr-content">
                        <h3>QR Code Tersedia!</h3>
                        <p>Kunjungan Anda telah disetujui. Silakan lihat QR Code untuk check-in.</p>
                    </div>
                    <a href="<?= base_url('qr_code/view/' . $visit['visit_id']); ?>" class="btn-view-qr">
                        <i class="fas fa-eye"></i>
                        Lihat QR Code
                    </a>
                </div>
            </div>
        <?php elseif ($status == 'pending'): ?>
            <div class="qr-section pending">
                <div class="qr-card">
                    <div class="qr-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="qr-content">
                        <h3>Menunggu Persetujuan</h3>
                        <p>QR Code akan tersedia setelah kunjungan Anda disetujui oleh admin.</p>
                    </div>
                </div>
            </div>
        <?php elseif ($status == 'rejected'): ?>
            <div class="qr-section rejected">
                <div class="qr-card">
                    <div class="qr-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="qr-content">
                        <h3>Kunjungan Ditolak</h3>
                        <p>Maaf, kunjungan Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div style="margin-top:20px; display:flex; gap:15px;">
            <?php if ($status == 'approved'): ?>
            <a href="<?= base_url('kunjungan/download_qr/'.$visit['visit_id']) ?>" class="btn btn-primary">
                <i class="fas fa-download"></i> Download QR
            </a>
            <?php endif; ?>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
        <?php else: ?>
            <p style="margin-top:20px;">Belum ada data kunjungan.</p>
        <?php endif; ?>
    </div>
</div>

<style>
/* Reset & Body */
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
    position: relative;
}

.btn-back-top {
    position: absolute;
    top: 30px;
    left: 30px;
    width: 40px;
    height: 40px;
    background: #f1f5f9;
    color: #475569;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
}

.btn-back-top:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    transform: translateX(-3px);
}

.form-title {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    margin-left: 50px;
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
}

.form-title i { 
    margin-right: 10px; 
    color: #3b82f6; 
}

/* Info Card */
.main-content > .form-container > div[style*="background:#f8fafc"],
.main-content > .form-container > div[style*="background: #f8fafc"] {
    background: #e8f2ff !important;
    padding: 25px !important;
    border-radius: 10px !important;
    margin-top: 15px !important;
    border: 1px solid #d1e3f9 !important;
}

/* Paragraf dalam info card */
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

/* QR Code Section - HORIZONTAL LAYOUT */
.qr-section {
    margin-top: 25px;
    padding: 25px;
    background: #e8f2ff;
    border: 1px solid #d1e3f9;
    border-radius: 12px;
    animation: fadeInUp 0.6s ease;
}

.qr-section.pending {
    background: #fef3c7;
    border-color: #fde68a;
}

.qr-section.rejected {
    background: #fee2e2;
    border-color: #fecaca;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.qr-card {
    display: flex;
    align-items: center;
    gap: 25px;
}

.qr-icon {
    width: 70px;
    height: 70px;
    flex-shrink: 0;
    background: #3b82f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-section.pending .qr-icon {
    background: #f59e0b;
}

.qr-section.rejected .qr-icon {
    background: #ef4444;
}

.qr-icon i {
    font-size: 32px;
    color: white;
}

.qr-content {
    flex: 1;
}

.qr-card h3 {
    color: #1e293b;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 6px;
}

.qr-card p {
    color: #64748b;
    font-size: 14px;
    margin: 0;
    line-height: 1.5;
    display: block;
    padding: 0;
}

.btn-view-qr {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: #3b82f6;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-view-qr:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.qr-section.pending .btn-view-qr {
    background: #f59e0b;
}

.qr-section.pending .btn-view-qr:hover {
    background: #d97706;
}

.qr-section.rejected .btn-view-qr {
    background: #ef4444;
}

.qr-section.rejected .btn-view-qr:hover {
    background: #dc2626;
}

/* Button Container */
.main-content .form-container > div[style*="margin-top:20px"],
.main-content .form-container > div[style*="margin-top: 20px"] {
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

.btn-back {
    background: #94a3b8 !important;
    color: white !important;
}

.btn-back:hover {
    background: #64748b !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(148, 163, 184, 0.3);
}

/* Responsive Mobile */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    
    .form-container {
        padding: 25px !important;
        padding-top: 60px !important;
    }

    .btn-back-top {
        top: 20px;
        left: 20px;
    }

    .form-title {
        margin-left: 0;
    }

    .main-content .form-container p {
        flex-direction: column;
        gap: 2px;
    }

    .main-content .form-container p strong {
        min-width: auto;
    }

    .qr-section {
        padding: 25px 20px;
    }

    .qr-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .qr-icon {
        width: 70px;
        height: 70px;
    }

    .qr-icon i {
        font-size: 30px;
    }

    .qr-card h3 {
        font-size: 18px;
    }

    .btn-view-qr {
        width: 100%;
        justify-content: center;
    }

    .btn {
        width: 100%;
    }
    
    .main-content .form-container > div[style*="display:flex"] {
        flex-direction: column;
    }
}

/* Print Styles */
@media print {
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

    /* Sembunyikan QR Section dan tombol saat print */
    .qr-section,
    .main-content .form-container > div[style*="margin-top"]:last-child,
    .main-content .form-container > div[style*="display:flex"] {
        display: none !important;
    }
    
    .form-title {
        margin-bottom: 20px !important;
    }
}
</style>