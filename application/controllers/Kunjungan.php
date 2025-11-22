<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Kunjungan Controller
 * Handle semua operasi kunjungan tamu
 * 
 * @property CI_Input            $input
 * @property CI_Session          $session
 * @property Kunjungan_model     $Kunjungan_model
 * @property CI_Form_validation  $form_validation
 * @property Ciqrcode            $ciqrcode
 */
class Kunjungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load dependencies
        $this->load->model('Kunjungan_model');
        $this->load->library(['session', 'form_validation', 'ciqrcode']);
        $this->load->helper(['url', 'form']);
        
        // Check login
        if (!$this->session->userdata('visitor_id')) {
            redirect('login');
        }
    }

    /**
     * Halaman form kunjungan (dashboard)
     */
    public function index()
    {
        $data['active_page'] = 'kunjungan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/dashboard', $data);
    }

    /**
     * Proses submit kunjungan baru
     */
    public function submit()
    {
        $visitor_id = $this->session->userdata('visitor_id');

        // Set validation rules
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('id_number', 'NIK', 'required|trim|numeric');
        $this->form_validation->set_rules('phone', 'No Telepon', 'required|trim');
        $this->form_validation->set_rules('institution', 'Instansi', 'required|trim');
        $this->form_validation->set_rules('to_whom', 'Tujuan ke', 'required|trim');
        $this->form_validation->set_rules('scheduled_date', 'Tanggal', 'required');
        $this->form_validation->set_rules('purpose', 'Tujuan', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('kunjungan');
            return;
        }

        // Generate unique QR token (7 karakter: huruf kapital + angka)
        $qr_token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 7));

        // Prepare data
        $data = [
            'visitor_id'     => $visitor_id,
            'fullname'       => $this->input->post('fullname', true),
            'id_number'      => $this->input->post('id_number', true),
            'phone'          => $this->input->post('phone', true),
            'institution'    => $this->input->post('institution', true),
            'to_whom'        => $this->input->post('to_whom', true),
            'scheduled_date' => $this->input->post('scheduled_date', true),
            'purpose'        => $this->input->post('purpose', true),
            'qr_token'       => $qr_token,
            'status'         => 'pending', // Default status
            'created_at'     => date('Y-m-d H:i:s')
        ];

        // Insert to database
        if ($this->Kunjungan_model->insert_visit($data)) {
            $this->session->set_flashdata('success', 'Data kunjungan berhasil disimpan! Menunggu persetujuan admin.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan data.');
        }

        redirect('kunjungan/daftar_kunjungan');
    }

    /**
     * Halaman daftar kunjungan
     */
    public function daftar_kunjungan()
    {
        $visitor_id = $this->session->userdata('visitor_id');
        
        $data['active_page'] = 'daftar_kunjungan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');
        $data['kunjungan'] = $this->Kunjungan_model->get_visits_by_visitor($visitor_id);

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/daftar_kunjungan', $data);
    }

    /**
     * Halaman detail/status kunjungan
     */
    public function status_kunjungan($visit_id = null)
    {
        $visitor_id = $this->session->userdata('visitor_id');

        // Get visit data
        if ($visit_id) {
            $visit = $this->Kunjungan_model->get_visit_by_id($visit_id, $visitor_id);
            
            if (!$visit) {
                $this->session->set_flashdata('error', 'Data kunjungan tidak ditemukan.');
                redirect('kunjungan/daftar_kunjungan');
                return;
            }
        } else {
            // Get last visit
            $visit = $this->Kunjungan_model->get_last_visit_by_visitor($visitor_id);
        }

        // Generate or get QR token
        if ($visit) {
            if (!empty($visit['qr_token'])) {
                $qr_token = $visit['qr_token'];
            } else {
                // Generate for old data (7 karakter)
                $created_at = $visit['created_at'] ?? date('Y-m-d H:i:s');
                $qr_token = strtoupper(substr(md5($visit['visit_id'] . $created_at), 0, 7));
                
                // Update database
                $this->Kunjungan_model->update_qr_token($visit['visit_id'], $qr_token);
                $visit['qr_token'] = $qr_token;
            }
        }

        $data['visit'] = $visit;
        $data['qr_token'] = $qr_token ?? null;
        $data['active_page'] = 'status_kunjungan';
        $data['nama'] = $this->session->userdata('nama');
        $data['username'] = $this->session->userdata('username');

        $this->load->view('Layouts/sidebar', $data);
        $this->load->view('tamu/kunjungan_detail', $data);
    }

    /**
     * Download QR Code - SIMPLIFIED VERSION
     */
    public function download_qr($visit_id = null)
    {
        $visitor_id = $this->session->userdata('visitor_id');

        // Validate visit_id
        if (!$visit_id) {
            die('ERROR: ID kunjungan tidak valid');
        }

        // Get visit data
        $visit = $this->Kunjungan_model->get_visit_by_id($visit_id, $visitor_id);

        if (!$visit) {
            die('ERROR: Data kunjungan tidak ditemukan untuk ID: ' . $visit_id);
        }

        // Check status - UPDATED: tambah validasi untuk semua status yang tidak boleh download
        $status = strtolower(trim($visit['status'] ?? ''));
        
        // Hanya status approved yang bisa download QR
        if ($status != 'approved') {
            $status_msg = '';
            switch($status) {
                case 'pending':
                    $status_msg = 'Kunjungan masih menunggu persetujuan. Tidak bisa download QR Code.';
                    break;
                case 'rejected':
                    $status_msg = 'Kunjungan ditolak. Tidak bisa download QR Code.';
                    break;
                case 'checked_in':
                    $status_msg = 'Anda sudah check-in. QR Code sudah digunakan.';
                    break;
                case 'checked_out':
                case 'completed':
                    $status_msg = 'Kunjungan sudah selesai. QR Code sudah tidak berlaku.';
                    break;
                case 'cancelled':
                    $status_msg = 'Kunjungan dibatalkan. Tidak bisa download QR Code.';
                    break;
                case 'no_show':
                    $status_msg = 'Kunjungan ditandai tidak hadir. Tidak bisa download QR Code.';
                    break;
                default:
                    $status_msg = 'Status tidak valid untuk download QR Code.';
            }
            die('ERROR: ' . $status_msg);
        }

        // Check GD library
        if (!extension_loaded('gd')) {
            die('ERROR: GD library tidak terinstall di server');
        }

        // Get or generate QR token (7 karakter)
        if (!empty($visit['qr_token'])) {
            $qr_token = $visit['qr_token'];
        } else {
            $created_at = $visit['created_at'] ?? date('Y-m-d H:i:s');
            $qr_token = strtoupper(substr(md5($visit['visit_id'] . $created_at), 0, 7));
            $this->Kunjungan_model->update_qr_token($visit['visit_id'], $qr_token);
        }

        // Generate QR Code
        $qr_path = $this->ciqrcode->generate_temp($qr_token, 'H', 12, 1);

        if (!$qr_path) {
            die('ERROR: Gagal generate QR Code - path null');
        }

        if (!file_exists($qr_path)) {
            die('ERROR: File QR tidak ditemukan di: ' . $qr_path);
        }

        // Load QR image
        $qr_image = @imagecreatefrompng($qr_path);
        
        if (!$qr_image) {
            $this->ciqrcode->delete_temp($qr_path);
            die('ERROR: Gagal load QR image dari PNG');
        }

        // Format tanggal
        $tanggal_indo = $this->_format_date_indonesian($visit['scheduled_date']);

        // Create canvas
        $card_width = 900;
        $card_height = 1400;
        $card = @imagecreatetruecolor($card_width, $card_height);

        if (!$card) {
            imagedestroy($qr_image);
            $this->ciqrcode->delete_temp($qr_path);
            die('ERROR: Gagal create canvas');
        }

        // Define colors
        $white = imagecolorallocate($card, 255, 255, 255);
        $black = imagecolorallocate($card, 0, 0, 0);
        $blue = imagecolorallocate($card, 59, 130, 246);
        $dark_blue = imagecolorallocate($card, 30, 58, 138);
        $gray = imagecolorallocate($card, 100, 116, 139);
        $light_gray = imagecolorallocate($card, 248, 250, 252);
        $light_gray2 = imagecolorallocate($card, 232, 232, 232);
        $green = imagecolorallocate($card, 16, 185, 129);
        $border_gray = imagecolorallocate($card, 226, 232, 240);

        // Background
        imagefilledrectangle($card, 0, 0, $card_width, $card_height, $white);

        // HEADER
        imagefilledrectangle($card, 0, 0, $card_width, 140, $blue);
        imagefilledrectangle($card, 0, 140, $card_width, 145, $dark_blue);

        // Header text centered
        $header_txt = "KARTU KUNJUNGAN";
        $header_w = strlen($header_txt) * imagefontwidth(5);
        $header_x = ($card_width - $header_w) / 2;
        imagestring($card, 5, $header_x, 40, $header_txt, $white);
        
        $subtitle_txt = "Sistem Informasi Tamu - PT. PINDAD";
        $subtitle_w = strlen($subtitle_txt) * imagefontwidth(3);
        $subtitle_x = ($card_width - $subtitle_w) / 2;
        imagestring($card, 3, $subtitle_x, 75, $subtitle_txt, $white);

        // Badge
        $badge_y = 105;
        $badge_width = 220;
        $badge_x = ($card_width - $badge_width) / 2;
        imagefilledrectangle($card, $badge_x, $badge_y, $badge_x + $badge_width, $badge_y + 30, $green);
        
        $badge_txt = "DISETUJUI";
        $badge_w = strlen($badge_txt) * imagefontwidth(4);
        $badge_txt_x = ($card_width - $badge_w) / 2;
        imagestring($card, 4, $badge_txt_x, $badge_y + 8, $badge_txt, $white);

        // QR SECTION
        $qr_section_y = 180;
        $qr_section_height = 420;
        imagefilledrectangle($card, 60, $qr_section_y, $card_width - 60, $qr_section_y + $qr_section_height, $light_gray);
        imagerectangle($card, 60, $qr_section_y, $card_width - 60, $qr_section_y + $qr_section_height, $border_gray);

        // Paste QR
        $qr_display_size = 320;
        $qr_x = ($card_width - $qr_display_size) / 2;
        $qr_y = $qr_section_y + 30;
        
        imagefilledrectangle($card, $qr_x - 8, $qr_y - 8, $qr_x + $qr_display_size + 8, $qr_y + $qr_display_size + 8, $white);
        imagerectangle($card, $qr_x - 8, $qr_y - 8, $qr_x + $qr_display_size + 8, $qr_y + $qr_display_size + 8, $black);
        
        imagecopyresampled($card, $qr_image, $qr_x, $qr_y, 0, 0, 
            $qr_display_size, $qr_display_size, imagesx($qr_image), imagesy($qr_image));

        $scan_txt = "Scan QR Code ini saat check-in";
        $scan_w = strlen($scan_txt) * imagefontwidth(3);
        $scan_x = ($card_width - $scan_w) / 2;
        imagestring($card, 3, $scan_x, $qr_y + $qr_display_size + 25, $scan_txt, $gray);

        // INFO BOX
        $info_y = $qr_section_y + $qr_section_height + 30;
        $info_box_height = 380;
        imagefilledrectangle($card, 60, $info_y, $card_width - 60, $info_y + $info_box_height, $light_gray);
        imagerectangle($card, 60, $info_y, $card_width - 60, $info_y + $info_box_height, $border_gray);

        $info_txt = "INFORMASI PENGUNJUNG";
        $info_w = strlen($info_txt) * imagefontwidth(4);
        $info_x = ($card_width - $info_w) / 2;
        imagestring($card, 4, $info_x, $info_y + 15, $info_txt, $black);
        
        imageline($card, 80, $info_y + 40, $card_width - 80, $info_y + 40, $border_gray);

        // Data rows
        $data_y = $info_y + 55;
        $line_height = 35;
        $label_x = 90;
        $value_x = 350;

        $data_rows = [
            ['Nama Lengkap', $visit['fullname'] ?? '-'],
            ['NIK', $visit['id_number'] ?? '-'],
            ['No. Telepon', $visit['phone'] ?? '-'],
            ['Instansi', $visit['institution'] ?? '-'],
            ['Tujuan Ke', $visit['to_whom'] ?? '-'],
            ['Keperluan', $visit['purpose'] ?? '-'],
            ['Tanggal Kunjungan', $tanggal_indo],
            ['Penanggung Jawab', $visit['penanggung_jawab'] ?? '-'],
        ];

        foreach ($data_rows as $idx => $row) {
            $current_y = $data_y + ($idx * $line_height);
            imagestring($card, 3, $label_x, $current_y, $row[0], $black);
            imagestring($card, 3, $label_x + 180, $current_y, ":", $black);
            
            $value = $row[1];
            if (strlen($value) > 45) {
                $wrapped = wordwrap($value, 45, "\n");
                $lines = explode("\n", $wrapped);
                foreach ($lines as $line_idx => $line) {
                    imagestring($card, 3, $value_x, $current_y + ($line_idx * 18), $line, $gray);
                }
            } else {
                imagestring($card, 3, $value_x, $current_y, $value, $gray);
            }
            
            if ($idx < count($data_rows) - 1) {
                imageline($card, 80, $current_y + 25, $card_width - 80, $current_y + 25, $border_gray);
            }
        }

        // SAFETY SECTION
        $safety_y = $info_y + $info_box_height + 30;
        $safety_height = 380;
        imagefilledrectangle($card, 60, $safety_y, $card_width - 60, $safety_y + $safety_height, $light_gray2);
        imagerectangle($card, 60, $safety_y, $card_width - 60, $safety_y + $safety_height, $border_gray);

        $safety_txt = "SAFETY INDUCTION - PT. PINDAD";
        $safety_w = strlen($safety_txt) * imagefontwidth(4);
        $safety_x = ($card_width - $safety_w) / 2;
        imagestring($card, 4, $safety_x, $safety_y + 15, $safety_txt, $black);
        
        $iso_txt = "Berdasarkan Quality System Management ISO";
        $iso_w = strlen($iso_txt) * imagefontwidth(2);
        $iso_x = ($card_width - $iso_w) / 2;
        imagestring($card, 2, $iso_x, $safety_y + 38, $iso_txt, $gray);
        
        imageline($card, 80, $safety_y + 55, $card_width - 80, $safety_y + 55, $border_gray);

        $rules_y = $safety_y + 70;
        $rules_x = 90;
        $rule_line_height = 28;
        
        $safety_rules = [
            "1. Ketahui jalur evakuasi dan titik kumpul saat darurat.",
            "2. Ketahui lokasi APAR dan gunakan bila terjadi kebakaran.",
            "3. Hindari menyentuh kabel atau peralatan listrik rusak.",
            "4. Jaga kebersihan dan kerapian area kerja.",
            "5. Atur posisi meja, kursi, dan layar komputer agar nyaman.",
            "6. Gunakan masker bila sedang sakit.",
            "7. Gunakan tangga untuk mengambil barang di tempat tinggi.",
            "8. Laporkan segera kondisi berbahaya atau kerusakan.",
            "9. Patuhi SOP dan hindari risiko yang membahayakan.",
            "10. Selalu waspada dan bantu rekan tetap aman.",
        ];

        foreach ($safety_rules as $idx => $rule) {
            $rule_y = $rules_y + ($idx * $rule_line_height);
            if (strlen($rule) > 65) {
                $wrapped = wordwrap($rule, 65, "\n");
                $lines = explode("\n", $wrapped);
                foreach ($lines as $line_idx => $line) {
                    imagestring($card, 2, $rules_x, $rule_y + ($line_idx * 15), $line, $black);
                }
            } else {
                imagestring($card, 2, $rules_x, $rule_y, $rule, $black);
            }
        }

        // FOOTER
        $footer_y = $card_height - 60;
        imageline($card, 60, $footer_y, $card_width - 60, $footer_y, $border_gray);
        
        $footer1_txt = "Digenerate pada: " . date('d/m/Y H:i:s');
        $footer1_w = strlen($footer1_txt) * imagefontwidth(2);
        $footer1_x = ($card_width - $footer1_w) / 2;
        imagestring($card, 2, $footer1_x, $footer_y + 15, $footer1_txt, $gray);
        
        $footer2_txt = "Harap tunjukkan kartu ini beserta identitas asli saat check-in";
        $footer2_w = strlen($footer2_txt) * imagefontwidth(2);
        $footer2_x = ($card_width - $footer2_w) / 2;
        imagestring($card, 2, $footer2_x, $footer_y + 35, $footer2_txt, $gray);

        // Cleanup
        imagedestroy($qr_image);
        $this->ciqrcode->delete_temp($qr_path);

        // Output
        $filename = 'Kartu_Kunjungan_' . str_replace(' ', '_', $visit['fullname']) . '_' . date('YmdHis') . '.png';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        imagepng($card);
        imagedestroy($card);
        exit;
    }

    /**
     * Helper: Format tanggal ke bahasa Indonesia
     */
    private function _format_date_indonesian($date)
    {
        if (empty($date)) {
            return '-';
        }

        $hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $bulan = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];

        $timestamp = strtotime($date);
        if (!$timestamp) {
            return '-';
        }

        $tanggal_raw = date('l, d F Y', $timestamp);
        return strtr($tanggal_raw, array_merge($hari, $bulan));
    }
}