<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property CI_Form_validation $form_validation
 * @property Admin_model $Admin_model
 * @property CI_Upload $upload
 * @property Qr_code_model $Qr_code_model
 * @property CI_Router $router
 */
class Qr_code extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Qr_code_model');
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);
        $this->load->database();

        $public_methods = ['view_by_token'];
        if (!in_array($this->router->method, $public_methods)) {
            if (!$this->session->userdata('user_id') && !$this->session->userdata('visitor_id')) {
                redirect('auth/login');
            }
        }
    }

    /** 🔹 Menampilkan QR Code dari kunjungan TERAKHIR yang APPROVED */
    public function index() {
        $visitor_id = $this->session->userdata('visitor_id');

        if ($visitor_id) {
            $this->db->select('visit_id, status, qr_token');
            $this->db->where('visitor_id', $visitor_id);
            $this->db->where('status', 'approved');
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(1);
            $visit = $this->db->get('visits')->row();

            if ($visit) {
                if (empty($visit->qr_token)) {
                    $this->Qr_code_model->generateQRCode($visit->visit_id);
                }
                redirect('qr_code/view/' . $visit->visit_id);
            } else {
                $data['title'] = 'QR Code Saya';
                $data['active_page'] = 'qr_code';
                $data['has_qr'] = false;
                $data['message'] = 'Anda belum memiliki QR Code. Silakan tunggu persetujuan kunjungan Anda.';
                
                $this->load->view('Layouts/sidebar', $data);
                $this->load->view('tamu/qr_code_empty', $data);
            }
        } else {
            $data['title'] = 'QR Code Management';
            $data['active_page'] = 'qr_code';
            $data['visits'] = $this->Qr_code_model->getAllVisitsWithQR();

            $this->load->view('Layouts/sidebar', $data);
            $this->load->view('tamu/qr_code_list', $data);
        }
    }

    /** 🔹 Generate QR Code */
    public function generate($visit_id) {
        $visit = $this->db->get_where('visits', ['visit_id' => $visit_id])->row();

        if (!$visit) {
            $this->session->set_flashdata('error', 'Kunjungan tidak ditemukan.');
            redirect('tamu/dashboard');
        }

        if ($visit->status !== 'approved') {
            $this->session->set_flashdata('error', 'Kunjungan belum disetujui.');
            redirect('tamu/dashboard');
        }

        $result = $this->Qr_code_model->generateQRCode($visit_id);

        if ($result['success']) {
            $this->session->set_flashdata('success', 'QR Code berhasil digenerate.');
        } else {
            $this->session->set_flashdata('error', 'Gagal generate QR Code.');
        }

        redirect('qr_code/view/' . $visit_id);
    }

    /** 🔹 View QR Code dengan sidebar dan data lengkap */
    public function view($visit_id) {
        $visit = $this->Qr_code_model->getVisitById($visit_id);

        if (!$visit) {
            show_404();
        }

        $data['title'] = 'QR Code - ' . ($visit['fullname'] ?? 'Visitor');
        $data['active_page'] = 'qr_code';
        $data['nama'] = $this->session->userdata('nama');
        
        $session_username = $this->session->userdata('username');
        $data['username'] = !empty($session_username) ? $session_username : ($visit['visitor_username'] ?? $visit['fullname'] ?? 'Guest');
        
        $data['visit'] = $visit;
        $data['qr_token'] = $visit['qr_token'] ?? '';

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/qr_code', $data);
    }

    /** 🔹 View QR Code via token (public) */
    public function view_by_token($qr_token) {
        $visit = $this->Qr_code_model->getVisitByToken($qr_token);

        if (!$visit) {
            show_404();
        }

        $data['title'] = 'QR Code - ' . ($visit->fullname ?? 'Visitor');
        $data['visit'] = (array) $visit;
        $data['qr_token'] = $visit->qr_token;
        $data['username'] = $visit->visitor_username ?? 'Guest';

        $this->load->view('tamu/qr_code_public', $data);
    }

    /** 🔹 Halaman Scan QR */
    public function scan() {
        $data['title'] = 'Scan QR Code';
        $data['active_page'] = 'scan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/scan_qr', $data);
    }

   /**
 * ✅ ULTRA SAFE VERSION - Check-in dengan query yang sangat aman
 * Fix untuk Database Error HTTP 500
 */
public function checkin() {
    // Set header PERTAMA KALI sebelum output apapun
    header('Content-Type: application/json; charset=utf-8');
    
    // Matikan display errors
    @ini_set('display_errors', 0);
    error_reporting(0);
    
    // Log request
    log_message('debug', '=== CHECK-IN START ===');
    log_message('debug', 'POST: ' . json_encode($_POST));
    
    try {
        // Only POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        // Get token
        $qr_token = trim($this->input->post('qr_token', true));
        
        if (empty($qr_token)) {
            echo json_encode(['success' => false, 'message' => 'QR Token kosong']);
            exit;
        }
        
        log_message('debug', 'QR Token: ' . $qr_token);
        
        // ✅ QUERY PALING AMAN - Tanpa alias, tanpa kompleksitas
        // Cari visit berdasarkan qr_token atau qr_code
        $this->db->where('qr_token', $qr_token);
        $this->db->or_where('qr_code', $qr_token);
        $visit = $this->db->get('visits')->row_array();
        
        log_message('debug', 'SQL: ' . $this->db->last_query());
        
        // Check DB error
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', 'DB Error: ' . json_encode($db_error));
            echo json_encode([
                'success' => false,
                'message' => 'Database error: ' . $db_error['message'],
                'debug' => ['sql' => $this->db->last_query()]
            ]);
            exit;
        }
        
        // Visit not found
        if (!$visit) {
            log_message('warning', 'Visit not found for token: ' . $qr_token);
            echo json_encode([
                'success' => false,
                'message' => 'QR Code tidak ditemukan di database'
            ]);
            exit;
        }
        
        log_message('debug', 'Visit found: ID=' . $visit['visit_id'] . ', Status=' . $visit['status']);
        
        // Check status
        if (strtolower($visit['status']) !== 'approved') {
            $status_messages = [
                'pending' => 'Kunjungan masih menunggu persetujuan',
                'rejected' => 'Kunjungan ditolak',
                'checked_in' => 'Sudah check-in pada ' . date('d/m/Y H:i', strtotime($visit['check_in'])),
                'checked_out' => 'Sudah check-out',
                'completed' => 'Kunjungan sudah selesai',
                'cancelled' => 'Kunjungan dibatalkan',
                'no_show' => 'Visitor tidak datang'
            ];
            
            $message = isset($status_messages[$visit['status']]) 
                ? $status_messages[$visit['status']] 
                : 'Status tidak valid: ' . $visit['status'];
            
            echo json_encode(['success' => false, 'message' => $message]);
            exit;
        }
        
        // Check if already checked in
        if (!empty($visit['check_in'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sudah check-in pada ' . date('d/m/Y H:i', strtotime($visit['check_in']))
            ]);
            exit;
        }
        
        // ✅ DO CHECK-IN
        $check_in_time = date('Y-m-d H:i:s');
        
        log_message('debug', 'Updating visit_id: ' . $visit['visit_id']);
        
        $this->db->where('visit_id', $visit['visit_id']);
        $update = $this->db->update('visits', [
            'check_in' => $check_in_time,
            'status' => 'checked_in'
        ]);
        
        log_message('debug', 'Update SQL: ' . $this->db->last_query());
        log_message('debug', 'Update result: ' . ($update ? 'TRUE' : 'FALSE'));
        
        // Check update error
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', 'Update Error: ' . json_encode($db_error));
            echo json_encode([
                'success' => false,
                'message' => 'Gagal update database',
                'debug' => ['sql' => $this->db->last_query()]
            ]);
            exit;
        }
        
        if (!$update) {
            log_message('error', 'Update returned FALSE (no rows affected)');
            echo json_encode([
                'success' => false,
                'message' => 'Update gagal (no rows affected)'
            ]);
            exit;
        }
        
        // ✅ SUCCESS - Ambil data visitor untuk response
        $this->db->where('visitor_id', $visit['visitor_id']);
        $visitor = $this->db->get('visitors')->row_array();
        
        // Update visit array dengan data terbaru
        $visit['check_in'] = $check_in_time;
        $visit['status'] = 'checked_in';
        
        // Tentukan nama untuk display (cek visitor_id dulu)
        $name = 'Visitor';
        if (!empty($visit['fullname'])) {
            $name = $visit['fullname'];
        } elseif ($visitor && !empty($visitor['fullname'])) {
            $name = $visitor['fullname'];
        } elseif ($visitor && !empty($visitor['username'])) {
            $name = $visitor['username'];
        }
        
        log_message('info', 'Check-in SUCCESS for visit_id: ' . $visit['visit_id'] . ', visitor: ' . $name);
        
        // Merge data visitor ke visit untuk response
        if ($visitor) {
            $visit['visitor_fullname'] = $visitor['fullname'] ?? '';
            $visit['visitor_username'] = $visitor['username'] ?? '';
            $visit['visitor_email'] = $visitor['email'] ?? '';
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Check-in berhasil! Selamat datang ' . $name,
            'visit' => $visit
        ]);
        
        log_message('debug', '=== CHECK-IN END ===');
        exit;
        
    } catch (Exception $e) {
        log_message('error', 'Exception: ' . $e->getMessage());
        log_message('error', 'Trace: ' . $e->getTraceAsString());
        
        echo json_encode([
            'success' => false,
            'message' => 'System error: ' . $e->getMessage(),
            'debug' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ]);
        exit;
    }
}
    /** 
     * 🔹 Proses Check-out 
     * Bisa dipanggil via AJAX atau redirect
     */
    public function checkout($visit_id) {
        // Validasi visit_id
        if (empty($visit_id)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID kunjungan tidak valid'
                ]);
            } else {
                $this->session->set_flashdata('error', 'ID kunjungan tidak valid');
                redirect('qr_code/active');
            }
            return;
        }

        // Process check-out
        $result = $this->Qr_code_model->processCheckOut($visit_id);

        // Response
        if ($this->input->is_ajax_request()) {
            // Convert object to array
            if (isset($result['visit']) && is_object($result['visit'])) {
                $result['visit'] = (array) $result['visit'];
            }
            echo json_encode($result);
        } else {
            if ($result['success']) {
                $this->session->set_flashdata('success', $result['message']);
            } else {
                $this->session->set_flashdata('error', $result['message']);
            }
            redirect('qr_code/active');
        }
    }

    /** 🔹 Daftar visitor yang masih aktif (checked-in) */
    public function active() {
        $data['title'] = 'Active Visitors';
        $data['active_page'] = 'active';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');
        $data['visits'] = $this->Qr_code_model->getActiveVisits();

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/active_visitors', $data);
    }

    /**
     * ✅ BONUS: API untuk validasi QR tanpa check-in
     * Untuk preview data sebelum confirm check-in
     */
    public function validate() {
        header('Content-Type: application/json');

        $qr_token = $this->input->post('qr_token', true);

        if (empty($qr_token)) {
            echo json_encode([
                'success' => false,
                'message' => 'QR Token kosong'
            ]);
            return;
        }

        $result = $this->Qr_code_model->validateQRToken(trim($qr_token));

        if (isset($result['visit']) && is_object($result['visit'])) {
            $result['visit'] = (array) $result['visit'];
        }

        echo json_encode($result);
    }

    /**
     * ✅ BONUS: Dashboard statistics
     */
    public function statistics() {
        $date = $this->input->get('date') ?? date('Y-m-d');
        $stats = $this->Qr_code_model->getCheckInStatistics($date);

        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode($stats);
        } else {
            $data['title'] = 'Check-in Statistics';
            $data['active_page'] = 'statistics';
            $data['nama'] = $this->session->userdata('nama');
            $data['username'] = $this->session->userdata('username');
            $data['stats'] = $stats;

            $this->load->view('Layouts/sidebar', $data);
            $this->load->view('admin/statistics', $data);
        }
    }
}