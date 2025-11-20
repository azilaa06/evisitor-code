<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Manajemen Kunjungan
 * FINAL VERSION - Merge MAIN + TIA (Clean, No Conflict)
 */
class Manajemen_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ========================================
    // 🔹 AMBIL DATA KUNJUNGAN
    // ========================================
    
    /**
     * Ambil semua data kunjungan dengan join ke tabel users
     * GABUNGAN: Fitur dari MAIN (join) + TIA (status_label)
     */
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
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->order_by('visits.scheduled_date', 'DESC');

        $data = $this->db->get()->result_array();

        // Tambahkan label status berdasarkan tanggal kunjungan (DARI MAIN)
        foreach ($data as &$row) {
            if ($row['status'] == 'approved') {
                $tanggal = date('Y-m-d', strtotime($row['TANGGAL']));
                $hariIni = date('Y-m-d');
                
                if ($tanggal == $hariIni) {
                    $row['status_label'] = 'Berkunjung';
                } elseif ($tanggal < $hariIni) {
                    $row['status_label'] = 'Telah Berkunjung';
                } else {
                    $row['status_label'] = 'Approved';
                }
            } else {
                $row['status_label'] = ucfirst($row['status']);
            }
        }
        
        return $data;
    }

    /**
     * Ambil data kunjungan berdasarkan status
     */
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
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.status', $db_status);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil pengunjung hari ini
     * GABUNGAN: Versi final dari TIA + MAIN
     */
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
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('DATE(visits.scheduled_date)', $today);
        $this->db->order_by('visits.scheduled_date', 'DESC');

        $data = $this->db->get()->result_array();

        // Tambahkan label status dan kondisi checkout (DARI MAIN)
        foreach ($data as &$row) {
            if (empty($row['check_out'])) {
                $row['status_label'] = 'Sedang Berkunjung';
            } else {
                $row['status_label'] = 'Telah Selesai';
            }
        }
        
        return $data;
    }

    // ========================================
    // 🔹 HITUNG DATA (COUNT)
    // ========================================
    
    /**
     * Hitung total berdasarkan status
     */
    public function count_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('visits');
    }

    /**
     * Hitung pengunjung yang sedang berkunjung (DARI MAIN)
     */
    public function get_count_sedang_berkunjung()
    {
        $this->db->where('check_in IS NOT NULL', null, false);
        $this->db->where('check_out IS NULL', null, false);
        return $this->db->count_all_results('visits');
    }

    /**
     * Hitung pengunjung yang telah berkunjung (DARI MAIN)
     */
    public function get_count_telah_berkunjung()
    {
        $this->db->where('check_out IS NOT NULL', null, false);
        return $this->db->count_all_results('visits');
    }

    /**
     * Hitung pengunjung hari ini
     */
    public function count_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_date)', $today);
        return $this->db->count_all_results('visits');
    }

    // ========================================
    // 🔹 DETAIL & UPDATE KUNJUNGAN
    // ========================================
    
    /**
     * Ambil detail kunjungan berdasarkan ID
     */
    public function get_by_id($visit_id)
    {
        $this->db->select('
            visits.*, 
            visits.fullname AS visitor_name,
            visits.id_number,
            visits.phone,
            visits.institution,
            visits.purpose,
            visits.to_whom,
            u1.fullname AS handled_by_name,
            u2.fullname AS approved_by_name
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.visit_id', $visit_id);
        return $this->db->get()->row_array();
    }

    /**
     * Update status kunjungan
     */
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

    /**
     * Update check in
     */
    public function check_in($visit_id)
    {
        $data = [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'approved'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    /**
     * Update check out
     */
    public function check_out($visit_id)
    {
        $data = [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // ========================================
    // 🔹 SEARCH & DELETE
    // ========================================
    
    /**
     * Search kunjungan
     * GABUNGAN: Join dari MAIN + search logic dari TIA
     */
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
            visits.institution AS INSTANSI,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('visits.fullname', $keyword);
            $this->db->or_like('visits.id_number', $keyword);
            $this->db->or_like('visits.institution', $keyword);
            $this->db->group_end();
        }
        
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Hapus data pengunjung (DARI MAIN)
     */
    public function delete_visitor($id)
    {
        $this->db->where('visit_id', $id);
        return $this->db->delete('visits');
    }

    /**
     * Ambil semua data visits (DARI MAIN)
     */
    public function get_all_visits()
    {
        $query = $this->db->get('visits');
        return $query->result_array();
    }
}