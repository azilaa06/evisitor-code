<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Admin_model $Admin_model
 * @property Get_today_visits $Get_today_visits
 */

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');   // memanggil model bernama Admin_model
        $this->load->library('session');    // session CI
        $this->load->helper('url');         // membantu dalam pembuatan link dan navigasi antar halaman
        $this->load->database();            // Mengaktifkan koneksi ke database
    }

    // Halaman form login admin
    public function login_admin()
    {
        // kalau sudah login, langsung ke dashboard
        // if ($this->session->userdata('status') == 'login') {
        //     redirect('index.php/dashboard'); // atau ke manajemen_kunjungan
        // }
        $this->load->view('auth/login_admin'); // memuat file view login_admin.php
    }

    // Proses login
    public function login_proses()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Ambil data user dari model
        $user = $this->Admin_model->get_user($username, $password);

        if ($user) {
            // Simpan data user ke session
            $this->session->set_userdata([
                'user_id'      => $user->user_id,
                'username'     => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'status'       => 'login'
            ]);

            redirect('index.php/dashboard');
        } else {
            // Kalau login gagal
            $this->session->set_flashdata('error', 'Username / Password salah!');
            redirect('index.php/auth/login_admin');
        }
    }

    // Logout
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('index.php/auth/login_admin');
    }

    // Login user
    public function user()
    {
        $this->load->view('auth/login_user');
    }

    // Halaman kunjungan
    public function kunjungan()
    {
        $this->load->view('tamu/kunjungan_detail');
    }

    // Dashboard admin (jika dibutuhkan)
    public function dashboard_admin()
    {
        $data['nama'] = $this->session->userdata('nama_lengkap');
        $this->load->view('admin/dashboard', $data);
    }

    // Profil admin
    public function profil()
    {
        // Cek session login dulu
        if (!$this->session->userdata('status') || $this->session->userdata('status') != 'login') {
            redirect('auth/login_admin');
        }

        // Kirim data user ke view
        $data['user'] = [
            'username'     => $this->session->userdata('username'),
            'nama_lengkap' => $this->session->userdata('nama_lengkap')
        ];

        $this->load->view('admin/profil_admin', $data);
    }
}
