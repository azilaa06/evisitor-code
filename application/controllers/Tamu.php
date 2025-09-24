<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input   $input
 * @property CI_Session $session
 * @property Tamu_model $Tamu_model
 */
class Tamu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tamu_model');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
    }

    // ========================
    // Proses Register User
    // ========================
    public function register()
    {
        // ambil input
        $username = $this->input->post('username', true); // bisa username / email
        $password = password_hash($this->input->post('password', true), PASSWORD_DEFAULT);
        $nama     = $this->input->post('nama', true); // hanya disimpan di session, bukan DB

        // data untuk tabel visitors
        $data = [
            'username' => $username,
            'password' => $password,
        ];

        $insert_id = $this->Tamu_model->register($data);

        if ($insert_id) {
            // simpan session (nama hanya ada di session)
            $this->session->set_userdata([
                'visitor_id' => $insert_id,
                'username'   => $username,
                'nama'       => $nama, // hanya session
                'logged_in'  => true
            ]);
            redirect('tamu/dashboard'); 
        } else {
            $this->session->set_flashdata('error', 'Username/Email sudah dipakai, coba yang lain.');
            redirect('auth/user'); 
        }
    }

    // ========================
    // Halaman Dashboard User
    // ========================
    public function dashboard()
    {
        // cek apakah sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/user'); // kalau belum login, kembalikan ke form login
        }

        // ambil data dari session
        $data = [
            'username' => $this->session->userdata('username'),
            'nama'     => $this->session->userdata('nama'),
        ];

        // load view dashboard
        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/dashboard', $data);
    }
}
