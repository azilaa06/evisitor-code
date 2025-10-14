<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Ambil user berdasarkan username dan password.
     * Password di-database memakai MD5, sehingga di sini kita cocokkan dengan md5($password).
     * NOTE: MD5 tidak aman untuk produksi. Pertimbangkan migrasi ke password_hash().
     */
    public function get_user($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password)); // cocokan MD5 agar login berhasil sekarang
        $query = $this->db->get('users');
        return $query->row();
    }
}
