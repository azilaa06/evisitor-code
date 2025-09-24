<style>
    .sidebar {
        width: 280px;
        background: #000B23;
        min-height: 100vh;
        padding: 20px 0;
        position: fixed;
        left: 0;
        top: 0;
        font-family: 'Inter', sans-serif;
    }

    .logo {
        display: flex;
        align-items: center;
        padding: 5px 30px;
        margin-bottom: 30px;
        transform: scale(0.5);
    }

    .logo i {
        margin-right: 10px;
    }

    .menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .menu-item {
        margin: 8px 0;
    }

    .menu-item a {
        display: flex;
        align-items: center;
        padding: 15px 30px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        border: none;
    }

    .menu-item.active a {
        background: #3b82f6;
        color: white;
        border-right: 4px solid #60a5fa;
    }

    .menu-item a:hover {
        background: #3b82f6;
        color: white;
    }

    .menu-item i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    .logout {
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        padding: 0 30px;
    }

    .logout a {
        display: flex;
        align-items: center;
        padding: 15px 0;
        color: #cbd5e1;
        text-decoration: none;
        border-top: 1px solid #334155;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .logout a:hover {
        color: white;
    }

    .logout i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
    }

    /* Responsive untuk tablet dan mobile */
    @media (max-width: 1024px) {
        .sidebar {
            width: 250px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
    }

    /* Tombol Toggle */
    #toggleSidebar {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 10px 12px;
        cursor: pointer;
        display: none; /* default sembunyi */
    }

    /* Overlay */
    #sidebarOverlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        z-index: 900;
    }
</style>

<!-- Tombol Toggle Sidebar -->
<button id="toggleSidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay -->
<div id="sidebarOverlay"></div>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <!-- Logo Section -->
    <div class="logo">
        <img src="<?= base_url('assets/img/footer-logo.png'); ?>">
    </div>
    
    <!-- Menu Items -->
    <ul class="menu">
        <li class="menu-item <?= ($active_page ?? '') == 'dashboard' ? 'active' : ''; ?>">
            <a href="<?= base_url('tamu/dashboard'); ?>">
                <i class="fas fa-user-plus"></i>
                Form Pendaftaran
            </a>
        </li>
        <li class="menu-item <?= ($active_page ?? '') == 'status_kunjungan' ? 'active' : ''; ?>">
            <a href="<?= base_url('tamu/status_kunjungan'); ?>">
                <i class="fas fa-eye"></i>
                Lihat Status Kunjungan
            </a>
        </li>
        <li class="menu-item <?= ($active_page ?? '') == 'qr_code' ? 'active' : ''; ?>">
            <a href="<?= base_url('tamu/qr_code'); ?>">
                <i class="fas fa-qrcode"></i>
                Lihat QR Code
            </a>
        </li>
    </ul>
    
    <!-- Logout Section -->
    <div class="logout">
        <a href="<?= base_url('auth/logout'); ?>">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</nav>

<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function checkScreen() {
        if (window.innerWidth <= 768) {
            toggleBtn.style.display = 'block';
        } else {
            toggleBtn.style.display = 'none';
            sidebar.classList.remove('active');
            overlay.style.display = 'none';
        }
    }

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.style.display = 'none';
    });

    window.addEventListener('resize', checkScreen);
    window.addEventListener('load', checkScreen);
</script>
