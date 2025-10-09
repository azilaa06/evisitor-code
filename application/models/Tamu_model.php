<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tamu_model extends CI_Model
{
    private $table_visitors = 'visitors'; // tabel untuk user/visitor login
    private $table_visits   = 'visits';   // tabel untuk data kunjungan

    // ========================
    // Register Visitor
    // ========================
    public function register($data)
    {
        // cek email unik
        $this->db->where('email', $data['email']);
        if ($this->db->get($this->table_visitors)->num_rows() > 0) {
            return false;
        }

        // cek username unik
        $this->db->where('username', $data['username']);
        if ($this->db->get($this->table_visitors)->num_rows() > 0) {
            return false;
        }

        // hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->db->insert($this->table_visitors, $data);
        return $this->db->insert_id();
    }

    // alias supaya controller bisa tetap pakai insert_visitor()
    public function insert_visitor($data)
    {
        return $this->register($data);
    }

    // ========================
    // Login Visitor
    // ========================
    public function login($email, $password)
    {
        $visitor = $this->db->get_where($this->table_visitors, ['email' => $email])->row_array();

        if ($visitor && password_verify($password, $visitor['password'])) {
            return $visitor;
        }
        return false;
    }

    // tambahan method check_login (kalau kamu mau pakai cara lain)
    public function check_login($email, $password)
    {
        return $this->login($email, $password);
    }

    // ========================
    // Insert Visit
    // ========================
    public function insert_visit($data)
    {
        $this->db->insert($this->table_visits, $data);
        return $this->db->affected_rows() > 0;
    }

    // ========================
    // Get visits by visitor
    // ========================
    public function get_visits_by_visitor($visitor_id)
    {
        $this->db->where('visitor_id', $visitor_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table_visits)->result();
    }

    // ========================
    // Get visitor by email
    // ========================
    public function get_by_email($email)
    {
        return $this->db->get_where($this->table_visitors, ['email' => $email])->row_array();
    }
}
