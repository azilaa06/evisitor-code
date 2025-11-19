<?php
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
     * GABUNGAN: Load data count dari MAIN + structure dari TIA
     */
    public function index()
    {
        // Data user
        $data['nama'] = $this->session->userdata('fullname');
        $data['active_page'] = 'dashboard';
        
        // ========================================
        // 🔥 AMBIL DATA COUNT UNTUK CARD DASHBOARD
        // ========================================
        
        // Card 1: Permohonan Ditolak
        $data['count_ditolak'] = $this->Manajemen_model->count_by_status('rejected');
        $data['total_ditolak'] = $data['count_ditolak']; // Untuk kompatibilitas view
        
        // Card 2: Sedang Berkunjung (sudah check_in tapi belum check_out)
        $data['count_berkunjung'] = $this->Manajemen_model->get_count_sedang_berkunjung();
        $data['total_berkunjung'] = $data['count_berkunjung']; // Untuk kompatibilitas view
        
        // Card 3: Menunggu Approval
        $data['count_menunggu'] = $this->Manajemen_model->count_by_status('pending');
        $data['total_menunggu'] = $data['count_menunggu']; // Untuk kompatibilitas view
        
        // Card 4: Telah Berkunjung (sudah checkout)
        $data['count_selesai'] = $this->Manajemen_model->get_count_telah_berkunjung();
        $data['total_selesai'] = $data['count_selesai']; // Untuk kompatibilitas view
        
        // Card 5: Pengunjung Hari Ini
        $data['count_today'] = count($this->Manajemen_model->get_today_visitors());
        $data['total_today'] = $data['count_today']; // Untuk kompatibilitas view
        
        // Card 6: Approved (semua yang statusnya approved)
        $data['count_approved'] = $this->Manajemen_model->count_by_status('approved');
        $data['total_approved'] = $data['count_approved']; // Untuk kompatibilitas view
        
        // ========================================
        // 🔥 LOAD VIEW
        // ========================================
        
        // Load sidebar dan dashboard
        $this->load->view('Layouts/sidebar_admin', $data);
        $this->load->view('admin/dashboard', $data);
    }

    /**
     * Halaman detail pengunjung hari ini
     * FITUR DARI BRANCH MAIN
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
?>