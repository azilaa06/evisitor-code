<style>
    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 220px;
        height: 100vh;
        background-color: #001233;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
        color: white;
    }

    .sidebar .logo img {
        width: 80px;
        margin-top: 10px;
        margin-bottom: 30px;
    }

    .sidebar a {
        width: 100%;
        padding: 12px 20px;
        text-decoration: none;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        transition: background 0.3s;
        justify-content: flex-start;
        box-sizing: border-box;
        overflow: hidden;
    }

    .sidebar a:hover {
        background-color: #003566;
    }

    .sidebar .logout {
        margin-top: auto;
        margin-bottom: 20px;
        padding-bottom: 50px;
        align-self: flex-start;
        width: 100%;
    }

    .sidebar .logout a {
        display: flex;
        align-items: center;
        padding: 15px 0;
        color: #cbd5e1;
        text-decoration: none;
        border-top: 1px solid #334155;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .sidebar .logout a:hover {
        color: white;
    }

    .sidebar a.active {
        background-color: #003566;
        /* biru terang */
        color: #fff;
        border-radius: 5px;
    }
    
</style>

<!-- Sidebar Navigation -->
<nav class="sidebar">
    <div class="logo">
        <img src="<?= base_url('assets/img/footer-logo.png'); ?>" alt="Logo" class="foto-kiri">
    </div>

    <a href="<?= base_url('index.php/dashboard'); ?>" class="<?= ($active_page == 'dashboard') ? 'active' : '' ?>">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="<?= base_url('index.php/manajemen_kunjungan'); ?>" class="<?= ($active_page == 'manajemen_data') ? 'active' : '' ?>">
        <i class="fas fa-database"></i> Manajemen Data
    </a>
    <a href="<?= base_url('index.php/profil'); ?>" class="<?= ($active_page == 'profil') ? 'active' : '' ?>">
        <i class="fas fa-user"></i> Profil
    </a>

    <div class="logout">
        <a href="<?= base_url('auth/logout'); ?>"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </div>
</nav>