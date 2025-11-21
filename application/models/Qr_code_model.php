<?php
// application/models/Qr_code_model.php

class Qr_code_model extends CI_Model {
    
    /**
     * ✅ SELECT HANYA kolom yang ADA di database dan DIPERLUKAN untuk QR Code view
     * Tabel visitors: visitor_id, username, email (TIDAK ADA fullname, foto)
     * Tabel visits: semua data lengkap visitor
     * Tabel users: fullname untuk approver
     */
    private function selectVisitColumns() {
        return 'v.visit_id, v.visitor_id, v.fullname, v.id_number, v.phone, 
                v.institution, v.purpose, v.to_whom, v.scheduled_date, 
                v.check_in, v.check_out, v.status, v.qr_code, v.qr_token, 
                v.approved_by, v.approved_at, v.created_at,
                vt.username as visitor_username,
                vt.email as visitor_email,
                u.fullname as approved_by_name';
    }
    
    /**
     * Generate QR Code dan Token setelah visit di-approve
     */
    public function generateQRCode($visit_id) {
        // Generate unique token (64 character)
        $qr_token = bin2hex(random_bytes(32));
        
        // QR Code data dalam format JSON
        $qr_data = json_encode([
            'visit_id' => $visit_id,
            'token' => $qr_token,
            'timestamp' => time()
        ]);
        
        // Encode QR data
        $qr_code = base64_encode($qr_data);
        
        // Update database dengan kedua kolom
        $this->db->where('visit_id', $visit_id);
        $update = $this->db->update('visits', [
            'qr_code' => $qr_code,
            'qr_token' => $qr_token
        ]);
        
        return $update ? [
            'success' => true,
            'qr_code' => $qr_code,
            'qr_token' => $qr_token
        ] : [
            'success' => false
        ];
    }

    /**
     * Validate QR Token untuk check-in
     */
    public function validateQRToken($qr_token) {
        $qr_token = trim($qr_token);
        
        if (empty($qr_token)) {
            return [
                'success' => false,
                'message' => 'QR Token tidak valid (kosong)'
            ];
        }

        $this->db->select($this->selectVisitColumns());
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        
        $this->db->group_start();
        $this->db->where('v.qr_token', $qr_token);
        $this->db->or_where('v.qr_code', $qr_token);
        $this->db->group_end();
        
        $visit = $this->db->get()->row();
        
        if (!$visit) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid atau kunjungan belum disetujui'
            ];
        }

        $status = strtolower(trim($visit->status));
        
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
        
        if (!empty($visit->check_in) && $visit->check_in !== null) {
            return [
                'success' => false,
                'message' => 'Visitor sudah melakukan check-in pada ' . date('d/m/Y H:i', strtotime($visit->check_in)),
                'visit' => $visit
            ];
        }
        
        return [
            'success' => true,
            'visit' => $visit
        ];
    }

    /**
     * Process Check-In
     */
    public function processCheckIn($qr_token) {
        $validation = $this->validateQRToken($qr_token);
        
        if (!$validation['success']) {
            return $validation;
        }
        
        $visit = $validation['visit'];
        
        $this->db->where('visit_id', $visit->visit_id);
        $update = $this->db->update('visits', [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'checked_in'
        ]);
        
        if ($update) {
            $this->db->select($this->selectVisitColumns());
            $this->db->from('visits v');
            $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
            $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
            $this->db->where('v.visit_id', $visit->visit_id);
            
            $updated_visit = $this->db->get()->row();
            
            if ($updated_visit) {
                $updated_visit->check_in_formatted = date('d/m/Y H:i:s', strtotime($updated_visit->check_in));
            }
            
            $visitor_name = !empty($updated_visit->fullname) ? $updated_visit->fullname : 
                           (!empty($updated_visit->visitor_username) ? $updated_visit->visitor_username : 'Visitor');
            
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
     * Process Check-Out
     */
    public function processCheckOut($visit_id) {
        $this->db->select($this->selectVisitColumns());
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
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
        
        $this->db->where('visit_id', $visit_id);
        $update = $this->db->update('visits', [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'checked_out'
        ]);
        
        if ($update) {
            $this->db->select($this->selectVisitColumns());
            $this->db->from('visits v');
            $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
            $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
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
     * Get visit detail by QR token
     */
    public function getVisitByToken($qr_token) {
        $this->db->select($this->selectVisitColumns());
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
     * Get visit detail by visit_id
     */
    public function getVisitById($visit_id) {
        $this->db->select($this->selectVisitColumns());
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.visit_id', $visit_id);
        
        $result = $this->db->get()->row();
        
        // Konversi ke array untuk konsistensi dengan controller lain
        if ($result) {
            return (array) $result;
        }
        
        return null;
    }

    /**
     * Get active visits (checked-in but not checked-out yet)
     */
    public function getActiveVisits() {
        $this->db->select($this->selectVisitColumns());
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.status', 'checked_in');
        $this->db->where('v.check_out IS NULL', null, false);
        $this->db->order_by('v.check_in', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get all visits with QR Code
     */
    public function getAllVisitsWithQR() {
        $this->db->select($this->selectVisitColumns());
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.qr_token IS NOT NULL', null, false);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get statistics untuk dashboard
     */
    public function getCheckInStatistics($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $this->db->where('DATE(check_in)', $date);
        $this->db->where_in('status', ['checked_in', 'checked_out', 'completed']);
        $total_checkin = $this->db->count_all_results('visits');

        $this->db->where('DATE(check_in)', $date);
        $this->db->where('status', 'checked_in');
        $active = $this->db->count_all_results('visits');

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