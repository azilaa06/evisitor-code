<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Kunjungan Model
 * Handle semua operasi database untuk tabel visits
 */
class Kunjungan_model extends CI_Model
{
    private $table = 'visits'; // NAMA TABEL
    private $table_users = 'users'; // TABEL USERS

    /**
     * 🟢 Simpan data kunjungan baru
     */
    public function insert_visit($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * 🟢 Ambil kunjungan terakhir berdasarkan visitor_id
     * Dengan nama penanggung jawab dari JOIN users
     */
    public function get_last_visit_by_visitor($visitor_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.visit_id', 'DESC');
        $this->db->limit(1);
        
        return $this->db->get()->row_array();
    }

    /**
     * 🟢 Ambil semua kunjungan berdasarkan visitor_id
     * Untuk halaman daftar kunjungan
     */
    public function get_visit_by_guest($visitor_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * 🟢 Ambil detail satu kunjungan berdasarkan visit_id
     * DENGAN NAMA PENANGGUNG JAWAB
     * 
     * @param int $visit_id ID kunjungan
     * @param int|null $visitor_id ID visitor untuk security check (opsional)
     * @return array|null
     */
    public function get_visit_by_id($visit_id, $visitor_id = null)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visit_id', $visit_id);
        
        // Security: pastikan hanya ambil data milik visitor tersebut
        if ($visitor_id !== null) {
            $this->db->where('v.visitor_id', $visitor_id);
        }
        
        return $this->db->get()->row_array();
    }

    /**
     * 🟢 Update status kunjungan
     * Dengan approved_by dan timestamp
     * 
     * @param int $visit_id ID kunjungan
     * @param string $status Status baru (pending, approved, rejected, checked_in, checked_out, completed, cancelled, no_show)
     * @param int|null $user_id ID user yang approve/reject
     * @return bool
     */
    public function update_status($visit_id, $status, $user_id = null)
    {
        $data = [
            'status' => $status
        ];

        // Jika ada user_id, simpan siapa yang approve/reject
        if ($user_id) {
            $data['approved_by'] = $user_id;
            $data['approved_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('visit_id', $visit_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * 🟢 Update QR token untuk kunjungan
     * Untuk data lama yang belum punya QR token
     * 
     * @param int $visit_id ID kunjungan
     * @param string $qr_token QR token baru
     * @return bool
     */
    public function update_qr_token($visit_id, $qr_token)
    {
        $this->db->where('visit_id', $visit_id);
        return $this->db->update($this->table, ['qr_token' => $qr_token]);
    }

    /**
     * 🆕 Ambil kunjungan berdasarkan QR token
     * Untuk proses scanning QR Code
     * 
     * @param string $qr_token QR token yang di-scan
     * @return array|null
     */
    public function get_visit_by_qr_token($qr_token)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.qr_token', $qr_token);
        
        return $this->db->get()->row_array();
    }

    /**
     * 🆕 Hitung total kunjungan berdasarkan visitor_id
     * Untuk statistik/dashboard
     * 
     * @param int $visitor_id ID visitor
     * @return int
     */
    public function count_visits_by_visitor($visitor_id)
    {
        $this->db->where('visitor_id', $visitor_id);
        return $this->db->count_all_results($this->table);
    }

    /**
     * 🆕 Ambil kunjungan dengan pagination - RETURN RESULT() untuk view
     * 
     * @param int $visitor_id ID visitor
     * @param int $limit Jumlah data per halaman
     * @param int $offset Offset data
     * @return object
     */
    public function get_visits_by_visitor($visitor_id, $limit = 100, $offset = 0)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result(); // Return result() bukan result_array()
    }

    /**
     * 🆕 Ambil kunjungan berdasarkan status
     * 
     * @param string $status Status (pending, approved, rejected, checked_in, checked_out, completed, cancelled, no_show)
     * @param int|null $visitor_id ID visitor (opsional untuk filter by visitor)
     * @return array
     */
    public function get_visits_by_status($status, $visitor_id = null)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.status', $status);
        
        if ($visitor_id !== null) {
            $this->db->where('v.visitor_id', $visitor_id);
        }
        
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * 🆕 Delete kunjungan (soft delete atau hard delete)
     * 
     * @param int $visit_id ID kunjungan
     * @param int|null $visitor_id ID visitor untuk security check
     * @return bool
     */
    public function delete_visit($visit_id, $visitor_id = null)
    {
        $this->db->where('visit_id', $visit_id);
        
        if ($visitor_id !== null) {
            $this->db->where('visitor_id', $visitor_id);
        }
        
        return $this->db->delete($this->table);
    }

    /**
     * 🆕 Check apakah QR token sudah digunakan
     * Untuk validasi saat generate QR baru
     * 
     * @param string $qr_token QR token yang akan dicek
     * @return bool
     */
    public function is_qr_token_exists($qr_token)
    {
        $this->db->where('qr_token', $qr_token);
        $count = $this->db->count_all_results($this->table);
        
        return $count > 0;
    }

    /**
     * 🔄 UPDATED: Check-in visitor (scan QR Code)
     * Update status ke 'checked_in' dan simpan waktu check-in
     * 
     * @param int $visit_id ID kunjungan
     * @return bool
     */
    public function check_in($visit_id)
    {
        $data = [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'checked_in'
        ];
        
        $this->db->where('visit_id', $visit_id);
        $this->db->where('status', 'approved'); // Hanya bisa check-in jika status approved
        return $this->db->update($this->table, $data);
    }

    /**
     * 🔄 UPDATED: Check-out visitor
     * Update status ke 'checked_out' dan simpan waktu check-out
     * 
     * @param int $visit_id ID kunjungan
     * @return bool
     */
    public function check_out($visit_id)
    {
        $data = [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'checked_out'
        ];
        
        $this->db->where('visit_id', $visit_id);
        $this->db->where('status', 'checked_in'); // Hanya bisa check-out jika sudah check-in
        return $this->db->update($this->table, $data);
    }

    /**
     * 🆕 Update general - untuk update field apapun
     * 
     * @param int $visit_id ID kunjungan
     * @param array $data Data yang akan diupdate
     * @return bool
     */
    public function update_visit($visit_id, $data)
    {
        $this->db->where('visit_id', $visit_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * 🔄 UPDATED: Ambil statistik kunjungan visitor - SEMUA STATUS
     * 
     * @param int $visitor_id ID visitor
     * @return array
     */
    public function get_visitor_statistics($visitor_id)
    {
        $stats = [];
        
        // Total kunjungan
        $stats['total'] = $this->count_visits_by_visitor($visitor_id);
        
        // Pending
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'pending');
        $stats['pending'] = $this->db->count_all_results($this->table);
        
        // Approved
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'approved');
        $stats['approved'] = $this->db->count_all_results($this->table);
        
        // Rejected
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'rejected');
        $stats['rejected'] = $this->db->count_all_results($this->table);
        
        // Checked In
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'checked_in');
        $stats['checked_in'] = $this->db->count_all_results($this->table);
        
        // Checked Out
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'checked_out');
        $stats['checked_out'] = $this->db->count_all_results($this->table);
        
        // Completed
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'completed');
        $stats['completed'] = $this->db->count_all_results($this->table);
        
        // Cancelled
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'cancelled');
        $stats['cancelled'] = $this->db->count_all_results($this->table);
        
        // No Show
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'no_show');
        $stats['no_show'] = $this->db->count_all_results($this->table);
        
        return $stats;
    }

    /**
     * 🆕 Cari kunjungan berdasarkan keyword
     * 
     * @param int $visitor_id ID visitor
     * @param string $keyword Keyword pencarian
     * @return array
     */
    public function search_visits($visitor_id, $keyword)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        
        $this->db->group_start();
        $this->db->like('v.fullname', $keyword);
        $this->db->or_like('v.institution', $keyword);
        $this->db->or_like('v.to_whom', $keyword);
        $this->db->or_like('v.purpose', $keyword);
        $this->db->group_end();
        
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * 🆕 Batalkan kunjungan
     * 
     * @param int $visit_id ID kunjungan
     * @param string|null $reason Alasan pembatalan
     * @return bool
     */
    public function cancel_visit($visit_id, $reason = null)
    {
        $data = [
            'status' => 'cancelled'
        ];
        
        if ($reason) {
            $data['rejection_reason'] = $reason;
        }
        
        $this->db->where('visit_id', $visit_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * 🆕 Tandai sebagai no show
     * 
     * @param int $visit_id ID kunjungan
     * @return bool
     */
    public function mark_no_show($visit_id)
    {
        $data = [
            'status' => 'no_show'
        ];
        
        $this->db->where('visit_id', $visit_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * 🆕 Ambil kunjungan hari ini berdasarkan status
     * Untuk dashboard resepsionis
     * 
     * @param string|null $status Status filter (opsional)
     * @return array
     */
    public function get_todays_visits($status = null)
    {
        $today = date('Y-m-d');
        
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('DATE(v.scheduled_date)', $today);
        
        if ($status) {
            $this->db->where('v.status', $status);
        }
        
        $this->db->order_by('v.scheduled_date', 'ASC');
        
        return $this->db->get()->result_array();
    }

    /**
     * 🆕 Hitung pending visits untuk notifikasi
     * 
     * @return int
     */
    public function count_pending_visits()
    {
        $this->db->where('status', 'pending');
        return $this->db->count_all_results($this->table);
    }

    /**
     * 🆕 Hitung checked-in visits (visitor yang sedang berada di lokasi)
     * 
     * @return int
     */
    public function count_checked_in_visits()
    {
        $this->db->where('status', 'checked_in');
        return $this->db->count_all_results($this->table);
    }
}