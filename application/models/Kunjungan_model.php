<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kunjungan_model extends CI_Model
{
    private $table = 'visits'; // NAMA TABEL (prefix e_visitor_ otomatis dari config)
    private $table_users = 'users'; // TABEL USERS

    // 🟢 Simpan data kunjungan baru
    public function insert_visit($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🟢 Ambil kunjungan terakhir berdasarkan visitor_id (dengan nama penanggung jawab)
    public function get_last_visit_by_visitor($visitor_id)
    {
        $this->db->select('v.*, u.fullname as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.visit_id', 'DESC');
        $this->db->limit(1);
        
        return $this->db->get()->row_array();
    }

    // 🟢 Ambil semua kunjungan berdasarkan visitor_id (untuk list pengunjung)
    public function get_visit_by_guest($visitor_id)
    {
        return $this->db->where('visitor_id', $visitor_id)
            ->get($this->table)
            ->result_array(); // semua kunjungan pengunjung
    }

    // 🟢 Ambil detail satu kunjungan berdasarkan visit_id
    public function get_visit_by_id($visit_id)
    {
        return $this->db->where('visit_id', $visit_id)
            ->get($this->table)
            ->row_array(); // hanya 1 row
    }

    // 🟢 Update status kunjungan
    public function update_status($visit_id, $status)
    {
        $this->db->where('visit_id', $visit_id);
        return $this->db->update($this->table, ['status' => $status]); // ✅ Pakai $this->table
    }
}