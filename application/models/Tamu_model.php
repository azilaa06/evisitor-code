<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tamu_model extends CI_Model
{
    private $table = 'visitors';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Ambil tamu berdasarkan email
    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, ['email' => $email])->row_array();
    }

    // Login tamu
    public function login($email, $password)
    {
        $user = $this->get_by_email($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Register tamu
    public function register($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->insert($this->table, $data);
    }
}
