<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $Admin_model
 */

class Profil_admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        // Optional: load model user kalau mau ambil data user
        // $this->load->model('User_model');
    }

    public function profil()
    {
        // Ambil data user dari session
        $data['user'] = (object)[
            'nama_lengkap' => $this->session->userdata('nama_lengkap') ?? 'Admin',
            'email' => $this->session->userdata('email') ?? 'admin@example.com',
            'password' => $this->session->userdata('password') ?? '********'
        ];
        $data['active_page'] = 'profil'; // untuk highlight menu sidebar

        $this->load->view('admin/profil', $data);
    }
}
