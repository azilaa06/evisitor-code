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
        $this->load->model('Admin_model');   // memanggil model bernama Admin_model
        $this->load->library('session');    // session CI
        $this->load->helper('url');         //membantu dalam pembuatan link dan navigasi antar halaman.(redirect/base_url
        $this->load->database();            //Mengaktifkan koneksi ke database
    }

    // Halaman form login admin
    public function login_admin() {
        $this->load->view('auth/login_admin'); //Baris ini memuat **file view** bernama `login_admin.php
    }

    public function login_proses() //Fungsinya untuk **memproses data login**
    {
        $username     = $this->input->post('username');
        $password     = $this->input->post('password');
        
        // Ambil data user dari model
        $user = $this->Admin_model->get_user($username, $password); //memanggil fungsi get_user() dari model Admin_model

            if ($user) { //pengecekan hasil dari model** sebelumnya
            $this->session->set_userdata([ //Simpan data user ke session
                'username'     => $user->username,
                'nama_lengkap' => $user->nama_lengkap, //Biasanya untuk ditampilkan di tampilan dashboard 
                'status'       => 'login' //sebagai tanda atau penanda bahwa user sedang login.
            ]);
                redirect('index.php/dashboard'); //tujuan halaman yang akan dituju.
            } else { //akan **dijalankan kalau login gagal**
            // Kalau salah, tampilkan pesan error
            $this->session->set_flashdata('error', 'Username / Password / Nama Lengkap salah!');
                redirect('index.php/auth/login_admin'); //Arahkan (pindahkan) user ke halaman lain
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
} // <-- pastikan class ditutup di sini


