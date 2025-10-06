<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Ambil semua data kunjungan
    public function get_all()
    {
        return $this->db->get('kunjungan')->result_array();
    }

    // Ambil data kunjungan berdasarkan status
    public function get_by_status($status)
    {
        return $this->db->get_where('kunjungan', ['status' => $status])->result_array();
    }
}
