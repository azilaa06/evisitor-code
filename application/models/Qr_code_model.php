<?php
// application/models/Qr_code_model.php

class Qr_code_model extends CI_Model {
    
    /**
     * ✅ FIXED: Generate QR Code sesuai struktur database Anda
     */
    public function generateQRCode($visit_id) {
        // Generate unique token
        $qr_token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 7));
        
        // Update KEDUA kolom: qr_token DAN qr_code
        $this->db->where('visit_id', $visit_id);
        $update = $this->db->update('visits', [
            'qr_token' => $qr_token,
            'qr_code' => $qr_token
        ]);
        
        return $update ? [
            'success' => true,
            'qr_token' => $qr_token
        ] : [
            'success' => false
        ];
    }

    /**
     * ✅ FIXED: Validasi QR Token sesuai enum status Anda
     * Enum: 'pending','approved','rejected','completed','checked_in','checked_out','cancelled','no_show'
     */
    public function validateQRToken($qr_token) {
        $qr_token = trim($qr_token);
        
        if (empty($qr_token)) {
            return [
                'success' => false,
                'message' => 'QR Token tidak valid (kosong)'
            ];
        }

        // Query dengan kolom yang sesuai
        $this->db->select('v.*, 
            vt.username as visitor_username,
            vt.fullname as visitor_fullname,
            vt.email as visitor_email,
            u.fullname as approved_by_name');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        
        // Cari di qr_token ATAU qr_code
        $this->db->group_start();
        $this->db->where('v.qr_token', $qr_token);
        $this->db->or_where('v.qr_code', $qr_token);
        $this->db->group_end();
        
        $visit = $this->db->get()->row();
        
        if (!$visit) {
            return [
                'success' => false,
                'message' => 'QR Code tidak ditemukan. Pastikan kunjungan sudah disetujui.'
            ];
        }

        // Cek status (sesuai enum database Anda)
        $status = strtolower(trim($visit->status));
        
        // Hanya 'approved' yang boleh check-in
        if ($status !== 'approved') {
            $status_messages = [
                'pending' => 'Kunjungan masih menunggu persetujuan',
                'rejected' => 'Kunjungan telah ditolak',
                'checked_in' => 'Sudah check-in sebelumnya',
                'checked_out' => 'Kunjungan sudah selesai (checked out)',
                'completed' => 'Kunjungan sudah diselesaikan',
                'cancelled' => 'Kunjungan dibatalkan',
                'no_show' => 'Visitor tidak datang'
            ];
            
            $message = isset($status_messages[$status]) 
                ? $status_messages[$status] 
                : 'Status tidak valid: ' . $visit->status;
            
            return [
                'success' => false,
                'message' => $message,
                'visit' => $visit
            ];
        }
        
        // Cek apakah sudah check-in
        if (!empty($visit->check_in)) {
            return [
                'success' => false,
                'message' => 'Visitor sudah melakukan check-in pada ' . date('d/m/Y H:i', strtotime($visit->check_in)),
                'visit' => $visit
            ];
        }
        
        // Valid - bisa check-in
        return [
            'success' => true,
            'visit' => $visit
        ];
    }

    /**
     * ✅ FIXED: Process Check-In
     */
    public function processCheckIn($qr_token) {
        $validation = $this->validateQRToken($qr_token);
        
        if (!$validation['success']) {
            return $validation;
        }
        
        $visit = $validation['visit'];
        
        // Update check-in time dan status ke 'checked_in'
        $this->db->where('visit_id', $visit->visit_id);
        $update = $this->db->update('visits', [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'checked_in'
        ]);
        
        if ($update) {
            // Ambil data terbaru
            $this->db->select('v.*, 
                vt.username as visitor_username,
                vt.fullname as visitor_fullname, 
                vt.email as visitor_email,
                u.fullname as approved_by_name');
            $this->db->from('visits v');
            $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
            $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
            $this->db->where('v.visit_id', $visit->visit_id);
            
            $updated_visit = $this->db->get()->row();
            
            if ($updated_visit) {
                $updated_visit->check_in_formatted = date('d/m/Y H:i:s', strtotime($updated_visit->check_in));
            }
            
            $visitor_name = !empty($updated_visit->fullname) ? $updated_visit->fullname : 
                           (!empty($updated_visit->visitor_fullname) ? $updated_visit->visitor_fullname : 'Visitor');
            
            return [
                'success' => true,
                'message' => 'Check-in berhasil! Selamat datang ' . $visitor_name,
                'visit' => $updated_visit
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal melakukan check-in. Silakan coba lagi.'
        ];
    }

    /**
     * ✅ FIXED: Process Check-Out
     */
    public function processCheckOut($visit_id) {
        $this->db->select('v.*, vt.username as visitor_username, vt.fullname as visitor_fullname');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->where('v.visit_id', $visit_id);
        $this->db->where('v.status', 'checked_in');
        
        $visit = $this->db->get()->row();
        
        if (!$visit) {
            return [
                'success' => false,
                'message' => 'Kunjungan tidak ditemukan atau belum check-in'
            ];
        }
        
        if (!empty($visit->check_out)) {
            return [
                'success' => false,
                'message' => 'Visitor sudah melakukan check-out sebelumnya'
            ];
        }
        
        // Update check-out time dan status ke 'checked_out'
        $this->db->where('visit_id', $visit_id);
        $update = $this->db->update('visits', [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'checked_out'
        ]);
        
        if ($update) {
            $this->db->select('v.*, vt.username as visitor_username, vt.fullname as visitor_fullname');
            $this->db->from('visits v');
            $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
            $this->db->where('v.visit_id', $visit_id);
            
            $updated_visit = $this->db->get()->row();
            
            return [
                'success' => true,
                'message' => 'Check-out berhasil. Terima kasih atas kunjungannya!',
                'visit' => $updated_visit
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal melakukan check-out. Silakan coba lagi.'
        ];
    }

    /**
     * Get visit by token
     */
    public function getVisitByToken($qr_token) {
        $this->db->select('v.*, 
            vt.username as visitor_username,
            vt.fullname as visitor_fullname, 
            vt.email as visitor_email, 
            u.fullname as approved_by_name');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        
        $this->db->group_start();
        $this->db->where('v.qr_token', $qr_token);
        $this->db->or_where('v.qr_code', $qr_token);
        $this->db->group_end();
        
        return $this->db->get()->row();
    }

    /**
     * Get visit by ID
     */
    public function getVisitById($visit_id) {
        $this->db->select('v.*, 
            vt.username as visitor_username,
            vt.fullname as visitor_fullname, 
            vt.email as visitor_email, 
            u.fullname as approved_by_name');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visit_id', $visit_id);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            return (array) $result;
        }
        
        return null;
    }

    /**
     * Get active visits (status = 'checked_in')
     */
    public function getActiveVisits() {
        $this->db->select('v.*, 
            vt.username as visitor_username,
            vt.fullname as visitor_fullname, 
            vt.email as visitor_email,
            u.fullname as approved_by_name');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.status', 'checked_in');
        $this->db->order_by('v.check_in', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get all visits dengan QR Code
     */
    public function getAllVisitsWithQR() {
        $this->db->select('v.*, 
            vt.username as visitor_username,
            vt.fullname as visitor_fullname, 
            vt.email as visitor_email, 
            u.fullname as approved_by_name');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.qr_token IS NOT NULL', null, false);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get statistics
     */
    public function getCheckInStatistics($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }

        // Total check-in hari ini
        $this->db->where('DATE(check_in)', $date);
        $this->db->where_in('status', ['checked_in', 'checked_out', 'completed']);
        $total_checkin = $this->db->count_all_results('visits');

        // Masih aktif (checked_in, belum checkout)
        $this->db->where('DATE(check_in)', $date);
        $this->db->where('status', 'checked_in');
        $active = $this->db->count_all_results('visits');

        // Sudah checkout
        $this->db->where('DATE(check_in)', $date);
        $this->db->where_in('status', ['checked_out', 'completed']);
        $completed = $this->db->count_all_results('visits');

        return [
            'total_checkin' => $total_checkin,
            'active' => $active,
            'completed' => $completed,
            'date' => $date
        ];
    }
}