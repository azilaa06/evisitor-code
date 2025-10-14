<!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- Tombol Toggle Sidebar -->
<button id="toggleSidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay -->
<div id="sidebarOverlay"></div>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <!-- Logo -->
    <div class="logo">
        <img src="<?= base_url('assets/img/footer-logo.png'); ?>" alt="Logo">
    </div>

    <!-- Menu Items -->
    <ul class="menu">
        <li class="menu-item <?= ($active_page ?? '') == 'dashboard' ? 'active' : ''; ?>">
            <a href="<?= base_url('index.php/dashboard'); ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="menu-item <?= ($active_page ?? '') == 'manajemen_data' ? 'active' : ''; ?>">
            <a href="<?= base_url('index.php/manajemen_kunjungan'); ?>">
                <i class="fas fa-database"></i> Manajemen Data
            </a>
        </li>
        <li class="menu-item <?= ($active_page ?? '') == 'profil' ? 'active' : ''; ?>">
            <a href="<?= base_url('index.php/profil'); ?>">
                <i class="fas fa-user"></i> Profil
            </a>
        </li>
    </ul>

    <!-- Logout -->
    <div class="logout">
        <a href="<?= base_url('auth/logout'); ?>">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </div>
</nav>

<!-- Script Sidebar -->
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

<!-- CSS Modern -->
<style>
    /* Modern Sidebar Styling - Gaya Mirip User */
    .sidebar {
        width: 260px;
        background: #000B23;
        min-height: 100vh;
        padding: 20px 0;
        position: fixed;
        left: 0;
        top: 0;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }

    .logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 25px;
        transform: scale(0.6);
    }

    .logo img {
        width: 100px;
    }

    .menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .menu-item {
        margin: 8px 15px;
    }

    .sidebar .menu-item a,
    .sidebar .logout a {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 14px;
        border-radius: 12px;
        position: relative;
    }

    /* Hover effect */
    .menu-item a:hover {
        background: rgba(59, 130, 246, 0.15);
        color: #93c5fd;
        transform: translateX(4px);
    }

    /* Active menu */
    .menu-item.active a {
        background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(37,99,235,0.25));
        color: #fff;
        border-left: 4px solid #3b82f6;
        padding-left: 16px;
        box-shadow: 0 4px 12px rgba(59,130,246,0.2);
    }

    .menu-item.active a::after {
        content: '';
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 8px #60a5fa;
    }

    /* Icon */
    .sidebar .menu-item i,
    .sidebar .logout i {
        margin-right: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .menu-item a:hover i {
        color: #93c5fd;
        transform: scale(1.1);
    }

    .menu-item.active a i {
        color: #60a5fa;
    }

    /* Logout section */
    .logout {
        position: absolute;
        bottom: 30px;
        left: 15px;
        right: 15px;
    }

    .logout a {
        border-top: 1px solid #334155;
        padding-top: 20px;
        color: #cbd5e1;
    }

    .logout a:hover {
        background: rgba(239,68,68,0.12);
        color: #fca5a5;
        transform: translateX(4px);
    }

    .logout a:hover i {
        color: #fca5a5;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .sidebar {
            width: 240px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            z-index: 1000;
        }

        .sidebar.active {
            transform: translateX(0);
        }
    }

    #toggleSidebar {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1100;
        background: #3b82f6;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 12px;
        cursor: pointer;
        display: none;
    }

    #sidebarOverlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 900;
    }
</style>
