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
        $this->load->model('Admin_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
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
            // ⭐ TAMBAHAN: Simpan user_id juga ke session
            $this->session->set_userdata([
                'user_id'      => $user->user_id,      // 👈 ID untuk approve/reject
                'username'     => $user->username,
                'nama_lengkap' => $user->fullname,     // 👈 AMBIL DARI DATABASE (fullname)
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
    public function dashboard_admin(){
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
}