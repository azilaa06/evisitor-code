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

    // ✅ Halaman form kunjungan (pakai dashboard.php)
    public function index()
    {
        // Pastikan sudah login
        if (!$this->session->userdata('visitor_id')) {
            redirect('index.php/login');
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
            redirect('index.php/login');
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
            redirect('index.php/kunjungan');
        }

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
            'created_at'     => date('Y-m-d H:i:s')
        ];

        // Simpan ke database
        $insert = $this->Kunjungan_model->insert_visit($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Data kunjungan berhasil disimpan!');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan data.');
        }

        redirect('index.php/kunjungan');
    }

    // ✅ Halaman detail/status kunjungan
    public function status_kunjungan()
    {
        $visitor_id = $this->session->userdata('visitor_id');
        if (!$visitor_id) {
            redirect('index.php/login');
        }

        $data['visit'] = $this->Kunjungan_model->get_last_visit_by_visitor($visitor_id);
        $data['active_page'] = 'status_kunjungan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/kunjungan_detail', $data);
    }
}
