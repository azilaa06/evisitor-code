<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Kunjungan_model $Kunjungan_model
 */
class Detail_kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Cek login admin
        if (!$this->session->userdata('status') || $this->session->userdata('status') != 'login') {
            redirect('index.php/auth/login_admin');
        }

        $this->load->model('Kunjungan_model');
    }

    // Tampilkan halaman detail kunjungan
    public function detail($id)
    {
        // Ambil data kunjungan berdasarkan ID
        $visit_data = $this->Kunjungan_model->get_visit_by_id($id);

        if (!$visit_data) {
            show_404();
        }

        // Kirim data ke view
        $data['pengunjung'] = $visit_data;
        $data['nama'] = $this->session->userdata('nama_lengkap');
        $data['active_page'] = 'manajemen_data';

        // Kirim data ke sidebar
        $sidebar_data = [
            'nama' => $data['nama'],
            'active_page' => $data['active_page']
        ];

        // Load tampilan
        $this->load->view('Layout/sidebar_admin', $sidebar_data);
        $this->load->view('admin/detail', $data);
    }

    // Update status kunjungan (approve/reject)
    public function update_status($id)
    {
        $status = $this->input->post('status');

        if (!in_array($status, ['approved', 'rejected'])) {
            show_error('Status tidak valid');
        }

        // Ambil user_id admin yang sedang login
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $user_id = $this->session->userdata('id_user') ?? $this->session->userdata('id');
        }

        // Update status + simpan siapa yang menangani
        $this->Kunjungan_model->update_status($id, $status, $user_id);

        // Tampilkan pesan sukses
        $this->session->set_flashdata('success', 'Status kunjungan berhasil diupdate!');

        // Redirect ke halaman manajemen data setelah update
        redirect('index.php/manajemen_kunjungan');
    }
}
