<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $Admin_model
 */

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');   // model login
        $this->load->library('session');    // session CI
        $this->load->helper('url');         // redirect/base_url
        $this->load->database();            // autoload db optional
    }

    // Halaman form login admin
    public function login_admin() {
        $this->load->view('auth/login_admin');
    }

    public function login_proses()
    {
        $username     = $this->input->post('username');
        $password     = $this->input->post('password');
        
        // Ambil data user dari model
        $user = $this->Admin_model->get_user($username, $password);

        if ($user) {
            // Simpan data user ke session
            $this->session->set_userdata([
                'username'     => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'status'       => 'login'
            ]);
            redirect('index.php/dashboard');
        } else {
            // Kalau salah, tampilkan pesan error
            $this->session->set_flashdata('error', 'Username / Password / Nama Lengkap salah!');
            redirect('index.php/auth/login_admin');
        }
    }

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('index.php/auth/login_admin');
    }

    // Login user
    public function user() {
        $this->load->view('auth/login_user');
    }


    // Dashboard admin
    public function dashboard_admin(){  // ambil nama dari session
        $data['nama'] = $this->session->userdata('nama_lengkap');
        $this->load->view('admin/dashboard', $data);
    }

    // Profil admin
    public function profil() {
        // Cek session login dulu
        if (!$this->session->userdata('status') || $this->session->userdata('status') != 'login') {
            redirect('auth/login_admin');
        }

        // Bisa kirim data user ke view
        $data['user'] = [
            'username'     => $this->session->userdata('username'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap')
        ];

        $this->load->view('admin/profil_admin', $data);
    }
    


    // Test koneksi ke tabel visitors
    public function test_db() {
        $query = $this->db->get('visitors');  // pastikan tabel ada
        echo '<pre>';
        print_r($query->result());
        echo '</pre>';
    }

} // <-- pastikan class ditutup di sini

}

