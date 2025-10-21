<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
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
        
        // TAMBAHKAN: Cek apakah user sudah login (opsional tapi recommended)
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('auth');
        // }
    }

    // parameter $status bisa kosong atau diisi
    public function data($status = null)
    {
        // Ambil keyword dari form search (GET)
        $keyword = $this->input->get('keyword');

        // Jika ada keyword, pakai hasil search; kalau tidak, tampilkan semua
        if (!empty($keyword)) {
            $data['kunjungan'] = $this->Manajemen_model->search($keyword);
        } else {
            // Kalau kosong, tampilkan semua (atau berdasarkan status jika ada)
            if ($status) {
                $data['kunjungan'] = $this->Manajemen_model->get_by_status($status);
            } else {
                $data['kunjungan'] = $this->Manajemen_model->get_all();
            }
        }
        
        // Simpan keyword biar bisa ditampilkan lagi di input search
        $data['keyword'] = $keyword;

        // Simpan status biar tahu sedang filter status apa
        $data['status'] = $status ? ucfirst($status) : "Semua Data";

        // Highlight menu sidebar
        $data['active_page'] = 'manajemen_data';

        // Load view
        $this->load->view('Layouts/sidebar_admin', $data);
        $this->load->view('admin/manajemen_data', $data);
    }

    // Method index ini sepertinya tidak terpakai, bisa dihapus atau disesuaikan
    public function index()
    {
        // Redirect ke method data() aja
        redirect('admin/manajemen_data');
    }
}