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

    // ini buat menghitung jumlah pengunjung menurut status.
    public function get_count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('visits'); // ganti 'visits' sesuai nama tabel kamu
    }
    //Hitung jumlah pengunjung hari ini
    public function get_today_visitors()
    {
        $this->db->where('DATE(scheduled_date)', date('Y-m-d'));
        return $this->db->count_all_results('visits');
    }
    //pengunjung yang sedang berkunjung sekarang
    public function get_sedang_berkunjung()
    {

        $this->db->where('check_out IS NULL', null, false);
        return $this->db->count_all_results('visits');
    }
    // Ngitung pengunjung yang sudah selesai berkunjung,
    public function get_telah_berkunjung()
    {
        $this->db->where('check_out IS NOT NULL', null, false);
        return $this->db->count_all_results('visits');
    }

    //Menampilkan data approved tapi belum checkout
    public function get_count_sedang_berkunjung()
    {
        $this->db->where('status', 'approved');
        $this->db->where('check_out IS NULL', null, false); // belum checkout
        return $this->db->count_all_results('visits');
    }

    //Menampilkan data approved tapi sudah checkout
    public function get_count_telah_berkunjung()
    {
        $this->db->where('status', 'approved');
        $this->db->where('check_out IS NOT NULL', null, false); // sudah checkout
        return $this->db->count_all_results('visits');
    }

    public function get_count_approved()
    {
        $this->db->where('status', 'approved');
        $this->db->where('check_out IS NOT NULL', null, false); // sudah checkout
        return $this->db->count_all_results('visits');
    }

    public function get_today_visitors_data()
    {
        $today = date('Y-m-d');
        return $this->db->where('DATE(scheduled_date)', $today)
            ->get('visits')
            ->result();
    }




    /**
     * Ambil data user berdasarkan user_id
     */
    public function get_user_by_id($id)
    {
        return $this->db->get_where($this->table, ['user_id' => $id])->row();
    }

    /**
     * Update data user berdasarkan user_id
     */
    public function update_user($id, $data) //(untuk hal profil)
    {
        $this->db->where('user_id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus user berdasarkan user_id
     */
    public function delete_user($id)
    {
        $this->db->where('user_id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Hapus foto lama dari folder uploads/profil/
     */
    public function delete_old_photo($filename)
    {
        $path = FCPATH . 'uploads/profil/' . $filename;
        if ($filename && file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Get all users
     */
    public function get_all_users()
    {
        return $this->db->get($this->table)->result();
    }
}
