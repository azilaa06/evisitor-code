<?php
// application/models/Qr_code_model.php

class Qr_code_model extends CI_Model {
    
    /**
     * Generate QR Code dan Token setelah visit di-approve
     */
    public function generateQRCode($visit_id) {
        // Generate unique token
        $qr_token = bin2hex(random_bytes(32)); // 64 character token
        
        // QR Code data
        $qr_data = json_encode([
            'visit_id' => $visit_id,
            'token' => $qr_token,
            'timestamp' => time()
        ]);
        
        // Encode QR data
        $qr_code = base64_encode($qr_data);
        
        // Update database
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
     * Validasi QR Token untuk check-in
     */
    public function validateQRToken($qr_token) {
        $this->db->where('qr_token', $qr_token);
        $this->db->where('status', 'approved');
        $visit = $this->db->get('visits')->row();
        
        if (!$visit) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid atau kunjungan belum disetujui'
            ];
        }
        
        // Cek apakah sudah check-in
        if ($visit->check_in !== null) {
            return [
                'success' => false,
                'message' => 'Visitor sudah melakukan check-in sebelumnya',
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
        // Validasi token
        $validation = $this->validateQRToken($qr_token);
        
        if (!$validation['success']) {
            return $validation;
        }
        
        $visit = $validation['visit'];
        
        // Update check-in time dan status
        $this->db->where('visit_id', $visit->visit_id);
        $update = $this->db->update('visits', [
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'checked_in'
        ]);
        
        if ($update) {
            $updated_visit = $this->db->get_where('visits', [
                'visit_id' => $visit->visit_id
            ])->row();
            
            return [
                'success' => true,
                'message' => 'Check-in berhasil',
                'visit' => $updated_visit
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal melakukan check-in'
        ];
    }

    /**
     * Process Check-Out
     */
    public function processCheckOut($visit_id) {
        $this->db->where('visit_id', $visit_id);
        $this->db->where('status', 'checked_in');
        $visit = $this->db->get('visits')->row();
        
        if (!$visit) {
            return [
                'success' => false,
                'message' => 'Kunjungan tidak ditemukan atau belum check-in'
            ];
        }
        
        if ($visit->check_out !== null) {
            return [
                'success' => false,
                'message' => 'Visitor sudah melakukan check-out sebelumnya'
            ];
        }
        
        // Update check-out time dan status
        $this->db->where('visit_id', $visit_id);
        $update = $this->db->update('visits', [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'checked_out'
        ]);
        
        if ($update) {
            $updated_visit = $this->db->get_where('visits', [
                'visit_id' => $visit_id
            ])->row();
            
            return [
                'success' => true,
                'message' => 'Check-out berhasil',
                'visit' => $updated_visit
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal melakukan check-out'
        ];
    }

    /**
     * Get visit detail by QR token
     * ✅ FIX: Ambil semua data lengkap dari tabel visits
     */
    public function getVisitByToken($qr_token) {
        $this->db->select('v.*, vt.username as visitor_username, vt.email as visitor_email, u.fullname as host_name');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.qr_token', $qr_token);
        
        return $this->db->get()->row();
    }

    /**
     * Get visit detail by visit_id
     * ✅ FIX: Ambil data lengkap termasuk fullname, id_number, phone, institution, dll dari tabel visits
     */
    public function getVisitById($visit_id) {
        $this->db->select('v.*, 
            vt.username as visitor_username, 
            vt.email as visitor_email, 
            u.fullname as penanggung_jawab');
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
     * Get active visits (checked-in but not checked-out)
     */
    public function getActiveVisits() {
        $this->db->select('v.*, vt.username as visitor_username, vt.email as visitor_email');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->where('v.status', 'checked_in');
        $this->db->where('v.check_out IS NULL', null, false);
        $this->db->order_by('v.check_in', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get all visits with QR Code
     * ✅ FIX: Gunakan approved_by sebagai pengganti handled_by
     */
    public function getAllVisitsWithQR() {
        $this->db->select('v.*, 
            vt.username as visitor_username, 
            vt.email as visitor_email, 
            u.fullname as penanggung_jawab');
        $this->db->from('visits v');
        $this->db->join('visitors vt', 'v.visitor_id = vt.visitor_id', 'left');
        $this->db->join('users u', 'v.approved_by = u.user_id', 'left');
        $this->db->where('v.qr_token IS NOT NULL', null, false);
        $this->db->order_by('v.created_at', 'DESC');
        
        return $this->db->get()->result();
    }
}