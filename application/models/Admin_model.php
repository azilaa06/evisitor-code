<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    private $table = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ========================================
    // 🔹 FITUR DARI BRANCH MAIN (Login dengan password_verify)
    // ========================================
    
    /**
     * Ambil user berdasarkan username & password (untuk login)
     * Menggunakan password_verify untuk keamanan
     */
    public function get_user($username, $password)
    {
        // Ambil user berdasarkan username
        $this->db->where('username', $username);
        $query = $this->db->get($this->table);
        $user = $query->row();
        
        // Jika user ditemukan, cek password dengan hash
        if ($user && password_verify($password, $user->password)) {
            return $user; // Login berhasil
        } 
        
        // Fallback: Cek dengan MD5 (untuk data lama yang masih pakai MD5)
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $query_md5 = $this->db->get($this->table);
        
        if ($query_md5->num_rows() > 0) {
            return $query_md5->row();
        }
        
        return null; // Login gagal
    }

    // ========================================
    // 🔹 FITUR DARI BRANCH TIA (Profil & Management User)
    // ========================================
    
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
    public function update_user($id, $data)
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

    // ========================================
    // 🔹 FITUR DARI BRANCH MAIN (Count by status)
    // ========================================
    
    /**
     * Hitung jumlah pengunjung menurut status
     */
    public function get_count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('visits');
    }
}
?>