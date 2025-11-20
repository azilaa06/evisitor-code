<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Manajemen Kunjungan
 * FINAL VERSION - Merge MAIN + TIA (Clean, No Conflict)
 * 
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
    
    // FIX LOGIN SESSION
    if ($this->session->userdata('status') != 'login') {
        redirect('auth/login_admin');
    }
}


    /**
     * Method Index - Tampilkan semua data kunjungan
     * FIX: Langsung tampilkan data, bukan redirect
     */
    public function index()
    {
        $this->data(); // Panggil method data tanpa parameter
    }

    /**
     * Method Data - Tampilkan data kunjungan dengan filter
     * GABUNGAN FINAL: Struktur dari MAIN + Fitur dari TIA
     * 
     * @param string|null $status - Filter status (optional)
     */
    public function data($status = null)
    {
        // Ambil keyword search dari GET parameter
        $keyword = $this->input->get('keyword');
        
        // Inisialisasi data untuk view
        $view_data = [
            'kunjungan' => [],
            'status' => 'Semua Data',
            'keyword' => $keyword,
            'nama' => $this->session->userdata('fullname') ?? 'Admin',
            'active_page' => 'manajemen_data'
        ];

        // LOGIC PENGAMBILAN DATA (Prioritas: Search > Status Khusus > Filter Status > All)
        
        // 1. Jika ada keyword search (DARI TIA)
        if (!empty($keyword)) {
            $view_data['kunjungan'] = $this->Manajemen_model->search($keyword);
            $view_data['status'] = 'Hasil Pencarian: "' . htmlspecialchars($keyword) . '"';
        }
        // 2. Jika filter Pengunjung Hari Ini (DARI MAIN)
        elseif ($status === 'Pengunjung_Hari_Ini') {
            $view_data['kunjungan'] = $this->Manajemen_model->get_today_visitors();
            $view_data['status'] = 'Pengunjung Hari Ini';
        }
        // 3. Jika ada filter status tertentu (DARI TIA + MAIN)
        elseif ($status) {
            $view_data['kunjungan'] = $this->Manajemen_model->get_by_status($status);
            
            // Mapping nama status untuk tampilan (DARI MAIN)
            $status_labels = [
                'pending' => 'Menunggu Approval',
                'approved' => 'Sedang Berkunjung',
                'rejected' => 'Permohonan Ditolak',
                'completed' => 'Telah Berkunjung',
                'menunggu' => 'Menunggu Approval',
                'berkunjung' => 'Sedang Berkunjung',
                'ditolak' => 'Permohonan Ditolak',
                'selesai' => 'Telah Berkunjung'
            ];
            
            $view_data['status'] = $status_labels[strtolower($status)] ?? ucfirst($status);
        }
        // 4. Default: Tampilkan semua data
        else {
            $view_data['kunjungan'] = $this->Manajemen_model->get_all();
        }

        // Load view dengan data
        $this->load->view('admin/manajemen_data', $view_data);
    }

    /**
     * Method untuk filter berdasarkan status (alternatif routing)
     * FIX: Supaya URL /manajemen_kunjungan/berkunjung works
     */
    public function menunggu()
    {
        $this->data('pending');
    }

    public function berkunjung()
    {
        $this->data('approved');
    }

    public function ditolak()
    {
        $this->data('rejected');
    }

    public function selesai()
    {
        $this->data('completed');
    }

    public function hari_ini()
    {
        $this->data('Pengunjung_Hari_Ini');
    }
}