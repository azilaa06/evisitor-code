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
        $visit_data = $this->Kunjungan_model->get_visit_by_id($id);

        if (!$visit_data) {
            show_404();
        }

        // ✅ PERBAIKAN: Kirim dengan key 'pengunjung' agar sesuai dengan view
        $data['pengunjung'] = $visit_data;
        $data['nama'] = $this->session->userdata('nama_lengkap');
        $data['active_page'] = 'manajemen_data';

        $sidebar_data = [
            'nama' => $data['nama'],
            'active_page' => $data['active_page']
        ];

        $this->load->view('Layouts/sidebar_admin', $sidebar_data);
        $this->load->view('admin/detail', $data);
    }

    // Update status kunjungan (approve/reject)
    public function update_status($id)
    {
        $status = $this->input->post('status');

        if (!in_array($status, ['approved', 'rejected'])) {
            show_error('Status tidak valid');
        }

        // ⭐ Ambil user_id admin/resepsionis yang sedang login
        $user_id = $this->session->userdata('user_id'); 

        // ✅ Jika user_id tidak ada di session, coba ambil dari key lain
        if (!$user_id) {
            $user_id = $this->session->userdata('id_user'); // Coba key alternatif
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('id'); // Coba key alternatif lagi
        }

        // Update status + simpan siapa yang approve/reject ke kolom handled_by
        $this->Kunjungan_model->update_status($id, $status, $user_id);

        // Set flash message sukses
        $this->session->set_flashdata('success', 'Status kunjungan berhasil diupdate!');

        // Redirect ke halaman manajemen data setelah update
        redirect('index.php/manajemen_kunjungan');
    }
}