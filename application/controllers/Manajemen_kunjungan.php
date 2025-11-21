<?php
// Location: application/controllers/Manajemen_kunjungan.php
// ===================================================================
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Manajemen Kunjungan
 * FIXED VERSION - Sesuai Requirement & Database
 * 
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Manajemen_model $Manajemen_model
 */
class Manajemen_kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Manajemen_model');
        
        // Cek login
        if ($this->session->userdata('status') != 'login') {
            redirect('auth/login_admin');
        }
    }

    /**
     * Method Index - Tampilkan semua data kunjungan
     */
    public function index()
    {
        $this->data(); // Panggil method data tanpa parameter
    }

    /**
     * Method Data - Tampilkan data kunjungan dengan filter
     * FIXED: Routing sesuai requirement
     * 
     * @param string|null $status - Filter status (optional)
     */
    public function data($status = null)
    {
        // Ambil keyword search dari GET parameter
        $keyword = $this->input->get('keyword');
        
        // Inisialisasi data untuk view
        $view_data = [
            'kunjungan' => [],
            'status' => 'Semua Data',
            'keyword' => $keyword,
            'nama' => $this->session->userdata('fullname') ?? 'Admin',
            'active_page' => 'manajemen_data'
        ];

        // ========================================
        // 🔥 LOGIC PENGAMBILAN DATA
        // ========================================
        
        // 1. Jika ada keyword search
        if (!empty($keyword)) {
            $view_data['kunjungan'] = $this->Manajemen_model->search($keyword);
            $view_data['status'] = 'Hasil Pencarian: "' . htmlspecialchars($keyword) . '"';
        }
        // 2. Filter berdasarkan status
        elseif ($status) {
            $status_lower = strtolower($status);
            
            // Route ke method yang sesuai
            switch ($status_lower) {
                case 'ditolak':
                case 'rejected':
                    $view_data['kunjungan'] = $this->Manajemen_model->get_ditolak();
                    $view_data['status'] = 'Permohonan Ditolak';
                    break;
                    
                case 'berkunjung':
                    $view_data['kunjungan'] = $this->Manajemen_model->get_sedang_berkunjung();
                    $view_data['status'] = 'Sedang Berkunjung';
                    break;
                    
                case 'menunggu':
                case 'pending':
                    $view_data['kunjungan'] = $this->Manajemen_model->get_menunggu();
                    $view_data['status'] = 'Menunggu Approval';
                    break;
                    
                case 'selesai':
                case 'completed':
                    $view_data['kunjungan'] = $this->Manajemen_model->get_telah_berkunjung();
                    $view_data['status'] = 'Telah Berkunjung';
                    break;
                    
                case 'pengunjung_hari_ini':
                    $view_data['kunjungan'] = $this->Manajemen_model->get_today_visitors();
                    $view_data['status'] = 'Pengunjung Hari Ini';
                    break;
                    
                case 'approved':
                    $view_data['kunjungan'] = $this->Manajemen_model->get_approved();
                    $view_data['status'] = 'Approved';
                    break;
                    
                default:
                    $view_data['kunjungan'] = $this->Manajemen_model->get_all();
                    $view_data['status'] = 'Semua Data';
            }
        }
        // 3. Default: Tampilkan semua data
        else {
            $view_data['kunjungan'] = $this->Manajemen_model->get_all();
        }

        // Load view dengan data
        $this->load->view('admin/manajemen_data', $view_data);
    }

    /**
     * Method untuk filter berdasarkan status (alternatif routing)
     */
    public function menunggu()
    {
        $this->data('menunggu');
    }

    public function berkunjung()
    {
        $this->data('berkunjung');
    }

    public function ditolak()
    {
        $this->data('ditolak');
    }

    public function selesai()
    {
        $this->data('selesai');
    }

    public function hari_ini()
    {
        $this->data('Pengunjung_Hari_Ini');
    }
    
    public function approved()
    {
        $this->data('approved');
    }
    
    /**
     * Detail kunjungan
     */
    public function detail($visit_id)
    {
        $data['visit'] = $this->Manajemen_model->get_by_id($visit_id);
        $data['nama'] = $this->session->userdata('fullname');
        $data['active_page'] = 'manajemen_data';
        
        if (!$data['visit']) {
            show_404();
        }
        
        $this->load->view('admin/detail_kunjungan', $data);
    }
    
    /**
     * Update status permohonan
     */
    public function update_status()
    {
        $visit_id = $this->input->post('visit_id');
        $status = $this->input->post('status');
        $user_id = $this->session->userdata('user_id');
        
        $result = $this->Manajemen_model->update_status($visit_id, $status, $user_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Status berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal update status');
        }
        
        redirect('manajemen_kunjungan/data');
    }
    
    /**
     * Check in pengunjung
     */
    public function check_in($visit_id)
    {
        $result = $this->Manajemen_model->check_in($visit_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Check-in berhasil');
        } else {
            $this->session->set_flashdata('error', 'Gagal check-in');
        }
        
        redirect('manajemen_kunjungan/data');
    }
    
    /**
     * Check out pengunjung
     */
    public function check_out($visit_id)
    {
        $result = $this->Manajemen_model->check_out($visit_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Check-out berhasil');
        } else {
            $this->session->set_flashdata('error', 'Gagal check-out');
        }
        
        redirect('manajemen_kunjungan/data');
    }
    
    /**
     * Delete kunjungan
     */
    public function delete($visit_id)
    {
        $result = $this->Manajemen_model->delete_visitor($visit_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data');
        }
        
        redirect('manajemen_kunjungan/data');
    }
}