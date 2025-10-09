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
                <?php if (($visit['status'] ?? '') == 'approved'): ?>
                    <span style="color:white; background:green; padding:4px 10px; border-radius:6px;">✔ Disetujui</span>
                <?php else: ?>
                    <span style="color:white; background:orange; padding:4px 10px; border-radius:6px;">⏳ Pending</span>
                <?php endif; ?>
            </p>
            <p><strong>Penanggung Jawab :</strong> <?= htmlspecialchars($visit['penanggung_jawab'] ?? '-') ?></p>
        </div>

        <div style="margin-top:20px; display:flex; gap:15px;">
            <a href="<?= base_url('kunjungan/download_qr/'.$visit['visit_id']) ?>" class="btn btn-primary">Download QR</a>
            <button onclick="window.print()" class="btn btn-secondary">Cetak</button>
        </div>
        <?php else: ?>
            <p style="margin-top:20px;">Belum ada data kunjungan.</p>
        <?php endif; ?>
    </div>
</div>
<style>
/* Reset & Body - WAJIB sama seperti dashboard */
* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
}

body {
    font-family: 'Inter', sans-serif;
    background: rgba(255, 255, 255, 0.9);
    min-height: 100vh;
    display: flex; /* INI YANG PENTING! */
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

/* Info Card - Light Blue Background */
.main-content > .form-container > div[style*="background:#f8fafc"],
.main-content > .form-container > div[style*="background: #f8fafc"] {
    background: #e8f2ff !important;
    padding: 25px !important;
    border-radius: 10px !important;
    margin-top: 15px !important;
    border: 1px solid #d1e3f9 !important;
}

/* Styling untuk paragraf dalam info card */
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
}

/* Print Styles */
@media print {
    .main-content {
        margin-left: 0;
    }
    
    .main-content .form-container > div[style*="margin-top"] {
        display: none !important;
    }
}
</style>