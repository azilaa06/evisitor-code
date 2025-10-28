<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manajemen_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Ambil semua data kunjungan dengan join ke tabel users
    public function get_all()
    {
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Ambil data kunjungan berdasarkan status
    public function get_by_status($status)
    {
        $status_map = [
            'ditolak' => 'rejected',
            'berkunjung' => 'approved',
            'menunggu' => 'pending',
            'selesai' => 'completed'
        ];

        $db_status = isset($status_map[strtolower($status)]) ? $status_map[strtolower($status)] : $status;

        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.status', $db_status);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Ambil pengunjung hari ini
    public function get_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.qr_code,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('DATE(visits.scheduled_date)', $today);
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // Hitung total berdasarkan status
    public function count_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('visits');
    }

    // Hitung pengunjung hari ini
    public function count_today_visitors()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(scheduled_date)', $today);
        return $this->db->count_all_results('visits');
    }

    // Ambil detail kunjungan berdasarkan ID
    public function get_by_id($visit_id)
    {
        $this->db->select('
            visits.*, 
            visits.fullname AS visitor_name,
            visits.id_number,
            visits.phone,
            visits.institution,
            visits.purpose,
            visits.to_whom,
            u1.fullname AS handled_by_name,
            u2.fullname AS approved_by_name
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');
        $this->db->where('visits.visit_id', $visit_id);
        return $this->db->get()->row_array();
    }

    // Update status kunjungan
    public function update_status($visit_id, $status, $user_id = null)
    {
        $data = [
            'status' => $status,
            'approved_at' => date('Y-m-d H:i:s')
        ];

        if ($user_id) {
            $data['approved_by'] = $user_id;
        }

        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // Update check in
    public function check_in($visit_id)
    {
        $data = [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'approved'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // Update check out
    public function check_out($visit_id)
    {
        $data = [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ];
        $this->db->where('visit_id', $visit_id);
        return $this->db->update('visits', $data);
    }

    // Search kunjungan (SUDAH DIPERBAIKI - ADA JOIN)
    public function search($keyword = null)
    {
        $this->db->select('
            visits.visit_id,
            visits.scheduled_date AS TANGGAL,
            visits.check_in,
            visits.check_out,
            visits.status,
            visits.fullname AS NAMA,
            visits.id_number AS NIK,
            visits.phone,
            visits.institution AS INSTANSI,
            u1.fullname AS PETUGAS,
            u2.fullname AS PENANGGUNG_JAWAB
        ');
        $this->db->from('visits');
        $this->db->join('users u1', 'visits.handled_by = u1.user_id', 'left');
        $this->db->join('users u2', 'visits.approved_by = u2.user_id', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('visits.fullname', $keyword);
            $this->db->or_like('visits.id_number', $keyword);
            $this->db->or_like('visits.institution', $keyword);
            $this->db->group_end();
        }
        
        $this->db->order_by('visits.scheduled_date', 'DESC');
        return $this->db->get()->result_array();
    }
}