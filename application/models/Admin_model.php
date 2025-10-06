<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // <--- load database
    }

    // Ambil user berdasarkan username, password, nama_lengkap
    public function get_user($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('username', $password);
        $query = $this->db->get('visitors'); // tabel visitors
        return $query->row(); // kembalikan object user jika ada
    }
}
