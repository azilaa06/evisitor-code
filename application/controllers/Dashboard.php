<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Loader $load
 * @property Admin_model $Admin_model
 * @property Manajemen_model $Manajemen_model
 */

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        // Load model yang dibutuhkan
        $this->load->model('Manajemen_model');
        
        // Cek login
        if (!$this->session->userdata('status') || $this->session->userdata('status') != 'login') {
            redirect('index.php/auth/login_admin');
        }
    }

    // Halaman dashboard admin
    public function index()
    {
        $data['nama'] = $this->session->userdata('nama_lengkap');
        $data['active_page'] = 'dashboard'; // untuk highlight menu sidebar

        // Ambil data count untuk card dashboard
        $data['count_ditolak'] = $this->Manajemen_model->count_by_status('rejected');
        $data['count_berkunjung'] = $this->Manajemen_model->count_by_status('approved');
        $data['count_menunggu'] = $this->Manajemen_model->count_by_status('pending');
        $data['count_selesai'] = $this->Manajemen_model->count_by_status('completed');
        $data['count_today'] = $this->Manajemen_model->count_today_visitors();

        // Load view dashboard (sidebar sudah di-load di dalam dashboard.php)
        $this->load->view('admin/dashboard', $data);
    }

    // Method tambahan untuk refresh data dashboard via AJAX (opsional)
    public function get_statistics()
    {
        $stats = [
            'ditolak' => $this->Manajemen_model->count_by_status('rejected'),
            'berkunjung' => $this->Manajemen_model->count_by_status('approved'),
            'menunggu' => $this->Manajemen_model->count_by_status('pending'),
            'selesai' => $this->Manajemen_model->count_by_status('completed'),
            'today' => $this->Manajemen_model->count_today_visitors()
        ];
        
        header('Content-Type: application/json');
        echo json_encode($stats);
    }
}