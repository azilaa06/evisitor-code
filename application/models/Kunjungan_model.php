<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kunjungan_model extends CI_Model
{
    private $table = 'visits'; // pastikan sesuai dengan nama tabel di database kamu

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
}
