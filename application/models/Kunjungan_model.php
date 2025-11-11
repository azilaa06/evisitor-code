<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kunjungan_model extends CI_Model
{
    private $table = 'visits'; // pastikan sesuai nama tabel di database

    
    // 🟢 Simpan data kunjungan baru
    public function insert_visit($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 🟢 Ambil kunjungan terakhir berdasarkan visitor_id
    public function get_last_visit_by_visitor($visitor_id)
    {
        return $this->db->where('visitor_id', $visitor_id)
            ->order_by('visit_id', 'DESC')
            ->limit(1)
            ->get($this->table)
            ->row_array();
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


    //Model yang bertugas menjalankan query untuk mengubah data di tabel.
    public function update_status($visit_id, $status)
    {
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', ['status' => $status]);
    }
}
