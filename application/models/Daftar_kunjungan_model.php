<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Daftar_kunjungan_model extends CI_Model
{
    private $table = 'visits';
    private $table_users = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_visitor($visitor_id)
    {
        $this->db->select('v.*, u.fullname as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visitor_id', $visitor_id);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_detail($visit_id)
    {
        $this->db->select('v.*, u.fullname as penanggung_jawab');
        $this->db->from($this->table . ' v');
        $this->db->join($this->table_users . ' u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visit_id', $visit_id);
        
        return $this->db->get()->row_array();
    }
}