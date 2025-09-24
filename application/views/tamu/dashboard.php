<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Visitor - Form Pendaftaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.9);;
            min-height: 100vh;
            display: flex;
        }
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 30px 40px;
        }

        .header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 30px;
        }

        .settings-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
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

        input[type="text"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
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

        .logout {
            position: absolute;
            bottom: 30px;
            left: 30px;
            right: 30px;
        }

        .logout a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #cbd5e1;
            text-decoration: none;
            border-top: 1px solid #334155;
        }

        .logout i {
            margin-right: 12px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }
            
            .main-content {
                margin-left: 250px;
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <div class="username">
            <?= $nama ?: $username ?>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-user-plus"></i>
                Form Pendaftaran
            </h2>
            
            <form id="registrationForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="namaLengkap">Nama Lengkap</label>
                        <input type="text" id="namaLengkap" name="nama_lengkap" placeholder="example123">
                    </div>
                    
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" placeholder="35XXXXXXXX">
                    </div>
                    
                    <div class="form-group">
                        <label for="noTelepon">No. Telepon</label>
                        <input type="tel" id="noTelepon" name="no_telepon" placeholder="08XXXXXXXX">
                    </div>
                    
                    <div class="form-group">
                        <label for="instansi">Instansi</label>
                        <input type="text" id="instansi" name="instansi">
                    </div>
                    
                    <div class="form-group">
                        <label for="tujuanKe">Tujuan ke</label>
                        <input type="text" id="tujuanKe" name="tujuan_ke" placeholder="Orang/Divisi">
                    </div>
                    
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" placeholder="dd/mm/yyyy">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="tujuan">Tujuan</label>
                        <textarea id="tujuan" name="tujuan" placeholder="Tujuan"></textarea>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="reset" class="btn btn-reset">Reset</button>
                    <button type="submit" class="btn btn-submit">Submit</button>
                </div>
            </form>
            
            <!-- Notice Section -->
            <div class="notice-section">
                <h3 class="notice-title">PERHATIAN</h3>
                <ul class="notice-list">
                    <li>Pengunjung wajib mengajukan surat permohonan resmi ke protokol@pintasbd.com.</li>
                    <li>Setelah disetujui, Pintasbd memberikan balasan berisi jadwal, prosedur kesehatan, dan perlengkapan yang harus dibawa oleh pengunjung.</li>
                    <li>Sebelum masuk area, semua peserta wajib ikut safety induction.</li>
                    <li>Kegiatan kunjungan biasanya meliputi paparan mengenai Pintasbd untuk ke area produksi/divisi tertentu, dan sesi foto.</li>
                    <li>Frekuensi kunjungan dibatasi maksimal 2-3 kali per minggu sesuai kondisi perusahaan.</li>
                    <li>Setelah kunjungan, ada sesi evaluasi dan feedback dari pengunjung serta evaluasi internal oleh Pintasbd.</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        // Form validation and submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = ['namaLengkap', 'nik', 'noTelepon', 'instansi', 'tujuanKe', 'tanggal', 'tujuan'];
            let isValid = true;
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.style.borderColor = '#ef4444';
                    isValid = false;
                } else {
                    input.style.borderColor = '#e5e7eb';
                }
            });
            
            if (isValid) {
                alert('Form berhasil disubmit!');
                // Here you would normally send the data to your CodeIgniter controller
                // Example: window.location.href = '<?= base_url("visitor/submit") ?>';
            } else {
                alert('Mohon lengkapi semua field yang diperlukan.');
            }
        });

        // Reset form
        document.querySelector('.btn-reset').addEventListener('click', function() {
            document.getElementById('registrationForm').reset();
            // Reset border colors
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.style.borderColor = '#e5e7eb';
            });
        });

        // Auto-resize textarea
        document.getElementById('tujuan').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // NIK validation (Indonesian ID number)
        document.getElementById('nik').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 16);
        });

        // Phone number validation
        document.getElementById('noTelepon').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 15);
        });
    </script>
</body>
</html>