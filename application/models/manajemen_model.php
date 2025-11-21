<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model Manajemen Kunjungan
 * FIXED VERSION - Sesuai Database & Requirement
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

        // Tambahkan label status
        foreach ($data as &$row) {
            $row['status_label'] = $this->get_status_label($row);
        }
        
        return $data;
    }

    /**
     * Helper: Generate label status berdasarkan kondisi
     */
    private function get_status_label($row)
    {
        // Jika sudah checkout
        if (!empty($row['check_out'])) {
            return 'Telah Berkunjung';
        }
        
        // Jika sudah checkin tapi belum checkout
        if (!empty($row['check_in']) && empty($row['check_out'])) {
            return 'Sedang Berkunjung';
        }
        
        // Jika status approved tapi belum checkin
        if ($row['status'] == 'approved' && empty($row['check_in'])) {
            return 'Approved';
        }
        
        // Status lainnya
        if ($row['status'] == 'pending') {
            return 'Menunggu Approval';
        }
        
        if ($row['status'] == 'rejected') {
            return 'Ditolak';
        }
        
        return ucfirst($row['status']);
    }

    /**
     * Ambil data yang statusnya REJECTED (Ditolak)
     */
    public function get_ditolak()
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
            visits.rejection_reason,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.status', 'rejected');
        $this->db->order_by('visits.scheduled_date', 'DESC');
        
        $data = $this->db->get()->result_array();
        
        foreach ($data as &$row) {
            $row['status_label'] = 'Ditolak';
        }
        
        return $data;
    }

    /**
     * FIXED: Ambil data yang SEDANG BERKUNJUNG (sudah check_in tapi belum check_out)
     */
    public function get_sedang_berkunjung()
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
        $this->db->where('visits.check_in IS NOT NULL', null, false);
        $this->db->where('visits.check_out IS NULL', null, false);
        $this->db->order_by('visits.check_in', 'DESC');
        
        $data = $this->db->get()->result_array();
        
        foreach ($data as &$row) {
            $row['status_label'] = 'Sedang Berkunjung';
        }
        
        return $data;
    }

    /**
     * Ambil data yang statusnya PENDING (Menunggu Approval)
     */
    public function get_menunggu()
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
            visits.purpose,
            visits.to_whom,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.status', 'pending');
        $this->db->order_by('visits.scheduled_date', 'DESC');
        
        $data = $this->db->get()->result_array();
        
        foreach ($data as &$row) {
            $row['status_label'] = 'Menunggu Approval';
        }
        
        return $data;
    }

    /**
     * FIXED: Ambil data yang TELAH BERKUNJUNG (sudah check_out)
     */
    public function get_telah_berkunjung()
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
        $this->db->where('visits.check_out IS NOT NULL', null, false);
        $this->db->order_by('visits.check_out', 'DESC');
        
        $data = $this->db->get()->result_array();
        
        foreach ($data as &$row) {
            $row['status_label'] = 'Telah Berkunjung';
        }
        
        return $data;
    }

    /**
     * FIXED: Ambil pengunjung hari ini yang STATUSNYA APPROVED
     * Syarat: scheduled_date = hari ini DAN status = approved
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
        $this->db->where('visits.status', 'approved');
        $this->db->order_by('visits.scheduled_date', 'DESC');

        $data = $this->db->get()->result_array();

        foreach ($data as &$row) {
            $row['status_label'] = $this->get_status_label($row);
        }
        
        return $data;
    }

    /**
     * Ambil data yang statusnya APPROVED
     */
    public function get_approved()
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
            visits.approved_at,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.status', 'approved');
        $this->db->order_by('visits.approved_at', 'DESC');
        
        $data = $this->db->get()->result_array();
        
        foreach ($data as &$row) {
            $row['status_label'] = $this->get_status_label($row);
        }
        
        return $data;
    }

    /**
     * Ambil data berdasarkan status (untuk compatibility)
     */
    public function get_by_status($status)
    {
        $status_map = [
            'ditolak' => 'rejected',
            'menunggu' => 'pending',
            'approved' => 'approved',
            'berkunjung' => 'berkunjung',
            'selesai' => 'selesai'
        ];

        $db_status = isset($status_map[strtolower($status)]) ? $status_map[strtolower($status)] : $status;

        // Route ke method yang sesuai
        if ($db_status == 'rejected') {
            return $this->get_ditolak();
        } elseif ($db_status == 'pending') {
            return $this->get_menunggu();
        } elseif ($db_status == 'berkunjung') {
            return $this->get_sedang_berkunjung();
        } elseif ($db_status == 'selesai') {
            return $this->get_telah_berkunjung();
        } elseif ($db_status == 'approved') {
            return $this->get_approved();
        } else {
            return $this->get_all();
        }
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
     * FIXED: Hitung yang SEDANG berkunjung (check_in ada, check_out kosong)
     */
    public function get_count_sedang_berkunjung()
    {
        $this->db->where('check_in IS NOT NULL', null, false);
        $this->db->where('check_out IS NULL', null, false);
        return $this->db->count_all_results('visits');
    }

    /**
     * FIXED: Hitung yang TELAH berkunjung (sudah check_out)
     */
    public function get_count_telah_berkunjung()
    {
        $this->db->where('check_out IS NOT NULL', null, false);
        return $this->db->count_all_results('visits');
    }

    /**
     * FIXED: Hitung pengunjung hari ini yang APPROVED
     */
    public function count_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_date)', $today);
        $this->db->where('status', 'approved');
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
            'check_in' => date('Y-m-d H:i:s')
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
            'check_out' => date('Y-m-d H:i:s')
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // ========================================
    // 🔹 SEARCH & DELETE
    // ========================================
    
    /**
     * Search kunjungan
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
        $data = $this->db->get()->result_array();
        
        foreach ($data as &$row) {
            $row['status_label'] = $this->get_status_label($row);
        }
        
        return $data;
    }

    /**
     * Hapus data pengunjung
     */
    public function delete_visitor($id)
    {
        $this->db->where('visit_id', $id);
        return $this->db->delete('visits');
    }

    /**
     * Ambil semua data visits
     */
    public function get_all_visits()
    {
        $query = $this->db->get('visits');
        return $query->result_array();
    }
}