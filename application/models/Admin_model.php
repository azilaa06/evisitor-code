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

    /**
     * Ambil user berdasarkan username & password (untuk login)
     */
    public function get_user($username, $password)
    {
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        return $this->db->get($this->table)->row();
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
}
?>