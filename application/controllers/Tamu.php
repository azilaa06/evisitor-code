<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input          $input
 * @property CI_Form_validation $form_validation
 * @property CI_Session        $session
 * @property Tamu_model        $Tamu_model
 * @property CI_DB_query_builder $db
 */
class Tamu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tamu_model');
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
        $this->load->database();
    }

    // ========================
    // Halaman Login
    // ========================
    public function login_page()
    {
        $this->load->view('auth/login_user');
    }

    // ========================
    // Proses Login
    // ========================
    public function login()
    {
        $email = $this->input->post('email', true);
        $password = $this->input->post('password', true);

        $errors = [];

        // Validasi form
        if (empty($email)) $errors[] = 'Email harus diisi!';
        if (empty($password)) $errors[] = 'Password harus diisi!';

        if (!empty($errors)) {
            $this->session->set_userdata('validation_errors', $errors);
            $this->session->set_userdata('show_form', 'login');
            $this->session->set_userdata('old_email', $email);
            redirect('tamu/login_page');
            return;
        }

        // Cek email di database
        $user_exists = $this->Tamu_model->get_by_email($email);
        if (!$user_exists) {
            $this->session->set_userdata('validation_errors', ['Email tidak terdaftar! Silakan register terlebih dahulu.']);
            $this->session->set_userdata('show_form', 'login');
            $this->session->set_userdata('old_email', $email);
            redirect('tamu/login_page');
            return;
        }

        // Cek password
        $user = $this->Tamu_model->login($email, $password);

        if ($user) {
            // Login berhasil
            $this->session->unset_userdata(['validation_errors', 'show_form', 'old_email', 'old_username']);

            $this->session->set_userdata([
                'visitor_id' => $user['visitor_id'], // ✅ fix di sini
                'username'   => $user['username'],
                'email'      => $user['email'],
                'logged_in'  => true
            ]);

            redirect('tamu/dashboard');
        } else {
            // Password salah
            $this->session->set_userdata('validation_errors', ['Password yang Anda masukkan salah!']);
            $this->session->set_userdata('show_form', 'login');
            $this->session->set_userdata('old_email', $email);
            redirect('tamu/login_page');
        }
    }

    // ========================
    // Proses Register
    // ========================
    public function register()
    {
        $email = $this->input->post('email', true);
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        $errors = [];

        // Validasi email
        if (empty($email)) {
            $errors[] = 'Email harus diisi!';
        } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.com$/', $email)) {
            $errors[] = 'Email harus format valid dan diakhiri dengan .com!';
        } elseif ($this->Tamu_model->get_by_email($email)) {
            $errors[] = 'Email sudah terdaftar!';
        }

        // Validasi username
        if (empty($username)) {
            $errors[] = 'Username harus diisi!';
        } elseif (strlen($username) < 4) {
            $errors[] = 'Username minimal 4 karakter!';
        } else {
            $this->db->where('username', $username);
            if ($this->db->get('visitors')->num_rows() > 0) {
                $errors[] = 'Username sudah digunakan!';
            }
        }

        // Validasi password
        if (empty($password)) {
            $errors[] = 'Password harus diisi!';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter!';
        }

        if (!empty($errors)) {
            $this->session->set_userdata('validation_errors', $errors);
            $this->session->set_userdata('show_form', 'register');
            $this->session->set_userdata('old_email', $email);
            $this->session->set_userdata('old_username', $username);
            redirect('tamu/login_page');
            return;
        }

        // Simpan data
        $data = [
            'username'   => $username,
            'email'      => $email,
            'password'   => $password,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Tamu_model->register($data)) {
            $this->session->unset_userdata(['validation_errors', 'show_form', 'old_email', 'old_username']);
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
            redirect('tamu/login_page');
        } else {
            $this->session->set_userdata('validation_errors', ['Registrasi gagal. Coba lagi.']);
            $this->session->set_userdata('show_form', 'register');
            redirect('tamu/login_page');
        }
    }

    // ========================
    // Dashboard Tamu
    // ========================
    public function dashboard()
    {
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('tamu/login_page');
            return;
        }

        $data = [
            'active_page' => 'dashboard',
            'username'    => $this->session->userdata('username'),
            'email'       => $this->session->userdata('email')
        ];

        // Tampilkan layout dan konten
        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/dashboard', $data);
    }

    // ========================
    // Logout
    // ========================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('tamu/login_page');
    }
}
