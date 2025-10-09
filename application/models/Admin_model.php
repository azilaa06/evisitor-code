<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct(); //Memanggil constructor bawaan CodeIgniter(agar controller bisa jalan)
        $this->load->database(); //: mengaktifkan koneksi ke database
    }

    public function get_user($username, $password) //mengambil data user dari database
    {
        $this->db->where('username', $username);
        $this->db->where('username', $password);
        $query = $this->db->get('visitors'); // tabel visitors
        return $query->row(); // kembalikan object user jika ada
    }
}
