<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $Admin_model
 */

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
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

        // Load view dashboard dan sidebar
        $this->load->view('layout/sidebar_admin', $data); // sidebar
        $this->load->view('admin/dashboard', $data);       // dashboard utama
    }
}
