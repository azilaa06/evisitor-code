<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2 class="page-title">
            <i class="fas fa-list-alt"></i>
            Daftar Kunjungan
        </h2>
    </div>

    <!-- Visitors List -->
    <div class="visitors-list">
        <?php if(!empty($kunjungan)): ?>
            <?php foreach($kunjungan as $row): ?>
                <?php 
                    $status_class = '';
                    $status_text = '';
                    
                    // Mapping status sesuai database
                    switch($row->status) {
                        case 'approved':
                            $status_class = 'status-disetujui';
                            $status_text = 'Disetujui';
                            break;
                        case 'rejected':
                            $status_class = 'status-ditolak';
                            $status_text = 'Ditolak';
                            break;
                        case 'completed':
                            $status_class = 'status-completed';
                            $status_text = 'Selesai';
                            break;
                        case 'checked_in':
                            $status_class = 'status-checked-in';
                            $status_text = 'Check In';
                            break;
                        case 'checked_out':
                            $status_class = 'status-checked-out';
                            $status_text = 'Check Out';
                            break;
                        case 'cancelled':
                            $status_class = 'status-cancelled';
                            $status_text = 'Dibatalkan';
                            break;
                        case 'no_show':
                            $status_class = 'status-no-show';
                            $status_text = 'Tidak Hadir';
                            break;
                        case 'pending':
                        default:
                            $status_class = 'status-pending';
                            $status_text = 'Pending';
                            break;
                    }

                    // Format tanggal kunjungan dari scheduled_date
                    $tanggal_kunjungan = date('d M Y', strtotime($row->scheduled_date));
                    $waktu_kunjungan = date('H:i', strtotime($row->scheduled_date));
                ?>
                <div class="visitor-card"
                     onclick="window.location.href='<?= base_url('kunjungan/status_kunjungan/' . $row->visit_id) ?>'">
                    <div class="visitor-info">
                        <i class="fas fa-user-circle"></i>
                        <div class="info-content">
                            <div class="info-row">
                                <span class="visitor-name"><?= htmlspecialchars($row->fullname) ?></span>
                            </div>
                            <div class="info-row secondary">
                                <i class="fas fa-building"></i>
                                <span><?= htmlspecialchars($row->institution) ?></span>
                            </div>
                            <div class="info-row secondary">
                                <i class="fas fa-clock"></i>
                                <span><?= $tanggal_kunjungan ?> • <?= $waktu_kunjungan ?></span>
                            </div>
                        </div>
                    </div>
                    <span class="status-badge <?= $status_class ?>">
                        <?= $status_text ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data kunjungan</p>
                <small>Kunjungan yang Anda buat akan muncul di sini</small>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        margin: 0;
    }

    /* Main Layout */
    .main-content {
        margin-left: 280px;
        padding: 40px 35px;
        min-height: 100vh;
        background-color: #f8fafc;
        transition: margin-left 0.3s ease;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 24px;
        font-weight: 600;
        color: #0f172a;
    }

    .page-title i {
        color: #3b82f6;
        font-size: 22px;
    }

    /* List Container */
    .visitors-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    /* Card */
    .visitor-card {
        cursor: pointer;
        background: white;
        padding: 20px 25px;
        border-radius: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        transition: all 0.25s ease;
    }

    .visitor-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 14px rgba(59,130,246,0.15);
        border-color: #93c5fd;
    }

    .visitor-info {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }

    .visitor-info > i {
        font-size: 20px;
        color: #3b82f6;
        background: rgba(59,130,246,0.1);
        padding: 12px;
        border-radius: 12px;
        min-width: 44px;
        text-align: center;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
    }

    .info-row {
        display: flex;
        gap: 8px;
        align-items: baseline;
    }

    .info-row.secondary {
        font-size: 13px;
        color: #64748b;
        align-items: center;
        gap: 6px;
    }

    .info-row.secondary i {
        font-size: 12px;
    }

    .visitor-label {
        font-weight: 500;
        color: #64748b;
        font-size: 13px;
        min-width: 130px;
    }

    .visitor-name {
        color: #0f172a;
        font-size: 15px;
        font-weight: 500;
    }

    /* Status Badge */
    .status-badge {
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: capitalize;
        min-width: 95px;
        text-align: center;
        white-space: nowrap;
    }

    .status-disetujui {
        background: rgba(74, 222, 128, 0.2);
        color: #15803d;
    }

    .status-ditolak {
        background: rgba(248, 113, 113, 0.2);
        color: #b91c1c;
    }

    .status-completed {
        background: rgba(147, 197, 253, 0.3);
        color: #1e40af;
    }

    .status-pending {
        background: rgba(253, 224, 71, 0.3);
        color: #78350f;
    }

    .status-checked-in {
        background: rgba(196, 181, 253, 0.3);
        color: #5b21b6;
    }

    .status-checked-out {
        background: rgba(153, 246, 228, 0.3);
        color: #115e59;
    }

    .status-cancelled {
        background: rgba(251, 146, 60, 0.2);
        color: #9a3412;
    }

    .status-no-show {
        background: rgba(148, 163, 184, 0.2);
        color: #334155;
    }

    /* No Data */
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .no-data i {
        font-size: 40px;
        margin-bottom: 15px;
        color: #cbd5e1;
    }

    .no-data p {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .no-data small {
        font-size: 14px;
        color: #cbd5e1;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .main-content {
            margin-left: 250px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 25px 20px;
        }

        .visitor-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .visitor-info {
            width: 100%;
        }

        .status-badge {
            align-self: flex-start;
        }

        .info-row {
            flex-direction: column;
            gap: 4px;
        }

        .visitor-label {
            min-width: auto;
        }
    }
</style>