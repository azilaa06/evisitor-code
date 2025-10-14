<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Manajemen_kunjungan
 * Controller untuk manajemen data kunjungan tanpa MY_Controller
 * 
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property CI_Form_validation $form_validation
 * @property Manajemen_model $Manajemen_model
 */
class Manajemen_kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Manajemen_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // Method index - tampilkan semua data
    public function index()
    {
        $data['title'] = 'Manajemen Kunjungan';
        $data['nama'] = $this->session->userdata('username');
        $data['kunjungan'] = $this->Manajemen_model->get_all();
        $this->load->view('admin/manajemen_data', $data);
    }

    // Method data - DIUBAH untuk handle STATUS dari dashboard
    public function data($status = null)
    {
        // Jika tidak ada parameter, redirect ke index
        if (!$status) {
            redirect('manajemen_kunjungan');
            return;
        }

        $data['title'] = 'Manajemen Kunjungan';
        $data['nama'] = $this->session->userdata('username');
        $data['status'] = $status;
        
        // Cek apakah parameter adalah ID (angka) atau STATUS (string)
        if (is_numeric($status)) {
            // Jika angka, anggap sebagai ID - tampilkan detail 1 kunjungan
            $data['kunjungan'] = $this->Manajemen_model->get_by_id($status);
            
            if (!$data['kunjungan']) {
                show_404();
                return;
            }
            
            // Load view detail (jika punya file detail.php)
            $this->load->view('admin/detail', $data);
        } else {
            // Jika string, anggap sebagai STATUS - tampilkan list berdasarkan status
            
            // Handle special case untuk "Pengunjung Hari Ini"
            if ($status == 'Pengunjung Hari Ini') {
                $data['kunjungan'] = $this->Manajemen_model->get_today_visitors();
            } else {
                // Gunakan method get_by_status yang sudah ada mapping di model
                $data['kunjungan'] = $this->Manajemen_model->get_by_status($status);
            }
            
            // Load view manajemen_data (list/tabel)
            $this->load->view('admin/manajemen_data', $data);
        }
    }

    // Method detail - untuk detail satu kunjungan (opsional, jika ingin URL lebih jelas)
    public function detail($id)
    {
        $data['title'] = 'Detail Kunjungan';
        $data['nama'] = $this->session->userdata('username');
        $data['kunjungan'] = $this->Manajemen_model->get_by_id($id);
        
        if (!$data['kunjungan']) {
            show_404();
            return;
        }
        
        // Load view detail
        $this->load->view('admin/detail', $data);
    }

    // Method untuk approve kunjungan
    public function approve($id)
    {
        $user_id = $this->session->userdata('user_id');
        $result = $this->Manajemen_model->update_status($id, 'approved', $user_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Kunjungan berhasil disetujui');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyetujui kunjungan');
        }
        
        redirect('manajemen_kunjungan');
    }

    // Method untuk reject kunjungan
    public function reject($id)
    {
        $user_id = $this->session->userdata('user_id');
        $result = $this->Manajemen_model->update_status($id, 'rejected', $user_id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Kunjungan berhasil ditolak');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak kunjungan');
        }
        
        redirect('manajemen_kunjungan');
    }

    // Method untuk check in
    public function checkin($id)
    {
        $result = $this->Manajemen_model->check_in($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Check-in berhasil');
        } else {
            $this->session->set_flashdata('error', 'Gagal check-in');
        }
        
        redirect('manajemen_kunjungan');
    }

    // Method untuk check out
    public function checkout($id)
    {
        $result = $this->Manajemen_model->check_out($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Check-out berhasil');
        } else {
            $this->session->set_flashdata('error', 'Gagal check-out');
        }
        
        redirect('manajemen_kunjungan');
    }

    // Method untuk search
    public function search()
    {
        $keyword = $this->input->post('keyword');
        
        $data['title'] = 'Hasil Pencarian';
        $data['nama'] = $this->session->userdata('username');
        $data['kunjungan'] = $this->Manajemen_model->search($keyword);
        
        $this->load->view('admin/manajemen_data', $data);
    }
}