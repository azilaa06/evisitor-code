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
            // Ambil kunjungan TERAKHIR yang statusnya APPROVED
            $this->db->select('visit_id, status, qr_token');
            $this->db->where('visitor_id', $visitor_id);
            $this->db->where('status', 'approved'); // Hanya yang sudah approved
            $this->db->order_by('created_at', 'DESC'); // Urutkan dari terbaru
            $this->db->limit(1); // Ambil 1 terakhir saja
            $visit = $this->db->get('visits')->row();

            if ($visit) {
                // Jika belum ada QR token, generate dulu
                if (empty($visit->qr_token)) {
                    $this->Qr_code_model->generateQRCode($visit->visit_id);
                }
                
                // Redirect ke halaman view QR Code
                redirect('qr_code/view/' . $visit->visit_id);
            } else {
                // Tidak ada kunjungan yang approved
                $data['title'] = 'QR Code Saya';
                $data['active_page'] = 'qr_code';
                $data['has_qr'] = false;
                $data['message'] = 'Anda belum memiliki QR Code. Silakan tunggu persetujuan kunjungan Anda.';
                
                $this->load->view('Layouts/sidebar', $data);
                $this->load->view('tamu/qr_code_empty', $data);
            }
        } else {
            // Tampilan untuk admin/staff
            $data['title'] = 'QR Code Management';
            $data['active_page'] = 'qr_code';
            $data['visits'] = $this->Qr_code_model->getAllVisitsWithQR();

            $this->load->view('Layouts/sidebar', $data);
            $this->load->view('tamu/qr_code_list', $data);
        }
    }

    /** 🔹 Generate QR Code */
    public function generate($visit_id) {
        // ✅ FIX: Gunakan 'visits' tanpa prefix
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

        // Data untuk sidebar
        $data['title'] = 'QR Code - ' . ($visit['fullname'] ?? 'Visitor');
        $data['active_page'] = 'qr_code';
        $data['nama'] = $this->session->userdata('nama');
        
        // Kirim username dari session (visitor yang login)
        $session_username = $this->session->userdata('username');
        $data['username'] = !empty($session_username) ? $session_username : ($visit['visitor_username'] ?? $visit['fullname'] ?? 'Guest');
        
        // Data untuk QR Code view
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
        $data['visit'] = (array) $visit; // Convert object to array
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

    /** 🔹 Proses Check-in via AJAX */
    public function checkin() {
        $qr_token = $this->input->post('qr_token');

        if (!$qr_token) {
            echo json_encode(['success' => false, 'message' => 'QR Token tidak ditemukan']);
            return;
        }

        $result = $this->Qr_code_model->processCheckIn($qr_token);
        echo json_encode($result);
    }

    /** 🔹 Proses Check-out */
    public function checkout($visit_id) {
        $result = $this->Qr_code_model->processCheckOut($visit_id);

        if ($this->input->is_ajax_request()) {
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

    /** 🔹 Daftar visitor yang masih aktif (check-in) */
    public function active() {
        $data['title'] = 'Active Visitors';
        $data['active_page'] = 'active';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');
        $data['visits'] = $this->Qr_code_model->getActiveVisits();

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/active_visitors', $data);
    }
}