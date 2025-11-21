<?php
// ===================================================================
// FILE 1: Dashboard.php
// Location: application/controllers/Dashboard.php
// ===================================================================
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $Admin_model
 * @property Manajemen_model $Manajemen_model
 */
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load model
        $this->load->model('Admin_model');
        $this->load->model('Manajemen_model');
        
        // Cek login
        if (!$this->session->userdata('status') || $this->session->userdata('status') != 'login') {
            redirect('index.php/auth/login_admin');
        }
    }

    /**
     * Halaman dashboard resepsionis
     * FIXED: Count sesuai dengan requirement
     */
    public function index()
    {
        // Data user
        $data['nama'] = $this->session->userdata('fullname');
        $data['active_page'] = 'dashboard';
        
        // ========================================
        // 🔥 AMBIL DATA COUNT UNTUK CARD DASHBOARD
        // ========================================
        
        // Card 1: Permohonan Ditolak (status = rejected)
        $data['count_ditolak'] = $this->Manajemen_model->count_by_status('rejected');
        $data['total_ditolak'] = $data['count_ditolak'];
        
        // Card 2: Sedang Berkunjung (check_in ada, check_out kosong)
        $data['count_berkunjung'] = $this->Manajemen_model->get_count_sedang_berkunjung();
        $data['total_berkunjung'] = $data['count_berkunjung'];
        
        // Card 3: Menunggu Approval (status = pending)
        $data['count_menunggu'] = $this->Manajemen_model->count_by_status('pending');
        $data['total_menunggu'] = $data['count_menunggu'];
        
        // Card 4: Telah Berkunjung (check_out ada)
        $data['count_selesai'] = $this->Manajemen_model->get_count_telah_berkunjung();
        $data['total_selesai'] = $data['count_selesai'];
        
        // Card 5: Pengunjung Hari Ini (tanggal hari ini + status approved)
        $data['count_today'] = $this->Manajemen_model->count_today_visitors();
        $data['total_today'] = $data['count_today'];
        
        // Card 6: Approved (status = approved)
        $data['count_approved'] = $this->Manajemen_model->count_by_status('approved');
        $data['total_approved'] = $data['count_approved'];
        
        // ========================================
        // 🔥 LOAD VIEW
        // ========================================
        
        $this->load->view('admin/dashboard', $data);
    }

    /**
     * Halaman detail pengunjung hari ini
     */
    public function pengunjung_hari_ini()
    {
        $data['nama'] = $this->session->userdata('fullname');
        $data['active_page'] = 'dashboard';
        $data['title'] = 'Pengunjung Hari Ini';
        
        // Ambil data pengunjung hari ini
        $data['visits'] = $this->Manajemen_model->get_today_visitors();
        
        // Load view
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/pengunjung_hari_ini', $data);
        $this->load->view('templates/footer');
    }
}