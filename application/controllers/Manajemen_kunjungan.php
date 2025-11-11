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

        // Ambil keyword dari form search (GET)
        $keyword = $this->input->get('keyword');

        // Status untuk judul halaman
        $data['status'] = $status ? ucfirst($status) : "Semua Data";


        // Jika ada keyword, pakai hasil search; kalau tidak, tampilkan semua
        if (!empty($keyword)) {
<<<<<<< HEAD
            $data['kunjungan'] = $this->Manajemen_model->search($keyword);
        } else {
            // Kalau kosong, tampilkan semua (atau berdasarkan status jika ada)
            if ($status) {
                $data['kunjungan'] = $this->Manajemen_model->get_by_status($status);
            } else {
                $data['kunjungan'] = $this->Manajemen_model->get_all();
=======
        $data['kunjungan'] = $this->Manajemen_model->search($keyword);

        }else {
            // Kalau kosong, tampilkan semua (atau berdasarkan status jika ada)
            if ($status) {
            $data['kunjungan'] = $this->Manajemen_model->get_by_status($status);
        } else {
            $data['kunjungan'] = $this->Manajemen_model->get_all();
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
            }
        }
        //Simpan keyword biar bisa ditampilkan lagi di input search
        $data['keyword'] = $keyword;

<<<<<<< HEAD
        // Simpan status biar tahu sedang filter status apa
=======
         // Simpan status biar tahu sedang filter status apa
>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        $data['status'] = $status;

        //Highlight menu sidebar
        $data['active_page'] = 'manajemen_data'; // untuk highlight menu sidebar

        // Load view
        $this->load->view('layout/sidebar_admin', $data);
        $this->load->view('admin/manajemen_data', $data);
    }


<<<<<<< HEAD
    //menampilkan halaman utama (view) dari menu manajemen data dengan fitur pencarian (search).
    public function index()
    {
        $keyword = $this->input->get('keyword'); // ambil dari form search
        $data['visits'] = $this->Manajemen_model->search($keyword); //Di sini controller memanggil fungsi search() dari model Manajemen_model
        $data['keyword'] = $keyword; //Ini menyimpan kata kunci yang tadi diinput

        //Bagian ini menampilkan halaman web dengan menggabungkan beberapa view file:
=======
    public function index()
    {
        $keyword = $this->input->get('keyword'); // ambil dari form search
        $data['visits'] = $this->Manajemen_model->search($keyword);
        $data['keyword'] = $keyword;

>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('manajemen_data', $data);
        $this->load->view('templates/footer');
    }
}
<<<<<<< HEAD
=======

>>>>>>> 4d35f43bec7cc0cd6247a03b34ce725a2f929514
