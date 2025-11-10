<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input            $input
 * @property CI_Session          $session
 * @property Kunjungan_model     $Kunjungan_model
 * @property CI_Form_validation  $form_validation
 */
class Kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kunjungan_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

// ========================
// KUNJUNGAN MODEL
// ========================
// Tambahkan method ini ke file: application/models/Kunjungan_model.php

/*
// 🟢 Update QR token untuk kunjungan (untuk data lama yang belum punya QR)
public function update_qr_token($visit_id, $qr_token)
{
    $this->db->where('visit_id', $visit_id);
    return $this->db->update('visits', ['qr_token' => $qr_token]);
}
*/

    // ✅ Halaman form kunjungan (pakai dashboard.php)
    public function index()
    {
        // Pastikan sudah login
        if (!$this->session->userdata('visitor_id')) {
            redirect('login');
        }

        $data['active_page'] = 'kunjungan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');

        // Gunakan view dashboard.php sebagai form kunjungan
        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/dashboard', $data);
    }

    // ✅ Proses simpan data kunjungan
    public function submit()
    {
        $visitor_id = $this->session->userdata('visitor_id');
        if (!$visitor_id) {
            redirect('login');
        }

        // Validasi form
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('id_number', 'NIK', 'required');
        $this->form_validation->set_rules('phone', 'No Telepon', 'required');
        $this->form_validation->set_rules('institution', 'Instansi', 'required');
        $this->form_validation->set_rules('to_whom', 'Tujuan ke', 'required');
        $this->form_validation->set_rules('scheduled_date', 'Tanggal', 'required');
        $this->form_validation->set_rules('purpose', 'Tujuan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Semua field harus diisi dengan benar.');
            redirect('kunjungan');
        }

        // Generate QR token unik untuk kunjungan baru
        $qr_token = 'VISIT-' . time() . '-' . substr(md5(uniqid(rand(), true)), 0, 8);

        // Ambil data input
        $data = [
            'visitor_id'     => $visitor_id,
            'fullname'       => $this->input->post('fullname'),
            'id_number'      => $this->input->post('id_number'),
            'phone'          => $this->input->post('phone'),
            'institution'    => $this->input->post('institution'),
            'to_whom'        => $this->input->post('to_whom'),
            'scheduled_date' => $this->input->post('scheduled_date'),
            'purpose'        => $this->input->post('purpose'),
            'qr_token'       => $qr_token,  // ✅ TAMBAHAN: Simpan QR token
            'created_at'     => date('Y-m-d H:i:s')
        ];

        // Simpan ke database
        $insert = $this->Kunjungan_model->insert_visit($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data kunjungan berhasil disimpan!');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan data.');
        }

        redirect('kunjungan');
    }

    // ✅ Halaman detail/status kunjungan
    public function status_kunjungan($visit_id = null)
    {
        $visitor_id = $this->session->userdata('visitor_id');
        if (!$visitor_id) {
            redirect('login');
        }

        // Kalau ada visit_id dari URL (diklik dari daftar), ambil data spesifik
        if ($visit_id) {
            $data['visit'] = $this->Kunjungan_model->get_visit_by_id($visit_id, $visitor_id);
            
            // Cek apakah kunjungan ini milik user yang login
            if (!$data['visit']) {
                $this->session->set_flashdata('error', 'Data kunjungan tidak ditemukan atau bukan milik Anda.');
                redirect('kunjungan/daftar_kunjungan');
            }
        } else {
            // Kalau tidak ada visit_id (akses langsung dari menu), ambil yang terakhir
            $data['visit'] = $this->Kunjungan_model->get_last_visit_by_visitor($visitor_id);
        }

        // ✅ PERBAIKAN: Generate QR token dengan prioritas
        if (isset($data['visit']['qr_token']) && !empty($data['visit']['qr_token'])) {
            // Jika sudah ada qr_token di database, pakai itu
            $data['qr_token'] = $data['visit']['qr_token'];
        } else {
            // Jika tidak ada (data lama), generate dari ID dan timestamp
            if (isset($data['visit']['id'])) {
                $created_at = isset($data['visit']['created_at']) ? $data['visit']['created_at'] : date('Y-m-d H:i:s');
                $data['qr_token'] = 'VISIT-' . $data['visit']['id'] . '-' . substr(md5($data['visit']['id'] . $created_at), 0, 8);
                
                // ✅ OPTIONAL: Update database dengan qr_token yang baru di-generate
                // Ini memastikan QR code konsisten untuk kunjungan yang sama
                $this->Kunjungan_model->update_qr_token($data['visit']['id'], $data['qr_token']);
            } else {
                // Fallback jika tidak ada ID
                $data['qr_token'] = 'NO_VISIT_DATA_' . time();
            }
        }

        $data['active_page'] = 'status_kunjungan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/kunjungan_detail', $data);
    }
}