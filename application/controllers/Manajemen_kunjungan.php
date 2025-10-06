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
    }

    // parameter $status bisa kosong atau diisi
    public function data($status = null)
    {
        
        // Status untuk judul halaman
        $data['status'] = $status ? ucfirst($status) : "Semua Data";

        // Kosongkan data kunjungan supaya tabel tetap tampil tapi tidak ada isi
        $data['kunjungan'] = [];
        $data['active_page'] = 'manajemen_data'; // untuk highlight menu sidebar

        // Load view
        $this->load->view('layout/sidebar_admin', $data);
        $this->load->view('admin/manajemen_data', $data);
    }
}