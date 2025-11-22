<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Daftar_kunjungan
 * Controller untuk menampilkan daftar dan detail kunjungan milik visitor (tamu).
 *
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Daftar_kunjungan_model $Daftar_kunjungan_model
 */
class Daftar_kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Daftar_kunjungan_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Pastikan visitor sudah login
        if (!$this->session->userdata('visitor_id')) {
            redirect('auth/login');
        }
    }

    /**
     * Menampilkan daftar kunjungan milik visitor yang login
     */
    public function index()
    {
        $visitor_id = $this->session->userdata('visitor_id');

        if (!$visitor_id) {
            redirect('auth/login_user');
        }

        $data['kunjungan'] = $this->Daftar_kunjungan_model->get_by_visitor($visitor_id);
        $data['active_page'] = 'daftar_kunjungan';

        // Load tampilan daftar kunjungan
        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/daftar_kunjungan', $data);
    }

    /**
     * Menampilkan detail kunjungan berdasarkan ID
     */
    public function detail($visit_id)
    {
        $visitor_id = $this->session->userdata('visitor_id');
        
        // Ambil detail kunjungan dan ubah key jadi 'visit' agar sesuai dengan view
        $kunjungan = $this->Daftar_kunjungan_model->get_detail($visit_id);

        if (!$kunjungan) {
            show_404();
        }

        // Validasi kepemilikan - pastikan kunjungan ini milik visitor yang login
        if ($kunjungan['visitor_id'] != $visitor_id) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('daftar_kunjungan');
            return;
        }

        // Kirim dengan key 'visit' agar sesuai dengan view yang sudah ada
        $data['visit'] = $kunjungan;
        $data['active_page'] = 'daftar_kunjungan';

        // Load sidebar dan halaman detail
        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/kunjungan_detail', $data);
    }
}