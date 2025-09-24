<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tamu_model extends CI_Model
{
    private $table = 'visitors'; // ganti sesuai nama tabel

    public function register($data)
    {
        // cek username unik
        $this->db->where('username', $data['username']);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return false; // username sudah dipakai
        }

        // insert data
        $this->db->insert($this->table, $data);
        return $this->db->insert_id(); // balikin ID user baru
    }
}
