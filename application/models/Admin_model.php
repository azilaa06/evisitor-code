<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    private $table = 'users'; // tambahkan baris ini

    public function __construct()
    {
        parent::__construct(); // Memanggil constructor bawaan CodeIgniter
        $this->load->database(); // Mengaktifkan koneksi ke database
    }

    public function get_user($username, $password)
    {
        // Ambil user berdasarkan username
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        $user = $query->row(); // Ambil satu baris data user

        // Jika user ditemukan, cek apakah password cocok dengan hash di database
        if ($user && password_verify($password, $user->password)) {
            return $user; // Login berhasil
        } else {
            return null; // Login gagal
        }
    }

    // Hitung jumlah pengunjung menurut status
    public function get_count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('visits'); // pastikan tabelnya benar
    }
}
