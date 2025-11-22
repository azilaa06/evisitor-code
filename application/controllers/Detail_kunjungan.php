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

    /**
     * Tampilkan halaman detail kunjungan
     * @param int $id - visit_id
     */
    public function detail($id)
    {
        // Ambil data kunjungan berdasarkan ID
        $visit_data = $this->Kunjungan_model->get_visit_by_id($id);

        if (!$visit_data) {
            show_404();
        }

        // Kirim data ke view dengan key 'pengunjung'
        $data['pengunjung'] = $visit_data;
        $data['nama'] = $this->session->userdata('nama_lengkap');
        $data['active_page'] = 'manajemen_data';

        // Data untuk sidebar
        $sidebar_data = [
            'nama' => $data['nama'],
            'active_page' => $data['active_page']
        ];

        // Load view
        $this->load->view('Layouts/sidebar_admin', $sidebar_data);
        $this->load->view('admin/detail', $data);
    }

    /**
     * Update status kunjungan (approve/reject)
     * @param int $id - visit_id
     */
    public function update_status($id)
    {
        $status = $this->input->post('status');

        // Validasi status
        if (!in_array($status, ['approved', 'rejected'])) {
            show_error('Status tidak valid');
        }

        // Ambil user_id admin/resepsionis yang sedang login
        $user_id = $this->session->userdata('user_id');

        // Jika user_id tidak ada di session, coba key alternatif
        if (!$user_id) {
            $user_id = $this->session->userdata('id_user') ?? $this->session->userdata('id');
        }

        // Update status + simpan siapa yang approve/reject
        $this->Kunjungan_model->update_status($id, $status, $user_id);

        // Set flash message sukses
        $this->session->set_flashdata('success', 'Status kunjungan berhasil diupdate!');

        // Redirect ke halaman manajemen kunjungan
        redirect('index.php/manajemen_kunjungan');
    }

    /**
     * Hapus data kunjungan
     * @param int $id - visit_id
     */
    public function delete($id)
    {
        // Ambil data kunjungan untuk validasi
        $visit_data = $this->Kunjungan_model->get_visit_by_id($id);

        if (!$visit_data) {
            show_404();
        }

        // Hapus data
        $this->Kunjungan_model->delete_visit($id);

        // Set flash message
        $this->session->set_flashdata('success', 'Data kunjungan berhasil dihapus!');

        // Redirect ke halaman manajemen
        redirect('index.php/manajemen_kunjungan?deleted=success');
    }

    /**
     * Check-out tamu
     * @param int $id - visit_id
     */
    public function checkout($id)
    {
        // Ambil data kunjungan
        $visit_data = $this->Kunjungan_model->get_visit_by_id($id);

        if (!$visit_data) {
            show_404();
        }

        // Validasi: hanya bisa checkout jika status checked_in
        if ($visit_data['status'] !== 'checked_in') {
            $this->session->set_flashdata('error', 'Kunjungan ini tidak bisa di-checkout! Pastikan tamu sudah check-in terlebih dahulu.');
            redirect('index.php/detail_kunjungan/detail/' . $id);
        }

        // Update status menjadi checked_out dan catat waktu checkout
        $this->Kunjungan_model->check_out($id);

        // Set flash message
        $this->session->set_flashdata('success', 'Check-out berhasil dilakukan!');

        // Redirect ke halaman manajemen
        redirect('index.php/manajemen_kunjungan');
    }
}