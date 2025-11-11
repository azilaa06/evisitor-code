<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $Admin_model
 * @property Manajemen_model $Manajemen_model   <-- tambahkan ini
 */

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load model disini
        $this->load->model('Admin_model');
        $this->load->model('Manajemen_model'); // tambahkan ini

        // Cek login
        if (!$this->session->userdata('status') || $this->session->userdata('status') != 'login') {
            redirect('index.php/auth/login_admin');
        }
    }

    // Halaman dashboard admin
    public function index()
    {
        $data['nama'] = $this->session->userdata('fullname');
        $data['active_page'] = 'dashboard'; // untuk highlight menu sidebar


        // ambil data jumlah dari model
        $data['total_ditolak'] = $this->Manajemen_model->count_by_status('rejected');
        $data['total_berkunjung'] = $this->Manajemen_model->get_count_sedang_berkunjung();
        $data['total_menunggu'] = $this->Manajemen_model->count_by_status('pending');
        $data['total_approved'] = $this->Manajemen_model->count_by_status('approved');
        $data['total_selesai'] = $this->Manajemen_model->get_count_telah_berkunjung();
        $data['total_today'] = count($this->Manajemen_model->get_today_visitors());



        // Load view (pastikan urutannya ini)
        $this->load->view('admin/dashboard', $data);
    }

    public function pengunjung_hari_ini()
    {
        $data['nama'] = $this->session->userdata('fullname');
        $data['active_page'] = 'dashboard';
        $data['title'] = 'Pengunjung Hari Ini';

        // Ambil data pengunjung hari ini dari model Manajemen_model
        $data['visits'] = $this->Manajemen_model->get_today_visitors(); // pastikan fungsi ini ada di Manajemen_model

        // Load view tampilan tabel
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/pengunjung_hari_ini', $data); // ini file view-nya
        $this->load->view('templates/footer');
    }
}
