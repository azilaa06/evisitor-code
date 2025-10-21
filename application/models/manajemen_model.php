<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Ambil semua data kunjungan dengan join ke tabel users dan visitors
    public function get_all()
    {
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            users.fullname AS PETUGAS
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Ambil data kunjungan berdasarkan status
    public function get_by_status($status)
    {
        $status_map = [
            'ditolak' => 'rejected',
            'berkunjung' => 'approved',
            'menunggu' => 'pending',
            'selesai' => 'completed'
        ];

        $db_status = isset($status_map[strtolower($status)]) ? $status_map[strtolower($status)] : $status;

        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            users.fullname AS PETUGAS
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
        $this->db->where('visits.status', $db_status);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Ambil pengunjung hari ini
    public function get_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            users.fullname AS PETUGAS
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left');
        $this->db->where('DATE(visits.scheduled_date)', $today);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Hitung total berdasarkan status
    public function count_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('visits');
    }

    // Hitung pengunjung hari ini
    public function count_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_date)', $today);
        return $this->db->count_all_results('visits');
    }

    // Ambil detail kunjungan berdasarkan ID
    public function get_by_id($visit_id) //menerima parameter $visit_id (ID kunjungan (visit_id) yang ingin kamu ambil datanya.)
    {
        //menentukan kolom apa aja yang ingin kamu ambil dari database.
        $this->db->select(' 
            visits.*, 
            visits.fullname AS visitor_name,
            visits.id_number,
            visits.phone,
            visits.institution,
            visits.purpose,
            visits.to_whom,
            users.fullname AS handled_by_name
        ');
        $this->db->from('visits');
        $this->db->join('users', 'visits.handled_by = users.user_id', 'left'); //supaya di halaman detail kunjungan (atau manajemen data) kamu bisa menampilkan nama petugas yang menangani tamu tersebut
        $this->db->where('visits.visit_id', $visit_id); //Filter data berdasarkan visit_id tertentu.
        return $this->db->get()->row_array();
    }

    // Update status kunjungan
    public function update_status($visit_id, $status, $user_id = null)
    {
        $data = [
            'status' => $status,
            'approved_at' => date('Y-m-d H:i:s')
        ];

        if ($user_id) {
            $data['approved_by'] = $user_id;
        }

        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // Update check in
    public function check_in($visit_id)
    {
        $data = [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'approved'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // Update check out
    public function check_out($visit_id)
    {
        $data = [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // Search kunjungan
    public function search($keyword = null)
    {
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI
        ');
        $this->db->from('visits');

        if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('visits.fullname', $keyword);
        $this->db->or_like('visits.institution', $keyword);  // ✅ bisa cari berdasarkan instansi
            $this->db->group_end();
        }
        $this->db->order_by('visits.scheduled_date', 'DESC'); //mengurutkan data berdasarkan kolom scheduled_date (tanggal kunjungan).(dari yg aku di urutkan dari yg baru di atas yg lama di bawah)
        return $this->db->get()->result_array();
        
    }
}