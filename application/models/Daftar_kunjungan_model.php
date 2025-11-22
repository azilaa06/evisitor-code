<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Daftar_kunjungan_model
 * Model untuk menampilkan daftar dan detail kunjungan
 * Support untuk semua status: pending, approved, rejected, checked_in, checked_out, completed, cancelled, no_show
 */
class Daftar_kunjungan_model extends CI_Model
{
    private $table = 'visits';
    private $table_users = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 🟢 Ambil semua kunjungan berdasarkan visitor_id
     * Return sebagai result() untuk foreach di view
     * 
     * @param int $visitor_id ID visitor
     * @return object
     */
    public function get_by_visitor($visitor_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * 🟢 Ambil detail kunjungan berdasarkan visit_id
     * Return sebagai row_array() untuk detail view
     * 
     * @param int $visit_id ID kunjungan
     * @return array|null
     */
    public function get_detail($visit_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visit_id', $visit_id);
        
        return $this->db->get()->row_array();
    }

    /**
     * 🆕 Ambil kunjungan berdasarkan status
     * 
     * @param int $visitor_id ID visitor
     * @param string $status Status yang dicari
     * @return object
     */
    public function get_by_status($visitor_id, $status)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->where('v.status', $status);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Ambil kunjungan dengan filter multiple status
     * Berguna untuk filter seperti: tampilkan hanya yang approved, checked_in, checked_out
     * 
     * @param int $visitor_id ID visitor
     * @param array $statuses Array status yang dicari
     * @return object
     */
    public function get_by_statuses($visitor_id, $statuses)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->where_in('v.status', $statuses);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Hitung jumlah kunjungan per status untuk visitor tertentu
     * 
     * @param int $visitor_id ID visitor
     * @return array
     */
    public function count_by_status($visitor_id)
    {
        $stats = [];
        
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
        
        // Total
        $this->db->where('visitor_id', $visitor_id);
        $stats['total'] = $this->db->count_all_results($this->table);
        
        return $stats;
    }

    /**
     * 🆕 Ambil kunjungan terbaru (limit)
     * 
     * @param int $visitor_id ID visitor
     * @param int $limit Jumlah data
     * @return object
     */
    public function get_recent($visitor_id, $limit = 5)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Ambil kunjungan berdasarkan range tanggal
     * 
     * @param int $visitor_id ID visitor
     * @param string $date_from Tanggal mulai (Y-m-d)
     * @param string $date_to Tanggal selesai (Y-m-d)
     * @return object
     */
    public function get_by_date_range($visitor_id, $date_from, $date_to)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->where('DATE(v.scheduled_date) >=', $date_from);
        $this->db->where('DATE(v.scheduled_date) <=', $date_to);
        $this->db->order_by('v.scheduled_date', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Search kunjungan berdasarkan keyword
     * 
     * @param int $visitor_id ID visitor
     * @param string $keyword Keyword pencarian
     * @return object
     */
    public function search($visitor_id, $keyword)
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
        $this->db->or_like('v.qr_token', $keyword);
        $this->db->group_end();
        
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Ambil kunjungan yang membutuhkan action (pending atau checked_in)
     * 
     * @param int $visitor_id ID visitor
     * @return object
     */
    public function get_pending_actions($visitor_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->where_in('v.status', ['pending', 'approved']);
        $this->db->order_by('v.scheduled_date', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Ambil history kunjungan yang sudah selesai
     * Status: checked_out, completed, cancelled, no_show
     * 
     * @param int $visitor_id ID visitor
     * @return object
     */
    public function get_history($visitor_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->where_in('v.status', ['checked_out', 'completed', 'cancelled', 'no_show', 'rejected']);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Ambil kunjungan yang aktif (approved atau checked_in)
     * 
     * @param int $visitor_id ID visitor
     * @return object
     */
    public function get_active($visitor_id)
    {
        $this->db->select('v.*, v.visit_id as visit_id,
            COALESCE(u.fullname, "Belum ditentukan") as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->where_in('v.status', ['approved', 'checked_in']);
        $this->db->order_by('v.scheduled_date', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * 🆕 Check apakah visitor punya kunjungan pending
     * 
     * @param int $visitor_id ID visitor
     * @return bool
     */
    public function has_pending_visit($visitor_id)
    {
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'pending');
        $count = $this->db->count_all_results($this->table);
        
        return $count > 0;
    }

    /**
     * 🆕 Check apakah visitor sedang check-in
     * 
     * @param int $visitor_id ID visitor
     * @return bool
     */
    public function is_currently_checked_in($visitor_id)
    {
        $this->db->where('visitor_id', $visitor_id);
        $this->db->where('status', 'checked_in');
        $count = $this->db->count_all_results($this->table);
        
        return $count > 0;
    }
}