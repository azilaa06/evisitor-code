<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct(); // Memanggil constructor bawaan CodeIgniter
        $this->load->database(); // Mengaktifkan koneksi ke database
    }

    public function get_user($username, $password)
    {
        // Ambil user berdasarkan username (bisa email juga)
        $this->db->where('username', $username); //cari user berdasarkan username/email.
        $query = $this->db->get('users');
        $user = $query->row(); // Ambil satu baris data user

        // Jika user ditemukan, cek apakah password cocok dengan hash di database
        if ($user && password_verify($password, $user->password)) { //membandingkan input password dengan hash di database.
            return $user; // Login berhasil
        } else {
            return null; // Login gagal
        }
    }
}
