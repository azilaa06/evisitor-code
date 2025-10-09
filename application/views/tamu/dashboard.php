<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Visitor - Form Kunjungan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 30px 40px;
        }

        .username {
            position: absolute;
            top: 20px;
            right: 60px;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 800px;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="tel"],
        input[type="date"],
        textarea {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .btn-reset {
            background: #ef4444;
            color: white;
        }

        .btn-reset:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-submit {
            background: #3b82f6;
            color: white;
        }

        .btn-submit:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .notice-section {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
        }

        .notice-title {
            font-weight: 600;
            color: #ef4444;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .notice-list {
            list-style: none;
            color: #475569;
            font-size: 13px;
            line-height: 1.6;
        }

        .notice-list li {
            margin-bottom: 8px;
            position: relative;
            padding-left: 15px;
        }

        .notice-list li::before {
            content: "•";
            color: #ef4444;
            position: absolute;
            left: 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <main class="main-content">
        <div class="username">
            <?= $nama ?? $username ?? 'Pengunjung'; ?>
        </div>

        <!-- ✅ Flashdata Notification -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-user-plus"></i>
                Form Pendaftaran Kunjungan
            </h2>

            <!-- ✅ arahkan ke controller yang benar -->
            <form id="registrationForm" method="post" action="<?= base_url('index.php/kunjungan/submit') ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="fullname">Nama Lengkap</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="id_number">NIK</label>
                        <input type="text" id="id_number" name="id_number" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="institution">Instansi</label>
                        <input type="text" id="institution" name="institution" required>
                    </div>
                    <div class="form-group">
                        <label for="to_whom">Tujuan ke</label>
                        <input type="text" id="to_whom" name="to_whom" required>
                    </div>
                    <div class="form-group">
                        <label for="scheduled_date">Tanggal</label>
                        <input type="date" id="scheduled_date" name="scheduled_date" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="purpose">Tujuan</label>
                        <textarea id="purpose" name="purpose" required></textarea>
                    </div>
                </div>

                <div class="button-group">
                    <button type="reset" class="btn btn-reset">Reset</button>
                    <button type="submit" class="btn btn-submit">Submit</button>
                </div>
            </form>

            <div class="notice-section">
                <h3 class="notice-title">PERHATIAN</h3>
                <ul class="notice-list">
                    <li>Pengunjung wajib mengajukan surat permohonan resmi ke protokol@pintasbd.com.</li>
                    <li>Setelah disetujui, Pintasbd memberikan balasan berisi jadwal dan prosedur.</li>
                    <li>Sebelum masuk area, semua peserta wajib ikut safety induction.</li>
                    <li>Kegiatan kunjungan biasanya meliputi paparan, kunjungan area, dan sesi foto.</li>
                    <li>Frekuensi kunjungan dibatasi maksimal 2-3 kali per minggu.</li>
                </ul>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>