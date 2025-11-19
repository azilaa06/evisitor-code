<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kunjungan_model'); // Sesuaikan dengan model Anda
    }

    public function process_checkin()
    {
        // Cek jika request adalah AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $qr_data = $this->input->post('qr_data');

        // Validasi QR data
        if (empty($qr_data)) {
            echo json_encode(['success' => false, 'message' => 'Data QR Code tidak valid']);
            return;
        }

        // Proses validasi dan check-in
        // Contoh: QR data berisi visit_id
        $visit_id = $this->extract_visit_id($qr_data);

        if (!$visit_id) {
            echo json_encode(['success' => false, 'message' => 'Format QR Code tidak dikenali']);
            return;
        }

        // Cek data kunjungan
        $kunjungan = $this->Kunjungan_model->get_by_id($visit_id);

        if (!$kunjungan) {
            echo json_encode(['success' => false, 'message' => 'Data kunjungan tidak ditemukan']);
            return;
        }

        // Cek status kunjungan
        if ($kunjungan['status'] != 'approved') {
            echo json_encode(['success' => false, 'message' => 'Status kunjungan tidak valid untuk check-in']);
            return;
        }

        // Update status menjadi berkunjung
        $update_data = [
            'status' => 'berkunjung',
            'check_in_time' => date('Y-m-d H:i:s')
        ];

        if ($this->Kunjungan_model->update($visit_id, $update_data)) {
            echo json_encode(['success' => true, 'message' => 'Check-in berhasil untuk ' . $kunjungan['NAMA']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal melakukan check-in']);
        }
    }

    private function extract_visit_id($qr_data)
    {
        // Sesuaikan dengan format QR code Anda
        // Contoh: jika QR berisi langsung visit_id
        return $qr_data;

        // Atau jika QR berisi JSON: {"visit_id":"123"}
        // $data = json_decode($qr_data, true);
        // return isset($data['visit_id']) ? $data['visit_id'] : null;
    }
}