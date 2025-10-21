<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $admin_model
 */

class Data_tamu extends CI_Controller
{

    public function __construct() //otomatis dijalankan setiap kali class (controller ini) dipanggil.//
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Cek apakah sudah login
        if (!$this->session->userdata('status')  //kalau session status kosong / tidak ada, artinya user belum login.//
            || $this->session->userdata('status') != 'login') {
                
            redirect('index.php/auth/login_admin');
        }
    }

    // Halaman Data Tamu
    public function index()
    {
        
        // Data user diambil dari session
        $data['user'] = [
            'username'     => $this->session->userdata('username'), //Ini mengambil data username dari session yang sebelumnya disimpan saat login
            'nama_lengkap' => $this->session->userdata('nama_lengkap') //mengambil nama lengkap user dari session.
        ];

        // Panggil view data_tamu.php
        $this->load->view('admin/data_tamu', $data);
    }
}