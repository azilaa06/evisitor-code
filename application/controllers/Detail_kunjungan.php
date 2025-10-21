<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
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
        $data['pengunjung'] = $this->Kunjungan_model->get_visit_by_id($id); //mengambil satu baris data pengunjung dari database berdasarkan ID yang diterima.

        if (!$data['pengunjung']) { // jika tidak ditemukan di database maka 404
            show_404();
        }

    
        $data['nama'] = $this->session->userdata('nama_lengkap'); //nama admin / user yang sedang login dari session.
        $data['active_page'] = 'manajemen_data'; //buat highligt menu di sidebar

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

        $this->Kunjungan_model->update_status($id, $status);

        // Redirect ke halaman manajemen data setelah update
        redirect('index.php/manajemen_kunjungan');
    }
}