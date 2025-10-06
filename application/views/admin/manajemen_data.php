<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Resepsionis</title>

    <!-- CSS Terpisah -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ================================================= */
        /* 1. Variabel dan Gaya Dasar */
        /* ================================================= */
        :root {
            --color-sidebar-bg: #111b33;
            --color-main-bg: #f5f6fa;
            --color-text-light: #b4b4b4;
            --color-text-dark: #333;
            --color-primary: #5d5dff;
            --color-pending: #fddb00;
            --color-approve: #4caf50;
            --color-reject: #e53935;
            --color-border: #ddd;
            --color-background-light: #f7f7f7;
            --font-family-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family-main);
            background-color: var(--color-main-bg);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
            background-color: var(--color-main-bg);
            margin-left: 220px;
            /* Sesuaikan dengan lebar sidebar/ geser tabel ke kanan  */
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .title-container {
            display: flex;
            align-items: center;
        }

        .header-icon {
            font-size: 24px;
            margin-right: 10px;
            color: var(--color-text-dark);
        }

        .user-info {
            font-weight: 600;
            color: var(--color-text-dark);
        }

        /* ================================================= */
        /* 2. Konten Manajemen Data */
        /* ================================================= */
        .data-management-section {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* --- Search & Filter --- */
        .search-filter-bar {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            gap: 10px;
        }

        .search-input-group {
            display: flex;
            align-items: center;
            flex-grow: 1;
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 10px 15px;
            background-color: #fff;
        }

        .search-input-group i {
            color: var(--color-text-light);
            margin-right: 10px;
        }

        .search-input-group input {
            border: none;
            outline: none;
            flex-grow: 1;
            background-color: transparent;
            font-size: 1em;
        }

        .filter-button {
            background: none;
            border: 1px solid var(--color-border);
            color: var(--color-text-dark);
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .filter-button:hover {
            background-color: var(--color-background-light);
            border-color: #aaa;
        }

        /* --- Tabel --- */
        .data-table-container {
            width: 100%;
            overflow-x: auto;
            /* Tambahkan scroll horizontal jika tabel lebar */
            border: 1px solid var(--color-border);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 700px;
            /* Biar tabel tidak terlalu sempit */
        }

        table th,
        table td {
            padding: 14px 15px;
        }

        table thead {
            background-color: var(--color-background-light);
            border-bottom: 2px solid var(--color-border);
        }

        table th {
            color: var(--color-text-dark);
            font-weight: 700;
            font-size: 0.9em;
            text-transform: uppercase;
        }

        table tbody tr:nth-child(odd) {
            background-color: #fcfcfc;
        }

        table tbody tr:hover {
            background-color: #f0f0f0;
        }

        /* --- Status Badges --- */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            min-width: 70px;
            text-transform: capitalize;
        }

        .status-badge.pending {
            background-color: var(--color-pending);
            color: var(--color-text-dark);
            border: 1px solid #e6c800;
        }

        .status-badge.approve {
            background-color: var(--color-approve);
            color: #fff;
        }

        .status-badge.reject {
            background-color: var(--color-reject);
            color: #fff;
        }

        /* --- Tombol --- */
        .action-button {
            background-color: #e9e9e9;
            color: var(--color-text-dark);
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
        }

        .action-button:hover {
            background-color: #dcdcdc;
        }

        .scan-qr-button {
            float: right;
            clear: both;
            background-color: var(--color-primary);
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 25px;
            box-shadow: 0 6px 15px rgba(93, 93, 255, 0.4);
            transition: background-color 0.3s, transform 0.1s, box-shadow 0.3s;
        }

        .scan-qr-button i {
            margin-right: 8px;
        }

        .scan-qr-button:hover {
            background-color: #4f4fdd;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(93, 93, 255, 0.5);
        }

        /* Clear float */
        .data-management-section::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>

    <?php $this->load->view('layout/sidebar_admin'); ?>

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
            <div class="search-filter-bar">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <button class="filter-button"><i class="fas fa-filter"></i> Filter</button>
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
                                    <td><?= date('d/m/y', strtotime($row['TANGGAL'])); ?></td>
                                    <td><?= htmlspecialchars($row['INSTANSI']); ?></td>
                                    <td>
                                        <span class="status-badge <?= strtolower($row['INSTANSI']); ?>">
                                            <?= ucfirst($row['INSTANSI']); ?>
                                        </span>
                                    </td>
                                    <td><button class="action-button">Details</button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">Belum ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

            <button class="scan-qr-button"><i class="fas fa-qrcode"></i> Scan QR</button>
        </div>
    </main>

</body>

</html>