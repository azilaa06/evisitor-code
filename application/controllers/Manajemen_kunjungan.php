<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Manajemen_model $Manajemen_model
 */

class Manajemen_kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Manajemen_model');
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    /**
     * Method untuk menampilkan data kunjungan
     * GABUNGAN: Fitur dari TIA + MAIN
     * @param string|null $status - Status filter (opsional)
     */
    public function data($status = null)
    {
        // Ambil keyword dari form search (GET)
        $keyword = $this->input->get('keyword');

        // PERBAIKAN: Handle status khusus "Pengunjung_Hari_Ini" (DARI MAIN)
        if ($status == 'Pengunjung_Hari_Ini') {
            // Panggil method khusus untuk pengunjung hari ini
            $data['kunjungan'] = $this->Manajemen_model->get_today_visitors();
            $data['status'] = "Pengunjung Hari Ini";
        } else {
            // Jika ada keyword, pakai hasil search; kalau tidak, tampilkan semua
            if (!empty($keyword)) {
                $data['kunjungan'] = $this->Manajemen_model->search($keyword);
                $data['status'] = "Hasil Pencarian";
            } else {
                // Kalau kosong, tampilkan semua (atau berdasarkan status jika ada)
                if ($status) {
                    $data['kunjungan'] = $this->Manajemen_model->get_by_status($status);
                    
                    // Set nama status untuk ditampilkan
                    $status_name = [
                        'ditolak' => 'Permohonan Ditolak',
                        'berkunjung' => 'Sedang Berkunjung',
                        'menunggu' => 'Menunggu Approval',
                        'selesai' => 'Telah Berkunjung'
                    ];
                    $data['status'] = isset($status_name[$status]) ? $status_name[$status] : ucfirst($status);
                } else {
                    $data['kunjungan'] = $this->Manajemen_model->get_all();
                    $data['status'] = "Semua Data";
                }
            }
        }
        
        // Simpan keyword biar bisa ditampilkan lagi di input search
        $data['keyword'] = $keyword;

        // Ambil nama user dari session
        $data['nama'] = $this->session->userdata('fullname') ?? 'Admin';

        // Highlight menu sidebar
        $data['active_page'] = 'manajemen_data';

        // Load view
        $this->load->view('admin/manajemen_data', $data);
    }

    /**
     * Method index - redirect ke data
     * GABUNGAN: Dari TIA (redirect ke data) + MAIN (search functionality)
     */
    public function index()
    {
        // Redirect ke halaman data semua kunjungan
        redirect('manajemen_kunjungan/data');
    }
}
?>